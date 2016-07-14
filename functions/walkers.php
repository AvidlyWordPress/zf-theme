<?php
/**
 * Custom Navigation Walker that uses BEM naming convention.
 *
 * Uses the following classes:
 *
 * $prefix__item
 * $prefix__item--has-children
 * $prefix__item--current-ancestor
 * $prefix__item--current-parent
 * $prefix__item--current
 * $prefix__link
 * $prefix__submenu
 *
 * If sublink naming is set to true, $prefix_submenu items have the following classes:
 *
 * $prefix__subitem
 * $prefix__subitem--has-children
 * $prefix__subitem--current-ancestor
 * $prefix__subitem--current-parent
 * $prefix__subitem--current
 * $prefix__sublink
 *
 * Use this from the wp_nav_menu walker parameter like this:
 *
 * 		'walker' => new zf_Walker_Nav_Menu( 'prefix', 'use_sublink' )
 *
 * @param string $prefix  Prefix for the HTML list.
 * @param string $sublink (Optional) Should the HTML list use sublink naming.
 *
 * @uses Walker_Nav_Menu
 */
class zf_Walker_Nav_Menu extends Walker_Nav_Menu {

	var $zf_custom_prefix;
	var $zf_sublink_naming;

	/**
	 * Setup the prefix and sublink naming for this walker.
	 */
	function __construct( $prefix, $sublink = null ) {
		$this->zf_custom_prefix  = ( isset( $prefix ) ) ? esc_attr( $prefix ) : 'menu';
		$this->zf_sublink_naming = ( isset( $sublink ) && 'use_sublink' == $sublink ) ? true : false;
	}

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker::start_lvl()
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"{$this->zf_custom_prefix}__submenu\">\n";
	}

	/**
	 * Start the element output.
	 *
	 * @see Walker::start_el()
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		// Setup classes
		$classes;

		// Find custom CSS classes added in admin
		if ( ! empty( $item->classes[0] ) ) {
			foreach ( $item->classes as $class ) {
				if ( 'menu-item' != $class ) {
					$classes[] = $class;
				} else {
					break;
				}
			}
		}

		if ( ( false == $this->zf_sublink_naming ) || ( true == $this->zf_sublink_naming && 0 == $item->menu_item_parent ) ) {

			// Item
			$classes[] = "{$this->zf_custom_prefix}__item";

			// Has children
			if ( in_array( 'menu-item-has-children', $item->classes ) ) {
				$classes[] = "{$this->zf_custom_prefix}__item--has-children";
			}

			// Current item
			if ( in_array( 'current-menu-item', $item->classes ) ) {
				$classes[] = "{$this->zf_custom_prefix}__item--current";
			}

			// Current ancestor
			if ( in_array( 'current-menu-ancestor', $item->classes ) ) {
				$classes[] = "{$this->zf_custom_prefix}__item--current-ancestor";
			}

			// Current parent
			if ( in_array( 'current-menu-parent', $item->classes ) ) {
				$classes[] = "{$this->zf_custom_prefix}__item--current-parent";
			}

			// Home link
			if ( in_array( 'menu-item-home', $item->classes ) ) {
				$classes[] = "{$this->zf_custom_prefix}__item--home";
			}
		} else {

			// Item
			$classes[] = "{$this->zf_custom_prefix}__subitem";

			// Has children
			if ( in_array( 'menu-item-has-children', $item->classes ) ) {
				$classes[] = "{$this->zf_custom_prefix}__subitem--has-children";
			}

			// Current item
			if ( in_array( 'current-menu-item', $item->classes ) ) {
				$classes[] = "{$this->zf_custom_prefix}__subitem--current";
			}

			// Current ancestor
			if ( in_array( 'current-menu-ancestor', $item->classes ) ) {
				$classes[] = "{$this->zf_custom_prefix}__subitem--current-ancestor";
			}

			// Current parent
			if ( in_array( 'current-menu-parent', $item->classes ) ) {
				$classes[] = "{$this->zf_custom_prefix}__subitem--current-parent";
			}

			// Home link
			if ( in_array( 'menu-item-home', $item->classes ) ) {
				$classes[] = "{$this->zf_custom_prefix}__subitem--home";
			}
		}

		/**
		 * Filter the CSS class(es) applied to a menu item's <li>.
		 *
		 * @since 3.0.0
		 *
		 * @see wp_nav_menu()
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filter the ID applied to a menu item's <li>.
		 *
		 * @since 3.0.1
		 *
		 * @see wp_nav_menu()
		 */
		$output .= $indent . '<li' . $class_names . '>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		/**
		 * Filter the HTML attributes applied to a menu item's <a>.
		 *
		 * @since 3.6.0
		 *
		 * @see wp_nav_menu()
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before;

		// Add link class
		if ( ( false == $this->zf_sublink_naming ) || ( true == $this->zf_sublink_naming && 0 == $item->menu_item_parent ) ) {
			$item_output .= '<a class="' . $this->zf_custom_prefix . '__link" '. $attributes .'>';
		} else {
			$item_output .= '<a class="' . $this->zf_custom_prefix . '__sublink" '. $attributes .'>';
		}

		/** This filter is documented in wp-includes/post-template.php */
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		/**
		 * Filter a menu item's starting output.
		 *
		 * The menu item's starting output only includes $args->before, the opening <a>,
		 * the menu item's title, the closing </a>, and $args->after. Currently, there is
		 * no filter for modifying the opening and closing <li> for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @see wp_nav_menu()
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/**
 * Custom walker for HTML list of pages.
 *
 * Enables creating an completely custom class structure for nav menus, eg.
 *
 * 	.prefix__list
 *  .prefix__item
 *  .prefix__link
 *     etc...*
 *
 * @uses Walker_Page
 */
class zf_Walker_Page extends Walker_Page {

	var $zf_custom_prefix;
	var $zf_top_page;

	function __construct( $prefix, $top_page ) {
		$this->zf_custom_prefix = esc_attr( $prefix );
		$this->zf_top_page = $top_page;
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class='{$this->zf_custom_prefix}subnav'>\n";
	}

	function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
		if ( $depth )
			$indent = str_repeat("\t", $depth);
		else
			$indent = '';

		extract($args, EXTR_SKIP);

		$css_class = array();

		$is_top_level = $page->post_parent == 0 || $page->post_parent == $this->zf_top_page;

		if ( $is_top_level ) {
			$css_class = array( $this->zf_custom_prefix . 'item' );
			if( isset( $args['pages_with_children'][ $page->ID ] ) )
				$css_class[] =  $this->zf_custom_prefix . 'item--has_subnav';
		}
		else {
			$css_class = array( $this->zf_custom_prefix . 'subitem' );
			if( isset( $args['pages_with_children'][ $page->ID ] ) )
				$css_class[] =  $this->zf_custom_prefix . 'subitem--has_subnav';
		}

		if ( !empty($current_page) ) {
			$_current_page = get_post( $current_page );

			if ( in_array( $page->ID, $_current_page->ancestors ) )
				$css_class[] = $this->zf_custom_prefix . 'item--current_ancestor';
			if ( $page->ID == $current_page && $is_top_level ) {
				$css_class[] = $this->zf_custom_prefix . 'item--current';
			} elseif ( $page->ID == $current_page ) {
				$css_class[] = $this->zf_custom_prefix . 'subitem--current';
			}
			elseif ( $_current_page && $page->ID == $_current_page->post_parent )
				$css_class[] = $this->zf_custom_prefix . 'item--current_parent';
		} elseif ( $page->ID == get_option('page_for_posts') ) {
			$css_class[] = $this->zf_custom_prefix . 'item--current_parent';
		}

		/**
		 * Filter the list of CSS classes to include with each page item in the list.
		 */
		$css_class = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

		if ( '' === $page->post_title )
			$page->post_title = sprintf( __( '#%d (no title)' ), $page->ID );

		/**
		 * Set a custom class on the anchor link
		 */
		$anchor_class = $is_top_level ? "{$this->zf_custom_prefix}link" : "{$this->zf_custom_prefix}sublink" ;

		/** This filter is documented in wp-includes/post-template.php */
		$output .= $indent . '<li class="' . $css_class . '"><a href="' . get_permalink($page->ID) . '" class="' . $anchor_class .'">' . $link_before . apply_filters( 'the_title', $page->post_title, $page->ID ) . $link_after . '</a>';

		if ( !empty($show_date) ) {
			if ( 'modified' == $show_date )
				$time = $page->post_modified;
			else
				$time = $page->post_date;

			$output .= " " . mysql2date($date_format, $time);
		}
	}
}

/**
 * Custom Category Walker
 *
 * Creates a list using BEM naming convention.
 *
 * Usage: wp_list_categories( 'walker' => new zf_Walker_Category( $prefix ) );
 *
 * @uses Walker_Category
 */
class zf_Walker_Category extends Walker_Category {

	/**
	 * Custom Prefix
	 */
	var $zf_custom_prefix;

	function __construct( $prefix ) {
		$this->zf_custom_prefix = esc_attr( $prefix );
	}

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;

		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class=\"{$this->zf_custom_prefix}__children\">\n";
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;

		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {

		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters(
			'list_cats',
			esc_attr( $category->name ),
			$category
		);

		$link = '<a href="' . esc_url( get_term_link( $category ) ) . '" ';
		$link .= 'class="' . $this->zf_custom_prefix . '__link" ';
		if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
			/**
			 * Filter the category description for display.
			 *
			 * @since 1.2.0
			 *
			 * @param string $description Category description.
			 * @param object $category    Category object.
			 */
			$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		}

		$link .= '>';
		$link .= $cat_name . '</a>';

		if ( ! empty( $args['feed_image'] ) || ! empty( $args['feed'] ) ) {
			$link .= ' ';

			if ( empty( $args['feed_image'] ) ) {
				$link .= '(';
			}

			$link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $args['feed_type'] ) ) . '"';

			if ( empty( $args['feed'] ) ) {
				$alt = ' alt="' . sprintf(__( 'Feed for all posts filed under %s' ), $cat_name ) . '"';
			} else {
				$alt = ' alt="' . $args['feed'] . '"';
				$name = $args['feed'];
				$link .= empty( $args['title'] ) ? '' : $args['title'];
			}

			$link .= '>';

			if ( empty( $args['feed_image'] ) ) {
				$link .= $name;
			} else {
				$link .= "<img src='" . $args['feed_image'] . "'$alt" . ' />';
			}
			$link .= '</a>';

			if ( empty( $args['feed_image'] ) ) {
				$link .= ')';
			}
		}

		if ( ! empty( $args['show_count'] ) ) {
			$link .= ' (' . number_format_i18n( $category->count ) . ')';
		}
		if ( 'list' == $args['style'] ) {
			$output .= "\t<li";
			$class = $this->zf_custom_prefix . '__item';

			/**
			 * Check if the term has children
			 */
			$terms = get_term_children( $category->term_id, $category->taxonomy );
			if ( ! empty( $terms ) ) {
				$class .= ' ' . $this->zf_custom_prefix . '__item--has-children';
			}

			if ( ! empty( $args['current_category'] ) ) {
				$_current_category = get_term( $args['current_category'], $category->taxonomy );
				if ( $category->term_id == $args['current_category'] ) {
					$class .=  ' ' . $this->zf_custom_prefix .'__item--current';
				} elseif ( $category->term_id == $_current_category->parent ) {
					$class .=  ' ' . $this->zf_custom_prefix .'__item--current-parent';
				}
			}
			$output .=  ' class="' . $class . '"';
			$output .= ">$link\n";
		} else {
			$output .= "\t$link<br />\n";
		}
	}
}

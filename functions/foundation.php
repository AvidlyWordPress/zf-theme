<?php
/**
 * Foundation Specific functions.
 */

/**
 * Make the admin bar play nicely with Foundation, thanks to Kirsten at
 * http://foundation.zurb.com/forum/posts/1744-the-wordpress-admin-bar-getting-it-to-play-nice-with-foundation-5
 */
add_action('wp_head', 'zf_theme_foundation_adminbar_styles');
function zf_theme_foundation_adminbar_styles() {
	if ( is_admin_bar_showing() ) {?>
		<style>
		.top-bar{ margin-top: 32px; }
		@media screen and (max-width: 600px) {
			.top-bar{ margin-top: 46px; }
			#wpadminbar { position: fixed !important; }
		}
		</style>
	<?php }
}

/**
 * The Modified Gallery shortcode.
 *
 * Adds foundation block-grid classes to the gallery. 
 * NOTE: This uses the XY grid classes as documented here http://foundation.zurb.com/sites/docs/xy-grid.html#block-grids
 * If you don't use the XY grid you will either need to edit this function or remove the filter altogether, which will revert to the 
 * regular WordPress gallery markup.
 *
 * @param array $attr Attributes of the shortcode.
 * @return string HTML content to display gallery.
 */
add_filter( 'post_gallery', 'zf_theme_foundation_gallery_shortcode', 10, 2 );
function zf_theme_foundation_gallery_shortcode( $defaults = '', $attr ) {
	global $post;

	static $instance = 0;
	$instance++;

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters( 'zf_theme_post_gallery', '', $attr );
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract( shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'li',
		'icontag'    => '',
		'captiontag' => 'div',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval( $id );
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( ! empty( $include ) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array( 
					'post_parent' => $id, 
					'exclude' => $exclude, 
					'post_status' => 'inherit', 
					'post_type' => 'attachment', 
					'post_mime_type' => 'image', 
					'order' => $order, 
					'orderby' => $orderby,
				) );
	} else {
		$attachments = get_children( array( 
					'post_parent' => $id, 
					'post_status' => 'inherit', 
					'post_type' => 'attachment', 
					'post_mime_type' => 'image', 
					'order' => $order, 
					'orderby' => $orderby,
				) );
	}

	if ( empty($attachments) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
		}
		return $output;
	}

	$itemtag = tag_escape( $itemtag );
	$captiontag = tag_escape( $captiontag );
	$columns = intval( $columns );
	$itemwidth = $columns > 0 ? floor( 100 / $columns ) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	global $post;

	static $instance = 0;
	$instance++;

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters( 'customizer_test_post_gallery', '', $attr );
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( ! $attr['orderby'] ) {
			unset( $attr['orderby'] );
		}
	}

	extract( shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'li',
		'icontag'    => '',
		'captiontag' => 'div',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
	), $attr) );

	$id = intval( $id );
	if ( 'RAND' == $order ) {
		$orderby = 'none';
	}

	if ( !empty( $include ) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array(
					'include' => $include, 
					'post_status' => 'inherit', 
					'post_type' => 'attachment', 
					'post_mime_type' => 'image', 
					'order' => $order, 
					'orderby' => $orderby,
				) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[ $val->ID ] = $_attachments[ $key ];
		}
	} elseif ( !empty( $exclude ) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array(
					'post_parent' => $id, 
					'exclude' => $exclude, 
					'post_status' => 'inherit', 
					'post_type' => 'attachment', 
					'post_mime_type' => 'image', 
					'order' => $order, 
					'orderby' => $orderby,
				) );
	} else {
		$attachments = get_children( array(
					'post_parent' => $id, 
					'post_status' => 'inherit', 
					'post_type' => 'attachment', 
					'post_mime_type' => 'image', 
					'order' => $order, 
					'orderby' => $orderby,
				) );
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		}
		return $output;
	}

	$itemtag = tag_escape( $itemtag );
	$captiontag = tag_escape( $captiontag );
	$columns = intval( $columns );
	$itemwidth = $columns > 0 ? floor( 100/$columns ) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$size_class = sanitize_html_class( $size );

	$small_columns = $columns > 2 ? 2 : $columns;
	$medium_columns = $columns > 4 ? 4 : $columns;

	$gallery_div = "<div class=\"gallery-wrap\"><ul id='$selector' class='no-bullet grid-x grid-padding-x small-up-{$small_columns} medium-up-{$medium_columns} large-up-{$columns} gallery galleryid-{$id} gallery-size-{$size_class}'>";
	$output = "\n\t\t" . $gallery_div;

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$thumb_src = wp_get_attachment_thumb_url( $id );
		$link_url = wp_get_attachment_url( $id );

		$has_caption = $captiontag && trim( $attachment->post_excerpt ) ? true : false;

		$link = "<a href='$link_url' class='gallery-item__link'><img src='$thumb_src' class='gallery-item__thumbnail' ";

		if ( $has_caption )
			$link .= " data-caption='". wptexturize( strip_tags( $attachment->post_excerpt ) ) . "' ";
		$link .= " /></a>";

//        $aria_labelledby = $has_caption ? " aria-labelledby='img-caption-{$attachment->ID}'" : '';

		$output .= "<{$itemtag} class='cell gallery-item'>";
		$output .= "
				$link
		";
		if ( $has_caption ) {
			$output .= "
				<{$captiontag} class='gallery-item__caption' id='img-caption-{$attachment->ID}'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
	}

	$output .= "
		</ul></div>\n";

	return $output;
}

/**
 * Filter video oembeds and wrap with Foundation's flex-video
 */
add_filter('embed_oembed_html', 'zf_theme_foundation_embed_oembed_html', 99, 4);
function zf_theme_foundation_embed_oembed_html( $html, $url, $attr, $post_id ) {

	$matches = array(
			'youtube.com',
			'vimeo.com',
			'youtu.be'
		);

	foreach ( $matches as $match ) {
		if ( false !== stripos( $url, $match ) )
			return '<div class="flex-video">' . $html . '</div>';
	}

	return $html;

}

/**
 * Walker class to add Foundation classes to nav menus
 *
 * @uses Walker_Nav_Menu
 */
class Foundation_Dropdown_Nav_Menu extends Walker_Nav_Menu {

	/**
	 * Add the 'vertical' class to child menus
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"vertical menu\">\n";
	}

	/**
	 * Modify the individual menu items
	 *
	 * @see Walker::start_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		/**
		 * Filter the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param array  $args  An array of arguments.
		 * @param object $item  Menu item data object.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		// Add the foundation 'is-active' class to current menu items
		if ( in_array( 'current-menu-item', $classes ) || in_array( 'current-menu-ancestor', $classes ) ) {
			$classes[] = 'is-active';
		}

		/**
		 * Filter the CSS class(es) applied to a menu item's list item element.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filter the ID applied to a menu item's list item element.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		/**
		 * Filter the HTML attributes applied to a menu item's anchor element.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Apply the core filter for a menu item's title
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output = $args->before;

		// Wrap menu-item and dropdown toggle in a separate element to enable
		// clicking top level links on touch devices
		if ( ! is_array( $item->classes ) ) {
			$item->classes = array( $item->classes );
		}
		if ( in_array( 'menu-item-has-children', $item->classes ) ) {
			$item_output .= '<span class="menu-item-link"><a'. $attributes .'>';
			$item_output .= $args->link_before . $title . $args->link_after;
			$item_output .= '</a></span><a href="#" class="menu-item-toggle"></a>';
		} else {
			$item_output .= '<a'. $attributes .'>';
			$item_output .= $args->link_before . $title . $args->link_after;
			$item_output .= '</a>';
		}
		$item_output .= $args->after;

		/**
		 * Filter a menu item's starting output.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

} // Walker_Nav_Menu

<?php
/**
 * Sub navigation, based on code from Simple Section Navigation by jakemgold and thinkoomph, http://wordpress.org/extend/plugins/simple-section-navigation/
 *
 *
 * @copyright Daniel Koskinen, Jake Goldman, thinkoomph
 * @author Daniel Koskinen
 * @version 1.2
 **/
function zf_section_nav( $args = array() ) {

		global $post;

		// Set sensible defaults
		$defaults = array(
			'show_all' => false,
			'show_top' => false, // whether to repeat top level parent
			'excluded' => array(), // id's of pages to be excluded
			'exclude_list' => '',
			'menu_class' => 'sidemenu',
			'link_before' => '',
			'link_after' => ''
		);

		$args = wp_parse_args( $args, $defaults );

		// initialise variables
		$show_all = $args['show_all'];
		$show_top = $args['show_top'];
		$excluded = $args['excluded'];
		$exclude_list = $args['exclude_list'];
		$menu_class = $args['menu_class'];
		$link_before = $args['link_before'];
		$link_after = $args['link_after'];

		if ( is_search() || is_404() ) return false; //doesn't apply to search or 404 page

		$post_ancestors = ( isset($post->ancestors) ) ? $post->ancestors : get_post_ancestors($post); //get the current page's ancestors either from existing value or by executing function
		$top_page = $post_ancestors ? end($post_ancestors) : $post->ID; //get the top page id

		$thedepth = 0; //initialize default variables

		if( !$show_all )
		{
			$ancestors_me = implode( ',', $post_ancestors ) . ',' . $post->ID;

			//exclude pages not in direct hierarchy
			foreach ($post_ancestors as $anc_id)
			{
				if ( in_array($anc_id,$excluded) ) return false; //if ancestor excluded, and hide on excluded, leave

				$pageset = get_pages(array( 'child_of' => $anc_id, 'parent' => $anc_id, 'exclude' => $ancestors_me ));
				foreach ($pageset as $page) {
					$excludeset = get_pages(array( 'child_of' => $page->ID, 'parent' => $page->ID ));
					foreach ($excludeset as $expage) { $exclude_list .= ',' . $expage->ID; }
				}
			}

			$thedepth = count($post_ancestors)+1; //prevents improper grandchildren from showing
		}

		//get the list of pages, including only those in our page list
		// uncomment walker if you want custom classes
		$children = wp_list_pages(array(
			'title_li' => '',
			'echo' => 0,
			'depth' => $thedepth,
			'child_of' => $top_page,
			'exclude' => $exclude_list,
			'link_before' => $link_before,
			'link_after' => $link_after,
			// 'walker' => new zf_Walker_Page( $menu_class . '__', $top_page )
			));

		if( !$children ) return false; 	//if there are no pages in this section, leave the function

		$sect_title = apply_filters( 'the_title', get_the_title($top_page), $top_page );
		if ( $show_top ) {
			if ( $link_before != '')
				$sect_title = $link_before . $sect_title;
			if ( $link_after != '' )
				$sect_title = $sect_title . $link_after;

			$headclass = ( $post->ID == $top_page ) ? $menu_class . "__item--current" : $menu_class . "__item--current_ancestor";
			if ( $post->post_parent == $top_page ) $headclass .= $menu_class . "__item--current_ancestor";
			$sect_title = '<a href="' . get_page_link($top_page) . '" id="toppage-' . $top_page . '" class="' . $headclass . '">' . $sect_title . '</a>';
		}


		if ( $menu_class != '' )
			echo "<ul class=\"". $menu_class ."\">";
		else echo "<ul>";
		if ($show_top) echo '<li class="'.$headclass.'">'.$sect_title.'</li>';
		echo apply_filters( 'simple_section_page_list', $children );
		echo "</ul>";
}

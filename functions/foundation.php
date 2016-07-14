<?php
/**
 * Foundation Specific functions.
 */

/**
 * Make the admin bar play nicely with Foundation, thanks to Kirsten at
 * http://foundation.zurb.com/forum/posts/1744-the-wordpress-admin-bar-getting-it-to-play-nice-with-foundation-5
 */
add_action('wp_head', 'zf_foundation_adminbar_styles');
function zf_foundation_adminbar_styles() {
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
 * Adds foundation block-grid classes to the gallery
 *
 * @param array $attr Attributes of the shortcode.
 * @return string HTML content to display gallery.
 */
add_filter( 'post_gallery', 'zf_foundation_gallery_shortcode', 10, 2 );
function zf_foundation_gallery_shortcode($defaults = '', $attr) {
    global $post;

    static $instance = 0;
    $instance++;

    // Allow plugins/themes to override the default gallery template.
    $output = apply_filters('zf_post_gallery', '', $attr);
    if ( $output != '' )
        return $output;

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if ( !$attr['orderby'] )
            unset( $attr['orderby'] );
    }

    extract(shortcode_atts(array(
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

    $id = intval($id);
    if ( 'RAND' == $order )
        $orderby = 'none';

    if ( !empty($include) ) {
        $include = preg_replace( '/[^0-9,]+/', '', $include );
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
        $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    } else {
        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }

    if ( empty($attachments) )
        return '';

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
        return $output;
    }

    $itemtag = tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $columns = intval($columns);
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $float = is_rtl() ? 'right' : 'left';

    $selector = "gallery-{$instance}";

    $size_class = sanitize_html_class( $size );

    $small_columns = $columns > 2 ? 2 : $columns;
    $medium_columns = $columns > 4 ? 4 : $columns;

    $gallery_div = "<div class=\"gallery-wrap\"><ul id='$selector' class='small-block-grid-{$small_columns} medium-block-grid-{$medium_columns} large-block-grid-{$columns} gallery galleryid-{$id} gallery-size-{$size_class}'>";
    $output = "\n\t\t" . $gallery_div;

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
        $thumb_src = wp_get_attachment_thumb_url($id);
        $link_url = wp_get_attachment_url($id);

        $has_caption = $captiontag && trim($attachment->post_excerpt) ? true : false;

        $link = "<a href='$link_url' class='gallery-item__link'><img src='$thumb_src' class='gallery-item__thumbnail' ";

        if ( $has_caption )
            $link .= " data-caption='". wptexturize( strip_tags( $attachment->post_excerpt ) ) . "' ";
        $link .= " /></a>";

//        $aria_labelledby = $has_caption ? " aria-labelledby='img-caption-{$attachment->ID}'" : '';

        $output .= "<{$itemtag} class='gallery-item'>";
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
 * Filter video oembeds and wrap with Foundations flex-video
 */
add_filter('embed_oembed_html', 'zf_foundation_embed_oembed_html', 99, 4);
function zf_foundation_embed_oembed_html( $html, $url, $attr, $post_id ) {

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

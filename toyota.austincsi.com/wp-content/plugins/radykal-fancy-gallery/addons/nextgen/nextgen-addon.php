<?php

/*
* Fancy Gallery Addon: NextGEN gallery
* This addon replaces the nggallery and album shortcodes for displaying a nextgen gallery as fancy gallery
*
*/

//remove the nextgen shortcodes
remove_shortcode( 'nggallery' );
remove_shortcode( 'nggalbum' );

//enable custom shortcodes
add_shortcode( 'nggallery', 'show_gallery' );
add_shortcode( 'nggalbum', 'show_album' );

function show_gallery( $atts ) {

	if( !class_exists('FancyGallery') )
		return '<p>'.__('Fancy Gallery plugin could not be found.', 'radykal').'</p>';

	if( !class_exists('nggGallery') )
		return '<p>'.__('NextGen Gallery plugin is not activated.', 'radykal').'</p>';



    extract(shortcode_atts(array(
        'id'        => 0,
        'template'  => '',
        'images'    => false,
        'fg_options' => 1
    ), $atts ));

    global $wpdb;

    $fg = new FancyGallery();

    // backward compat for user which uses the name instead, still deprecated
    if( !is_numeric($id) )
        $id = $wpdb->get_var( $wpdb->prepare ("SELECT gid FROM $wpdb->nggallery WHERE name = '%s' ", $id) );

    $out = nggShowGallery( $id, $template, $images );


    //fancy gallery starts
    if($fg_options == 1) {
	    $options = $wpdb->get_results("SELECT options FROM {$fg->gallery_table_name} WHERE ID=1");
    }
    else {

	    $options = $wpdb->get_results("SELECT options FROM {$fg->gallery_table_name} WHERE title='$fg_options'");

    }

    $options = unserialize($options[0]->options);
    $options = $fg->check_options_availability($options);

    FancyGallery::$add_script = true;
    //add necessary scripts
	switch($options['gallery']) {
		case 'prettyphoto':
			FancyGallery::$add_pp_script = true;
		break;
		case 'fancybox':
			FancyGallery::$add_fb_script = true;
		break;
		case 'inline':
			FancyGallery::$add_inline_script = true;
		break;
	}

    $ngg_options = nggGallery::get_option('ngg_options');
    $options['thumbnail_width'] = $ngg_options['thumbwidth'];
    $options['thumbnail_height'] = $ngg_options['thumbheight'];

    $current_page = (get_the_ID() == false) ? 0 : get_the_ID();
    $gallery_id = 'ngg-gallery-' . $id . '-' . $current_page;
    ob_start();
    ?>
    <script type="text/javascript">jQuery(document).ready(function($){
  $ = jQuery.noConflict();
  var $nextGenGallery = $('#<?php echo $gallery_id; ?>').removeClass("ngg-galleryoverview").addClass("fg-panel").hide(),
  	  $media = $nextGenGallery.find(".ngg-gallery-thumbnail");

  $nextGenGallery.empty().append('<div title=""></div>');
  $media.each(function(index, item) {
  	var $mediaLink = $(item).children('a');
  	$nextGenGallery.children('div:last').append('<a href="'+$mediaLink.attr('href')+'" data-thumbnail="'+$mediaLink.children('img').attr('src')+'" title="'+$mediaLink.children('img').attr('title')+'" data-description="'+$mediaLink.attr('title')+'"></a>');
  });
  $nextGenGallery.show();
});
	</script>
	<?php
    echo $fg ->get_js_code($options, $gallery_id, '');
    $out .= ob_get_contents();
    ob_end_clean();
    return $out;
}


function show_album( $atts ) {


	if( !class_exists('FancyGallery') )
		return '<p>'.__('Fancy Gallery plugin could not be found.', 'radykal').'</p>';

	if( !class_exists('nggGallery') )
		return '<p>'.__('NextGen Gallery plugin is not activated.', 'radykal').'</p>';


	extract(shortcode_atts(array(
        'id'        => 0,
        'template'  => 'extend',
        'gallery'   => '',
        'fg_options' => 1
    ), $atts ));

    $fg = new FancyGallery();

    global $wpdb;

    $out = nggShowAlbum($id, $template, $gallery);

    //fancy gallery starts
    $gallery  = get_query_var('gallery');

    //get fancy gallery options
    if($fg_options == 1) {
	    $options = $wpdb->get_results("SELECT options FROM {$fg->gallery_table_name} WHERE ID=1");
    }
    else {
	    $options = $wpdb->get_results("SELECT options FROM {$fg->gallery_table_name} WHERE title='$fg_options'");
    }

    $options = unserialize($options[0]->options);
    $options = $fg->check_options_availability($options);


    FancyGallery::$add_script = true;
    //add necessary scripts
	switch($options['gallery']) {
		case 'prettyphoto':
			FancyGallery::$add_pp_script = true;
		break;
		case 'fancybox':
			FancyGallery::$add_fb_script = true;
		break;
		case 'inline':
			FancyGallery::$add_inline_script = true;
		break;
	}

    $ngg_options = nggGallery::get_option('ngg_options');
    $options['thumbnail_width'] = $ngg_options['thumbwidth'];
    $options['thumbnail_height'] = $ngg_options['thumbheight'];

    $current_page = (get_the_ID() == false) ? 0 : get_the_ID();
    $gallery_id = 'ngg-gallery-' . $gallery . '-' . $current_page;
    ob_start();
    ?>
    <script type="text/javascript">jQuery(document).ready(function($){
  $ = jQuery.noConflict();
  var $nextGenGallery = $('#<?php echo $gallery_id; ?>').removeClass("ngg-galleryoverview").addClass("fg-panel").hide(),
  	  $media = $nextGenGallery.find(".ngg-gallery-thumbnail");

  $nextGenGallery.empty().append('<div title=""></div>');
  $media.each(function(index, item) {
  	var $mediaLink = $(item).children('a');
  	$nextGenGallery.children('div:last').append('<a href="'+$mediaLink.attr('href')+'" data-thumbnail="'+$mediaLink.children('img').attr('src')+'" title="'+$mediaLink.children('img').attr('title')+'" data-description="'+$mediaLink.attr('title')+'"></a>');
  });
  $nextGenGallery.show();
});
	</script>
	<?php
    echo $fg ->get_js_code($options, $gallery_id, '');
    $out .= ob_get_contents();
    ob_end_clean();
    return $out;
}

?>
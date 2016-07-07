<?php

/*
Addon Name: Gallery Shortcode
Plugin URI: http://codecanyon.net/item/fancy-gallery-wordpress-plugin/400535
Version: 1.0.2
Author: Rafael Dery
Author URI: http://codecanyon.net/user/radykal
*/

//remove default gallery shortcode
remove_shortcode('gallery', 'gallery_shortcode');

//enable gallery shortcode for fancy gallery
add_shortcode('gallery', 'fg_gallery_shortcode');

function fg_gallery_shortcode($atts) {

	extract( shortcode_atts( array(
		'options_title' => ''
	), $atts ) );

	if(class_exists('FancyGallery')) {

		global $post, $wpdb;

		$fg_gs = new FancyGallery();

		$options = $wpdb->get_results("SELECT options FROM ".$fg_gs->gallery_table_name." WHERE title='$options_title'");
		$options = $fg_gs->check_options_availability(unserialize($options[0]->options));

		FancyGallery::$add_script = true;
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


		$args = array(
			'order' => 'ASC',
			'orderby' => 'menu_order',
			'post_mime_type' => 'image',
			'post_parent' => $post->ID,
			'post_type' => 'attachment'
		);

		$images = get_children( $args );

		if($images) {
			$selector = 'fancygallery-gallery-shortcode';
			ob_start();

			?>
			<div id="<?php echo $selector; ?>" class="fg-panel">
				<div title="">
				<?php foreach($images as $image) {
					$image_attributes = wp_get_attachment_image_src( $image->ID, 'full' );
					echo $fg_gs->get_media_link($image_attributes[0], $image_attributes[0], $options['thumbnail_width'], $options['thumbnail_height'], $options['thumbnail_zc'], $image->post_title, $image->post_content);
				}
				?>
				</div>
			</div>

			<?php

			echo $fg_gs->get_js_code($options, $selector, '');

			$output_gallery = ob_get_contents();
			ob_end_clean();
			return $output_gallery;
		}
	}
}

?>
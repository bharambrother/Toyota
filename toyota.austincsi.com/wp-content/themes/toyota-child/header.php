<?php

/**

 * The Header for our theme.

 *

 * @package Betheme

 * @author Muffin group

 * @link http://muffingroup.com

 */

?><!DOCTYPE html>

<?php 

	if( $_GET && key_exists('mfn-rtl', $_GET) ):

		echo '<html class="no-js" lang="ar" dir="rtl">';

	else:

?>

<html class="no-js" <?php language_attributes(); ?><?php mfn_tag_schema(); ?>>

<?php endif; ?>



<!-- head -->













<!-- meta -->

<meta charset="<?php bloginfo( 'charset' ); ?>" />

<?php 

	if( mfn_opts_get('responsive') ){

		if( mfn_opts_get('responsive-zoom') ){

			echo '<meta name="viewport" content="width=device-width, initial-scale=1">';

		} else {

			echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">';

		}

		 

	}

?>



<?php do_action('wp_seo'); ?>



<link rel="shortcut icon" href="<?php mfn_opts_show( 'favicon-img', THEME_URI .'/images/favicon.ico' ); ?>" />	

<?php if( mfn_opts_get('apple-touch-icon') ): ?>

<link rel="apple-touch-icon" href="<?php mfn_opts_show( 'apple-touch-icon' ); ?>" />

<?php endif; ?>	



<!-- wp_head() -->

<?php wp_head(); ?>

<script>

jQuery(document).ready(function(){

	jQuery(".openImgG").click(function(){

		jQuery('.fg-listItem:first-child img').click();

	}) 

	jQuery("#menu-item-51").click(function(){

		jQuery(".tcom-search-overlay").addClass("open").removeClass("close");

		jQuery("html").css("overflow","hidden");

	}) 

	jQuery(".tcom-nav-close-btn").click(function(){

		jQuery(".tcom-search-overlay").addClass("close").removeClass("open");

		jQuery("html").css("overflow","auto");

	}) 

	jQuery('.tcom-nav-search-input').keyup(function(){



    		if( jQuery(this).val().length === 0 ) {

       			jQuery(".tcom-nav-search-submit-btn").removeClass('show');



   	 	}else{

			jQuery(".tcom-nav-search-submit-btn").addClass('show');



		}

	});

})

</script>

</head>



<!-- body -->

<body <?php body_class(); ?>>





<div class="tcom-search-overlay tcom-overlay tcom-flyout close" id="tcom-nav-search-flyout">

        <div class="tcom-nav-search-wrapper">

            <div class="tcom-nav-search-box">

                <form autocomplete="off" class="tcom-nav-search-form" method="get" action="/">

                    <input type="text"  isie="false" class="tcom-nav-search-input" placeholder="Type here to search" name="s">

                    

                    <button class="tcom-nav-search-submit-btn">Go</button>

                </form>

            </div>



            <div class="tcom-nav-close-btn-wrapper">

                <button class="tcom-nav-close-btn">

                    <!--[if gte IE 9]><!-->        <svg xml:space="preserve" enable-background="new 0 0 45 45" viewBox="0 0 45 45" height="45px" width="45px" y="0px" x="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1" class="tcom-icon tcom-icon-btn-close-large"><g><polygon points="43.555,45 45,43.556 23.945,22.5 45,1.445 43.555,0 22.5,21.056 1.445,0 0,1.446 21.055,22.5 0,43.556 1.445,45 22.5,23.945 "/></g></svg>

<!--<![endif]-->

                </button>

            </div>

        </div>

    </div>	





	

	<?php do_action( 'mfn_hook_top' ); ?>

	

	<?php get_template_part( 'includes/header', 'sliding-area' ); ?>

	

	<?php if( mfn_header_style( true ) == 'header-creative' ) get_template_part( 'includes/header', 'creative' ); ?>

	

	<!-- #Wrapper -->

	<div id="Wrapper">

	

		<?php 

			// Header Featured Image ----------

			$header_style = '';

			

			// Image -----

			if( mfn_ID() && ! is_search() ){

				

				if( ( ( mfn_ID() == get_option( 'page_for_posts' ) ) || ( get_post_type( mfn_ID() ) == 'page' ) ) && has_post_thumbnail( mfn_ID() ) ){



					// Pages & Blog Page ---

					$subheader_image = wp_get_attachment_image_src( get_post_thumbnail_id( mfn_ID() ), 'full' );

					$header_style .= ' style="background-image:url('. $subheader_image[0] .');"';



				} elseif( get_post_meta( mfn_ID(), 'mfn-post-header-bg', true ) ){



					// Single Post ---

					$header_style .= ' style="background-image:url('. get_post_meta( mfn_ID(), 'mfn-post-header-bg', true ) .');"';



				}

			}

			

			// Attachment -----

			if( mfn_opts_get('img-subheader-attachment') == 'fixed' ){

				

				$header_style .= ' class="bg-fixed"';

				

			} elseif( mfn_opts_get('img-subheader-attachment') == 'parallax' ){

				

				if( mfn_opts_get( 'parallax' ) == 'stellar' ){

					$header_style .= ' class="bg-parallax" data-stellar-background-ratio="0.5"';

				} else {

					$header_style .= ' class="bg-parallax" data-enllax-ratio="0.3"';

				}

				

			}

		?>

		

		<?php if( mfn_header_style( true ) == 'header-below' ) echo mfn_slider(); ?>



		<!-- #Header_bg -->

		<div id="Header_wrapper" <?php echo $header_style; ?>>

	

			<!-- #Header -->

			<header id="Header">

				<?php if( mfn_header_style( true ) != 'header-creative' ) get_template_part( 'includes/header', 'top-area' ); ?>	

				<?php if( mfn_header_style( true ) != 'header-below' ) echo mfn_slider(); ?>

			</header>

				

			<?php 

				if( ( mfn_opts_get('subheader') != 'all' ) && 

					( ! get_post_meta( mfn_ID(), 'mfn-post-hide-title', true ) ) &&

					( get_post_meta( mfn_ID(), 'mfn-post-template', true ) != 'intro' )	){



					

					$subheader_advanced = mfn_opts_get( 'subheader-advanced' );

					

					$subheader_style = '';

					

					if( mfn_opts_get( 'subheader-padding' ) ){

						$subheader_style .= 'padding:'. mfn_opts_get( 'subheader-padding' ) .';';

					}				

					

					

					if( is_search() ){

						// Page title -------------------------

						

						echo '<div id="Subheader" style="'. $subheader_style .'">';

							echo '<div class="container">';

								echo '<div class="column one">';



									if( trim( $_GET['s'] ) ){

										global $wp_query;

										$total_results = $wp_query->found_posts;

									} else {

										$total_results = 0;

									}



									$translate['search-results'] = mfn_opts_get('translate') ? mfn_opts_get('translate-search-results','results found for:') : __('results found for:','betheme');								

									echo '<h1 class="title">'. $total_results .' '. $translate['search-results'] .' '. esc_html( $_GET['s'] ) .'</h1>';

									

								echo '</div>';

							echo '</div>';

						echo '</div>';

						

						

					} elseif( ! mfn_slider_isset() || ( is_array( $subheader_advanced ) && isset( $subheader_advanced['slider-show'] ) ) ){

						// Page title -------------------------

						

						

						// Subheader | Options

						$subheader_options = mfn_opts_get( 'subheader' );





						if( is_home() && ! get_option( 'page_for_posts' ) && ! mfn_opts_get( 'blog-page' ) ){

							$subheader_show = false;

						} elseif( is_array( $subheader_options ) && isset( $subheader_options['hide-subheader'] ) ){

							$subheader_show = false;

						} elseif( get_post_meta( mfn_ID(), 'mfn-post-hide-title', true ) ){

							$subheader_show = false;

						} else {

							$subheader_show = true;

						}

						

						if( is_array( $subheader_options ) && isset( $subheader_options['hide-breadcrumbs'] ) ){

							$breadcrumbs_show = false;

						} else {

							$breadcrumbs_show = true;

						}

						

						

						if( is_array( $subheader_advanced ) && isset( $subheader_advanced['breadcrumbs-link'] ) ){

							$breadcrumbs_link = 'has-link';

						} else {

							$breadcrumbs_link = 'no-link';

						}

						

						

						// Subheader | Print

						if( $subheader_show ){

							echo '<div id="Subheader" style="'. $subheader_style .'">';

								echo '<div class="container">';

									echo '<div class="column one">';

										

										// Title

										$title_tag = mfn_opts_get( 'subheader-title-tag', 'h1' );

										echo '<'. $title_tag .' class="title">'. mfn_page_title() .'</'. $title_tag .'>';

										

										// Breadcrumbs

										if( $breadcrumbs_show ) mfn_breadcrumbs( $breadcrumbs_link );

										

									echo '</div>';

								echo '</div>';

							echo '</div>';

						}

						

					}

					

					

				}

			?>

		

		</div>

		

		<?php 

			// Single Post | Template: Intro

			if( get_post_meta( mfn_ID(), 'mfn-post-template', true ) == 'intro' ){

				get_template_part( 'includes/header', 'single-intro' );

			}

		?>

		

		<?php do_action( 'mfn_hook_content_before' );

		

// Omit Closing PHP Tags
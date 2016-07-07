<?php

/**

 * The search template file.

 *

 * @package Betheme

 * @author Muffin group

 * @link http://muffingroup.com

 */



get_header();

?>
<link rel='stylesheet' id='js_composer_front-css'  href='/wp-content/plugins/js_composer/assets/css/js_composer.min.css?ver=4.11.2.1' type='text/css' media='all' />
<div class="section_wrapper">

<h2 style="font-size: 38px;color: #000000;" class="vc_custom_heading uppercase headline">Search Results</h2>	
<style>r
.footer_top{
overflow:auto;
}
</style>
<?

$translate['search-title'] = mfn_opts_get('translate') ? mfn_opts_get('translate-search-title','Ooops...') : __('Ooops...','betheme');

$translate['search-subtitle'] = mfn_opts_get('translate') ? mfn_opts_get('translate-search-subtitle','No results found for:') : __('No results found for:','betheme');



$translate['published'] 	= mfn_opts_get('translate') ? mfn_opts_get('translate-published','Published by') : __('Published by','betheme');

$translate['at'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-at','at') : __('at','betheme');

$translate['readmore'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-readmore','Read more') : __('Read more','betheme');

?>



<?php
echo do_shortcode('[button title="HOME" icon="" icon_position="" link="/" target="" color="" font_color="" size="1" full_width="" class="searchHome" download="" onclick=""]'); 
echo '<div id="searchResult" style="'. $subheader_style .'">';

							echo '<div class="container">';

								echo '<div class="column one">';



									if( trim( $_GET['s'] ) ){

										global $wp_query;

										$total_results = $wp_query->found_posts;

									} else {

										$total_results = 0;

									}



									$translate['search-results'] = mfn_opts_get('translate') ? mfn_opts_get('translate-search-results','results found for:') : __('results found for:','betheme');								

									echo '<h1 class="title">'. esc_html( $_GET['s'] ). ': ' .$total_results .' Results'.'</h1>';

									

								echo '</div>';

							echo '</div>';

						echo '</div>';

 ?>

<div id="Content">

	<div class="content_wrapper clearfix">





		<!-- .sections_group -->

		<div class="sections_group">

		

			<div class="section">

				<div class="section_wrapper clearfix">

				

					<?php if( have_posts() && trim( $_GET['s'] ) ): ?>

					

						<div class="column one column_blog">	

							<div class="blog_wrapper isotope_wrapper">

				

								<div class="posts_group classic">

									<?php

										while ( have_posts() ):

											the_post();

											?>

											<div id="post-<?php the_ID(); ?>" <?php post_class( array('post-item', 'clearfix', 'no-img') ); ?>>

												

												<div class="post-desc-wrapper">

													<div class="post-desc">

													

														<?php /* if( mfn_opts_get( 'blog-meta' ) ): ?>

															<div class="post-meta clearfix">

																<div class="author-date">

																	<span class="author"><span><?php echo $translate['published']; ?> </span><i class="icon-user"></i> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author_meta( 'display_name' ); ?></a></span>

																	<span class="date"><span><?php echo $translate['at']; ?> </span><i class="icon-clock"></i> <?php echo get_the_date(); ?></span>

																</div>

															</div>

														<?php  endif; */ ?>

														

													

														<div class="post-title">

															<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

														</div>

														

														<!-- <div class="post-excerpt">

															<?php the_excerpt(); ?>

														</div> -->

		

														<div class="post-footer">

															<div class="post-links">

																<i class="icon-doc-text"></i> <a href="<?php the_permalink(); ?>" class="post-more"><?php echo $translate['readmore']; ?></a>

															</div>

														</div>

							

													</div>

												</div>

											</div>

											<?php

										endwhile;

									?>

								</div>

						

								<?php	

									// pagination

									if(function_exists( 'mfn_pagination' )):

										echo mfn_pagination();

									else:

										?>

											<div class="nav-next"><?php next_posts_link(__('&larr; Older Entries', 'betheme')) ?></div>

											<div class="nav-previous"><?php previous_posts_link(__('Newer Entries &rarr;', 'betheme')) ?></div>

										<?php

									endif;

								?>

						

							</div>

						</div>

						

					<?php else: ?>

					

						<div class="column one column_blog">

						

						<?php /*?><div class="snf-pic">

								<i class="themecolor <?php mfn_opts_show( 'error404-icon', 'icon-traffic-cone' ); ?>"></i>

							</div><?php */?>

							

							<?php /*?><div class="snf-desc">

								<h2><?php// echo $translate['search-title']; ?></h2>

								<h4><?php //echo $translate['search-subtitle'] .' '. esc_html( $_GET['s'] ); ?></h4>

							</div><?php */?>

	

										

						</div>	

						

					<?php endif; ?>

			<?php



$my_id = 290;

$post_id_5369 = get_post($my_id);

$content = $post_id_5369->post_content;

$content = apply_filters('the_content', $content);

$content = str_replace(']]>', ']]>', $content);

echo $content;

?>		

				</div>

    

     







			</div>

			

   

  



		</div>

		

		

		<!-- .four-columns - sidebar -->

		

		<?php if( is_active_sidebar( 'mfn-search' ) ):  ?>

			<div class="sidebar four columns">

				<div class="widget-area clearfix <?php mfn_opts_show( 'sidebar-lines' ); ?>">

					<?php dynamic_sidebar( 'mfn-search' ); ?>

				</div>

			</div>

		<?php endif; ?>



	</div>

</div>

</div>

<style>

/*div{border:1px solid}*/

/*-----footer styling------*/

.footer_top{

    border-top-width: 2px !important;

    background-color: #edf0f5 !important;

    border-top-color: #e0e0e0 !important;

    border-top-style: solid !important;

    padding: 20px 10px 15px;

}

.vc_custom_1463414739553 {

    border-top-width: 1px !important;

    background-color: #edf0f5 !important;

    border-top-color: #000000 !important;

    border-top-style: dotted !important;

    padding: 0 10px;

}

.vc_custom_1463409864308 {

    background-color: #edf0f5 !important;

    padding: 0 10px;

}

.section_wrapper {

    /*max-width: inherit;*/

    padding: 40px 15px 0;

}

.post-meta {

    display: none;

}



.post-footer {

    display: none;

}



.post-title {

    margin-bottom: 20px;

}



.post-item {

    margin-bottom: 20px;

}

.headline {

    padding-left: 20px;

}

.headline:before {

    left: 40px;    

}

div#Subheader {    display: none;    }

#Subheader:after{background:none}

#Top_bar.loading {

    display: block;

}

/*----------search overlay----------*/

#searchResult .column.one {    margin: 15px 20px 0;}

#searchResult h1.title {    font-size: 1.6em;}



.tcom-nav-search-box {    height: 72px; border-bottom: 1px solid #c00;}

.tcom-nav-search-box input[type="text"]{

margin: 0;

    line-height: normal;

    font-size: 3.2em;

    font-family: "tcomLight","HelvNeueLight","Helvetica Neue Light",Arial,sans-serif;

    padding: 0;
	padding-right:114px;
}

.tcom-nav-close-btn{   transition:all 0s;     float: right; box-shadow: none;}

.tcom-nav-close-btn .tcom-icon {    fill: #ccc;        width: 45px;    }

.tcom-search-overlay {top:94px; transition: opacity .7s;}

.tcom-nav-close-btn:hover:after {  transition:all 0s;  background: transparent !important;}

/*----------search overlay----------*/

.footer_top:before {

    position: absolute;

    width: 4000px;

    height: 92px;

    /* left: -1400px; */

    margin-top: -22px;

    right: -1400px;

    text-indent: -99999;

    background-color: #edf0f5 !important;

    border-bottom: 1px dotted;

    border-top: 2px solid #e0e0e0 !important;

}

.vc_row.wpb_row.vc_row-fluid.footer_signup.vc_custom_1463414739553.vc_row-has-fill:before {

    position: absolute;

    width: 4000px;

    left: -1400px;

    background-color: #edf0f5;

    height: 520px;

    margin-top: 2px;

}
#back_to_top.sticky.scroll {
    opacity: .2;
    background: aliceblue;
    border-radius: 100px;
    height: 35px;
}

span.button_icon {
    padding: 5px 12px !important;
    color:#fff;
}
a.button .button_icon i {
    color: rgba(250,250,250,.5);
}
.pager a.page.active {
    background-color: #d61f26;
}
/*-----footer styling------*/
/*----Tablet-----*/
@media only screen and (max-width: 1239px) and (min-width: 960px){
#Top_bar .top_bar_left {    width: 100%;}

}
@media (max-width: 60em){
    /*topbar*/
    #Top_bar .top_bar_left {    width: 100%;    }
    #Top_bar .menu > li {        padding: 0 5px;    }
    /*searchbar*/
    .vc_column_container.vc_col-sm-6 .vc_column-inner{ padding:0}
    .footersearch form input[type="text"]{width:250px}
}/*ends tablet css*/
/*----Tablet-----*/
/*----Mobile-----*/
@media(max-width: 47.9375em){

    .section_wrapper {padding: 20px 0 !important;}
    .section_wrapper .column{padding:0 15px}
.vc_row.wpb_row.vc_row-fluid.footer_top {
    position: absolute !important;
    left: 16px !important;
    bottom: 49px;
    z-index: 1;
    background-color: #fff !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    width: 100%;
    padding: 14px
}
.vc_row.wpb_row.vc_row-fluid.footer_top:before {
    background-color: #fff !important;
}
.footer-legal.wpb_column{
    margin-top: 85px;
    height: 48px;
    padding: 0px 10px 10px 30px;
    background-color: #fff;
}
.section_wrapper {
    padding: 20px 0 0 !important;
}
#Top_bar .menu_wrapper.active .menu-main-menu-container {
    display: block !important;
}
#searchResult .column.one { margin: 15px 0px 0;}
.headline {    padding-left: 10px;}
.headline:before {    left: 30px;}
}/*ends mobile css*/
/*----Mobile-----*/
</style>
<script>

jQuery('.tcom-footer-links-title').click(function(){
if(jQuery(window).outerWidth() < 750){
jQuery(event.target).parent().parent().parent().toggleClass('open')
}
event.preventDefault();

})

jQuery('.responsive-menu-toggle').append('<svg xml:space="preserve" enable-background="new 16.636 16.635 13 13" viewBox="16.636 16.635 13 13" height="13px" width="13px" y="0px" x="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1" class="tcom-icon mobile-nav-close"><g><polygon points="29.636,18.08 28.191,16.635 23.136,21.69 18.08,16.635 16.636,18.08 21.691,23.136 16.636,28.19 18.081,29.635 23.136,24.58 28.191,29.635 29.636,28.19 24.581,23.136"></polygon></g></svg>')
jQuery('.responsive-menu-toggle').click(function(){
	jQuery(this).toggleClass('active');
jQuery(this).parent().toggleClass('active');
})
</script>
<?php //get_footer();



// Omit Closing PHP Tags
<?php
/**
 * The main template file.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

$translate['search-placeholder'] = mfn_opts_get('translate') ? mfn_opts_get('translate-search-placeholder','Enter your search') : __('Enter your search','betheme');
?>
<style>
body.search  .searchHome {
    float: right !important;
}
body.search .headline {
    float: left;
}
div#searchResult {
    clear: both;
}
</style>
<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						
	<?php if( mfn_opts_get('header-search') == 'shop' ): ?>
		<input type="hidden" name="post_type" value="product" />
	<?php endif;?>
    <svg class="icon-search vdisabled" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="34px" height="34px" viewBox="0 0 34 34" enable-background="new 0 0 34 34" xml:space="preserve" ><g><path d="M30.402,34c-0.439,0-0.853-0.171-1.165-0.482l-8.505-8.504c-0.551-0.553-0.63-1.398-0.238-2.033 c-2.145,1.628-4.819,2.596-7.713,2.596h-0.018C5.726,25.576,0,19.838,0,12.783C0,5.735,5.739,0,12.792,0 c7.054,0,12.792,5.735,12.792,12.784c0,0.072-0.008,0.142-0.023,0.21c-0.044,2.816-1.002,5.412-2.59,7.506 c0.636-0.393,1.525-0.287,2.04,0.253l8.501,8.487c0.65,0.701,0.645,1.667,0.012,2.322l-1.955,1.954 C31.256,33.829,30.843,34,30.402,34z M22.286,23.845l8.117,8.117l1.556-1.555l-8.125-8.113L22.286,23.845z M12.792,1.924 c-5.993,0-10.869,4.872-10.869,10.86c0,5.993,4.863,10.869,10.84,10.869v0.962l0.016-0.962c5.989,0,10.86-4.872,10.86-10.86 c0-0.067,0.006-0.132,0.02-0.195C23.56,6.694,18.723,1.924,12.792,1.924z"></path></g></svg>
    
    <button class="icon_close" onclick="javascript: void(0); return false;" role="button">
							<svg class="site-search__close-svg" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="45px" height="45px" viewBox="0 0 45 45" enable-background="new 0 0 45 45" xml:space="preserve"><g><polygon points="43.555,45 45,43.556 23.945,22.5 45,1.445 43.555,0 22.5,21.056 1.445,0 0,1.446 21.055,22.5 0,43.556 1.445,45 22.5,23.945 "></polygon></g></svg>
						</button>
	
	<input type="text" class="field" name="s" id="s" placeholder="Type here to search" />
    <button class="submit-btn" role="button">GO</button>			
	
</form>

<script>
jQuery(document).ready(function() {
	
jQuery("#logo").attr("href", "http://www.toyota.com/usa/");

	//jQuery('.field').focus(function()
	//{
		//alert("hello");
		jQuery('.field').on('input',function(e){
			if (jQuery(this).val() != "") {
			jQuery(".submit-btn").css('display','block');
			} else {
			jQuery(".submit-btn").css('display','none');
			}
		});
	//});
	


});
</script>
<?php
/*
 * Custom Share Buttons With Floating Sidebar Pro(C)
 * @get_csbwf_pro_sidebar_options()
 * @get_csbwf_pro_sidebar_content()
 * */
?>
<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
if(! session_id()){session_start();}
// get all options value for "Custom Share Buttons with Floating Sidebar"
	function get_csbwf_pro_sidebar_options() {
		global $wpdb;
		$ctOptions = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'csbwfs_%'");
		foreach ($ctOptions as $option) {
			$ctOptions[$option->option_name] =  $option->option_value;
		}
		return $ctOptions;	
	}
/** Get the current url*/
if(!function_exists('csbwfs_pro_current_path_protocol')):
function csbwfs_pro_current_path_protocol($s, $use_forwarded_host=false)
{
    $pwahttp = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $pwasprotocal = strtolower($s['SERVER_PROTOCOL']);
    $pwa_protocol = substr($pwasprotocal, 0, strpos($pwasprotocal, '/')) . (($pwahttp) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$pwahttp && $port=='80') || ($pwahttp && $port=='443')) ? '' : ':'.$port;
    $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $pwa_protocol . '://' . $host;
}
endif;
if(!function_exists('csbwfs_pro_get_current_page_url')):
function csbwfs_pro_get_current_page_url($s, $use_forwarded_host=false)
{
    return csbwfs_pro_current_path_protocol($s, $use_forwarded_host) . $s['REQUEST_URI'];
}
endif;
/* 
 * Site is browsing in mobile or not
 * @IsMobile()
 * */
function isCsbwfsMobilePro() {
// Check the server headers to see if they're mobile friendly
if(isset($_SERVER["HTTP_X_WAP_PROFILE"])) {
    return true;
}
// Let's NOT return "mobile" if it's an iPhone, because the iPhone can render normal pages quite well.
if(strstr($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
    return false;
}
// If the http_accept header supports wap then it's a mobile too
if(preg_match("/wap\.|\.wap/i",$_SERVER["HTTP_ACCEPT"])) {
    return true;
}
// Still no luck? Let's have a look at the user agent on the browser. If it contains
// any of the following, it's probably a mobile device. Kappow!
if(isset($_SERVER["HTTP_USER_AGENT"])){
    $user_agents = array("midp", "j2me", "avantg", "docomo", "novarra", "palmos", "palmsource", "240x320", "opwv", "chtml", "pda", "windows\ ce", "mmp\/", "blackberry", "mib\/", "symbian", "wireless", "nokia", "hand", "mobi", "phone", "cdm", "up\.b", "audio", "SIE\-", "SEC\-", "samsung", "HTC", "mot\-", "mitsu", "sagem", "sony", "alcatel", "lg", "erics", "vx", "NEC", "philips", "mmm", "xx", "panasonic", "sharp", "wap", "sch", "rover", "pocket", "benq", "java", "pt", "pg", "vox", "amoi", "bird", "compal", "kg", "voda", "sany", "kdd", "dbt", "sendo", "sgh", "gradi", "jb", "\d\d\di", "moto");
    foreach($user_agents as $user_string){
        if(preg_match("/".$user_string."/i",$_SERVER["HTTP_USER_AGENT"])) {
            return true;
        }
    }
}
// None of the above? Then it's probably not a mobile device.
return false;
}
// Get plugin options
global $pluginOptionsVal;
$pluginOptionsVal=get_csbwf_pro_sidebar_options();
//check plugin in enable or not
if(isset($pluginOptionsVal['csbwfs_pro_active']) && $pluginOptionsVal['csbwfs_pro_active']==1){
	
if((isCsbwfsMobilePro()) && 
isset($pluginOptionsVal['csbwfs_deactive_for_mob']) && $pluginOptionsVal['csbwfs_deactive_for_mob']!='')
{
// silent is Gold;
}else
{
add_action('wp_footer','get_csbwf_pro_sidebar_content');
add_action( 'wp_enqueue_scripts', 'csbwf_pro_sidebar_scripts' );
add_action('wp_footer','csbwf_pro_sidebar_load_inline_js');
add_action('wp_footer','csbwfs_pro_cookie');
add_action('wp_head','print_inline_css');
}

}

function print_inline_css()
{
	echo '<style type="text/css" media="print">#csbwfs-social-inner { display:none; }</style>';
	}
function csbwfs_pro_cookie()
{
	echo $cookieVal='<script>csbwfsCheckCookie();function csbwfsSetCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname+"="+cvalue+"; "+expires;
}

function csbwfsGetCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(\';\');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==\' \') c = c.substring(1);
        if (c.indexOf(name) != -1) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function csbwfsCheckCookie() {
    var button_status=csbwfsGetCookie("csbwfs_show_hide_status");
    if (button_status != "") {
        
    } else {
      // user = prompt("Please enter your name:","");
     //  if (user != "" && user != null) {
        csbwfsSetCookie("csbwfs_show_hide_status", "active",1);
      // }
    }
}

</script>';


}
/* Share count fucntion */
if(get_option('csbwfs_pro_buttons_active')!='' || get_option('csbwfs_pro_active')!=''){
add_filter( 'wp_head', 'csbfs_pro_share_count');
}
function csbfs_pro_share_count()
{
global $post;	
$pluginOptionsVal=get_csbwf_pro_sidebar_options();
/** get current url */   
$shareurl = csbwfs_pro_get_current_page_url($_SERVER);

require dirname(__FILE__).'/lib/csbwfs-share-count.php';
	
if($pluginOptionsVal['csbwfs_fpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_fb_page_url']) && $pluginOptionsVal['csbwfs_fb_page_url']!=''):
$fbshareurl=$pluginOptionsVal['csbwfs_fb_page_url'];
else:
$fbshareurl=$shareurl;
endif;
$fbcntdiv='';
$fbcntval=csbwfs_return_count_fb($fbshareurl);
endif;
	

if($pluginOptionsVal['csbwfs_gpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_gp_page_url']) && $pluginOptionsVal['csbwfs_gp_page_url']!=''):
$gpshareurl=$pluginOptionsVal['csbwfs_gp_page_url'];
else:
$gpshareurl=$shareurl;
endif;
$gpcntdiv='';
$gpcntval=csbwfs_return_count_gp($gpshareurl);
endif;

if($pluginOptionsVal['csbwfs_lpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_li_page_url']) && $pluginOptionsVal['csbwfs_li_page_url']!=''):
$lishareurl=$pluginOptionsVal['csbwfs_li_page_url'];
else:
$lishareurl=$shareurl;
endif;
$licntdiv='';
$licntval=csbwfs_return_count_li($lishareurl);
endif;

if($pluginOptionsVal['csbwfs_ppublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_pin_page_url']) && $pluginOptionsVal['csbwfs_pin_page_url']!=''):
$pinshareurl=$pluginOptionsVal['csbwfs_pin_page_url'];
$pinshareurlCond='no';
else:
$pinshareurl=$shareurl;
$pinshareurlCond='';
endif;
$pincntdiv='';
$pincntval=csbwfs_return_count_pi($pinshareurl);
endif;

if(isset($pluginOptionsVal['csbwfs_stpublishBtn']) && $pluginOptionsVal['csbwfs_stpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_st_page_url']) && $pluginOptionsVal['csbwfs_st_page_url']!=''):
$stshareurl=$pluginOptionsVal['csbwfs_st_page_url'];
else:
$stshareurl=$shareurl;
endif;
$stcntdiv='';
$stcntval=csbwfs_return_count_st($stshareurl);
endif;

if(isset($pluginOptionsVal['csbwfs_republishBtn']) && $pluginOptionsVal['csbwfs_republishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_re_page_url']) && $pluginOptionsVal['csbwfs_re_page_url']!=''):
$reshareurl=$pluginOptionsVal['csbwfs_re_page_url'];
else:
$reshareurl=$shareurl;
endif;
$recntdiv='';
$recntval=csbwfs_return_count_re($reshareurl);
endif;

if(isset($pluginOptionsVal['csbwfs_tupublishBtn']) && $pluginOptionsVal['csbwfs_tupublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_tu_page_url']) && $pluginOptionsVal['csbwfs_tu_page_url']!=''):
$tushareurl=$pluginOptionsVal['csbwfs_tu_page_url'];
else:
$tushareurl=$shareurl;
endif;
$tucntdiv='';
$tucntval=csbwfs_return_count_tu($tushareurl);
endif;

}

if(isset($pluginOptionsVal['csbwfs_pro_buttons_active']) && $pluginOptionsVal['csbwfs_pro_buttons_active']==1){
add_action( 'wp_enqueue_scripts', 'csbwf_pro_sidebar_scripts' );
add_filter( 'the_content', 'csbfs_pro_the_content_filter', 20);
}

//register style and scrip files
function csbwf_pro_sidebar_scripts() {
$pluginOptionsVal=get_csbwf_pro_sidebar_options();
wp_enqueue_script( 'jquery' ); // wordpress jQuery
wp_register_style( 'csbwf_pro_sidebar_style', plugins_url( 'css/csbwfs-pro.css',__FILE__ ) );
wp_enqueue_style( 'csbwf_pro_sidebar_style' );
/** default lightbox form css*/
if(isset($pluginOptionsVal['csbwfs_mail_css']) && $pluginOptionsVal['csbwfs_mail_css']==''):
wp_register_style( 'csbwf_pro_sidebar_form_style', plugins_url( 'css/csbwfs-form.css',__FILE__ ) );
wp_enqueue_style( 'csbwf_pro_sidebar_form_style' );
endif;
}

/*
-----------------------------------------------------------------------------------------------
                              "Add the jQuery code in head section using hooks"
-----------------------------------------------------------------------------------------------
*/


function csbwf_pro_sidebar_load_inline_js()
{
   $pluginOptionsVal=get_csbwf_pro_sidebar_options();
	$jscnt='<script>
	  var windWidth=jQuery( window ).width();
	  //alert(windWidth);
	  var animateWidth;
	  var defaultAnimateWidth;
	  animateHeight="49";
	 defaultAnimateHeight= animateHeight-2;';
  $jscnt.='
	jQuery(document).ready(function()
  { if(windWidth < "500" )
   {
	   animateWidth="45";
	   defaultAnimateWidth= animateWidth-10;
	   }else
			   {
				   animateWidth="55";
				   defaultAnimateWidth= animateWidth-10;
				   }';
if($pluginOptionsVal['csbwfs_position']=='right' || $pluginOptionsVal['csbwfs_position']=='left'): 
	if($pluginOptionsVal['csbwfs_delayTimeBtn']!='0'):
	$jscnt.='jQuery("#csbwfs-delaydiv").hide();
	setTimeout(function(){
	jQuery("#csbwfs-delaydiv").fadeIn();}, '.$pluginOptionsVal['csbwfs_delayTimeBtn'].');';
	endif;
	/** animation effect */  
	if(isset($pluginOptionsVal['csbwfs_disable_hover']) && $pluginOptionsVal['csbwfs_disable_hover']!='yes'):
	$jscnt.='jQuery("#csbwfs-delaydiv .csbwfs-social-widget .csbwfs-sbutton a").hover(function(){
	jQuery(this).animate({width:animateWidth});
	},function(){
	jQuery(this).stop( true, true ).animate({width:defaultAnimateWidth});
	});';
	endif;
	if(isset($pluginOptionsVal['csbwfs_printpublishBtn']) && $pluginOptionsVal['csbwfs_printpublishBtn']!=''):
	$jscnt.='jQuery("div.csbwfs-print a").click(function(){
     var csbwfsdivElements1 = jQuery("body").html();
     jQuery("#csbwfs-delaydiv").html("");
	 var csbwfsdivElements = jQuery("body").html();
	 document.body.innerHTML = 
          "<html><head><title></title></head><body>" + 
          csbwfsdivElements + "</body>";
           window.print();
           window.close();
          jQuery("body").html(csbwfsdivElements1);
	});
	';
	endif;
/** custom image buttons start here */	
	$csbwfsCustomWidth3=$csbwfsCustomWidth4='150';
	$csbwfsCustomWidth=$csbwfsCustomWidth2=$csbwfsCustomWidth5=$csbwfsCustomWidth6=$csbwfsCustomWidth7=$csbwfsCustomWidth8=$csbwfsCustomWidth9=$csbwfsCustomWidth10='45';
	$animatedWidth1=$animatedWidth2=$animatedWidth5=$animatedWidth6=$animatedWidth7=$animatedWidth8=$animatedWidth9=$animatedWidth10='55px';
	
	if(isset($pluginOptionsVal['csbwfs_custom_width']) && $pluginOptionsVal['csbwfs_custom_width']!=''):
	$csbwfsCustomWidth=$pluginOptionsVal['csbwfs_custom_width'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom1_defltwidth']) && $pluginOptionsVal['csbwfs_custom1_defltwidth']!=''):
	$animatedWidth1=$pluginOptionsVal['csbwfs_custom1_defltwidth'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom2_width']) && $pluginOptionsVal['csbwfs_custom2_width']!=''):
	$csbwfsCustomWidth2=$pluginOptionsVal['csbwfs_custom2_width'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom2_defltwidth']) && $pluginOptionsVal['csbwfs_custom2_defltwidth']!=''):
	$animatedWidth2=$pluginOptionsVal['csbwfs_custom2_defltwidth'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom3_width']) && $pluginOptionsVal['csbwfs_custom3_width']!=''):
	$csbwfsCustomWidth3=$pluginOptionsVal['csbwfs_custom3_width'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom4_width']) && $pluginOptionsVal['csbwfs_custom4_width']!=''):
	$csbwfsCustomWidth4=$pluginOptionsVal['csbwfs_custom4_width'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom5_width']) && $pluginOptionsVal['csbwfs_custom5_width']!=''):
	$csbwfsCustomWidth5=$pluginOptionsVal['csbwfs_custom5_width'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom6_width']) && $pluginOptionsVal['csbwfs_custom6_width']!=''):
	$csbwfsCustomWidth6=$pluginOptionsVal['csbwfs_custom6_width'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom7_width']) && $pluginOptionsVal['csbwfs_custom7_width']!=''):
	$csbwfsCustomWidth7=$pluginOptionsVal['csbwfs_custom7_width'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom8_width']) && $pluginOptionsVal['csbwfs_custom8_width']!=''):
	$csbwfsCustomWidth8=$pluginOptionsVal['csbwfs_custom8_width'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom9_width']) && $pluginOptionsVal['csbwfs_custom9_width']!=''):
	$csbwfsCustomWidth9=$pluginOptionsVal['csbwfs_custom9_width'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom10_width']) && $pluginOptionsVal['csbwfs_custom10_width']!=''):
	$csbwfsCustomWidth10=$pluginOptionsVal['csbwfs_custom10_width'];
    endif;
	

	if(isset($pluginOptionsVal['csbwfs_custom5_defltwidth']) && $pluginOptionsVal['csbwfs_custom5_defltwidth']!=''):
	$animatedWidth5=$pluginOptionsVal['csbwfs_custom5_defltwidth'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom6_defltwidth']) && $pluginOptionsVal['csbwfs_custom6_defltwidth']!=''):
	$animatedWidth6=$pluginOptionsVal['csbwfs_custom6_defltwidth'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom7_defltwidth']) && $pluginOptionsVal['csbwfs_custom7_defltwidth']!=''):
	$animatedWidth7=$pluginOptionsVal['csbwfs_custom7_defltwidth'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom8_defltwidth']) && $pluginOptionsVal['csbwfs_custom8_defltwidth']!=''):
	$animatedWidth8=$pluginOptionsVal['csbwfs_custom8_defltwidth'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom9_defltwidth']) && $pluginOptionsVal['csbwfs_custom9_defltwidth']!=''):
	$animatedWidth9=$pluginOptionsVal['csbwfs_custom9_defltwidth'];
	endif;

	if(isset($pluginOptionsVal['csbwfs_custom10_defltwidth']) && $pluginOptionsVal['csbwfs_custom10_defltwidth']!=''):
	$animatedWidth10=$pluginOptionsVal['csbwfs_custom10_defltwidth'];
	endif;
/** Check animation remove or not */
if(isset($pluginOptionsVal['csbwfs_c1publishBtn']) && $pluginOptionsVal['csbwfs_c1publishBtn']!=''):
$jscnt.='jQuery("div#csbwfs-c1 a").hover(function(){
	jQuery("div.custom1 .title").show();
	jQuery("div#csbwfs-c1 a").animate({width:"'.$csbwfsCustomWidth.'"});
	},function(){
	jQuery("div#csbwfs-c1 a").stop( true, true ).animate({width:"'.$animatedWidth1.'"});
	   jQuery("div.custom1 .title").hide();
	});';
	endif;

if(isset($pluginOptionsVal['csbwfs_c2publishBtn']) && $pluginOptionsVal['csbwfs_c2publishBtn']!=''):
	$jscnt.='jQuery("div#csbwfs-c2 a").hover(function(){
	jQuery("div.custom2 .title").show();
	jQuery("div#csbwfs-c2 a").animate({width:"'.$csbwfsCustomWidth2.'"});
	},function(){
	jQuery("div#csbwfs-c2 a").stop( true, true ).animate({width:"'.$animatedWidth2.'"});
	jQuery("div.custom2 .title").hide();
	});';
endif;

if(isset($pluginOptionsVal['csbwfs_c3publishBtn']) && $pluginOptionsVal['csbwfs_c3publishBtn']!=''):
	if($pluginOptionsVal['csbwfs_custom3_defltwidth']){$wth3=$pluginOptionsVal['csbwfs_custom3_defltwidth'];}else {$wth3='45';}
	$jscnt.='jQuery("div#csbwfs-c3 a").hover(function(){
	jQuery("div.custom3 .title").show();
	jQuery("div#csbwfs-c3 a").animate({width:'.$csbwfsCustomWidth3.'});
	},function(){
	jQuery("div#csbwfs-c3 a").stop( true, true ).animate({width:'.$wth3.'});
	   jQuery("div.custom3 .title").hide();
	});';
 endif;
if(isset($pluginOptionsVal['csbwfs_c4publishBtn']) && $pluginOptionsVal['csbwfs_c4publishBtn']!=''):
	if($pluginOptionsVal['csbwfs_custom4_defltwidth']){$wth4=$pluginOptionsVal['csbwfs_custom4_defltwidth'];}else {$wth4='45';}
	$jscnt.='jQuery("div#csbwfs-c4 a").hover(function(){
	jQuery("div.custom4 .title").show();
	jQuery("div#csbwfs-c4 a").animate({width:'.$csbwfsCustomWidth4.'});
	},function(){
	jQuery("div#csbwfs-c4 a").stop( true, true ).animate({width:'.$wth4.'});
	jQuery("div.custom4 .title").hide();
	});';
endif;
if(isset($pluginOptionsVal['csbwfs_c5publishBtn']) && $pluginOptionsVal['csbwfs_c5publishBtn']!=''):
	$jscnt.='jQuery("div#csbwfs-c5 a").hover(function(){
	jQuery("div.custom5 .title").show();
	jQuery("div#csbwfs-c5 a").animate({width:"'.$csbwfsCustomWidth5.'"});
	},function(){
	jQuery("div#csbwfs-c5 a").stop( true, true ).animate({width:"'.$animatedWidth5.'"});
	   jQuery("div.custom5 .title").hide();
	});';
endif;
if(isset($pluginOptionsVal['csbwfs_c6publishBtn']) && $pluginOptionsVal['csbwfs_c6publishBtn']!=''):
	$jscnt.='jQuery("div#csbwfs-c6 a").hover(function(){
	jQuery("div.custom6 .title").show();
	jQuery("div#csbwfs-c6 a").animate({width:"'.$csbwfsCustomWidth6.'"});
	},function(){
	jQuery("div#csbwfs-c6 a").stop( true, true ).animate({width:"'.$animatedWidth6.'"});
	jQuery("div.custom6 .title").hide();
	});';
endif;
if(isset($pluginOptionsVal['csbwfs_c7publishBtn']) && $pluginOptionsVal['csbwfs_c7publishBtn']!=''):
	$jscnt.='jQuery("div#csbwfs-c7 a").hover(function(){
	jQuery("div.custom7 .title").show();
	jQuery("div#csbwfs-c7 a").animate({width:"'.$csbwfsCustomWidth7.'"});
	},function(){
	jQuery("div#csbwfs-c7 a").stop( true, true ).animate({width:"'.$animatedWidth7.'"});
	   jQuery("div.custom7 .title").hide();
	});';
	endif;
if(isset($pluginOptionsVal['csbwfs_c8publishBtn']) && $pluginOptionsVal['csbwfs_c8publishBtn']!=''):
	$jscnt.='jQuery("div#csbwfs-c8 a").hover(function(){
	jQuery("div.custom8 .title").show();
	jQuery("div#csbwfs-c8 a").animate({width:"'.$csbwfsCustomWidth8.'"});
	},function(){
	jQuery("div#csbwfs-c8 a").stop( true, true ).animate({width:"'.$animatedWidth8.'"});
	jQuery("div.custom8 .title").hide();
	});';
endif;
if(isset($pluginOptionsVal['csbwfs_c9publishBtn']) && $pluginOptionsVal['csbwfs_c9publishBtn']!=''):
	$jscnt.='jQuery("div#csbwfs-c9 a").hover(function(){
	jQuery("div.custom9 .title").show();
	jQuery("div#csbwfs-c9 a").animate({width:"'.$csbwfsCustomWidth9.'"});
	},function(){
	jQuery("div#csbwfs-c9 a").stop( true, true ).animate({width:"'.$animatedWidth9.'"});
	   jQuery("div.custom9 .title").hide();
	});';
endif;
if(isset($pluginOptionsVal['csbwfs_c10publishBtn']) && $pluginOptionsVal['csbwfs_c10publishBtn']!=''):
	$jscnt.='jQuery("div#csbwfs-c10 a").hover(function(){
	jQuery("div.custom10 .title").show();
	jQuery("div#csbwfs-c10 a").animate({width:"'.$csbwfsCustomWidth10.'"});
	},function(){
	jQuery("div#csbwfs-c10 a").stop( true, true ).animate({width:"'.$animatedWidth10.'"});
	jQuery("div.custom10 .title").hide();
	});';
endif;
/* End custom buttons */
else: //bottom position
 // silent
 endif;
 
if(isset($pluginOptionsVal['csbwfs_auto_hide']) && $pluginOptionsVal['csbwfs_auto_hide']!=''):
$jscnt.='csbwfsSetCookie("csbwfs_show_hide_status","in_active","1");';
endif;

  $jscnt.='jQuery("div.csbwfs-show").hide();
  jQuery("div.csbwfs-show a").click(function(){
    jQuery("div#csbwfs-social-inner").show();
     jQuery("div.csbwfs-show").hide();
    jQuery("div.csbwfs-hide").show();
    csbwfsSetCookie("csbwfs_show_hide_status","active","1");
  });
  
  jQuery("div.csbwfs-hide a").click(function(){
     jQuery("div.csbwfs-show").show();
      jQuery("div.csbwfs-hide").hide();
     jQuery("div#csbwfs-social-inner").hide();
     csbwfsSetCookie("csbwfs_show_hide_status","in_active","1");
  });';
   $jscnt.='var button_status=csbwfsGetCookie("csbwfs_show_hide_status");
    if (button_status =="in_active") {
      jQuery("div.csbwfs-show").show();
      jQuery("div.csbwfs-hide").hide();
     jQuery("div#csbwfs-social-inner").hide();
    } else {
      jQuery("div#csbwfs-social-inner").show();
     jQuery("div.csbwfs-show").hide();
    jQuery("div.csbwfs-hide").show();
    }';

  
$jscnt.='});';

if($pluginOptionsVal['csbwfs_mpublishBtn']!=''){
$formdivstyle='';
$csbwfs_form_width=get_option('csbwfs_form_width');
if($csbwfs_form_width!=''){$formdivstyle.=' width:'.$csbwfs_form_width.';';}
$csbwfs_form_bg = get_option('csbwfs_form_bg');
if($csbwfs_form_bg!=''){$formdivstyle.=' background:'.$csbwfs_form_bg.';';}
$thnkpge='';
/*if(isset($pluginOptionsVal['csbwfs_form_thankyou']) && $pluginOptionsVal['csbwfs_form_thankyou']!='')
{
$thnkpge='';	
}*/
$jscnt.='jQuery(".csbwfs-lighbox a img").click(function(e) {
	e.preventDefault();
	var content =jQuery("#csbwfs_contact").html();
			var csbwfs_lightbox_content = 
			"<div id=\"csbwfs_lightbox\">" +
				"<div id=\"csbwfs_content\" style=\"'.$formdivstyle.'\">" +
				"<div class=\"close\"><span ></span></div>"  + content  +
				"</div>" +	
			"</div>";
			//insert lightbox HTML into page
			jQuery("#maillightbox").append(csbwfs_lightbox_content).hide().fadeIn(1000);

			 
});	

jQuery(document).submit(function(e){
    var form = jQuery(e.target);
    if(form.is("#csbwfs_form"))
    {
	var cptha1=parseInt(document.getElementById("cswbfs_hdn_cpthaval1").value);
	var cptha2=parseInt(document.getElementById("cswbfs_hdn_cpthaval2").value);
	var cptha3=document.getElementById("cswbfs_hdn_cpthaaction").value;
	var cptha4=parseInt(document.getElementById("csbwfs_code").value);
	if(cptha3=="x"){
	var finalVl = cptha1 * cptha2;
    }else
    {
   var finalVl = cptha1 + cptha2;		
	} 
			
	if( (document.getElementById("csbwfs_name").value=="") || (document.getElementById("csbwfs_email").value=="") )
	{
	//alert("Please fill all required fields 1");
	jQuery("#csbwfs_form .csbwfs-req-fields").css("border","1px solid red");
	return false;
	}
	
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.getElementById("csbwfs_email").value))
	  { 
		  } 
		  else
		  {
			  jQuery("#csbwfs_form .csbwfs-req-fields").css("border","1px solid #ccc");
			  jQuery("#csbwfs_form .csbwfs-req-email").css("border","1px solid red");
			 // alert("Please enter valid email 1"); 
			  return false;
			  }
	
    if(cptha4!=finalVl)
	{
	 jQuery("#csbwfs_form .csbwfs-req-fields").css("border","1px solid #ccc");
	 jQuery("#csbwfs_form .csbwfs-req-cptcha").css("border","1px solid red");
	 jQuery("#csbwfs_form .csbwfs-req-cptcha")
	 jQuery("#csbwfs_form #cptchaErr").html("Please fill correct captcha code");
	 return false;
	}
	
	e.preventDefault();
        jQuery.ajax({
            type: "POST",
            url: form.attr("action"), 
            data: form.serialize(), 
            success: function(data) {
			jQuery("#csbwfs_form")[0].reset();
			jQuery("#maillightbox form").hide();
            jQuery("#maillightbox .csbwfsmsg").show();
            jQuery("#maillightbox .csbwfsmsg").html(data);
           // console.log(data);

            }
        });
    }
	});';
}
	
$jscnt.='</script>';	
echo $jscnt;
}	

 
/*
-----------------------------------------------------------------------------------------------
                              "Custom Share Buttons with Floating Sidebar Pro" HTML
-----------------------------------------------------------------------------------------------
*/

function get_csbwf_pro_sidebar_content() {
global $post;
$pluginOptionsVal=get_csbwf_pro_sidebar_options();
/** Share button Title */
$csbwfs_fb_title ='Share on facebook';
if(isset($pluginOptionsVal['csbwfs_fb_title']) && $pluginOptionsVal['csbwfs_fb_title']!='')
$csbwfs_fb_title=$pluginOptionsVal['csbwfs_fb_title'];

$csbwfs_tw_title ='Share on twitter';
if(isset($pluginOptionsVal['csbwfs_tw_title']) && $pluginOptionsVal['csbwfs_tw_title']!='')
$csbwfs_tw_title=$pluginOptionsVal['csbwfs_tw_title'];

$csbwfs_li_title='Share on linkdin';
if(isset($pluginOptionsVal['csbwfs_li_title']) && $pluginOptionsVal['csbwfs_li_title']!='')
$csbwfs_li_title=$pluginOptionsVal['csbwfs_li_title'];

$csbwfs_pin_title='Share on pintrest';
if(isset($pluginOptionsVal['csbwfs_pin_title']) && $pluginOptionsVal['csbwfs_pin_title']!='')
$csbwfs_pin_title=$pluginOptionsVal['csbwfs_pin_title'];

$csbwfs_gp_title='Share on google';
if(isset($pluginOptionsVal['csbwfs_gp_title']) && $pluginOptionsVal['csbwfs_gp_title']!='')
$csbwfs_gp_title=$pluginOptionsVal['csbwfs_gp_title'];

$csbwfs_mail_title='Send contact request';
if(isset($pluginOptionsVal['csbwfs_mail_title']) && $pluginOptionsVal['csbwfs_mail_title']!='')
$csbwfs_mail_title=$pluginOptionsVal['csbwfs_mail_title'];

$csbwfs_gmail_title='Send contact request';
if(isset($pluginOptionsVal['csbwfs_gmail_title']) && $pluginOptionsVal['csbwfs_gmail_title']!='')
$csbwfs_gmail_title=$pluginOptionsVal['csbwfs_gmail_title'];

$csbwfs_yt_title='Share on youtube';
if(isset($pluginOptionsVal['csbwfs_yt_title']) && $pluginOptionsVal['csbwfs_yt_title']!='')
$csbwfs_yt_title=$pluginOptionsVal['csbwfs_yt_title'];

$csbwfs_re_title='Share on reddit';
if(isset($pluginOptionsVal['csbwfs_re_title']) && $pluginOptionsVal['csbwfs_re_title']!='')
$csbwfs_re_title=$pluginOptionsVal['csbwfs_re_title'];

$csbwfs_st_title='Share on stumbleupon';
if(isset($pluginOptionsVal['csbwfs_st_title']) && $pluginOptionsVal['csbwfs_st_title']!='')
$csbwfs_st_title=$pluginOptionsVal['csbwfs_st_title'];

$csbwfs_gt_title='Translate page';
if(isset($pluginOptionsVal['csbwfs_gt_title']) && $pluginOptionsVal['csbwfs_gt_title']!='')
$csbwfs_gt_title=$pluginOptionsVal['csbwfs_gt_title'];

$csbwfs_inst_title='Share on instagram';
if(isset($pluginOptionsVal['csbwfs_inst_title']) && $pluginOptionsVal['csbwfs_inst_title']!='')
$csbwfs_inst_title=$pluginOptionsVal['csbwfs_inst_title'];

$csbwfs_digg_title='Share on Diggit';
if(isset($pluginOptionsVal['csbwfs_digg_title']) && $pluginOptionsVal['csbwfs_digg_title']!='')
$csbwfs_digg_title=$pluginOptionsVal['csbwfs_digg_title'];

$csbwfs_yum_title='Share on Yummly';
if(isset($pluginOptionsVal['csbwfs_yum_title']) && $pluginOptionsVal['csbwfs_yum_title']!='')
$csbwfs_yum_title=$pluginOptionsVal['csbwfs_yum_title'];

$csbwfs_vk_title='Share on VK';
if(isset($pluginOptionsVal['csbwfs_vk_title']) && $pluginOptionsVal['csbwfs_vk_title']!='')
$csbwfs_vk_title=$pluginOptionsVal['csbwfs_vk_title'];

$csbwfs_print_title='Print';
if(isset($pluginOptionsVal['csbwfs_print_title']) && $pluginOptionsVal['csbwfs_print_title']!='')
$csbwfs_print_title=$pluginOptionsVal['csbwfs_print_title'];

$csbwfs_buf_title='Share on Buffer';
if(isset($pluginOptionsVal['csbwfs_buf_title']) && $pluginOptionsVal['csbwfs_buf_title']!='')
$csbwfs_buf_title=$pluginOptionsVal['csbwfs_buf_title'];

$csbwfs_gm_title='Share on Gmail';
if(isset($pluginOptionsVal['csbwfs_gm_title']) && $pluginOptionsVal['csbwfs_gm_title']!='')
$csbwfs_gm_title=$pluginOptionsVal['csbwfs_gm_title'];
$csbwfs_de_title='Share on Delicious';
if(isset($pluginOptionsVal['csbwfs_de_title']) && $pluginOptionsVal['csbwfs_de_title']!='')
$csbwfs_de_title=$pluginOptionsVal['csbwfs_de_title'];
$csbwfs_tu_title='Share on Tumblr';
if(isset($pluginOptionsVal['csbwfs_tu_title']) && $pluginOptionsVal['csbwfs_tu_title']!='')
$csbwfs_tu_title=$pluginOptionsVal['csbwfs_tu_title'];
$csbwfs_bl_title='Share on Blogger';
if(isset($pluginOptionsVal['csbwfs_bl_title']) && $pluginOptionsVal['csbwfs_bl_title']!='')
$csbwfs_bl_title=$pluginOptionsVal['csbwfs_bl_title'];
$csbwfs_we_title='Share on Weibo';
if(isset($pluginOptionsVal['csbwfs_we_title']) && $pluginOptionsVal['csbwfs_we_title']!='')
$csbwfs_we_title=$pluginOptionsVal['csbwfs_we_title'];
/** get current url */   
$shareurl = csbwfs_pro_get_current_page_url($_SERVER);
if(is_category())
	{
	   $category_id = get_query_var('cat');
	   $cats = get_the_category();
	   $ShareTitle=$cats[0]->name;
	}elseif(is_singular())
	{
	   $ShareTitle=$post->post_title;
	}
	elseif(is_archive())
	{
	   global $wp;
       if ( is_day() ) :
		 $ShareTitle='Daily Archives: '. get_the_date(); 
		elseif ( is_month() ) : 
		 $ShareTitle='Monthly Archives: '. get_the_date('F Y'); 
		elseif ( is_year() ) : 
		 $ShareTitle='Yearly Archives: '. get_the_date('Y'); 
		elseif ( is_author() ) : 
		 $ShareTitle='Author Archives: '. get_the_author(); 
		else :
		 $ShareTitle ='Blog Archives';
		endif;			
	   
	   //$ShareTitle=$post->post_title;
	}
	else
	{
        $ShareTitle=get_bloginfo('name');
		}
    		
$csbwfsImgAlt= $ShareTitle;
$ShareDesc=$ShareOgDesc=$csbwfsOgimg='';		
if(isset($pluginOptionsVal['csbwfs_og_tags_enable']) && $pluginOptionsVal['csbwfs_og_tags_enable']=='yes'){
/* og title */
$ogtile=get_post_meta($post->ID,"csbwfs_og_title",true);
$csbwfs_dft_og_title=get_option('csbwfs_dft_og_title');
if($ogtile=='' && $csbwfs_dft_og_title!=''){$ogtile=$csbwfs_dft_og_title;}
if($ogtile!=''){$ShareTitle=$ogtile;}
/* og description */
$ShareOgDesc=get_post_meta($post->ID,"csbwfs_og_description",true);
$csbwfs_dft_og_desc=get_option('csbwfs_dft_og_desc');
if($ShareOgDesc=='' && $csbwfs_dft_og_desc!=''){$ShareOgDesc=$csbwfs_dft_og_desc;}
if($ShareOgDesc!=''){$ShareDesc=$ShareOgDesc;}
$ShareOgImg=get_post_meta($post->ID,"csbwfs_og_image_path",true);
$csbwfs_dft_og_img=get_option('csbwfs_dft_og_img');
if($ShareOgImg=='' && $csbwfs_dft_og_img!=''){$ShareOgImg=$csbwfs_dft_og_img;}
if($ShareOgImg!=''){$csbwfsOgimg=$ShareOgImg;}
}
$ShareTitle= htmlspecialchars(urlencode($ShareTitle));
$ShareDesc= htmlspecialchars(urlencode($ShareDesc));
/* Get All buttons Image */
//get facebook button image
if($pluginOptionsVal['csbwfs_fb_image']!=''){ $fImg=$pluginOptionsVal['csbwfs_fb_image'];} 
   else{$fImg=plugins_url('images/fb.png',__FILE__);}   
//get twitter button image  
if($pluginOptionsVal['csbwfs_tw_image']!=''){ $tImg=$pluginOptionsVal['csbwfs_tw_image'];} 
   else{$tImg=plugins_url('images/tw.png',__FILE__);}   
//get linkdin button image
if($pluginOptionsVal['csbwfs_li_image']!=''){ $lImg=$pluginOptionsVal['csbwfs_li_image'];} 
   else{$lImg=plugins_url('images/in.png',__FILE__);}   
//get mail button image  
if($pluginOptionsVal['csbwfs_mail_image']!=''){ $mImg=$pluginOptionsVal['csbwfs_mail_image'];} 
   else{$mImg=plugins_url('images/ml.png',__FILE__);}   
//get google plus button image 
if($pluginOptionsVal['csbwfs_gp_image']!=''){ $gImg=$pluginOptionsVal['csbwfs_gp_image'];} 
   else{$gImg=plugins_url('images/gp.png',__FILE__);}  
//get pinterest button image   
if($pluginOptionsVal['csbwfs_pin_image']!=''){ $pImg=$pluginOptionsVal['csbwfs_pin_image'];} 
   else{$pImg=plugins_url('images/pinit.png',__FILE__);}    
//get youtube button image
if(isset($pluginOptionsVal['csbwfs_yt_image']) && $pluginOptionsVal['csbwfs_yt_image']!=''){ $ytImg=$pluginOptionsVal['csbwfs_yt_image'];} 
   else{$ytImg=plugins_url('images/yt.png',__FILE__);}     
//get reddit plus button image 
if(isset($pluginOptionsVal['csbwfs_re_image']) && $pluginOptionsVal['csbwfs_re_image']!=''){ $reImg=$pluginOptionsVal['csbwfs_re_image'];} 
   else{$reImg=plugins_url('images/reddit.png',__FILE__);}   
//get stumbleupon button image   
if(isset($pluginOptionsVal['csbwfs_st_image']) && $pluginOptionsVal['csbwfs_st_image']!=''){ $stImg=$pluginOptionsVal['csbwfs_st_image'];} 
   else{$stImg=plugins_url('images/st.png',__FILE__);}  
//get google translate button image   
if(isset($pluginOptionsVal['csbwfs_gt_image']) && $pluginOptionsVal['csbwfs_gt_image']!=''){ $gtImg=$pluginOptionsVal['csbwfs_gt_image'];} 
else{$gtImg=plugins_url('images/GTB.png',__FILE__);}   
//get instgrame button image   
if(isset($pluginOptionsVal['csbwfs_inst_image']) && $pluginOptionsVal['csbwfs_inst_image']!=''){ $instImg=$pluginOptionsVal['csbwfs_inst_image'];} 
   else{$instImg=plugins_url('images/inst.png',__FILE__);}   
//get diggit button image   
if(isset($pluginOptionsVal['csbwfs_digg_image']) && $pluginOptionsVal['csbwfs_digg_image']!=''){ $diggImg=$pluginOptionsVal['csbwfs_digg_image'];} 
   else{$diggImg=plugins_url('images/diggit.png',__FILE__);}   
//get yummly button image   
if(isset($pluginOptionsVal['csbwfs_yum_image']) && $pluginOptionsVal['csbwfs_yum_image']!=''){ $yumImg=$pluginOptionsVal['csbwfs_yum_image'];} 
   else{$yumImg=plugins_url('images/yum.png',__FILE__);}
//get VK button image   
if(isset($pluginOptionsVal['csbwfs_vk_image']) && $pluginOptionsVal['csbwfs_vk_image']!=''){ $vkImg=$pluginOptionsVal['csbwfs_vk_image'];} 
   else{$vkImg=plugins_url('images/vk.png',__FILE__);}
//get Buffer button image   
if(isset($pluginOptionsVal['csbwfs_buf_image']) && $pluginOptionsVal['csbwfs_buf_image']!=''){ $bufImg=$pluginOptionsVal['csbwfs_buf_image'];} 
   else{$bufImg=plugins_url('images/buf.png',__FILE__);}  
//get print button image   
if(isset($pluginOptionsVal['csbwfs_print_image']) && $pluginOptionsVal['csbwfs_print_image']!=''){ $printImg=$pluginOptionsVal['csbwfs_print_image'];} 
   else{$printImg=plugins_url('images/print.png',__FILE__);}              
//get gmail button image   
if(isset($pluginOptionsVal['csbwfs_gm_image']) && $pluginOptionsVal['csbwfs_gm_image']!=''){ $gmImg=$pluginOptionsVal['csbwfs_gm_image'];} 
   else{$gmImg=plugins_url('images/gm.png',__FILE__);}              
//get delicious button image   
if(isset($pluginOptionsVal['csbwfs_de_image']) && $pluginOptionsVal['csbwfs_de_image']!=''){ $deImg=$pluginOptionsVal['csbwfs_de_image'];} 
   else{$deImg=plugins_url('images/de.png',__FILE__);}              
//get blogger button image   
if(isset($pluginOptionsVal['csbwfs_bl_image']) && $pluginOptionsVal['csbwfs_bl_image']!=''){ $blImg=$pluginOptionsVal['csbwfs_bl_image'];} 
   else{$blImg=plugins_url('images/bl.png',__FILE__);}              
//get tumbler button image   
if(isset($pluginOptionsVal['csbwfs_tu_image']) && $pluginOptionsVal['csbwfs_tu_image']!=''){ $tuImg=$pluginOptionsVal['csbwfs_tu_image'];} 
   else{$tuImg=plugins_url('images/tu.png',__FILE__);}              
//get Weibo button image   
if(isset($pluginOptionsVal['csbwfs_we_image']) && $pluginOptionsVal['csbwfs_we_image']!=''){ $weImg=$pluginOptionsVal['csbwfs_we_image'];} 
   else{$weImg=plugins_url('images/we.png',__FILE__);}              
//get email message
if(is_page() || is_single() || is_category() || is_archive()){
	
if($pluginOptionsVal['csbwfs_mailMessage']!=''){ $mailMsg=$pluginOptionsVal['csbwfs_mailMessage'];} else{
$mailMsg='?subject='.$ShareTitle.'&body='.$shareurl;}
 }else
 {
	 $mailMsg='?subject='.get_bloginfo('name').'&body='.home_url('/');
	 }
 

// Top Margin
if($pluginOptionsVal['csbwfs_top_margin']!=''){
	$margin=$pluginOptionsVal['csbwfs_top_margin'];
}else
{
	$margin='25%';
	}
// Define distance from left/right/bottom
$csbwfsDistance='0';
$csbwfsShareimage='';
if(isset($pluginOptionsVal['csbwfs_sbi_image']) && $pluginOptionsVal['csbwfs_sbi_image']!='')
{
	$csbwfsShareimage=$pluginOptionsVal['csbwfs_sbi_image'];
	}
if(isset($pluginOptionsVal['csbwfs_position_from_lr']) && $pluginOptionsVal['csbwfs_position_from_lr']!='' && !isCsbwfsMobilePro())
$csbwfsDistance=$pluginOptionsVal['csbwfs_position_from_lr'];
$bottomPosition='';
if($pluginOptionsVal['csbwfs_position']=='right'){
	$style=' style="top:'.$margin.';right:'.$csbwfsDistance.';"';
	$idName=' id="csbwfs-right"';
	if($csbwfsShareimage!==''){$showImg=$csbwfsShareimage;}else{$showImg=plugins_url('images/hide-r.png',__FILE__);}
	$hideImg='show.png';
	
}elseif($pluginOptionsVal['csbwfs_position']=='bottom'){
	$style=' style="right:0;bottom:'.$csbwfsDistance.';top:auto;"';
	$idName=' id="csbwfs-bottom"';
	if($csbwfsShareimage!==''){$showImg=$csbwfsShareimage;}else{$showImg=plugins_url('images/hide-b.png',__FILE__);}
	$hideImg='hideb.png';
	$bottomPosition='bottom';
	
}else
{
	$idName=' id="csbwfs-left"';
	$style=' style="top:'.$margin.';left:'.$csbwfsDistance.';"';
    if($csbwfsShareimage!==''){$showImg=$csbwfsShareimage;}else{$showImg=plugins_url('images/hide-l.png',__FILE__);}
	$hideImg='hide.png';
	}


//Set horizontal Sidebar Position for mobile 
if(isset($pluginOptionsVal['csbwfs_pro_horizontal_for_mobile']) && $pluginOptionsVal['csbwfs_pro_horizontal_for_mobile']=='yes'){
if(isCsbwfsMobilePro()):
	$style=' style="right:0;bottom:0;top:auto;"';
	$idName=' id="csbwfs-bottom"';
	if($csbwfsShareimage!==''){$showImg=$csbwfsShareimage;}else{$showImg=plugins_url('images/hide-b.png',__FILE__);}
	$hideImg='show.png';
	$bottomPosition='bottom';
	endif;
}
   
/* Get All buttons background color */

//get facebook button image background color 
if($pluginOptionsVal['csbwfs_fb_bg']!=''){ $fImgbg=' style="background:'.$pluginOptionsVal['csbwfs_fb_bg'].';"';} 
   else{$fImgbg='';}   
//get twitter button image  background color 
if($pluginOptionsVal['csbwfs_tw_bg']!=''){ $tImgbg=' style="background:'.$pluginOptionsVal['csbwfs_tw_bg'].';"';} 
   else{$tImgbg='';}   
//get linkdin button image background color 
if($pluginOptionsVal['csbwfs_li_bg']!=''){ $lImgbg=' style="background:'.$pluginOptionsVal['csbwfs_li_bg'].';"';} 
   else{$lImgbg='';}   
//get mail button image  background color 
if($pluginOptionsVal['csbwfs_mail_bg']!=''){ $mImgbg=' style="background:'.$pluginOptionsVal['csbwfs_mail_bg'].';"';} 
   else{$mImgbg='';}   
//get google plus button image  background color 
if($pluginOptionsVal['csbwfs_gp_bg']!=''){ $gImgbg=' style="background:'.$pluginOptionsVal['csbwfs_gp_bg'].';"';} 
   else{$gImgbg='';}  
//get pinterest button image   background color 
if($pluginOptionsVal['csbwfs_pin_bg']!=''){ $pImgbg=' style="background:'.$pluginOptionsVal['csbwfs_pin_bg'].';"';} 
   else{$pImgbg='';}  
    
//get youtube button image   background color 
if(isset($pluginOptionsVal['csbwfs_yt_bg']) && $pluginOptionsVal['csbwfs_yt_bg']!=''){ $ytImgbg=' style="background:'.$pluginOptionsVal['csbwfs_yt_bg'].';"';} 
   else{$ytImgbg='';}   
//get reddit button image   background color 
if(isset($pluginOptionsVal['csbwfs_re_bg']) && $pluginOptionsVal['csbwfs_re_bg']!=''){ $reImgbg=' style="background:'.$pluginOptionsVal['csbwfs_re_bg'].';"';} 
   else{$reImgbg='';}  
//get stumbleupon button image   background color 
if(isset($pluginOptionsVal['csbwfs_st_bg']) && $pluginOptionsVal['csbwfs_st_bg']!=''){ $stImgbg=' style="background:'.$pluginOptionsVal['csbwfs_st_bg'].';"';} 
   else{$stImgbg='';}  
//get gmail button image   background color 
if(isset($pluginOptionsVal['csbwfs_gm_bg']) && $pluginOptionsVal['csbwfs_gm_bg']!=''){ $gmImgbg=' style="background:'.$pluginOptionsVal['csbwfs_gm_bg'].';"';} 
   else{$gmImgbg='';}  
   
//get google translate button image   background color 
if(isset($pluginOptionsVal['csbwfs_gt_bg']) && $pluginOptionsVal['csbwfs_gt_bg']!=''){ $gtImgbg=' style="background:'.$pluginOptionsVal['csbwfs_gt_bg'].';"';} 
   else{$gtImgbg='';}  
//get instgrame button image   background color 
if(isset($pluginOptionsVal['csbwfs_inst_bg']) && $pluginOptionsVal['csbwfs_inst_bg']!=''){ $instImgbg=' style="background:'.$pluginOptionsVal['csbwfs_inst_bg'].';"';} 
   else{$instImgbg='';}     
//get diggit button image   background color 
if(isset($pluginOptionsVal['csbwfs_digg_bg']) && $pluginOptionsVal['csbwfs_digg_bg']!=''){ $diggImgbg=' style="background:'.$pluginOptionsVal['csbwfs_digg_bg'].';"';} 
   else{$diggImgbg='';}     
//get yummly button image   background color 
if(isset($pluginOptionsVal['csbwfs_yum_bg']) && $pluginOptionsVal['csbwfs_yum_bg']!=''){ $yumImgbg=' style="background:'.$pluginOptionsVal['csbwfs_yum_bg'].';"';} 
   else{$yumImgbg='';}
//get VK button image   background color 
if(isset($pluginOptionsVal['csbwfs_vk_bg']) && $pluginOptionsVal['csbwfs_vk_bg']!=''){ $vkImgbg=' style="background:'.$pluginOptionsVal['csbwfs_vk_bg'].';"';} 
   else{$vkImgbg='';}
//get buffer button image   background color 
if(isset($pluginOptionsVal['csbwfs_buf_bg']) && $pluginOptionsVal['csbwfs_buf_bg']!=''){ $bufImgbg=' style="background:'.$pluginOptionsVal['csbwfs_buf_bg'].';"';} 
   else{$bufImgbg='';}
//get print button image   background color 
if(isset($pluginOptionsVal['csbwfs_print_bg']) && $pluginOptionsVal['csbwfs_print_bg']!=''){ $printImgbg=' style="background:'.$pluginOptionsVal['csbwfs_print_bg'].';"';} 
   else{$printImgbg='';}
//get gmail button image   background color 
if(isset($pluginOptionsVal['csbwfs_gm_bg']) && $pluginOptionsVal['csbwfs_gm_bg']!=''){ $gmImgbg=' style="background:'.$pluginOptionsVal['csbwfs_gm_bg'].';"';} 
   else{$gmImgbg='';}
//get delicious image   background color 
if(isset($pluginOptionsVal['csbwfs_de_bg']) && $pluginOptionsVal['csbwfs_de_bg']!=''){ $deImgbg=' style="background:'.$pluginOptionsVal['csbwfs_de_bg'].';"';} 
   else{$deImgbg='';}
//get blogger button image   background color 
if(isset($pluginOptionsVal['csbwfs_bl_bg']) && $pluginOptionsVal['csbwfs_bl_bg']!=''){ $blImgbg=' style="background:'.$pluginOptionsVal['csbwfs_bl_bg'].';"';} 
   else{$blImgbg='';}
//get tumbler button image   background color 
if(isset($pluginOptionsVal['csbwfs_tu_bg']) && $pluginOptionsVal['csbwfs_tu_bg']!=''){ $tuImgbg=' style="background:'.$pluginOptionsVal['csbwfs_tu_bg'].';"';} 
   else{$tuImgbg='';}
//get Weibo button image   background color 
if(isset($pluginOptionsVal['csbwfs_we_bg']) && $pluginOptionsVal['csbwfs_we_bg']!=''){ $weImgbg=' style="background:'.$pluginOptionsVal['csbwfs_we_bg'].';"';} 
   else{$weImgbg='';}
/** Message */ 
if($pluginOptionsVal['csbwfs_show_btn']!=''){ $showbtn=$pluginOptionsVal['csbwfs_show_btn'];} 
   else{$showbtn='Show';}   
//get show/hide button message 
if($pluginOptionsVal['csbwfs_hide_btn']!=''){ $hidebtn=$pluginOptionsVal['csbwfs_hide_btn'];} 
   else{$hidebtn='Hide';}   
//get mail button message 
if($pluginOptionsVal['csbwfs_share_msg']!=''){ $sharemsg=$pluginOptionsVal['csbwfs_share_msg'];} 
   else{$sharemsg='Share This With Your Friends';}   
/** Custom Button */

$csbwfsCustomBtn1 =$csbwfsCustomBtn2 =$csbwfsCustomBtn3 =$csbwfsCustomBtn4 =$csbwfsCustomBtn5 =$csbwfsCustomBtn6 =$csbwfsCustomBtn7 =$csbwfsCustomBtn8 =$csbwfsCustomBtn9 =$csbwfsCustomBtn10 =''; 

if($bottomPosition!='bottom'):

/** Start Custom Image Button */
// Custom Image1
if($pluginOptionsVal['csbwfs_c1publishBtn']!=''):
$csbwfsCustomWidth='45px';$csbwfsCustomHeight='45px';
if(isset($pluginOptionsVal['csbwfs_custom1_defltwidth']) && $pluginOptionsVal['csbwfs_custom1_defltwidth']!=''):
$csbwfsCustomWidth=$pluginOptionsVal['csbwfs_custom1_defltwidth'];
endif;
if(isset($pluginOptionsVal['csbwfs_custom1_hght']) && $pluginOptionsVal['csbwfs_custom1_hght']!=''):
$csbwfsCustomHeight=$pluginOptionsVal['csbwfs_custom1_hght'];
endif;
$csbwfsCustomBtn1 .='<div class="custom1 extraImgBtns" id="csbwfs-sbutton1"><div id="csbwfs-c1"><a href="'.$pluginOptionsVal['csbwfs_custom_url'].'"  style="background:'.$pluginOptionsVal['csbwfs_custom_bg'].';width:'.$csbwfsCustomWidth.';display:block;text-align:center;" target="'.$pluginOptionsVal['csbwfs_custom_target'].'" title="'.$pluginOptionsVal['csbwfs_custom_title'].'"><img src="'.$pluginOptionsVal['csbwfs_custom_image'].'" alt="'.$pluginOptionsVal['csbwfs_custom_title'].'"  width="'.$csbwfsCustomWidth.'" height="'.$csbwfsCustomHeight.'"></a></div></div>';
endif;
// Custom Image2
if($pluginOptionsVal['csbwfs_c2publishBtn']!=''):
$csbwfsCustom2Width='45px';$csbwfsCustom2Height='45px';
if(isset($pluginOptionsVal['csbwfs_custom2_defltwidth']) && $pluginOptionsVal['csbwfs_custom2_defltwidth']!=''):
$csbwfsCustom2Width=$pluginOptionsVal['csbwfs_custom2_defltwidth'];
endif;
if(isset($pluginOptionsVal['csbwfs_custom2_hght']) && $pluginOptionsVal['csbwfs_custom2_hght']!=''):
$csbwfsCustom2Height=$pluginOptionsVal['csbwfs_custom2_hght'];
endif;
$csbwfsCustomBtn2 .='<div class="custom2 extraImgBtns" id="csbwfs-sbutton2"><div id="csbwfs-c2"><a href="'.$pluginOptionsVal['csbwfs_custom2_url'].'"  style="background:'.$pluginOptionsVal['csbwfs_custom2_bg'].';width:'.$csbwfsCustom2Width.';display:block;text-align:center;" target="'.$pluginOptionsVal['csbwfs_custom2_target'].'" title="'.$pluginOptionsVal['csbwfs_custom2_title'].'"><img width="'.$csbwfsCustom2Width.'" height="'.$csbwfsCustom2Height.'" src="'.$pluginOptionsVal['csbwfs_custom2_image'].'" alt="'.$pluginOptionsVal['csbwfs_custom2_title'].'"></a></div></div>';
endif;
// Custom Image3
if($pluginOptionsVal['csbwfs_c5publishBtn']!=''):
$csbwfsCustom5Width='45px';$csbwfsCustom5Height='45px';
if(isset($pluginOptionsVal['csbwfs_custom5_defltwidth']) && $pluginOptionsVal['csbwfs_custom5_defltwidth']!=''):
$csbwfsCustom5Width=$pluginOptionsVal['csbwfs_custom5_defltwidth'];
endif;
if(isset($pluginOptionsVal['csbwfs_custom5_hght']) && $pluginOptionsVal['csbwfs_custom5_hght']!=''):
$csbwfsCustom5Height=$pluginOptionsVal['csbwfs_custom5_hght'];
endif;
$csbwfsCustomBtn5 .='<div class="custom5 extraImgBtns" id="csbwfs-sbutton5"><div id="csbwfs-c5"><a href="'.$pluginOptionsVal['csbwfs_custom5_url'].'"  style="background:'.$pluginOptionsVal['csbwfs_custom5_bg'].';width:'.$csbwfsCustomWidth.';display:block;text-align:center;" target="'.$pluginOptionsVal['csbwfs_custom5_target'].'" title="'.$pluginOptionsVal['csbwfs_custom5_title'].'"><img src="'.$pluginOptionsVal['csbwfs_custom5_image'].'" alt="'.$pluginOptionsVal['csbwfs_custom5_title'].'"  width="'.$csbwfsCustom5Width.'" height="'.$csbwfsCustom5Height.'"></a></div></div>';
endif;
// Custom Image4
if($pluginOptionsVal['csbwfs_c6publishBtn']!=''):
$csbwfsCustom6Width='45px';$csbwfsCustom6Height='45px';
if(isset($pluginOptionsVal['csbwfs_custom6_defltwidth']) && $pluginOptionsVal['csbwfs_custom6_defltwidth']!=''):
$csbwfsCustom6Width=$pluginOptionsVal['csbwfs_custom6_defltwidth'];
endif;
if(isset($pluginOptionsVal['csbwfs_custom6_hght']) && $pluginOptionsVal['csbwfs_custom6_hght']!=''):
$csbwfsCustom6Height=$pluginOptionsVal['csbwfs_custom6_hght'];
endif;
$csbwfsCustomBtn6 .='<div class="custom6 extraImgBtns" id="csbwfs-sbutton6"><div id="csbwfs-c6"><a href="'.$pluginOptionsVal['csbwfs_custom6_url'].'"  style="background:'.$pluginOptionsVal['csbwfs_custom6_bg'].';width:'.$csbwfsCustom6Width.';display:block;text-align:center;" target="'.$pluginOptionsVal['csbwfs_custom6_target'].'" title="'.$pluginOptionsVal['csbwfs_custom6_title'].'"><img width="'.$csbwfsCustom6Width.'" height="'.$csbwfsCustom6Height.'" src="'.$pluginOptionsVal['csbwfs_custom6_image'].'" alt="'.$pluginOptionsVal['csbwfs_custom6_title'].'"></a></div></div>';
endif;
// Custom Image5
if($pluginOptionsVal['csbwfs_c7publishBtn']!=''):
$csbwfsCustom7Width='45px';$csbwfsCustom7Height='45px';
if(isset($pluginOptionsVal['csbwfs_custom7_defltwidth']) && $pluginOptionsVal['csbwfs_custom7_defltwidth']!=''):
$csbwfsCustom7Width=$pluginOptionsVal['csbwfs_custom7_defltwidth'];
endif;
if(isset($pluginOptionsVal['csbwfs_custom7_hght']) && $pluginOptionsVal['csbwfs_custom7_hght']!=''):
$csbwfsCustom7Height=$pluginOptionsVal['csbwfs_custom7_hght'];
endif;
$csbwfsCustomBtn7 .='<div class="custom7 extraImgBtns" id="csbwfs-sbutton7"><div id="csbwfs-c7"><a href="'.$pluginOptionsVal['csbwfs_custom7_url'].'"  style="background:'.$pluginOptionsVal['csbwfs_custom7_bg'].';width:'.$csbwfsCustom7Width.';display:block;text-align:center;" target="'.$pluginOptionsVal['csbwfs_custom7_target'].'" title="'.$pluginOptionsVal['csbwfs_custom7_title'].'"><img src="'.$pluginOptionsVal['csbwfs_custom7_image'].'" alt="'.$pluginOptionsVal['csbwfs_custom7_title'].'"  width="'.$csbwfsCustom7Width.'" height="'.$csbwfsCustom7Height.'"></a></div></div>';
endif;
// Custom Image6
if($pluginOptionsVal['csbwfs_c8publishBtn']!=''):
$csbwfsCustom8Width='45px';$csbwfsCustom8Height='45px';
if(isset($pluginOptionsVal['csbwfs_custom8_defltwidth']) && $pluginOptionsVal['csbwfs_custom8_defltwidth']!=''):
$csbwfsCustom8Width=$pluginOptionsVal['csbwfs_custom8_defltwidth'];
endif;
if(isset($pluginOptionsVal['csbwfs_custom8_hght']) && $pluginOptionsVal['csbwfs_custom8_hght']!=''):
$csbwfsCustom8Height=$pluginOptionsVal['csbwfs_custom8_hght'];
endif;
$csbwfsCustomBtn8 .='<div class="custom2 extraImgBtns" id="csbwfs-sbutton8"><div id="csbwfs-c8"><a href="'.$pluginOptionsVal['csbwfs_custom8_url'].'"  style="background:'.$pluginOptionsVal['csbwfs_custom8_bg'].';width:'.$csbwfsCustom8Width.';display:block;text-align:center;" target="'.$pluginOptionsVal['csbwfs_custom8_target'].'" title="'.$pluginOptionsVal['csbwfs_custom8_title'].'"><img width="'.$csbwfsCustom8Width.'" height="'.$csbwfsCustom8Height.'" src="'.$pluginOptionsVal['csbwfs_custom8_image'].'" alt="'.$pluginOptionsVal['csbwfs_custom8_title'].'"></a></div></div>';
endif;
// Custom Image7
if($pluginOptionsVal['csbwfs_c9publishBtn']!=''):
$csbwfsCustom9Width='45px';$csbwfsCustom9Height='45px';
if(isset($pluginOptionsVal['csbwfs_custom9_defltwidth']) && $pluginOptionsVal['csbwfs_custom9_defltwidth']!=''):
$csbwfsCustom9Width=$pluginOptionsVal['csbwfs_custom9_defltwidth'];
endif;
if(isset($pluginOptionsVal['csbwfs_custom9_hght']) && $pluginOptionsVal['csbwfs_custom9_hght']!=''):
$csbwfsCustom9Height=$pluginOptionsVal['csbwfs_custom9_hght'];
endif;
$csbwfsCustomBtn9 .='<div class="custom9 extraImgBtns" id="csbwfs-sbutton9"><div id="csbwfs-c9"><a href="'.$pluginOptionsVal['csbwfs_custom9_url'].'"  style="background:'.$pluginOptionsVal['csbwfs_custom9_bg'].';width:'.$csbwfsCustom9Width.';display:block;text-align:center;" target="'.$pluginOptionsVal['csbwfs_custom9_target'].'" title="'.$pluginOptionsVal['csbwfs_custom9_title'].'"><img src="'.$pluginOptionsVal['csbwfs_custom9_image'].'" alt="'.$pluginOptionsVal['csbwfs_custom9_title'].'"  width="'.$csbwfsCustom9Width.'" height="'.$csbwfsCustom9Height.'"></a></div></div>';
endif;
// Custom Image8
if($pluginOptionsVal['csbwfs_c10publishBtn']!=''):
$csbwfsCustom10Width='45px';$csbwfsCustom10Height='45px';
if(isset($pluginOptionsVal['csbwfs_custom10_defltwidth']) && $pluginOptionsVal['csbwfs_custom10_defltwidth']!=''):
$csbwfsCustom10Width=$pluginOptionsVal['csbwfs_custom10_defltwidth'];
endif;
if(isset($pluginOptionsVal['csbwfs_custom10_hght']) && $pluginOptionsVal['csbwfs_custom10_hght']!=''):
$csbwfsCustom10Height=$pluginOptionsVal['csbwfs_custom10_hght'];
endif;
$csbwfsCustomBtn10 .='<div class="custom10 extraImgBtns" id="csbwfs-sbutton10"><div id="csbwfs-c10"><a href="'.$pluginOptionsVal['csbwfs_custom10_url'].'"  style="background:'.$pluginOptionsVal['csbwfs_custom10_bg'].';width:'.$csbwfsCustom10Width.';display:block;text-align:center;" target="'.$pluginOptionsVal['csbwfs_custom10_target'].'" title="'.$pluginOptionsVal['csbwfs_custom10_title'].'"><img width="'.$csbwfsCustom10Width.'" height="'.$csbwfsCustom10Height.'" src="'.$pluginOptionsVal['csbwfs_custom10_image'].'" alt="'.$pluginOptionsVal['csbwfs_custom10_title'].'"></a></div></div>';
endif;
/* End custom image button */
/* Custom Text Button */
/** Text Button 1*/
if($pluginOptionsVal['csbwfs_c3publishBtn']!=''):
if($pluginOptionsVal['csbwfs_custom3_defltwidth']){$wth3=$pluginOptionsVal['csbwfs_custom3_defltwidth'];}else {$wth3='';}
if($pluginOptionsVal['csbwfs_custom3_txt_color']){$clr3='color:'.$pluginOptionsVal['csbwfs_custom3_txt_color'].';';}else {$clr3='color:#ffffff;';}
if($pluginOptionsVal['csbwfs_custom3_hght']){$hght3='height:'.$pluginOptionsVal['csbwfs_custom3_hght'].'px;';}else {$hght3='';}
$csbwfsCustomBtn3 .='<div class="custom3 extraTxtBtns" id="csbwfs-sbutton3" style="'.$hght3.'"><div id="csbwfs-c3"><a href="'.$pluginOptionsVal['csbwfs_custom3_url'].'"  style="background:'.$pluginOptionsVal['csbwfs_custom3_bg'].';'.$clr3.'width:'.$wth3.'px;'.$hght3.'" target="'.$pluginOptionsVal['csbwfs_custom3_target'].'" title="'.$pluginOptionsVal['csbwfs_custom3_title'].'">'.$pluginOptionsVal['csbwfs_custom3_title'].'</a></div></div>';
endif;

/** Text Button 2 */
if($pluginOptionsVal['csbwfs_c4publishBtn']!=''):
if($pluginOptionsVal['csbwfs_custom4_defltwidth']){$wth4=$pluginOptionsVal['csbwfs_custom4_defltwidth'];}else {$wth4='#ffffff;';}
if($pluginOptionsVal['csbwfs_custom4_txt_color']){$clr4='color:'.$pluginOptionsVal['csbwfs_custom4_txt_color'].';';}else {$clr4='color:#ffffff;';}
if($pluginOptionsVal['csbwfs_custom3_hght']){$hght4='height:'.$pluginOptionsVal['csbwfs_custom4_hght'].'px;';}else {$hght4='';}
$csbwfsCustomBtn4 .='<div class="custom4 extraTxtBtns" id="csbwfs-sbutton4" style="'.$hght4.'"><div id="csbwfs-c4"><a href="'.$pluginOptionsVal['csbwfs_custom4_url'].'"  style="background:'.$pluginOptionsVal['csbwfs_custom4_bg'].';'.$clr4.'width:'.$wth4.'px;'.$hght4.'" target="'.$pluginOptionsVal['csbwfs_custom4_target'].'" title="'.$pluginOptionsVal['csbwfs_custom4_title'].'">'.$pluginOptionsVal['csbwfs_custom4_title'].'</a></div></div>';
endif;
endif;

/** Check display Show/Hide button or not*/
if(isset($pluginOptionsVal['csbwfs_rmSHBtn']) && $pluginOptionsVal['csbwfs_rmSHBtn']!=''):
$isActiveHideShowBtn='yes';
else:
$isActiveHideShowBtn='no';
endif;
$floatingSidebarContent='<div id="csbwfs-delaydiv" ><div class="csbwfs-social-widget" '.$idName.' title="'.$sharemsg.'" '.$style.'><div class="csbwfs-responive-div">';

if($isActiveHideShowBtn!='yes') :
$floatingSidebarContent .= '<div class="csbwfs-show"><a href="javascript:" title="'.$showbtn.'" id="csbwfs-show"><img src="'.$showImg.'" alt="'.$showbtn.'" width="30" height="80"></a></div>';
endif;


$floatingSidebarContent .= '<div id="csbwfs-social-inner">';
if($bottomPosition!='bottom'):
/** Total Sum of Share */
if(isset($pluginOptionsVal['csbwfs_count_sum']) && $pluginOptionsVal['csbwfs_count_sum']!=''):
$totalShareCount=csbwfs_return_count_total();
if($totalShareCount!='0'){
$floatingSidebarContent .='<div id="csbwfs-cntsum"><div class="csbwfs-count-bubble"><div class="iQa IY">'.$totalShareCount.'</div></div>';
$floatingSidebarContent .='<div id="csbwfs-sum"><button id="csbwfs-cntsum-txt">'.$pluginOptionsVal['csbwfs_count_sum'].'</button></div></div>';
}
endif;
endif;
/* sort buttons order */
$btnsordaryy=get_option('csbwfs_btns_order');
asort($btnsordaryy);
foreach($btnsordaryy as $csbwfskey=>$csbwfskeyval)
{
/* Custom 1 */
if($csbwfskey=='cs1'){
if($csbwfsCustomBtn1!='' && $bottomPosition!='bottom')
$floatingSidebarContent .=$csbwfsCustomBtn1;
}
/* Custom 2 */
if($csbwfskey=='cs2'){
if($csbwfsCustomBtn2!='' && $bottomPosition!='bottom')
$floatingSidebarContent .=$csbwfsCustomBtn2;
}
/* Custom 3 */
if($csbwfskey=='cs3' && $bottomPosition!='bottom'){
if($csbwfsCustomBtn3!='')
$floatingSidebarContent .=$csbwfsCustomBtn3;
}
/* Custom 4 */
if($csbwfskey=='cs4' && $bottomPosition!='bottom'){
if($csbwfsCustomBtn4!='')
$floatingSidebarContent .=$csbwfsCustomBtn4;
}
/* Custom 5 */
if($csbwfskey=='cs5'){
if($csbwfsCustomBtn5!='' && $bottomPosition!='bottom')
$floatingSidebarContent .=$csbwfsCustomBtn5;
}
/* Custom 6 */
if($csbwfskey=='cs6'){
if($csbwfsCustomBtn6!='' && $bottomPosition!='bottom')
$floatingSidebarContent .=$csbwfsCustomBtn6;
}

/* Custom 7 */
if($csbwfskey=='cs7' && $bottomPosition!='bottom'){
if($csbwfsCustomBtn7!='')
$floatingSidebarContent .=$csbwfsCustomBtn7;
}
/* Custom 8 */
if($csbwfskey=='cs8' && $bottomPosition!='bottom'){
if($csbwfsCustomBtn8!='')
$floatingSidebarContent .=$csbwfsCustomBtn8;
}
/* Custom 9 */
if($csbwfskey=='cs9'){
if($csbwfsCustomBtn9!='' && $bottomPosition!='bottom')
$floatingSidebarContent .=$csbwfsCustomBtn9;
}
/* Custom 10 */
if($csbwfskey=='cs10'){
if($csbwfsCustomBtn10!='' && $bottomPosition!='bottom')
$floatingSidebarContent .=$csbwfsCustomBtn10;
}

/** FB */
if($csbwfskey=='fa'){
if($pluginOptionsVal['csbwfs_fpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_fb_page_url']) && $pluginOptionsVal['csbwfs_fb_page_url']!=''):
$fbshareurl=$pluginOptionsVal['csbwfs_fb_page_url'];
else:
$fbshareurl=$shareurl;
endif;
$fbcntdiv='';
if($_SESSION['fb_count_content']!=''){$fbcntdiv='csbwfs-count-sharecountBtns';}
$floatingSidebarContent .='<div class="csbwfs-sbutton '.$fbcntdiv.'"><div class="csbwfs-fb"><a href="javascript:" onclick="window.open(\'//www.facebook.com/sharer/sharer.php?u='.$fbshareurl.'\',\'Facebook\',\'width=800,height=300\')" title="'.$csbwfs_fb_title.'" '.$fImgbg.'> <img src="'.$fImg.'" alt="'.$csbwfs_fb_title.'" title="'.$csbwfs_fb_title.'" width="30" height="30">'.$_SESSION['fb_count_content'].'</a></div></div>';
endif;
}
/** GP */
if($csbwfskey=='gp'){
if($pluginOptionsVal['csbwfs_gpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_gp_page_url']) && $pluginOptionsVal['csbwfs_gp_page_url']!=''):
$gpshareurl=$pluginOptionsVal['csbwfs_gp_page_url'];
else:
$gpshareurl=$shareurl;
endif;
$gpcntdiv='';
if($_SESSION['gp_count_content']!=''){$gpcntdiv='csbwfs-count-sharecountBtns';}
$floatingSidebarContent .='<div class="csbwfs-sbutton '.$gpcntdiv.'"><div class="csbwfs-gp"><a href="javascript:"  onclick="javascript:window.open(\'//plus.google.com/share?url='.$gpshareurl.'\',\'\',\'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=800\');return false;" title="'.$csbwfs_gp_title.'"  '.$gImgbg.'><img src="'.$gImg.'" alt="'.$csbwfs_gp_title.'" width="30" height="30">'.$_SESSION['gp_count_content'].'</a></div></div>';
endif;
}
/**  LI */
if($csbwfskey=='li'){
if($pluginOptionsVal['csbwfs_lpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_li_page_url']) && $pluginOptionsVal['csbwfs_li_page_url']!=''):
$lishareurl=$pluginOptionsVal['csbwfs_li_page_url'];
else:
$lishareurl=$shareurl;
endif;
$licntdiv='';
$licntval=csbwfs_return_count_li($lishareurl);if($licntval!=''){$licntdiv='csbwfs-count-sharecountBtns';}
$licntdiv='';
if($_SESSION['li_count_content']!=''){$licntdiv='csbwfs-count-sharecountBtns';}
$floatingSidebarContent .='<div class="csbwfs-sbutton '.$licntdiv.'"><div class="csbwfs-li"><a href="javascript:" onclick="javascript:window.open(\'//www.linkedin.com/shareArticle?mini=true&url='. $lishareurl.'&title='.$ShareTitle.'&summary='.$ShareOgDesc.'\',\'\',\'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=800\');return false;" title="'.$csbwfs_li_title.'" '.$lImgbg.'><img src="'.$lImg.'" alt="'.$csbwfs_li_title.'" width="30" height="30">'.$_SESSION['li_count_content'].'</a></div></div>';
endif;
}
/** PIN */
if($csbwfskey=='pi'){

if($pluginOptionsVal['csbwfs_ppublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_pin_page_url']) && $pluginOptionsVal['csbwfs_pin_page_url']!=''):
$pinshareurl=$pluginOptionsVal['csbwfs_pin_page_url'];
$pinshareurlCond='no';
else:
$pinshareurl=$shareurl;
$pinshareurlCond='';
endif;
$pincntdiv='';
if($_SESSION['pin_count_content']!=''){$pincntdiv='csbwfs-count-sharecountBtns';}
if($pinshareurlCond!=''):
$floatingSidebarContent .='<div class="csbwfs-sbutton '.$pincntdiv.'"><div class="csbwfs-pin"><a href="'.$pinshareurl.'" target="_blank" '.$pImgbg.' title="'.$csbwfs_pin_title.'"><img src="'.$pImg.'" alt="'.$csbwfs_pin_title.'" width="30" height="30">'.$_SESSION['pin_count_content'].'</a></div></div>';
else:
$floatingSidebarContent .='<div class="csbwfs-sbutton '.$pincntdiv.'"><div class="csbwfs-pin"><a onclick="javascript:void((function(){var e=document.createElement(\'script\');e.setAttribute(\'type\',\'text/javascript\');e.setAttribute(\'charset\',\'UTF-8\');e.setAttribute(\'src\',\'//assets.pinterest.com/js/pinmarklet.js?r=\'+Math.random()*99999999);document.body.appendChild(e)})());" href="javascript:" '.$pImgbg.' title="'.$csbwfs_pin_title.'"><img src="'.$pImg.'" alt="'.$csbwfs_pin_title.'" width="30" height="30">'.$_SESSION['pin_count_content'].'</a></div></div>';
endif;
endif;
}
/** Stumbleupon */
if($csbwfskey=='st'){
if(isset($pluginOptionsVal['csbwfs_stpublishBtn']) && $pluginOptionsVal['csbwfs_stpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_st_page_url']) && $pluginOptionsVal['csbwfs_st_page_url']!=''):
$stshareurl=$pluginOptionsVal['csbwfs_st_page_url'];
else:
$stshareurl=$shareurl;
endif;
$stcntdiv='';
if($_SESSION['st_count_content']!=''){$stcntdiv='csbwfs-count-sharecountBtns';}
$floatingSidebarContent .='<div class="csbwfs-sbutton '.$stcntdiv.'"><div class="csbwfs-st"><a onclick="window.open(\'//www.stumbleupon.com/submit?url='.$stshareurl.'&amp;title='.$ShareTitle.'\',\'Stumbleupon\',\'toolbar=0,status=0,width=1000,height=800\');"  href="javascript:void(0);" '.$stImgbg.' title="'.$csbwfs_st_title.'"><img src="'. $stImg.'" alt="'.$csbwfs_st_title.'" width="30" height="30">'.$_SESSION['st_count_content'].'</a></div></div>';
endif;
}
/** Reddit */
if($csbwfskey=='re'){
if(isset($pluginOptionsVal['csbwfs_republishBtn']) && $pluginOptionsVal['csbwfs_republishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_re_page_url']) && $pluginOptionsVal['csbwfs_re_page_url']!=''):
$reshareurl=$pluginOptionsVal['csbwfs_re_page_url'];
else:
$reshareurl=$shareurl;
endif;
$recntdiv='';
if($_SESSION['re_count_content']!=''){$recntdiv='csbwfs-count-sharecountBtns';}
$floatingSidebarContent .='<div class="csbwfs-sbutton '.$recntdiv.'"><div class="csbwfs-re"><a onclick="window.open(\'//reddit.com/submit?url='.$reshareurl.'&amp;title='.$ShareTitle.'\',\'Reddit\',\'toolbar=0,status=0,width=1000,height=800\');" href="javascript:void(0);" '.$reImgbg.' title="'.$csbwfs_re_title.'"><img src="'.$reImg.'" alt="'.$csbwfs_re_title.'" width="30" height="30">'.$_SESSION['re_count_content'].'</a></div></div>';
endif;
}
/** Tumbler */
if($csbwfskey=='tu'){
if(isset($pluginOptionsVal['csbwfs_tupublishBtn']) && $pluginOptionsVal['csbwfs_tupublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_tu_page_url']) && $pluginOptionsVal['csbwfs_tu_page_url']!=''):
$tushareurl=$pluginOptionsVal['csbwfs_tu_page_url'];
else:
$tushareurl=$shareurl;
endif;
$tucntdiv='';
if($_SESSION['tu_count_content']!=''){$tucntdiv='csbwfs-count-sharecountBtns';}
$floatingSidebarContent .='<div class="csbwfs-sbutton '.$tucntdiv.'"><div class="csbwfs-tu"><a onclick="window.open(\'//tumblr.com/widgets/share/tool?canonicalUrl='.$tushareurl.'&amp;title='.$ShareTitle.'\',\'Tumbler\',\'toolbar=0,status=0,width=540,height=600\');" href="javascript:void(0);" '.$tuImgbg.' title="'.$csbwfs_tu_title.'"><img src="'.$tuImg.'" alt="'.$csbwfs_tu_title.'" width="30" height="30">'.$_SESSION['tu_count_content'].'</a></div></div>';
endif;
}
/** TW */
if($csbwfskey=='tw'){
if($pluginOptionsVal['csbwfs_tpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_tw_page_url']) && $pluginOptionsVal['csbwfs_tw_page_url']!=''):
$twshareurl=$pluginOptionsVal['csbwfs_tw_page_url'];
else:
$twshareurl=$shareurl;
endif;
$twun='';
if(isset($pluginOptionsVal['csbwfs_tu_un']) && $pluginOptionsVal['csbwfs_tu_un']!=''):
$twun='&via='.$pluginOptionsVal['csbwfs_tu_un'];
endif;

$floatingSidebarContent .='<div class="csbwfs-sbutton"><div class="csbwfs-tw"><a href="javascript:" onclick="window.open(\'//twitter.com/share?url='.$twshareurl.'&text='.$ShareTitle.' - '.$ShareOgDesc.$twun.'\',\'_blank\',\'width=800,height=300\')" title="'.$csbwfs_tw_title.'" '.$tImgbg.'><img src="'.$tImg.'" alt="'.$csbwfs_tw_title.'" width="30" height="30"></a></div></div>';
endif;
}
/** Diggit */
if($csbwfskey=='di'){
if(isset($pluginOptionsVal['csbwfs_diggpublishBtn']) && $pluginOptionsVal['csbwfs_diggpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_digg_page_url']) && $pluginOptionsVal['csbwfs_digg_page_url']!=''):
$dishareurl=$pluginOptionsVal['csbwfs_digg_page_url'];
else:
$dishareurl=$shareurl;
endif;
$floatingSidebarContent .='<div class="csbwfs-sbutton"><div class="csbwfs-digg"><a onclick="window.open(\'//digg.com/submit?url='.$dishareurl.'\',\'Diggit\',\'toolbar=0,status=0,width=1000,height=800\');" href="javascript:void(0);" '.$diggImgbg.' title="'.$csbwfs_digg_title.'"><img src="'.$diggImg.'" alt="'.$csbwfs_digg_title.'" width="30" height="30"></a></div></div>';
endif;
}
/** Yummly */
if($csbwfskey=='yu'){
if(isset($pluginOptionsVal['csbwfs_yumpublishBtn']) && $pluginOptionsVal['csbwfs_yumpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_yum_page_url']) && $pluginOptionsVal['csbwfs_yum_page_url']!=''):
$yushareurl=$pluginOptionsVal['csbwfs_yum_page_url'];
else:
$yushareurl=$shareurl;
endif;
$floatingSidebarContent .='<div class="csbwfs-sbutton"><div class="csbwfs-yum"><a onclick="window.open(\'//www.yummly.com/urb/verify?url='.$yushareurl.'&amp;title='.$ShareTitle.'\',\'Yummly\',\'toolbar=0,status=0,width=1000,height=800\');" href="javascript:void(0);" '.$yumImgbg.' title="'.$csbwfs_yum_title.'"><img src="'.$yumImg.'" alt="'.$csbwfs_yum_title.'" width="30" height="30"></a></div></div>';
endif;
}
/** VK */
if($csbwfskey=='vk'){
if(isset($pluginOptionsVal['csbwfs_vkpublishBtn']) && $pluginOptionsVal['csbwfs_vkpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_vk_page_url']) && $pluginOptionsVal['csbwfs_vk_page_url']!=''):
$vkshareurl=$pluginOptionsVal['csbwfs_vk_page_url'];
else:
$vkshareurl=$shareurl;
endif;
$floatingSidebarContent .='<div class="csbwfs-sbutton"><div class="csbwfs-vk"><a onclick="window.open(\'//vk.com/share.php?url='.$vkshareurl.'&amp;title='.$ShareTitle.'\',\'Vk\',\'toolbar=0,status=0,width=1000,height=800\');" href="javascript:void(0);" '.$vkImgbg.' title="'.$csbwfs_vk_title.'"><img src="'.$vkImg.'" alt="'.$csbwfs_vk_title.'" width="30" height="30"></a></div></div>';
endif;
}
/** Buffer */
if($csbwfskey=='bu'){
if(isset($pluginOptionsVal['csbwfs_bufpublishBtn']) && $pluginOptionsVal['csbwfs_bufpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_buf_page_url']) && $pluginOptionsVal['csbwfs_buf_page_url']!=''):
$bushareurl=$pluginOptionsVal['csbwfs_buf_page_url'];
else:
$bushareurl=$shareurl;
endif;
$floatingSidebarContent .='<div class="csbwfs-sbutton"><div class="csbwfs-buf"><a onclick="window.open(\'//bufferapp.com/add?url='.$bushareurl.'&amp;title='.$ShareTitle.'\',\'Buffer\',\'toolbar=0,status=0,width=1000,height=800\');" href="javascript:void(0);" '.$bufImgbg.' title="'.$csbwfs_buf_title.'"><img src="'.$bufImg.'" alt="'.$csbwfs_buf_title.'" width="30" height="30"></a></div></div>';
endif;
}
/** WhatsApp */
if($csbwfskey=='wh'){
if(isCsbwfsMobilePro()):
if(isset($pluginOptionsVal['csbwfs_whatsapppublishBtn']) && $pluginOptionsVal['csbwfs_whatsapppublishBtn']!=''):
$floatingSidebarContent .='<div class="csbwfs-sbutton whatsapp"><div class="csbwfs-whats"><a href="whatsapp://send?text='.$ShareTitle.'&nbsp;'.$ShareDesc.'&nbsp;'.$shareurl.'"  title="Share on WhatsApp" > <img src="'.plugins_url('images/whatsapp.png',__FILE__).'" alt="Share With Whatsapp" width="30" height="30"></a></div></div>';
endif;
endif;
}
/** SMS */
if($csbwfskey=='sms'){
if(isCsbwfsMobilePro()):
if(isset($pluginOptionsVal['csbwfs_smspublishBtn']) && $pluginOptionsVal['csbwfs_smspublishBtn']!=''):
$floatingSidebarContent .='<div class="csbwfs-sbutton sms"><div class="csbwfs-sms"><a href="sms://&body='.$ShareTitle.'&nbsp;'.$ShareDesc.'&nbsp;'.$shareurl.'"  title="Share on SMS" > <img src="'.plugins_url('images/sms.png',__FILE__).'" alt="Share With SMS" width="30" height="30"></a></div></div>';
endif;
endif;
}
/** Telegram */
if($csbwfskey=='te'){
if(isset($pluginOptionsVal['csbwfs_tepublishBtn']) && $pluginOptionsVal['csbwfs_tepublishBtn']!=''):
if(isCsbwfsMobilePro()):
$telegramShareurl='tg://msg_url?url='.$shareurl.'&text='.$ShareTitle;
else:
$telegramShareurl='https://telegram.me/share/url?url='.$shareurl.'&text='.$ShareTitle;
endif;
$floatingSidebarContent .='<div class="csbwfs-sbutton telegram"><div class="csbwfs-te"><a href="'.$telegramShareurl.'"  title="Share on Telegram" > <img src="'.plugins_url('images/tg.png',__FILE__).'" alt="Share With Telegram" width="30" height="30"></a></div></div>';
endif;
}
/** RSS Feed */
if($csbwfskey=='rs'){
if(isset($pluginOptionsVal['csbwfs_rsspublishBtn']) && $pluginOptionsVal['csbwfs_rsspublishBtn']!=''):
$floatingSidebarContent .='<div class="csbwfs-sbutton rss"><div class="csbwfs-rss"><a onclick="window.open(\''.$pluginOptionsVal['csbwfs_rssPath'].'\');" href="javascript:void(0);"  title="Browse RSS Feed" > <img src="'.plugins_url('images/rss.png',__FILE__).'" alt="Browse RSS Feed" width="30" height="30"></a></div></div>';
endif;
}
/** Line */
if($csbwfskey=='line'){
if(isCsbwfsMobilePro()):
if(isset($pluginOptionsVal['csbwfs_linepublishBtn']) && $pluginOptionsVal['csbwfs_linepublishBtn']!=''):
$floatingSidebarContent .='<div class="csbwfs-sbutton line"><div class="csbwfs-line"><a href="//line.me/R/msg/text/?'.$ShareTitle.'%0D%0A'.$shareurl.'"  title="LINE it!" > <img src="'.plugins_url('images/line.png',__FILE__).'" alt="LINE it!" width="30" height="30"></a></div></div>';
endif;
endif;
}
/** YT */	
if($csbwfskey=='yt'){ 	 
if(isset($pluginOptionsVal['csbwfs_ytpublishBtn']) && $pluginOptionsVal['csbwfs_ytpublishBtn']!=''):
$floatingSidebarContent .='<div class="csbwfs-sbutton yt"><div class="csbwfs-yt"><a onclick="window.open(\''.$pluginOptionsVal['csbwfs_ytPath'].'\');" href="javascript:void(0);" '.$ytImgbg.' title="'.$csbwfs_yt_title.'"><img src="'.$ytImg.'" alt="'.$csbwfs_yt_title.'" width="30" height="30"></a></div></div>';
endif;
}
/** G-Mail */	
if($csbwfskey=='gm'){ 	 
if(isset($pluginOptionsVal['csbwfs_gmtpublishBtn']) && $pluginOptionsVal['csbwfs_gmtpublishBtn']!=''):
$floatingSidebarContent .='<div class="csbwfs-sbutton gm"><div class="csbwfs-gm"><a onclick="window.open(\''.$pluginOptionsVal['csbwfs_ytPath'].'\');" href="javascript:void(0);" '.$gmImgbg.' title="'.$csbwfs_gmail_title.'"><img src="'.plugins_url('images/gmail.png',__FILE__).'" alt="'.$csbwfs_gmail_title.'" width="30" height="30"></a></div></div>';
endif;
}
/** Instagram */
if($csbwfskey=='in'){
if(isset($pluginOptionsVal['csbwfs_instpublishBtn']) && $pluginOptionsVal['csbwfs_instpublishBtn']!=''):
$floatingSidebarContent .='<div class="csbwfs-sbutton inst"><div class="csbwfs-inst"><a onclick="window.open(\''.$pluginOptionsVal['csbwfs_inst_page_url'].'\');" href="javascript:void(0);" '.$instImgbg.' title="'.$csbwfs_inst_title.'"><img src="'.$instImg.'" alt="'.$csbwfs_inst_title.'" width="30" height="30"></a></div></div>';
endif;
} 
/** Google Translate */
if($csbwfskey=='gt'){
if($pluginOptionsVal['csbwfs_gtpublishBtn']!=''):
$floatingSidebarContent .='<div class="csbwfs-sbutton gt" id="csbwfs-sbutton"><div class="csbwfs-gt"><a href="//translate.google.com/translate?u='.$shareurl.'"  '.$gtImgbg.' target="_blank" title="'.$csbwfs_gt_title.'"> <img src="'.$gtImg.'" alt="'.$csbwfs_gt_title.'" width="30" height="30"></a></div></div>';
endif;
}
/** Mail*/
if($csbwfskey=='ma'){
if($pluginOptionsVal['csbwfs_mpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_mail_page_url']) && $pluginOptionsVal['csbwfs_mail_page_url']!=''):
$floatingSidebarContent .='<div class="csbwfs-sbutton mail"><div class="csbwfs-ml"><a href="'.$pluginOptionsVal['csbwfs_mail_page_url'].'" title="'.$csbwfs_mail_title.'" '.$mImgbg.' ><img src="'.$mImg.'" alt="'.$csbwfs_mail_title.'" width="30" height="30"></a></div></div>';
else:
$floatingSidebarContent .='<div class="csbwfs-sbutton mail"><div class="csbwfs-lighbox csbwfs-ml"><a href="javascript:"  '.$mImgbg.' title="'.$csbwfs_mail_title.'"><img src="'.$mImg.'" alt="'.$csbwfs_mail_title.'" width="30" height="30"></a></div></div>';
endif;
endif;
}
/** Print */
if($csbwfskey=='pr'){
if($pluginOptionsVal['csbwfs_printpublishBtn']!=''):
$floatingSidebarContent .='<div class="csbwfs-sbutton print"><div class="csbwfs-print" class="csbwfs-print"><a href="javascript:"  '.$printImgbg.' title="'.$csbwfs_print_title.'" ><img src="'.$printImg.'" alt="'.$csbwfs_print_title.'"  width="30" height="30"></a></div></div>';
endif;
}
/** Skype */
if($csbwfskey=='sk'){
if(isset($pluginOptionsVal['csbwfs_skypublishBtn']) && $pluginOptionsVal['csbwfs_skyUnpublishBtn']!=''):
$csbwfsSkypeName=$pluginOptionsVal['csbwfs_skyUnpublishBtn'];
$floatingSidebarContent .='<div class="csbwfs-sbutton skype"><div class="csbwfs-skype"><a href="skype:live:'.$csbwfsSkypeName.'?chat"  target="_blank" title="Chat on Skype"> <img src="'.plugins_url('images/skype.png',__FILE__).'" alt="Chat on Skype" width="30" height="30"></a></div></div>';
endif;
}

/** Blogger */
if($csbwfskey=='bl'){
if(isset($pluginOptionsVal['csbwfs_blpublishBtn']) && $pluginOptionsVal['csbwfs_blpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_bl_page_url']) && $pluginOptionsVal['csbwfs_bl_page_url']!=''):
$blshareurl=$pluginOptionsVal['csbwfs_bl_page_url'];
else:
$blshareurl=$shareurl;
endif;
$floatingSidebarContent .='<div class="csbwfs-sbutton"><div class="csbwfs-bl"><a onclick="window.open(\'//www.blogger.com/blog-this.g?u='.$blshareurl.'&amp;t='.$ShareTitle.'\',\'Blogger\',\'toolbar=0,status=0,width=1000,height=800\');" href="javascript:void(0);" '.$blImgbg.' title="'.$csbwfs_bl_title.'"><img src="'.$blImg.'" alt="'.$csbwfs_bl_title.'" width="30" height="30"></a></div></div>';
endif;
}
/** Gmail */
if($csbwfskey=='gm'){
if(isset($pluginOptionsVal['csbwfs_gmpublishBtn']) && $pluginOptionsVal['csbwfs_gmpublishBtn']!=''):
$floatingSidebarContent .='<div class="csbwfs-sbutton"><div class="csbwfs-gm"><a onclick="window.open(\'//mail.google.com/mail/u/0/?view=cm&amp;fs=1&amp;to='.get_option('admin_email').'&amp;su=\'+encodeURIComponent(document.title)+\'&amp;body='.$shareurl.'&amp;ui=2&amp;tf=1\',\'Gmail\',\'toolbar=0,status=0,width=1000,height=800\');" href="javascript:void(0);" '.$gmImgbg.' title="'.$csbwfs_gm_title.'"><img src="'.$gmImg.'" alt="'.$csbwfs_gm_title.'" width="30" height="30"></a></div></div>';
endif;
}
/** Delicious */
if($csbwfskey=='de'){
if(isset($pluginOptionsVal['csbwfs_depublishBtn']) && $pluginOptionsVal['csbwfs_depublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_de_page_url']) && $pluginOptionsVal['csbwfs_de_page_url']!=''):
$deshareurl=$pluginOptionsVal['csbwfs_de_page_url'];
else:
$deshareurl=$shareurl;
endif;
$floatingSidebarContent .='<div class="csbwfs-sbutton"><div class="csbwfs-de"><a onclick="window.open(\'//delicious.com/save?v=5&provider=MRWEBSOLUTION&noui&jump=close&url=\'+encodeURIComponent(location.href)+\'&title=\'+encodeURIComponent(document.title), \'delicious\',\'toolbar=no,width=550,height=550\'); return false;" href="javascript:void(0);" '.$deImgbg.' title="'.$csbwfs_de_title.'"><img src="'.$deImg.'" alt="'.$csbwfs_de_title.'" width="30" height="30"></a></div></div>';
endif;
}
/** Weibo */
if($csbwfskey=='we'){
if(isset($pluginOptionsVal['csbwfs_wepublishBtn']) && $pluginOptionsVal['csbwfs_wepublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_we_page_url']) && $pluginOptionsVal['csbwfs_we_page_url']!=''):
$weshareurl=$pluginOptionsVal['csbwfs_we_page_url'];
else:
$weshareurl=$shareurl;
endif;
$floatingSidebarContent .='<div class="csbwfs-sbutton"><div class="csbwfs-we"><a onclick="window.open(\'//service.weibo.com/share/share.php?url=\'+encodeURIComponent(location.href)+\'&amp;title='.$ShareTitle.' - '.$ShareOgDesc.'&amp;pic='.$csbwfsOgimg.'\', \'Weibo\',\'toolbar=no,width=550,height=550\'); return false;" href="javascript:void(0);" '.$weImgbg.' title="'.$csbwfs_we_title.'"><img src="'.$weImg.'" alt="'.$csbwfs_we_title.'" width="30" height="30"></a></div></div>';
endif;
}

}
$floatingSidebarContent .='</div>'; //End social-share-inner

if($isActiveHideShowBtn!='yes') :
$floatingSidebarContent .='<div class="csbwfs-hide"><a href="javascript:" title="'.$hidebtn.'" id="csbwfs-hide"><img src="'.plugins_url('images/'.$hideImg,__FILE__).'" alt="'.$hidebtn.'" width="20" height="20"></a></div>';
endif;

$floatingSidebarContent .='</div></div><div id="maillightbox"></div>'; //End social-inner
if($pluginOptionsVal['csbwfs_mpublishBtn']!=''):
$floatingSidebarContent .='<div id="csbwfsBox" style="display:none">';
if(isset($pluginOptionsVal['csbwfs_mail_form_shortcode']) && $pluginOptionsVal['csbwfs_mail_form_shortcode']==''):
$operationAry=array('+','x','+','x');
$random_action=array_rand($operationAry,2);
$random_actionVal=$operationAry[$random_action[0]];
$actnVal1=rand(1,9);
$actnVal2=rand(1,9);
$formtitle='Contact us';
$formtitleval=get_option('csbwfs_form_heading');
if($formtitleval!=''){ $formtitle=$formtitleval;}
$formsubheading='';
$formsubheadingval=get_option('csbwfs_form_subheading');
if($formsubheadingval!=''){ $formsubheading='<div class="subheading">'.$formsubheadingval.'</div>';}

$floatingSidebarContent .='<div id="csbwfs_contact"><script>jQuery("#csbwfs_lightbox .close span").click(function() {	    jQuery("#maillightbox").html("");
		jQuery("#csbwfs_lightbox").hide().fadeOut(1000);});</script>
      <div class="heading">'.$formtitle.'</div>'.$formsubheading.' 
      <div class="csbwfsmsg"></div>
      <form action="'.home_url('/').'" method="post" id="csbwfs_form">
        <p><label>Name<span class="req">*</span>: </label> <br><input name="csbwfs_name" id="csbwfs_name" type="text"  class="csbwfs-req-fields" /></p>
        <p><label>Email<span class="req">*</span>: </label> <br><input name="csbwfs_email" id="csbwfs_email" type="text" class="csbwfs-req-fields csbwfs-req-email"/></p>
        <p><label>Message: </label> <br><textarea name="csbwfs_message" id="csbwfs_message" rows="3" cols="46"></textarea></p>
        <div class="csbwfsCptcha"><label>Captcha<span class="req">*</span>: </label> &nbsp;&nbsp; <div id="firstAct">'.$actnVal1.'</div> <div id="Acttion">'.$random_actionVal.'</div> <div id="secondAct">'.$actnVal2.'</div> <div>=</div><div class="input"><input type="text" name="csbwfs_code" id="csbwfs_code"  size="10" class="csbwfs-req-fields csbwfs-req-cptcha"/></div><div id="cptchaErr"></div></div>
        <p><strong>Request URL: </strong> '.$shareurl.'</p>
        <p><input name="cswbfs_submit_btn" type="submit" value="Submit" class="cswbfs_submit_btn" id="cswbfs_submit_btn"/><input name="cswbfs_submit_form" type="hidden" value="submit-csbwfs-form" /><input name="cswbfs_hdn_cptha" type="hidden" value="" /><input name="cswbfs_hdn_cpthaval1" id="cswbfs_hdn_cpthaval1" type="hidden" value="'.$actnVal1.'" /><input name="cswbfs_hdn_cpthaval2" id="cswbfs_hdn_cpthaval2" type="hidden" value="'.$actnVal2.'" /><input name="cswbfs_hdn_cpthaaction" id="cswbfs_hdn_cpthaaction" type="hidden" value="'.$random_actionVal.'" /><input type="hidden" name="csbwfs_request_url" value="'.$shareurl.'"></p>
        <p>Please complete all <span class="req">*</span> marked fields. Thank You!</p></form></div>'; 
        //End social-inner
else:
$js='<script type="text/javascript">
if(jQuery("#csbwfs_contact .wpcf7 form").hasClass("invalid")){
	csbwfs_form_lightbox();
	}
	
	
function csbwfs_form_lightbox(){
	var content =jQuery("#csbwfs_contact").html();
			var csbwfs_lightbox_content = 
			"<div id=\"csbwfs_lightbox\">" +
				"<div id=\"csbwfs_content\">" +
				"<div class=\"close\"><span ></span></div>"  + content  +
				"</div>" +	
			"</div>";
			//insert lightbox HTML into page
			jQuery("#maillightbox").append(csbwfs_lightbox_content).hide().fadeIn(1000);
	}
</script>';
$floatingSidebarContent .='<div id="csbwfs_contact"><script>jQuery("#csbwfs_lightbox .close span").click(function() {	    jQuery("#maillightbox").html("");
		jQuery("#csbwfs_lightbox").hide().fadeOut(1000);});</script>
      <div class="heading">'.$formtitle.'</div>'.$formsubheading.'
      <div class="csbwfsmsg"></div>
      '.do_shortcode($pluginOptionsVal['csbwfs_mail_form_shortcode']).'</div>'.$js; //End social-inner
endif;
endif;
$floatingSidebarContent .='</div></div>';
/** Check conditions */
    // Returns the content.
    global $post;
    $csbwfsPageIds=explode(',',$pluginOptionsVal['csbwfs_custom_page_ids']);
    $returnfloatingSidebarContent='';
   if((is_home() && is_front_page()) && $pluginOptionsVal['csbwfs_hide_home']!='yes'):
	$returnfloatingSidebarContent=$floatingSidebarContent;
    endif;
	/* front page */
	if(is_front_page() && $pluginOptionsVal['csbwfs_hide_home']!='yes'):
	$returnfloatingSidebarContent=$floatingSidebarContent;
    endif;
    /* blog */
	if(is_home() && $pluginOptionsVal['csbwfs_hide_blog']!='yes'):
	$returnfloatingSidebarContent=$floatingSidebarContent;
    endif;
		
	/* post */
     if(is_single() && $pluginOptionsVal['csbwfs_hide_post']=='yes'):
	  if(in_array($post->ID,$csbwfsPageIds) && $pluginOptionsVal['csbwfs_hide_on']=='show'):
     	$returnfloatingSidebarContent=$floatingSidebarContent;
	  endif;
    endif;
	if(is_single() && $pluginOptionsVal['csbwfs_hide_post']==''):
	 $returnfloatingSidebarContent=$floatingSidebarContent;
	endif;
    /* page */
     if(is_page() && $pluginOptionsVal['csbwfs_hide_page']=='yes'):
       if(in_array($post->ID,$csbwfsPageIds) && $pluginOptionsVal['csbwfs_hide_on']=='show'):
       	$returnfloatingSidebarContent=$floatingSidebarContent;
	  endif;
    endif;
	if(is_page() && $pluginOptionsVal['csbwfs_hide_page']==''):
	if(!is_front_page()):
	 $returnfloatingSidebarContent=$floatingSidebarContent;
	 endif;
	endif;
  /* product */
     if(is_singular( 'product' ) && $pluginOptionsVal['csbwfs_hide_product']=='yes'):
       if(in_array($post->ID,$csbwfsPageIds) && $pluginOptionsVal['csbwfs_hide_on']=='show'):
       	$returnfloatingSidebarContent=$floatingSidebarContent;
	   endif;
    endif;
   if(is_singular( 'product' ) && $pluginOptionsVal['csbwfs_hide_product']==''):
	$returnfloatingSidebarContent=$floatingSidebarContent;
	endif;

    /* custom post type */
    $postypeval=get_option('cswbfs_exclude_post_type');
     if($postypeval!=''):
      if(is_singular()){
      $customPostAry=explode(',',$postypeval);
      if(is_singular( $customPostAry )):
        $returnfloatingSidebarContent='';
	   else:
	    $returnfloatingSidebarContent=$floatingSidebarContent;
       endif;
      }
    endif;
	
    /* Archive */
    if(is_archive() && $pluginOptionsVal['csbwfs_hide_archive']=='yes'):
      $returnfloatingSidebarContent='';
      endif;
	
	/* hide for custom ids */
	if(is_singular()):

		if(in_array($post->ID,$csbwfsPageIds) && $pluginOptionsVal['csbwfs_hide_on']=='hide'):
		$returnfloatingSidebarContent='';
		endif;

		if(in_array($post->ID,$csbwfsPageIds) && $pluginOptionsVal['csbwfs_hide_on']=='show'):
		$returnfloatingSidebarContent=$floatingSidebarContent;
		endif;
			//echo 'kkkkk'.$returnfloatingSidebarContent."ggggggggg";

    endif;
	
	/* 404 page */
    if(is_404()):
     $returnfloatingSidebarContent='';
    endif;
    
	//echo 'kkkkk'.$returnfloatingSidebarContent."ggggggggg";
	//exit;
    print $returnfloatingSidebarContent; // return sidebar content
}

/** CSBWFS Contact Form */
if(isset($_POST['cswbfs_submit_form']) && $_POST['cswbfs_hdn_cptha']=='')
{

$cptha1=strip_tags($_POST['cswbfs_hdn_cpthaval1']);
$cptha2=strip_tags($_POST['cswbfs_hdn_cpthaval2']);
$cptha3=strip_tags($_POST['cswbfs_hdn_cpthaaction']);
$cptha4=strip_tags($_POST['csbwfs_code']);
if($cptha3=='x'){ 
$finalCechking=($cptha1*$cptha2);
}else {
$finalCechking=($cptha1+$cptha2);
}

if($cptha4==$finalCechking){

$pluginOptionsVal=get_csbwf_pro_sidebar_options();

include(ABSPATH . "wp-includes/pluggable.php"); 
$cswbfsSiteEmail = get_option( 'admin_email' );
$cswbfsSiteTitle = get_option( 'blogname' );
$cswbfsSiteUrl = get_option( 'siteurl' );

$csbwfs_msg_body='';	
$csbwfs_thank_msg=$pluginOptionsVal['csbwfs_mail_thank_msg'];

$csbwfsUserMsg="Thank you!\r\nYou are very important to us, all information received will always remain confidential. We will contact you as soon as we review your message.\r\n\r\n Thanks\r\n".$cswbfsSiteTitle." Team";
$csbwfsUserSubject="Thank you for contacting us";

$csbwfs_mail_cc='';

$csbwfsMail = strip_tags($_POST['csbwfs_email']);
$csbwfsMsg = strip_tags($_POST['csbwfs_message']);
$csbwfsName = strip_tags($_POST['csbwfs_name']);
$csbwfsReqUrl = $_POST['csbwfs_request_url'];

$csbwfs_mail_to= $pluginOptionsVal['csbwfs_mail_to'];
$csbwfs_mail_from= $pluginOptionsVal['csbwfs_mail_from'];
$csbwfs_mail_subject= $pluginOptionsVal['csbwfs_mail_subject'];
$csbwfs_mail_welcome_msg= $pluginOptionsVal['csbwfs_mail_welcome_msg'];

if(isset($csbwfs_mail_to) && $csbwfs_mail_to!='')
$cswbfsSiteEmail=$csbwfs_mail_to;	

if(isset($csbwfs_mail_subject) && $csbwfs_mail_subject!='')
$csbwfsSubject=strip_tags($csbwfs_mail_subject);

if(isset($csbwfs_mail_from) && $csbwfs_mail_from!='')
$cswbfsSiteEmail=strip_tags($csbwfs_mail_from);

if(isset($csbwfs_mail_welcome_msg) && $csbwfs_mail_welcome_msg!='')
$csbwfsUserMsg=strip_tags($csbwfs_mail_welcome_msg);

/** Admin Mail */
$csbwfs_msg_body .= "Dear Admin,\n Please find new request details given below"."\n";
$csbwfs_msg_body .= "Name: ".$csbwfsName."\r\n";
$csbwfs_msg_body .= "Email: ".$csbwfsMail."\r\n";
$csbwfs_msg_body .= "Message: ".$csbwfsMsg."\r\n";
$csbwfs_msg_body .= "Contact Request URL: ".$_SERVER['REQUEST_URI']."\r\n\r\n";

$csbwfs_msg_body .= "Thanks \r\n".$cswbfsSiteTitle."\r\n--\r\n\r\n
This e-mail was sent from a contact form on ".$cswbfsSiteTitle." (".$cswbfsSiteUrl.")\n IP Address: ".$_SERVER['REMOTE_ADDR'];

$csbwfsSubject="New Contact Request on ".$cswbfsSiteTitle." (Social Flating Sidebar)";

$csbwfsheaders = "MIME-Version: 1.0" . "\r\n";
$csbwfsheaders .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$csbwfsheaders .= 'From: '.$csbwfsName.' <'.$csbwfsMail.'>';

if(isset($csbwfs_mail_cc) && $csbwfs_mail_cc!='')
$csbwfs_mail_cc=$csbwfs_mail_cc;
	
$csbwfsheaders .= 'Cc: '.$csbwfs_mail_cc; // note you can just use a simple email address

//send mail to admin
wp_mail( $cswbfsSiteEmail, $csbwfsSubject, $csbwfs_msg_body, $csbwfsheaders );

/** User Email */
$csbwfsUsrheaders = "MIME-Version: 1.0" . "\r\n";
$csbwfsUsrheaders .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$csbwfsUsrheaders.= 'From: '.$cswbfsSiteTitle.' <'.$cswbfsSiteEmail.'>';
$csbwfsUsrSubject=$csbwfsUserSubject;
$csbwfs_usrmsg_body=nl2br($csbwfsUserMsg);
//send mail to user
if(isset($pluginOptionsVal['csbwfs_mail_thank_msg']) && $csbwfs_thank_msg!=''):
$csbwfsthanksMsg=strip_tags(nl2br($csbwfs_thank_msg));
else:
$csbwfsthanksMsg='Your message has been successfully sent. We will contact you very soon!';
endif;
wp_mail( $csbwfsMail, $csbwfsUsrSubject, $csbwfs_usrmsg_body, $csbwfsUsrheaders );
if(wp_mail( $cswbfsSiteEmail, $csbwfsSubject, $csbwfs_msg_body, $csbwfsheaders ))
{
	echo $csbwfsthanksMsg; exit;
	}else{
		echo "Mail failed,Please contact to site administrator!"; exit;
		}
}
else
{
	echo "The Captcha code you entered was incorrect.Try again!!"; exit;
	}
}
/**
 * Add social share bottons to the end of every post/page.
 *
 * @uses is_home()
 * @uses is_page()
 * @uses is_single()
 */
if(!function_exists('csbfs_pro_the_content_filter')):
function csbfs_pro_the_content_filter( $content ) {
global $post;
$pluginOptionsVal=get_csbwf_pro_sidebar_options();
/** Share button Title */
$csbwfs_fb_title ='Share on facebook';
if(isset($pluginOptionsVal['csbwfs_page_fb_title']) && $pluginOptionsVal['csbwfs_page_fb_title']!='')
$csbwfs_fb_title=$pluginOptionsVal['csbwfs_page_fb_title'];
$csbwfs_tw_title ='Share on twitter';
if(isset($pluginOptionsVal['csbwfs_page_tw_title']) && $pluginOptionsVal['csbwfs_page_tw_title']!='')
$csbwfs_tw_title=$pluginOptionsVal['csbwfs_page_tw_title'];
$csbwfs_li_title='Share on linkdin';
if(isset($pluginOptionsVal['csbwfs_page_li_title']) && $pluginOptionsVal['csbwfs_page_li_title']!='')
$csbwfs_li_title=$pluginOptionsVal['csbwfs_page_li_title'];
$csbwfs_pin_title='Share on pintrest';
if(isset($pluginOptionsVal['csbwfs_page_pin_title']) && $pluginOptionsVal['csbwfs_page_pin_title']!='')
$csbwfs_pin_title=$pluginOptionsVal['csbwfs_page_pin_title'];
$csbwfs_gp_title='Share on google';
if(isset($pluginOptionsVal['csbwfs_page_gp_title']) && $pluginOptionsVal['csbwfs_page_gp_title']!='')
$csbwfs_gp_title=$pluginOptionsVal['csbwfs_page_gp_title'];
$csbwfs_mail_title='Send contact request';
if(isset($pluginOptionsVal['csbwfs_page_mail_title']) && $pluginOptionsVal['csbwfs_page_mail_title']!='')
$csbwfs_mail_title=$pluginOptionsVal['csbwfs_page_mail_title'];
$csbwfs_yt_title='Share on youtube';
if(isset($pluginOptionsVal['csbwfs_page_yt_title']) && $pluginOptionsVal['csbwfs_page_yt_title']!='')
$csbwfs_yt_title=$pluginOptionsVal['csbwfs_page_yt_title'];
$csbwfs_re_title='Share on reddit';
if(isset($pluginOptionsVal['csbwfs_page_re_title']) && $pluginOptionsVal['csbwfs_page_re_title']!='')
$csbwfs_re_title=$pluginOptionsVal['csbwfs_page_re_title'];
$csbwfs_st_title='Share on stumbleupon';
if(isset($pluginOptionsVal['csbwfs_page_st_title']) && $pluginOptionsVal['csbwfs_page_st_title']!='')
$csbwfs_st_title=$pluginOptionsVal['csbwfs_page_st_title'];
$csbwfs_gt_title='Translate page';
if(isset($pluginOptionsVal['csbwfs_page_gt_title']) && $pluginOptionsVal['csbwfs_page_gt_title']!='')
$csbwfs_gt_title=$pluginOptionsVal['csbwfs_page_gt_title'];
$csbwfs_inst_title='Share on instagram';
if(isset($pluginOptionsVal['csbwfs_page_inst_title']) && $pluginOptionsVal['csbwfs_page_inst_title']!='')
$csbwfs_inst_title=$pluginOptionsVal['csbwfs_page_inst_title'];
$csbwfs_digg_title='Share on Diggit';
if(isset($pluginOptionsVal['csbwfs_page_digg_title']) && $pluginOptionsVal['csbwfs_page_digg_title']!='')
$csbwfs_digg_title=$pluginOptionsVal['csbwfs_page_digg_title'];
$csbwfs_yum_title='Share on Yummly';
if(isset($pluginOptionsVal['csbwfs_page_yum_title']) && $pluginOptionsVal['csbwfs_page_yum_title']!='')
$csbwfs_yum_title=$pluginOptionsVal['csbwfs_page_yum_title'];
$csbwfs_vk_title='Share on VK';
if(isset($pluginOptionsVal['csbwfs_page_vk_title']) && $pluginOptionsVal['csbwfs_page_vk_title']!='')
$csbwfs_vk_title=$pluginOptionsVal['csbwfs_page_vk_title'];
$csbwfs_buf_title='Share on Buffer';
if(isset($pluginOptionsVal['csbwfs_page_buf_title']) && $pluginOptionsVal['csbwfs_page_buf_title']!='')
$csbwfs_buf_title=$pluginOptionsVal['csbwfs_page_buf_title'];
$csbwfs_print_title='Print';
if(isset($pluginOptionsVal['csbwfs_page_print_title']) && $pluginOptionsVal['csbwfs_page_print_title']!='')
$csbwfs_print_title=$pluginOptionsVal['csbwfs_page_print_title'];
$csbwfs_gmail_title='New Contact Request';
if(isset($pluginOptionsVal['csbwfs_page_gm_title']) && $pluginOptionsVal['csbwfs_page_gm_title']!='')
$csbwfs_gmail_title=$pluginOptionsVal['csbwfs_page_gm_title'];
$csbwfs_we_title='Share on Weibo';
if(isset($pluginOptionsVal['csbwfs_page_we_title']) && $pluginOptionsVal['csbwfs_page_we_title']!='')
$csbwfs_we_title=$pluginOptionsVal['csbwfs_page_we_title'];
global $post;
if(is_category())
	{
	  $ShareTitle=get_the_title();
	  $shareurl =get_permalink();   
	}
	elseif(is_archive())
	{
		$ShareTitle=get_the_title();
		$shareurl =get_permalink();
	}
	elseif(is_singular())
	{
	   $ShareTitle=$post->post_title;
	   $shareurl=get_permalink($post->ID);
	}
	else
	{
        $shareurl =home_url('/');
        $ShareTitle=get_bloginfo('name');
		}
//$shareurl = csbwfs_pro_get_current_page_url($_SERVER);
$ShareDesc=$ShareOgDesc=$csbwfsOgimg='';			
if(isset($pluginOptionsVal['csbwfs_og_tags_enable']) && $pluginOptionsVal['csbwfs_og_tags_enable']=='yes'){
/* og title */
$ogtile=get_post_meta($post->ID,"csbwfs_og_title",true);
$csbwfs_dft_og_title=get_option('csbwfs_dft_og_title');
if($ogtile=='' && $csbwfs_dft_og_title!=''){$ogtile=$csbwfs_dft_og_title;}
if($ogtile!=''){$ShareTitle=$ogtile;}
/* og description */
$ShareOgDesc=get_post_meta($post->ID,"csbwfs_og_description",true);
$csbwfs_dft_og_desc=get_option('csbwfs_dft_og_desc');
if($ShareOgDesc=='' && $csbwfs_dft_og_desc!=''){$ShareOgDesc=$csbwfs_dft_og_desc;}
if($ShareOgDesc!=''){$ShareDesc=$ShareOgDesc;}
/* og image */
$ShareOgImg=get_post_meta($post->ID,"csbwfs_og_image_path",true);
$csbwfs_dft_og_img=get_option('csbwfs_dft_og_img');
if($ShareOgImg=='' && $csbwfs_dft_og_img!=''){$ShareOgImg=$csbwfs_dft_og_img;}
if($ShareOgImg!=''){$csbwfsOgimg=$ShareOgImg;}
}


$ShareTitle= htmlspecialchars(urlencode($ShareTitle));
$ShareDesc= htmlspecialchars(urlencode($ShareDesc));
/* Get All buttons Image */
//get facebook button image
if($pluginOptionsVal['csbwfs_page_fb_image']!=''){ $fImg=$pluginOptionsVal['csbwfs_page_fb_image'];} 
   else{$fImg=plugins_url('images/fb.png',__FILE__);}   
//get twitter button image  
if($pluginOptionsVal['csbwfs_page_tw_image']!=''){ $tImg=$pluginOptionsVal['csbwfs_page_tw_image'];} 
   else{$tImg=plugins_url('images/tw.png',__FILE__);}   
//get linkdin button image
if($pluginOptionsVal['csbwfs_page_li_image']!=''){ $lImg=$pluginOptionsVal['csbwfs_page_li_image'];} 
   else{$lImg=plugins_url('images/in.png',__FILE__);}   
//get mail button image  
if($pluginOptionsVal['csbwfs_page_mail_image']!=''){ $mImg=$pluginOptionsVal['csbwfs_page_mail_image'];} 
   else{$mImg=plugins_url('images/ml.png',__FILE__);}   
//get google plus button image 
if($pluginOptionsVal['csbwfs_page_gp_image']!=''){ $gImg=$pluginOptionsVal['csbwfs_page_gp_image'];} 
   else{$gImg=plugins_url('images/gp.png',__FILE__);}  
//get pinterest button image   
if($pluginOptionsVal['csbwfs_page_pin_image']!=''){ $pImg=$pluginOptionsVal['csbwfs_page_pin_image'];} 
   else{$pImg=plugins_url('images/pinit.png',__FILE__);}      
//get youtube button image   
if(isset($pluginOptionsVal['csbwfs_page_yt_image']) && $pluginOptionsVal['csbwfs_page_yt_image']!=''){ $ytImg=$pluginOptionsVal['csbwfs_page_yt_image'];} 
   else{$ytImg=plugins_url('images/yt.png',__FILE__);}   
//get Reddit button image   
if(isset($pluginOptionsVal['csbwfs_page_re_image']) && $pluginOptionsVal['csbwfs_page_re_image']!=''){ $reImg=$pluginOptionsVal['csbwfs_page_re_image'];} 
   else{$reImg=plugins_url('images/reddit.png',__FILE__);}   
//get Stumbleupon button image   
if(isset($pluginOptionsVal['csbwfs_page_st_image']) && $pluginOptionsVal['csbwfs_page_st_image']!=''){ $stImg=$pluginOptionsVal['csbwfs_page_st_image'];} 
   else{$stImg=plugins_url('images/st.png',__FILE__);}   
//get Google Translate button image   
if(isset($pluginOptionsVal['csbwfs_page_gt_image']) && $pluginOptionsVal['csbwfs_page_gt_image']!=''){ $gtImg=$pluginOptionsVal['csbwfs_page_gt_image'];} 
   else{$gtImg=plugins_url('images/GTB.png',__FILE__);}   
//get Instagram button image   
if(isset($pluginOptionsVal['csbwfs_page_inst_image']) && $pluginOptionsVal['csbwfs_page_inst_image']!=''){ $instImg=$pluginOptionsVal['csbwfs_page_inst_image'];} 
   else{$instImg=plugins_url('images/inst.png',__FILE__);} 
//get diggit button image   
if($pluginOptionsVal['csbwfs_page_digg_image']!=''){ $diggImg=$pluginOptionsVal['csbwfs_page_digg_image'];} 
   else{$diggImg=plugins_url('images/diggit.png',__FILE__);}   
//get Yummly button image   
if($pluginOptionsVal['csbwfs_page_yum_image']!=''){ $yumImg=$pluginOptionsVal['csbwfs_page_yum_image'];} 
   else{$yumImg=plugins_url('images/yum.png',__FILE__);}   
//get VK button image   
if($pluginOptionsVal['csbwfs_page_vk_image']!=''){ $vkImg=$pluginOptionsVal['csbwfs_page_vk_image'];} 
   else{$vkImg=plugins_url('images/vk.png',__FILE__);}   
//get Buffer button image   
if($pluginOptionsVal['csbwfs_page_buf_image']!=''){ $bufImg=$pluginOptionsVal['csbwfs_page_buf_image'];} 
   else{$bufImg=plugins_url('images/buf.png',__FILE__);}   
//get print button image   
if($pluginOptionsVal['csbwfs_page_print_image']!=''){ $printImg=$pluginOptionsVal['csbwfs_page_print_image'];} 
   else{$printImg=plugins_url('images/print.png',__FILE__);}
/* 
 * New buttons 
 **/
//get gmail button image   
if(isset($pluginOptionsVal['csbwfs_page_gm_image']) && $pluginOptionsVal['csbwfs_page_gm_image']!=''){ $gmImg=$pluginOptionsVal['csbwfs_page_gm_image'];} 
   else{$gmImg=plugins_url('images/gm.png',__FILE__);}              
//get delicious button image   
if(isset($pluginOptionsVal['csbwfs_page_de_image']) && $pluginOptionsVal['csbwfs_page_de_image']!=''){ $deImg=$pluginOptionsVal['csbwfs_page_de_image'];} 
   else{$deImg=plugins_url('images/de.png',__FILE__);}              
//get blogger button image   
if(isset($pluginOptionsVal['csbwfs_page_bl_image']) && $pluginOptionsVal['csbwfs_page_bl_image']!=''){ $blImg=$pluginOptionsVal['csbwfs_page_bl_image'];} 
   else{$blImg=plugins_url('images/bl.png',__FILE__);}              
//get tumbler button image   
if(isset($pluginOptionsVal['csbwfs_page_tu_image']) && $pluginOptionsVal['csbwfs_page_tu_image']!=''){ $tuImg=$pluginOptionsVal['csbwfs_page_tu_image'];} 
else{$tuImg=plugins_url('images/tu.png',__FILE__);} 
if(isset($pluginOptionsVal['csbwfs_page_we_image']) && $pluginOptionsVal['csbwfs_page_we_image']!=''){ $weImg=$pluginOptionsVal['csbwfs_page_we_image'];} 
else{$weImg=plugins_url('images/we.png',__FILE__);} 
 $csbwfs_gm_title='Share on Gmail';
if(isset($pluginOptionsVal['csbwfs_page_gm_title']) && $pluginOptionsVal['csbwfs_page_gm_title']!='')
$csbwfs_gm_title=$pluginOptionsVal['csbwfs_page_gm_title'];
$csbwfs_de_title='Share on Delicious';
if(isset($pluginOptionsVal['csbwfs_page_de_title']) && $pluginOptionsVal['csbwfs_page_de_title']!='')
$csbwfs_de_title=$pluginOptionsVal['csbwfs_page_de_title'];
$csbwfs_tu_title='Share on Tumblr';
if(isset($pluginOptionsVal['csbwfs_page_tu_title']) && $pluginOptionsVal['csbwfs_page_tu_title']!='')
$csbwfs_tu_title=$pluginOptionsVal['csbwfs_page_tu_title'];
$csbwfs_bl_title='Share on Blogger';
if(isset($pluginOptionsVal['csbwfs_page_bl_title']) && $pluginOptionsVal['csbwfs_page_bl_title']!='')
$csbwfs_bl_title=$pluginOptionsVal['csbwfs_page_bl_title'];
/* End new buttons */  
//get email message 
if(is_page() || is_single() || is_category() || is_archive()){
		if($pluginOptionsVal['csbwfs_mailMessage']!=''){ $mailMsg=$pluginOptionsVal['csbwfs_mailMessage'];} else{
		 $mailMsg='?subject='.get_the_title().'&body='.$shareurl;}
 }else
 {
	 $mailMsg='?subject='.get_bloginfo('name').'&body='.home_url('/');
	 }
if(isset($pluginOptionsVal['csbwfs_btn_position']) && $pluginOptionsVal['csbwfs_btn_position']!=''):
$btnPosition=$pluginOptionsVal['csbwfs_btn_position'];
else:
$btnPosition='left';
endif;

if(isset($pluginOptionsVal['csbwfs_btn_text']) && $pluginOptionsVal['csbwfs_btn_text']!=''):
$btnText=$pluginOptionsVal['csbwfs_btn_text'];
else:
$btnText='';
endif;
$shareButtonContent='<div id="socialButtonOnPage" class="'.$btnPosition.'SocialButtonOnPage">';
if($btnText!=''):
$shareButtonContent.='<div class="csbwfs-sharethis-arrow"><span>'.$btnText.'</span></div>';
endif;
/** Total Sum of Share */
/*
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-fb">';
$shareButtonContent .='<div class="csbwfs-count-post">'.csbwfs_return_count_total().'</div>';
$shareButtonContent .='<button id="csbwfs-cntsum-txt">Shares</button></div></div>';
*/

/** check buttons order */
$btnsordaryy=get_option('csbwfs_btns_order');
asort($btnsordaryy);
foreach($btnsordaryy as $csbwfskey=>$csbwfskeyval)
{
/** FB */
if($csbwfskey=='fa'){
if($pluginOptionsVal['csbwfs_fpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_fb_page_url']) && $pluginOptionsVal['csbwfs_fb_page_url']!=''):
$fbshareurl=$pluginOptionsVal['csbwfs_fb_page_url'];
else:
$fbshareurl=$shareurl;
endif;

$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-fb">';
if($_SESSION['fb_count_content']!= ''):
$shareButtonContent .='<div class="csbwfs-count-post">'.$_SESSION['fb_count_content'].'</div>';
endif;
$shareButtonContent .='<a href="javascript:" onclick="window.open(\'//www.facebook.com/sharer/sharer.php?u='.$fbshareurl.'\',\'_blank\',\'width=800,height=300\')" title="'.$csbwfs_fb_title.'" > <img src="'.$fImg.'" width="30" height="30"  alt="'.$csbwfs_fb_title.'"></a></div></div>';
endif;
}
/** TW */
if($csbwfskey=='tw'){
if($pluginOptionsVal['csbwfs_tpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_tw_page_url']) && $pluginOptionsVal['csbwfs_tw_page_url']!=''):
$twshareurl=$pluginOptionsVal['csbwfs_tw_page_url'];
else:
$twshareurl=$shareurl;
endif;
$twun='';
if(isset($pluginOptionsVal['csbwfs_tu_un']) && $pluginOptionsVal['csbwfs_tu_un']!=''):
$twun='&via='.$pluginOptionsVal['csbwfs_tu_un'];
endif;
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-tw"><a href="javascript:" onclick="window.open(\'//twitter.com/share?url='.$twshareurl.'&text='.$ShareTitle.' - '.$ShareOgDesc.$twun.'\',\'_blank\',\'width=800,height=300\')" title="'.$csbwfs_tw_title.'"><img width="30" height="30" src="'.$tImg.'" alt="'.$csbwfs_tw_title.'"></a></div></div>';
endif;
}
/** GP */
if($csbwfskey=='gp'){
if($pluginOptionsVal['csbwfs_gpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_gp_page_url']) && $pluginOptionsVal['csbwfs_gp_page_url']!=''):
$gpshareurl =$pluginOptionsVal['csbwfs_gp_page_url'];
else:
$gpshareurl=$shareurl;
endif;

$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-gp">';
if($_SESSION['gp_count_content'] != ''):
$shareButtonContent .='<div class="csbwfs-count-post">'.$_SESSION['gp_count_content'].'</div>';
endif;
$shareButtonContent .='<a href="javascript:"  onclick="javascript:window.open(\'//plus.google.com/share?url='.$gpshareurl.'\',\'\',\'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=800\');return false;" title="'.$csbwfs_gp_title.'"><img width="30" height="30" src="'.$gImg.'" alt="'.$csbwfs_gp_title.'" ></a></div></div>';
endif;
}
/**  LI */
if($csbwfskey=='li'){
if($pluginOptionsVal['csbwfs_lpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_li_page_url']) && $pluginOptionsVal['csbwfs_li_page_url']!=''):
$lishareurl=$pluginOptionsVal['csbwfs_li_page_url'];
else:
$lishareurl=$shareurl;
endif;
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-li">';
if($_SESSION['li_count_content'] != ''):
$shareButtonContent .='<div class="csbwfs-count-post">'.$_SESSION['li_count_content'].'</div>';
endif;
$shareButtonContent .='<a href="javascript:" onclick="javascript:window.open(\'//www.linkedin.com/shareArticle?mini=true&url='. $lishareurl.'&title='.$ShareTitle.'&summary='.$ShareOgDesc.'\',\'\',\'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=800\');return false;" title="'.$csbwfs_li_title.'"><img width="30" height="30" src="'.$lImg.'" alt="'.$csbwfs_li_title.'" ></a></div></div>';
endif;
}
/** PIN */
if($csbwfskey=='pi'){
if($pluginOptionsVal['csbwfs_ppublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_pin_page_url']) && $pluginOptionsVal['csbwfs_pin_page_url']!=''):
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-pin">';
if($_SESSION['pin_count_content'] != ''):
$shareButtonContent .='<div class="csbwfs-count-post">'.$_SESSION['pin_count_content'].'</div>';
endif;
$shareButtonContent .='<a href="'.$pluginOptionsVal['csbwfs_pin_page_url'].'" target="_blank" title="'.$csbwfs_pin_title.'"><img width="30" height="30" src="'.$pImg.'" alt="'.$csbwfs_pin_title.'" ></a></div></div>';
else:
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-pin"><a onclick="javascript:void((function(){var e=document.createElement(\'script\');e.setAttribute(\'type\',\'text/javascript\');e.setAttribute(\'charset\',\'UTF-8\');e.setAttribute(\'src\',\'//assets.pinterest.com/js/pinmarklet.js?r=\'+Math.random()*99999999);document.body.appendChild(e)})());" href="javascript:" title="'.$csbwfs_pin_title.'"><img width="30" height="30" src="'.$pImg.'" alt="'.$csbwfs_pin_title.'"></a></div></div>';
endif;
endif;
}
/** Stumbleupon */
if($csbwfskey=='st'){
if(isset($pluginOptionsVal['csbwfs_stpublishBtn']) && $pluginOptionsVal['csbwfs_stpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_st_page_url']) && $pluginOptionsVal['csbwfs_st_page_url']!=''):
$stshareurl=$pluginOptionsVal['csbwfs_st_page_url'];
else:
$stshareurl=$shareurl;
endif;

$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-st">';
if($_SESSION['st_count_content'] != ''):
$shareButtonContent .='<div class="csbwfs-count-post">'.$_SESSION['st_count_content'].'</div>';
endif;
$shareButtonContent .='<a onclick="window.open(\'//www.stumbleupon.com/submit?url='.$stshareurl.'&amp;title='.$ShareTitle.'\',\'Stumbleupon\',\'toolbar=0,status=0,width=1000,height=800\');"  href="javascript:void(0);" title="'.$csbwfs_st_title.'"><img width="30" height="30" src="'. $stImg.'" alt="'.$csbwfs_st_title.'" ></a></div></div>';
endif;
} 
/** Reddit */
if($csbwfskey=='re'){
if(isset($pluginOptionsVal['csbwfs_republishBtn']) && $pluginOptionsVal['csbwfs_republishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_re_page_url']) && $pluginOptionsVal['csbwfs_re_page_url']!=''):
$reshareurl=$pluginOptionsVal['csbwfs_re_page_url'];
else:
$reshareurl=$shareurl;
endif;

$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-re">';
if($_SESSION['re_count_content'] != ''):
$shareButtonContent .='<div class="csbwfs-count-post">'.$_SESSION['re_count_content'].'</div>';
endif;
$shareButtonContent .='<a onclick="window.open(\'//reddit.com/submit?url='.$reshareurl.'&amp;title='.$ShareTitle.'\',\'Reddit\',\'toolbar=0,status=0,width=1000,height=800\');" href="javascript:void(0);" title="'.$csbwfs_re_title.'"><img width="30" height="30" src="'.$reImg.'" alt="'.$csbwfs_re_title.'"></a></div></div>';
endif;
}
/** Tumbler */
if($csbwfskey=='tu'){
if(isset($pluginOptionsVal['csbwfs_tupublishBtn']) && $pluginOptionsVal['csbwfs_tupublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_tu_page_url']) && $pluginOptionsVal['csbwfs_tu_page_url']!=''):
$tushareurl=$pluginOptionsVal['csbwfs_tu_page_url'];
else:
$tushareurl=$shareurl;
endif;

$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-tu">';
if($_SESSION['tu_count_content'] != ''):
$shareButtonContent .='<div class="csbwfs-count-post">'.$_SESSION['tu_count_content'].'</div>';
endif;
$shareButtonContent .='<a onclick="window.open(\'//tumblr.com/widgets/share/tool?canonicalUrl='.$tushareurl.'&amp;title='.$ShareTitle.'\',\'Tumbler\',\'toolbar=0,status=0,width=540,height=600\');" href="javascript:void(0);"  title="'.$csbwfs_tu_title.'"><img width="30" height="30" src="'.$tuImg.'" alt="'.$csbwfs_tu_title.'"></a></div></div>';
endif;
}
/** Diggit */
if($csbwfskey=='di'){
if(isset($pluginOptionsVal['csbwfs_diggpublishBtn']) && $pluginOptionsVal['csbwfs_diggpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_digg_page_url']) && $pluginOptionsVal['csbwfs_digg_page_url']!=''):
$digshareurl=$pluginOptionsVal['csbwfs_digg_page_url'];
else:
$digshareurl=$shareurl;
endif;
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-digg"><a onclick="window.open(\'//www.digg.com/submit?url='.$digshareurl.'\',\'Diggit\',\'toolbar=0,status=0,width=1000,height=800\');" href="javascript:void(0);" title="'.$csbwfs_digg_title.'"><img width="30" height="30" src="'.$diggImg.'" alt="'.$csbwfs_digg_title.'"></a></div></div>';
endif;
}
/** Yummly */
if($csbwfskey=='yu'){
if(isset($pluginOptionsVal['csbwfs_yumpublishBtn']) && $pluginOptionsVal['csbwfs_yumpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_yum_page_url']) && $pluginOptionsVal['csbwfs_yum_page_url']!=''):
$yushareurl=$pluginOptionsVal['csbwfs_yum_page_url'];
else:
$yushareurl=$shareurl;
endif;
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-yum"><a onclick="window.open(\'//www.yummly.com/urb/verify?url='.$yushareurl.'&amp;title='.$ShareTitle.'\',\'Yummly\',\'toolbar=0,status=0,width=1000,height=800\');" href="javascript:void(0);" title="'.$csbwfs_yum_title.'"><img width="30" height="30" src="'.$yumImg.'" alt="'.$csbwfs_yum_title.'"></a></div></div>';
endif;
}
/** VK */
if($csbwfskey=='vk'){
if(isset($pluginOptionsVal['csbwfs_vkpublishBtn']) && $pluginOptionsVal['csbwfs_vkpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_vk_page_url']) && $pluginOptionsVal['csbwfs_vk_page_url']!=''):
$vkshareurl=$pluginOptionsVal['csbwfs_vk_page_url'];
else:
$vkshareurl=$shareurl;
endif;
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-vk"><a onclick="window.open(\'//vk.com/share.php?url='.$vkshareurl.'&amp;title='.$ShareTitle.'\',\'Vk\',\'toolbar=0,status=0,width=1000,height=800\');" href="javascript:void(0);" title="'.$csbwfs_vk_title.'"><img width="30" height="30" src="'.$vkImg.'" alt="'.$csbwfs_vk_title.'"></a></div></div>';
endif;
}
/** Buffer */
if($csbwfskey=='bu'){
if(isset($pluginOptionsVal['csbwfs_bufpublishBtn']) && $pluginOptionsVal['csbwfs_bufpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_buf_page_url']) && $pluginOptionsVal['csbwfs_buf_page_url']!=''):
$bufshareurl=$pluginOptionsVal['csbwfs_buf_page_url'];
else:
$bufshareurl=$shareurl;
endif;
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-buf"><a onclick="window.open(\'//bufferapp.com/add?url='.$bufshareurl.'&amp;title='.$ShareTitle.'\',\'Buffer\',\'toolbar=0,status=0,width=1000,height=800\');" href="javascript:void(0);"  title="'.$csbwfs_buf_title.'"><img width="30" height="30" src="'.$bufImg.'" alt="'.$csbwfs_buf_title.'"></a></div></div>';
endif;
}
/** WhatsApp */
if($csbwfskey=='wh'){
if(isCsbwfsMobilePro()):
if(isset($pluginOptionsVal['csbwfs_whatsapppublishBtn']) && $pluginOptionsVal['csbwfs_whatsapppublishBtn']!=''):
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-whats"><a href="whatsapp://send?text='.$ShareTitle.'&nbsp;'.$ShareDesc.'&nbsp;'.$shareurl.'"  target="_blank" title="Share on WhatsApp"> <img src="'.plugins_url('images/whatsapp.png',__FILE__).'" alt="Share With Whatsapp" width="30" height="30"></a></div></div>';
endif;
endif;
}/** Skype */
if($csbwfskey=='sk'){
if(isset($pluginOptionsVal['csbwfs_skypublishBtn']) && $pluginOptionsVal['csbwfs_skyUnpublishBtn']!=''):
$csbwfsSkypeName=$pluginOptionsVal['csbwfs_skyUnpublishBtn']; 
$shareButtonContent .='<div class="csbwfs-sbutton-post skype"><div class="csbwfs-skype"><a href="skype:live:'.$csbwfsSkypeName.'?chat"  target="_blank" title="Chat on Skype"> <img src="'.plugins_url('images/skype.png',__FILE__).'" alt="Chat on Skype" width="30" height="30"></a></div></div>';
endif;
}

/** SMS */
if($csbwfskey=='sms'){
if(isCsbwfsMobilePro()):
if(isset($pluginOptionsVal['csbwfs_smspublishBtn']) && $pluginOptionsVal['csbwfs_smspublishBtn']!=''):
$shareButtonContent .='<div class="csbwfs-sbutton-post sms"><div class="csbwfs-sms"><a href="sms://&body='.$ShareTitle.'&nbsp;'.$ShareDesc.'&nbsp;'.$shareurl.'"  title="Share on SMS" > <img src="'.plugins_url('images/sms.png',__FILE__).'" alt="Share With SMS" width="30" height="30"></a></div></div>';
endif;
endif;
}
/** Telegram */
if($csbwfskey=='te'){
if(isset($pluginOptionsVal['csbwfs_tepublishBtn']) && $pluginOptionsVal['csbwfs_tepublishBtn']!=''):
if(isCsbwfsMobilePro()):
$telegramShareurl='tg://msg_url?url='.$shareurl.'&text='.$ShareTitle;
else:
$telegramShareurl='https://telegram.me/share/url?url='.$shareurl.'&text='.$ShareTitle;
endif;
$shareButtonContent .='<div class="csbwfs-sbutton-post telegram"><div class="csbwfs-te"><a href="'.$telegramShareurl.'"  title="Share on Telegram" > <img src="'.plugins_url('images/tg.png',__FILE__).'" alt="Share With Telegram" width="30" height="30"></a></div></div>';
endif;
}
/** Line */
if($csbwfskey=='line'){
if(isCsbwfsMobilePro()):
if(isset($pluginOptionsVal['csbwfs_linepublishBtn']) && $pluginOptionsVal['csbwfs_linepublishBtn']!=''):
$shareButtonContent .='<div class="csbwfs-sbutton-post line"><div class="csbwfs-line"><a href="//line.me/R/msg/text/?'.$ShareTitle.'%0D%0A'.$shareurl.'"  title="LINE it!" > <img src="'.plugins_url('images/line.png',__FILE__).'" alt="LINE it!" width="30" height="30"></a></div></div>';
endif;
endif;
}
/** YT */	
if($csbwfskey=='yt'){ 	 
if(isset($pluginOptionsVal['csbwfs_ytpublishBtn']) && $pluginOptionsVal['csbwfs_ytpublishBtn']!=''):
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-yt"><a onclick="window.open(\''.$pluginOptionsVal['csbwfs_ytPath'].'\');" href="javascript:void(0);" title="'.$csbwfs_yt_title.'"><img src="'.$ytImg.'" alt="'.$csbwfs_yt_title.'" width="30" height="30"></a></div></div>';
endif;
}
/** Instagram */
if($csbwfskey=='in'){
if(isset($pluginOptionsVal['csbwfs_instpublishBtn']) && $pluginOptionsVal['csbwfs_instpublishBtn']!=''):
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-inst"><a onclick="window.open(\''.$pluginOptionsVal['csbwfs_inst_page_url'].'\');" href="javascript:void(0);" title="'.$csbwfs_inst_title.'"><img src="'.$instImg.'" alt="'.$csbwfs_inst_title.'"  width="30" height="30"></a></div></div>';
endif; 
}
/** Google Translate */
if($csbwfskey=='gt'){
if($pluginOptionsVal['csbwfs_gtpublishBtn']!=''):
$shareButtonContent .='<div class="csbwfs-sbutton-post" ><div class="csbwfs-gt"><a href="//translate.google.com/translate?u='.$shareurl.'" target="_blank" title="'.$csbwfs_gt_title.'"> <img src="'.$gtImg.'" alt="'.$csbwfs_gt_title.'"  width="30" height="30"></a></div></div>';
endif;
}
/** Mail*/
if($csbwfskey=='ma'){
if($pluginOptionsVal['csbwfs_mpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_mail_page_url']) && $pluginOptionsVal['csbwfs_mail_page_url']!=''):
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-ml"><a href="'.$pluginOptionsVal['csbwfs_mail_page_url'].'" title="'.$csbwfs_mail_title.'"><img src="'.$mImg.'" alt="'.$csbwfs_mail_title.'"   width="30" height="30"></a></div></div>';
else:
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-ml csbwfs-lighbox"><a href="javascript:" title="'.$csbwfs_mail_title.'"><img  width="30" height="30" src="'.$mImg.'" alt="'.$csbwfs_mail_title.'" ></a></div></div>';
endif;
endif;
}
/** RSS Feed */	 
if($csbwfskey=='rs'){	 
if(isset($pluginOptionsVal['csbwfs_rsspublishBtn']) && $pluginOptionsVal['csbwfs_rsspublishBtn']!=''):
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-rss"><a onclick="window.open(\''.$pluginOptionsVal['csbwfs_rssPath'].'\');" href="javascript:void(0);" title="Browse RSS Feed"><img src="'.plugins_url('images/rss.png',__FILE__).'" alt="Browse RSS Feed" width="30" height="30"></a></div></div>';
endif;
}
/** Print*/
if($csbwfskey=='pr'){	
if($pluginOptionsVal['csbwfs_printpublishBtn']!=''):
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-print" class="csbwfs-print"><a href="javascript:" title="'.$csbwfs_print_title.'" ><img  width="30" height="30" src="'.$printImg.'" alt="'.$csbwfs_print_title.'" ></a></div></div>';
endif;
}
/** Gmail */
if($csbwfskey=='gm'){
if(isset($pluginOptionsVal['csbwfs_gmpublishBtn']) && $pluginOptionsVal['csbwfs_gmpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_gm_page_url']) && $pluginOptionsVal['csbwfs_gm_page_url']!=''):
$gmshareurl=$pluginOptionsVal['csbwfs_gm_page_url'];
else:
$gmshareurl=$shareurl;
endif;
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-gm"><a onclick="window.open(\'//mail.google.com/mail/u/0/?view=cm&amp;fs=1&amp;to='.get_option('admin_email').'&amp;su=\'+encodeURIComponent(document.title)+\'&amp;body='.$gmshareurl.'&amp;ui=2&amp;tf=1\',\'Gmail\',\'toolbar=0,status=0,width=1000,height=800\');" href="javascript:void(0);"  title="'.$csbwfs_gm_title.'"><img width="30" height="30" src="'.$gmImg.'" alt="'.$csbwfs_gm_title.'"></a></div></div>';
endif;
}
/** Blogger */
if($csbwfskey=='bl'){
if(isset($pluginOptionsVal['csbwfs_blpublishBtn']) && $pluginOptionsVal['csbwfs_blpublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_bl_page_url']) && $pluginOptionsVal['csbwfs_bl_page_url']!=''):
$blshareurl=$pluginOptionsVal['csbwfs_bl_page_url'];
else:
$blshareurl=$shareurl;
endif;
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-bl"><a onclick="window.open(\'//www.blogger.com/blog-this.g?u='.$blshareurl.'&amp;t='.$ShareTitle.'\',\'Blogger\',\'toolbar=0,status=0,width=1000,height=800\');" href="javascript:void(0);"  title="'.$csbwfs_bl_title.'"><img width="30" height="30" src="'.$blImg.'" alt="'.$csbwfs_bl_title.'"></a></div></div>';
endif;
}
/** Declias */
if($csbwfskey=='de'){
if(isset($pluginOptionsVal['csbwfs_depublishBtn']) && $pluginOptionsVal['csbwfs_depublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_de_page_url']) && $pluginOptionsVal['csbwfs_de_page_url']!=''):
$deshareurl=$pluginOptionsVal['csbwfs_de_page_url'];
else:
$deshareurl=$shareurl;
endif;
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-de"><a onclick="window.open(\'//delicious.com/save?v=5&provider=MRWEBSOLUTION&noui&jump=close&url=\'+encodeURIComponent(location.href)+\'&title=\'+encodeURIComponent(document.title), \'delicious\',\'toolbar=no,width=550,height=550\'); return false;" href="javascript:void(0);"  title="'.$csbwfs_de_title.'"><img width="30" height="30" src="'.$deImg.'" alt="'.$csbwfs_de_title.'"></a></div></div>';
endif;
}
/** Weibo */
if($csbwfskey=='we'){
if(isset($pluginOptionsVal['csbwfs_wepublishBtn']) && $pluginOptionsVal['csbwfs_wepublishBtn']!=''):
if(isset($pluginOptionsVal['csbwfs_we_page_url']) && $pluginOptionsVal['csbwfs_we_page_url']!=''):
$weshareurl=$pluginOptionsVal['csbwfs_we_page_url'];
else:
$weshareurl=$shareurl;
endif;
$shareButtonContent .='<div class="csbwfs-sbutton-post"><div class="csbwfs-we"><a onclick="window.open(\'//service.weibo.com/share/share.php?url=\'+encodeURIComponent(location.href)+\'&amp;title='.$ShareTitle.' - '.$ShareOgDesc.'&amp;pic='.$csbwfsOgimg.'\', \'Weibo\',\'toolbar=no,width=550,height=550\'); return false;" href="javascript:void(0);"  title="'.$csbwfs_we_title.'"><img width="30" height="30" src="'.$weImg.'" alt="'.$csbwfs_we_title.'"></a></div></div>';
endif;
}
}
/** Total Sum of Share */
/*
if(isset($pluginOptionsVal['csbwfs_count_sum']) && $pluginOptionsVal['csbwfs_count_sum']!=''):
$totalShareCount=csbwfs_return_count_total();
if($totalShareCount!='0'){
$shareButtonContent .='<div class="csbwfs-sbutton-post">';
$shareButtonContent .='<div class="csbwfs-count-post">'.$totalShareCount.'</div>';
$shareButtonContent .='<button id="csbwfs-page-cntsum-txt">Shares</button></div></div>';
}
endif;
*/
$shareButtonContent.='</div>';
 // Returns the content.
    global $post;
    $shareButtonContentReturn='';
	/* DEFAULT HOME */
	if((is_home() && is_front_page()) && $pluginOptionsVal['csbwfs_page_show_home']=='yes'):
	$shareButtonContentReturn=$shareButtonContent;
    endif;
	/* STATIC front page */
	if(is_front_page() && $pluginOptionsVal['csbwfs_page_show_home']=='yes'):
    $shareButtonContentReturn=$shareButtonContent;
    endif;
    /* STATIC blog */
	if(is_home() && $pluginOptionsVal['csbwfs_page_show_blog']=='yes'):
	$shareButtonContentReturn=$shareButtonContent;
    endif;
	
    if(is_single() && $pluginOptionsVal['csbwfs_page_show_post']=='yes'):
     $shareButtonContentReturn=$shareButtonContent;
      // echo 'dfff case 6';
    endif;
    
    if(is_page() && $pluginOptionsVal['csbwfs_page_show_page']=='yes'):
	if(!is_front_page()):
     $shareButtonContentReturn=$shareButtonContent;
     endif;
    endif;
     if(is_singular( 'product' ) && $pluginOptionsVal['csbwfs_page_show_page']=='yes'):
     $shareButtonContentReturn=$shareButtonContent;
      //echo 'dfff case 10';
    endif;
  
    /* custom post type */
    $postypeval=get_option('cswbfs_include_post_type');
     if($postypeval!=''):
      if(is_singular()){
      $customPostAry=explode(',',$postypeval);
      if(is_singular( $customPostAry )):
        $shareButtonContentReturn=$shareButtonContent;
       //  echo 'dfff case 11';
	   else:
	   $shareButtonContentReturn='';
	   // echo 'dfff case 12';
       endif;
      }
    endif;
    
    if(is_archive() && $pluginOptionsVal['csbwfs_page_show_archive']=='yes'):
     $shareButtonContentReturn=$shareButtonContent;
      //echo 'dfff case 14';
    endif;
    /* hide on specific pages */
    if(is_singular()){
    if(isset($pluginOptionsVal['csbwfs_page_custom_page_ids']) && $pluginOptionsVal['csbwfs_page_custom_page_ids']!=''){
    $csbwfsPageIds=explode(',',$pluginOptionsVal['csbwfs_page_custom_page_ids']);
   // print_r($csbwfsPageIds); echo $pluginOptionsVal['csbwfs_page_show_on'];
    // Show
    if(in_array($post->ID,$csbwfsPageIds) && $pluginOptionsVal['csbwfs_page_show_on']=='show'):
       $shareButtonContentReturn=$shareButtonContent;
     //echo 'dfff case 15';
    endif;
   // Hide
    if(in_array($post->ID,$csbwfsPageIds) && $pluginOptionsVal['csbwfs_page_show_on']=='hide'):
	  $shareButtonContentReturn='';
	  // echo 'dfff case 16';
       endif;
    }
   }
    if(is_404()):
     $shareButtonContentReturn='';
      //echo 'dfff case 17';
    endif;
    //return $content.$shareButtonContent;
  /** Buttons position on content */
   if(isset($pluginOptionsVal['csbwfs_btn_display']) && $pluginOptionsVal['csbwfs_btn_display']=='above')
    {
    return $shareButtonContentReturn.$content;
    }else {
		return $content.$shareButtonContentReturn;
	}
}
endif;
/* end if share button content */
/** shortcode */
//add_shortcode( 'csbwfs', 'csbfs_pro_the_content_filter' );
//add_filter('widget_text', 'do_shortcode');
//add_shortcode( 'footag', 'csbfs_pro_the_content_filter' );
/*
* Facebook OG tag
*
*/
$pluginOptionsVal=get_csbwf_pro_sidebar_options();
if(isset($pluginOptionsVal['csbwfs_og_tags_enable']) && $pluginOptionsVal['csbwfs_og_tags_enable']=='yes'){
//define action for create new og meta boxes
add_action( 'add_meta_boxes', 'csbwfs_add_meta_box' );
//Define action for save "CSBWFS" OG Meta Box fields Value
add_action( 'save_post', 'csbwfs_save_meta_box_data' );
//Add OG Meta Tag in header
add_action('wp_head','csbwfs_add_og_tag_header',5);
}
/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function csbwfs_add_meta_box() {
	$screens = array( 'post', 'page','product','event' ); //define post type
	foreach ( $screens as $screen ) {
		add_meta_box(
			'csbwfs_sectionid',
			__( 'Custom Floating Sidebar OG tag Information', 'csbwfs_textdomain' ),
			'csbwfs_meta_box_callback',
			$screen
		);
	}
}
/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
 global $csbwfs_meta_box;
 $csbwfs_prefix = 'csbwfs_og_';
    $csbwfs_meta_box = array(
    'id' => 'csbwfs-og-meta-box',
    'title' => 'Custom Floating Sidebar Pro OG tag Information',
    'page' => '',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
    array(
    'name' => 'OG Title: ',
    'desc' => '',
    'id' => $csbwfs_prefix . 'title',
    'type' => 'text',
    'std' => ''
    ),
    array(
    'name' => 'OG Description: ',
    'desc' => '',
    'id' => $csbwfs_prefix. 'description',
    'type' => 'text',
    'std' => ''
    ),
    array(
    'name' => 'OG Image: ',
    'desc' => '',
    'id' => $csbwfs_prefix. 'image_path',
    'type' => 'image',
    'std' => ''
    ),
   /* array(
    'name' => 'Canonical URL: ',
    'desc' => '',
    'id' => $csbwfs_prefix. 'canonical',
    'type' => 'text',
    'std' => ''
    )*/
    )
 );
function csbwfs_meta_box_callback( $post ) {
global $csbwfs_meta_box;
 	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'csbwfs_og_meta_box', 'csbwfs_meta_box_nonce' );
	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
   foreach ($csbwfs_meta_box['fields'] as $field) {
    $meta = get_post_meta( $post->ID, $field['id'], true );
    echo '<p>',
    '<label for="', $field['id'], '">', $field['name'], '</label>','';
    switch ($field['type']) {
    case 'text':
    echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
    break;
    case 'image':
    echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:60%" /><input type="button" id="meta-image-button" class="button" value="Choose or Upload an Image" />', '<br />', $field['desc'];
    break;
    case 'textarea':
    echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
    break;
    case 'select':
    echo '<select name="', $field['id'], '" id="', $field['id'], '" >';
    $optionVal=explode(',',$field['options']);
    foreach($optionVal as $optVal):
    if($meta==$optVal){
    $valseleted =' selected="selected"';}else {
		 $valseleted ='';
		}
    echo '<option value="', $optVal, '" ',$valseleted,' id="', $field['id'], '">', $optVal, '</option>';
    endforeach;
    echo '</select>',$field['desc'];
    break;
    echo '</p>';
}
} 
}
/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function csbwfs_save_meta_box_data( $post_id ) {
	global $csbwfs_meta_box;
	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */
	// Check if our nonce is set.
	if ( ! isset( $_POST['csbwfs_meta_box_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['csbwfs_meta_box_nonce'], 'csbwfs_og_meta_box' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}
	/* OK, it's safe for us to save the data now. */
	foreach ($csbwfs_meta_box['fields'] as $field) 
	{
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old){
		 update_post_meta($post_id, $field['id'], $new);
		} 
		elseif ('' == $new && $old) {
		delete_post_meta($post_id, $field['id'], $old);
		}
	}
	// Update the meta field in the database.
}
/** send og meta to header section */

function csbwfs_add_og_tag_header()
{
global $meta_box, $post;
echo '<!-- START CSBWFS OG Tags -->
<meta property="og:type" content="website">';
$canonicalUrl=get_permalink($post->ID);
//$canonicalcustomUrl=get_post_meta($post->ID,"csbwfs_og_canonical",true);
$lang=strtolower(get_bloginfo("language"));
//if($canonicalcustomUrl!=''){$canonicalUrl=$canonicalcustomUrl;}
if($canonicalUrl!=''){
echo '
<link rel="alternate" href="'.$canonicalUrl.'" hreflang="'.$lang.'" />';
//echo '<link rel="canonical" href="'.$canonicalUrl.'" />';
}
$ogtile=get_post_meta($post->ID,"csbwfs_og_title",true);
$csbwfs_dft_og_title=get_option('csbwfs_dft_og_title');
if($ogtile=='' && $csbwfs_dft_og_title!=''){$ogtile=$csbwfs_dft_og_title;}
elseif($ogtile=='' && $csbwfs_dft_og_title==''){$ogtile=wp_title('',FALSE,'right');}else{}
if($ogtile!=''){
echo '
<meta property="og:title" content="'.$ogtile.'">';}
$ogdescription=get_post_meta($post->ID,"csbwfs_og_description",true);
$csbwfs_dft_og_desc=get_option('csbwfs_dft_og_desc');
if($ogdescription=='' && $csbwfs_dft_og_desc!=''){$ogdescription=$csbwfs_dft_og_desc;}
if($ogdescription!=''){
echo '
<meta property="og:description" content="'.$ogdescription.'">';}
$ogimage=get_post_meta($post->ID,"csbwfs_og_image_path",true);
$csbwfs_dft_og_img=get_option('csbwfs_dft_og_img');
if($ogimage=='' && $csbwfs_dft_og_img!=''){$ogimage=$csbwfs_dft_og_img;}
if($ogimage!=''){
echo '
<meta property="og:image" content="'.$ogimage.'">';}
echo '
<!-- END CSBWFS OG Tags -->
';

} 

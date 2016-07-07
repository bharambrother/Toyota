<?php
/*
 * CSBWFS Share Count(c)
 *
 * */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$pageUrl=$shareurl; //get share url
$TotalSumCount=0;
/** GooglePlus */
function csbwfs_get_plusones($pageUrl)  {
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.rawurldecode($pageUrl).'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
$curl_results = curl_exec ($curl);
curl_close ($curl);
$json = json_decode($curl_results, true);
return isset($json[0]['result']['metadata']['globalCounts']['count'])?intval( $json[0]['result']['metadata']['globalCounts']['count'] ):0;
}
/**  Check way of share count */
if(isset($pluginOptionsVal['csbwfs_wayofcount']) && $pluginOptionsVal['csbwfs_wayofcount']!='default'){
/** Facebook */
function csbwfs_get_fb($pageUrl) { 
$ch =  curl_init('https://api.facebook.com/restserver.php?method=links.getStats&format=json&urls='.$pageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
$json = json_decode($result, true);
return isset($json[0]['total_count'])?intval($json[0]['total_count']):0;
}

/** LinkedIn */
function csbwfs_get_linkedin($pageUrl) {
$ch =  curl_init("https://www.linkedin.com/countserv/count/share?url=".$pageUrl."&format=json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
$json = json_decode($result, true);
return isset($json['count'])?intval($json['count']):0;
}
/** StumbleUpon */
function csbwfs_get_stumble($pageUrl) {
$ch =  curl_init('https://www.stumbleupon.com/services/1.01/badge.getinfo?url='.$pageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
$json = json_decode($result, true);
return isset($json['result']['views'])?intval($json['result']['views']):0;
}
/** Reddit */
function csbwfs_get_re($pageUrl){
$ch =  curl_init('https://np.reddit.com/button_info.json?url='.$pageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
$json = json_decode($result, true);
return isset($json['data']['children']['0']['data']['score'])?intval($json['data']['children']['0']['data']['score']):0;
}
/** Tumblr */
function csbwfs_get_tu($pageUrl){
$ch =  curl_init('http://api.tumblr.com/v2/share/stats?url='.$pageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
$json = json_decode($result, true);
return isset($json['response']['note_count'])?intval($json['response']['note_count']):0;
}
/** Pinterest */
function csbwfs_get_pin($pageUrl){
$ch =  curl_init('http://api.pinterest.com/v1/urls/count.json?callback=receivecount&url='.$pageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
$result_data = substr($result,13); 
$result_data = substr($result_data,0,-1);
$pinterest = json_decode($result_data);  
$pincount = $pinterest->count;
return isset($pincount)?intval($pincount):0;
}
}else
{
/** file_get_content_function */
	/** Facebook */
function csbwfs_get_fb($pageUrl) { 
$json_string = file_get_contents('https://api.facebook.com/restserver.php?method=links.getStats&format=json&urls='.$pageUrl);
$json = json_decode($json_string, true);
return isset($json[0]['total_count'])?intval($json[0]['total_count']):0;
}

/** LinkedIn */
function csbwfs_get_linkedin($pageUrl) {
$json_string =file_get_contents("https://www.linkedin.com/countserv/count/share?url=".$pageUrl."&format=json");
$json = json_decode($json_string, true);
return isset($json['count'])?intval($json['count']):0;
}
/** StumbleUpon */
function csbwfs_get_stumble($pageUrl) {
$json_string =file_get_contents('https://www.stumbleupon.com/services/1.01/badge.getinfo?url='.$pageUrl);
$json = json_decode($json_string, true);
return isset($json['result']['views'])?intval($json['result']['views']):0;
}
/** Reddit */
function csbwfs_get_re($pageUrl){
$json = json_decode(file_get_contents('https://np.reddit.com/button_info.json?url='.$pageUrl),true);
return isset($json['data']['children']['0']['data']['score'])?intval($json['data']['children']['0']['data']['score']):0;
}
/** Tumblr */
function csbwfs_get_tu($pageUrl){
$json = json_decode(file_get_contents('http://api.tumblr.com/v2/share/stats?url='.$pageUrl),true);
return isset($json['response']['note_count'])?intval($json['response']['note_count']):0;
}
/** Pinterest */
function csbwfs_get_pin($pageUrl){
$result = file_get_contents('http://api.pinterest.com/v1/urls/count.json?callback=receivecount&url='.$pageUrl);
$result_data = substr($result,13); 
$result_data = substr($result_data,0,-1);
$pinterest = json_decode($result_data);  
$pincount = $pinterest->count;
return isset($pincount)?intval($pincount):0;
}
}
/** END WAY OF SHARE COUNT */
/** Share count start */
$csbwfsTwCunt=$csbwfsFbCunt=$csbwfsStCunt=$csbwfsLiCunt=$csbwfsPiCunt=$csbwfsGpCunt=$csbwfsReCunt=$csbwfsTuCunt='';
$rediv=$stdiv=$pindiv=$lidiv=$gpdiv=$twdiv=$fbdiv=$tudiv=$csbwfsIsEnableCount='';
/* Facebbok */
function csbwfs_return_count_fb($shareurl){
if(get_option('csbwfs_share_count_fb')!=''):
$fbct=csbwfs_get_fb(urlencode($shareurl));
$csbwfsFbCunt='<div class="csbwfs-count">'.csbwfs_thousandsCurrencyFormat($fbct).'</div>';
$_SESSION['fb_count'] = $fbct;
else:
$csbwfsFbCunt='';$_SESSION['fb_count'] =0;
endif;
$_SESSION['fb_count_content'] = $csbwfsFbCunt;
return $csbwfsFbCunt;

}
/* Google Plus */
function csbwfs_return_count_gp($shareurl){
if(get_option('csbwfs_share_count_gp')!=''):
$gpct=csbwfs_get_plusones(urlencode($shareurl));
$csbwfsGpCunt='<div class="csbwfs-count">'.csbwfs_thousandsCurrencyFormat($gpct).'</div>';
$_SESSION['gp_count'] = $gpct;
else:
$_SESSION['gp_count']=0;$csbwfsGpCunt='';
endif;
$_SESSION['gp_count_content'] = $csbwfsGpCunt;
return $csbwfsGpCunt;
}
/* Linkdin */
function csbwfs_return_count_li($shareurl){
if(get_option('csbwfs_share_count_li')!=''):
$lict=csbwfs_get_linkedin(urlencode($shareurl));
$csbwfsLiCunt='<div class="csbwfs-count">'.csbwfs_thousandsCurrencyFormat($lict).'</div>';
$_SESSION['li_count'] = $lict;
else:
$_SESSION['li_count'] =0;$csbwfsLiCunt='';
endif;
$_SESSION['li_count_content'] = $csbwfsLiCunt;
return $csbwfsLiCunt;
}
/* Pinterest */
function csbwfs_return_count_pi($shareurl){
if(get_option('csbwfs_share_count_pin')!=''):
$pict=csbwfs_get_pin(urlencode($shareurl));
$csbwfsPiCunt='<div class="csbwfs-count">'.csbwfs_thousandsCurrencyFormat($pict).'</div>';
$_SESSION['pin_count'] = $pict;
else:
$csbwfsPiCunt='';$_SESSION['pin_count'] =0;
endif;
$_SESSION['pin_count_content'] = $csbwfsPiCunt;
return $csbwfsPiCunt;
}
/* Stumbleupon */
function csbwfs_return_count_st($shareurl){
if(get_option('csbwfs_share_count_st')!=''):
$stct=csbwfs_get_stumble(urlencode($shareurl));
$csbwfsStCunt='<div class="csbwfs-count">'.csbwfs_thousandsCurrencyFormat($stct).'</div>';
$_SESSION['st_count'] = $stct;
else:
$csbwfsStCunt='';$_SESSION['st_count'] =0;
endif;
$_SESSION['st_count_content'] = $csbwfsStCunt;
return $csbwfsStCunt;
}
/* Reddit */
function csbwfs_return_count_re($shareurl){
if(get_option('csbwfs_share_count_re')!=''):
$rect=csbwfs_get_re(urlencode($shareurl));
$csbwfsReCunt='<div class="csbwfs-count">'.csbwfs_thousandsCurrencyFormat($rect).'</div>';
$_SESSION['re_count'] = $rect;
else:
$csbwfsReCunt='';$_SESSION['re_count'] =0;
endif;
$_SESSION['re_count_content'] = $csbwfsReCunt;
return $csbwfsReCunt;
}
/* Tumblr */
function csbwfs_return_count_tu($shareurl){
if(get_option('csbwfs_share_count_tu')!=''):
$tuct=csbwfs_get_tu(urlencode($shareurl));
$csbwfsTuCunt='<div class="csbwfs-count">'.csbwfs_thousandsCurrencyFormat($tuct).'</div>';
$_SESSION['tu_count'] = $tuct;
else:
$csbwfsTuCunt='';$_SESSION['tu_count'] =0;
endif;
$_SESSION['tu_count_content'] = $csbwfsTuCunt;
return $csbwfsTuCunt;
}
/* Total Count*/
function csbwfs_return_count_total(){
/** pinit */
if(!isset($_SESSION['fb_count']) || get_option('csbwfs_fpublishBtn')!='yes'){$fb_count=0;}else{$fb_count=$_SESSION['fb_count'];}
if(!isset($_SESSION['pin_count']) || get_option('csbwfs_ppublishBtn')!='yes'){$pin_count=0;}else{$pin_count=$_SESSION['pin_count'];}
if(!isset($_SESSION['gp_count']) || get_option('csbwfs_gpublishBtn')!='yes'){$gp_count=0;}else{$gp_count=$_SESSION['gp_count'];}
if(!isset($_SESSION['li_count']) || get_option('csbwfs_lpublishBtn')!='yes'){$li_count=0;}else{$li_count=$_SESSION['li_count'];}
if(!isset($_SESSION['tu_count']) || get_option('csbwfs_tupublishBtn')!='yes'){$tu_count=0;}else{$tu_count=$_SESSION['tu_count'];}
if(!isset($_SESSION['re_count']) || get_option('csbwfs_republishBtn')!='yes'){$re_count=0;}else{$re_count=$_SESSION['re_count'];}
if(!isset($_SESSION['st_count']) || get_option('csbwfs_stpublishBtn')!='yes'){$st_count=0;}else{$st_count=$_SESSION['st_count'];}
$csbwfsTotalCunt=intval($fb_count)+intval($gp_count)+intval($li_count)+intval($pin_count)+intval($tu_count)+intval($re_count)+intval($st_count);
return csbwfs_thousandsCurrencyFormat($csbwfsTotalCunt);
}
/** Change numberformat of social share count */
function csbwfs_thousandsCurrencyFormat($num) {
  $x = round($num);
  if(strlen($num) < 4){return $num;}
  $x_number_format = number_format($x);
  $x_array = explode(',', $x_number_format);
  $x_parts = array('k', 'm', 'b', 't');
  $x_count_parts = count($x_array) - 1;
  $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
  $x_display .= $x_parts[$x_count_parts - 1];
  return $x_display;
}
?>

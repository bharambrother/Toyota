/* Custom Share Buttons With Floting Sidebar admin js*/
jQuery(document).ready(function(){
	jQuery(".csbwfs-tab").hide();
	jQuery("#div-csbwfs-general").show();
	jQuery(".csbwf-tab-links").click(function(){
	var divid=jQuery(this).attr("id");
	jQuery(".csbwf-tab-links").removeClass("active");
	jQuery(".csbwfs-tab").hide();
	jQuery("#"+divid).addClass("active");
	jQuery("#div-"+divid).show();
	});
	jQuery("#publish5").click(function(){
	if(jQuery("#publish5").prop("checked"))
	{jQuery("#mailmsg").show();}else{jQuery("#mailmsg").hide();} 
	});
	jQuery("#ytBtns").click(function(){
	if(jQuery("#ytBtns").prop("checked"))
	{jQuery("#ytpath").fadeIn();}else{jQuery("#ytpath").fadeOut();} 
	});
	jQuery("#csbwfs_instpublishBtn").click(function(){
	if(jQuery(this).prop("checked"))
	{jQuery("#csbwfs_inst").fadeIn();}else{jQuery("#csbwfs_inst").fadeOut();} 
	});
	/* add image upload image button */
	jQuery(".cswbfsUploadBtn").click(function() {
	var tdbuttonid = jQuery(this).parent("td").attr("id");
	//alert(tdbuttonid);
	inputfieldId = jQuery("#"+tdbuttonid+" .inputButtonid").attr("id");
	//alert(inputfieldId);
	formfield = jQuery("#"+inputfieldId).attr("name");
	tb_show( "", "media-upload.php?type=image&amp;TB_iframe=true" );
	return false;
	});
	window.send_to_editor = function(html) {
	imgurl = jQuery(html).attr('src');
	jQuery("#"+inputfieldId).val(imgurl);
	tb_remove();
   }
   /** reset share buttons settings */
   jQuery('#div-csbwfs-share-buttons #csbwfs_resetpage').click(function(){
	   jQuery('#div-csbwfs-share-buttons .inputButtonid').val('');
	   jQuery('#div-csbwfs-share-buttons .csbwfs_page_title').val('');
	   })
/** reset floating sidebar settings  */	   
   jQuery('#div-csbwfs-sidebar #csbwfs_reset').click(function(){
	   jQuery('#div-csbwfs-sidebar .inputButtonid').val('');
	   jQuery('#div-csbwfs-sidebar .csbwfs_title').val('');
	   })
/** selecte content of selected area */
/*
var highlighted;
jQuery(document).on('mouseup', function(e){     
   if (window.getSelection) {
      highlighted  = window.getSelection();
   } else if (document.selection) {
      highlighted = document.selection.createRange().text;
   }        
   var selectedText = highlighted.toString() !=='' && highlighted;
  
   alert(selectedText); // test
});
jQuery(document).on('dblclick', function(e){     
   if (window.getSelection) {
      highlighted  = window.getSelection();
   } else if (document.selection) {
      highlighted = document.selection.createRange().text;
   }        
   var selectedText = highlighted.toString() !=='' && highlighted;
  
   alert(selectedText); // test
});
*/ 

   });
(function( ) {
 
    // Add Color Picker to all inputs that have 'color-field' class
    jQuery(function() {
        jQuery('.color-field').wpColorPicker();
    });
     
})( jQuery );

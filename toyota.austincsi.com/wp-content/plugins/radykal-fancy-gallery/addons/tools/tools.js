
//-----------Document ready----------

jQuery(document).ready(function($) {

	$ = jQuery.noConflict();
	
	$('#fg-export-all').change(function() {
		$('#fg-export-galleries').find('input').prop('checked', $(this).is(':checked'));
	});
	
	$('#fg-export-galleries').find('input').change(function() {
		$('#fg-export-all').prop('checked', $('#fg-export-galleries').find('input:checked').length == $('#fg-export-galleries').find('input').length);
	});
	
	$('#export_fg_tables').click(function() {
		if($('#fg-export-galleries').find('input:checked').length == 0) {
			alert('No galleries selected, please select one or more galleries!');
			return false;
		}
	});
	
	
});
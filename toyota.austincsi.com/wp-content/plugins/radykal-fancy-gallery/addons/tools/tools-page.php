<div class="wrap">

	<div class="icon32" id="icon-tools"><br/></div>
	<h2><?php _e('Tools', 'radykal'); ?></h2>
	<h3><?php _e('Export/Import instructions', 'radykal'); ?></h3>
	<p><?php _e('You can only export the database tables and options. You have to copy the directory with the images manually into the new directory.', 'radykal'); ?></p>
	<ol>
		<li><?php printf(__('Copy the <code>%s</code> directory to the new <code>wp-content</code> directory.', 'radykal'), $this->fg->content_dir); ?></li>
		<li><?php _e('When using media files from the media library, be sure to export and import the media library as well.', 'radykal'); ?></li>
	</ol>
	<br />
	<!-- Export form -->
  	<h3><?php _e('Export', 'radykal'); ?></h3>
	<form method="post">
		<?php wp_nonce_field('fg-export-tables','fg_nonce') ;?>
		<label><input type="checkbox" id="fg-export-all" /> <strong><?php _e('Export all galleries', 'radykal'); ?></strong></label>
		<ul id="fg-export-galleries">
		<?php foreach($galleries as $gallery) {
			echo '<li><label><input type="checkbox" name="export_gallery[]" value="'.$gallery->ID.'" /> '.$gallery->title.'</label></li>';
		}
			
		?>
		</ul>
		<?php 
			if( current_user_can('manage_options') )
				submit_button( __('Download Export file', 'radykal'), 'fg-button fg-primary-button', 'export_fg_tables' ); ?>
	</form>
	<br />
	<!-- Import form -->
  	<h3><?php _e('Import', 'radykal'); ?></h3>
	<form method="post" enctype="multipart/form-data">
		<?php wp_nonce_field('fg-import-tables','fg_nonce') ;?>
		<input type="file" name="fg_import_xml" />
		<?php 
			if( current_user_can('manage_options') )
				submit_button( __('Import XML file', 'radykal'), 'fg-button fg-primary-button', 'import_fg_tables' ); ?>
	</form>
  
</div>
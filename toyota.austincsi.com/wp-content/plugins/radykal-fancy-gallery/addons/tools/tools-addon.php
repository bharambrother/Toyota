<?php

/*
Addon Name: Tools
Plugin URI: http://codecanyon.net/item/fancy-gallery-wordpress-plugin/400535
Version: 1.0.1
Author: Rafael Dery
Author URI: http://codecanyon.net/user/radykal
*/

if(!class_exists('FancyGalleryTools')) {

	class FancyGalleryTools {

		private $fg;

		public function __construct() {

			$this->fg = new FancyGallery();

			add_action( 'admin_init', array( &$this,'init_admin' ) );
			add_action( 'admin_menu', array( &$this,'add_menu_pages' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_admin_styles_scripts') );
			add_action( 'admin_notices',array( &$this, 'admin_notices' ) );

		}

		public function init_admin() {

			//create export xml
			if( isset($_POST['export_fg_tables']) && check_admin_referer('fg-export-tables','fg_nonce') ) {
				$this->export();
			}

			//import xml file
			if( isset($_POST['import_fg_tables']) && check_admin_referer('fg-import-tables','fg_nonce') ) {
				// Sanity check
			    if ( empty( $_FILES["fg_import_xml"] ) )
			        wp_die( 'No file found' );

			    $file = $_FILES["fg_import_xml"];

			    if ( $file["type"] != "text/xml" )
			        wp_die( sprintf( __( "There was an error importing the xml file. File type detected: '%s'. 'text/xml' expected", 'radykal' ), $file['type'] ) );

			    if ( $file["size"] > 2097152 ) {
			        $size = size_format( $file['size'], 2 );
			        wp_die( sprintf( __( 'File size too large (%s). Maximum 2MB', 'radykal' ), $size ) );
			    }

			    if( $file["error"] > 0 )
			        wp_die( sprintf( __( "Error encountered: %d", 'radykal' ), $file["error"] ) );

			    $imported = $this->import( $file['tmp_name'] );
			    wp_redirect( add_query_arg( 'imported_fg', $imported ) );
			    exit();
			}

		}

		public function add_menu_pages() {

			add_submenu_page( 'fancy-gallery', __('Tools'), __('Tools'),  FancyGallery::CAPABILITY, 'fancy-gallery-tools', array($this, 'tools_admin_page') );

		}

		public function enqueue_admin_styles_scripts( $hook ) {

			if($hook == 'fancy-gallery_page_fancy-gallery-tools') {
				wp_enqueue_script( 'fg-tools', plugins_url('/tools.js', __FILE__) );
			}
		}

		public function tools_admin_page() {

			global $wpdb;
			$galleries = $wpdb->get_results("SELECT * FROM {$this->fg->gallery_table_name} ORDER BY ID ASC");

			require_once(dirname(__FILE__).'/tools-page.php');
		}

		public function admin_notices() {

			$imported = $_GET['imported_fg'];
			if ( $imported ) {
				echo '<div class="updated"><p>'.$imported['galleries'] .' '. __('Galleries imported!').'</p><p>'.$imported['albums'] .' '. __('Albums imported!').'</p><p>'.$imported['medias'] .' '. __('Media imported!').'</p></div>';
			}

		}

		private function export() {

			global $wpdb;

			$gallery_ids = implode(',', $_POST['export_gallery']);

			$galleries = $wpdb->get_results("SELECT * FROM {$this->fg->gallery_table_name} WHERE ID IN ($gallery_ids) ORDER BY ID ASC");
			$albums = $wpdb->get_results("SELECT * FROM {$this->fg->album_table_name} WHERE gallery_ID IN ($gallery_ids) ORDER BY ID ASC");
			$album_ids = array();
			foreach($albums as $album) {
				array_push($album_ids, $album->ID);
			}
			$album_ids = implode(',', $album_ids);
			$medias = $wpdb->get_results("SELECT * FROM {$this->fg->images_table_name} WHERE album_ID IN ($album_ids) ORDER BY ID ASC");


			/* Create a file name */
			$sitename = sanitize_key( get_bloginfo( 'name' ) );
		    if ( ! empty($sitename) ) $sitename .= '.';
		    $filename = $sitename . 'fancy-gallery.' . date( 'Y-m-d' ) . '.xml';

		    /* Print header */
		    header( 'Content-Description: File Transfer' );
		    header( 'Content-Disposition: attachment; filename=' . $filename );
		    header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );

		    echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . "\" ?>\n";

		      /* Print comments */
		    echo "<!-- This is a export of the Fancy Gallery plugin -->\n";
			echo "<fancygallery>\n";

			if( $galleries ) {

echo '<galleries>
';
foreach($galleries as $gallery) { ?>
	<gallery>
		<id><?php echo absint($gallery->ID); ?></id>
		<title><?php echo $gallery->title; ?></title>
		<options><?php echo $this->wrap_cdata($gallery->options); ?></options>
	</gallery>
<?php }
echo '</galleries>';

			}

			if( $albums ) {

echo '<albums>
';
foreach($albums as $album) { ?>
	<album>
		<id><?php echo absint($album->ID); ?></id>
		<title><?php echo $album->title; ?></title>
		<thumbnail><?php echo $album->thumbnail; ?></thumbnail>
		<sort><?php echo absint($album->sort); ?></sort>
		<gallery_id><?php echo absint($album->gallery_id); ?></gallery_id>
	</album>
<?php }
echo '</albums>';

			}

			if( $medias ) {

echo '<medias>
';
foreach($medias as $media) { ?>
	<media>
		<id><?php echo absint($media->ID); ?></id>
		<file><?php echo $media->file; ?></file>
		<thumbnail><?php echo $media->thumbnail; ?></thumbnail>
		<title><?php echo $media->title; ?></title>
		<description><?php echo $this->wrap_cdata($media->description); ?></description>
		<sort><?php echo absint($media->sort); ?></sort>
		<album_id><?php echo absint($media->album_id); ?></album_id>
	</media>
<?php }
echo '</medias>';

			}

			echo "</fancygallery>";
			die();
		}

		private function import( $file ) {

			global $wpdb;

			$xml = simplexml_load_file( $file );

			var_dump($xml);

			//check if its a valid xml file
			if ( ! $xml )
		        return false;

		    $galleries_successful_counter = 0;
		    $albums_successful_counter = 0;
		    $medias_successful_counter = 0;

			$wpdb->query("SET FOREIGN_KEY_CHECKS=0;");
			$wpdb->query("TRUNCATE TABLE {$this->fg->images_table_name}");
			$wpdb->query("TRUNCATE TABLE {$this->fg->album_table_name}");
			$wpdb->query("TRUNCATE TABLE {$this->fg->gallery_table_name}");

		    //import playlists
			$galleries = $xml->xpath('/fancygallery/galleries/gallery');
			if( $galleries ) {
				foreach($galleries as $gallery) {
					$gallery_title = (string) $gallery->title;
					$successful = false;
					/*if ( $wpdb->get_row("SELECT title FROM {$fg->gallery_table_name} WHERE title='$gallery_title' LIMIT 1") == null ) {

					}*/
					$successful = $wpdb->insert(
						$this->fg->gallery_table_name,
						array( 'ID' => $gallery->ID, 'title' => $gallery_title, 'options' => $gallery->options ),
						array( '%d', '%s', '%s')
					);
					if($successful)
						$galleries_successful_counter++;
				}
			}

			//import albums
			$albums = $xml->xpath('/fancygallery/albums/album');
			if( $albums ) {
				foreach($albums as $album) {
					$album_title = (string) $album->title;
					$album_thumbnail = (string) $album->thumbnail;
					$gallery_id = (int) $album->gallery_id;
					$successful = false;
					/*if ( $wpdb->get_row("SELECT title FROM {$fg->album_table_name} WHERE title='$album_title' AND gallery_id=$gallery_id LIMIT 1") == null ) {

					}*/
					$successful = $wpdb->insert(
						$this->fg->album_table_name,
						array( 'ID' => (int) $album->ID, 'gallery_id' => $gallery_id, 'title' => $album_title, 'thumbnail' => $album_thumbnail, 'sort' => (int) $album->sort ),
						array( '%d', '%d', '%s', '%s', '%d')
					);
					if($successful)
						$albums_successful_counter++;
				}
			}

			//import medias
			$medias = $xml->xpath('/fancygallery/medias/media');
			if( $medias ) {
				foreach($medias as $media) {
					$successful = false;
					/*if ( $wpdb->get_row("SELECT title FROM {$fg->album_table_name} WHERE title='$album_title' AND gallery_id=$gallery_id LIMIT 1") == null ) {

					}*/
					$successful = $wpdb->insert(
						$this->fg->images_table_name,
						array( 'ID' => (int) $media->ID, 'album_id' => $media->album_id, 'file' => $media->file, 'thumbnail' => (string) $media->thumbnail, 'title' => (string) $media->title, 'description' => (string) $media->description, 'sort' => (int) $media->sort ),
						array( '%d', '%d', '%s', '%s', '%s', '%s', '%d')
					);
					if($successful)
						$medias_successful_counter++;
				}
			}

			$wpdb->query("SET FOREIGN_KEY_CHECKS=1;");

			return array('galleries' => $galleries_successful_counter, 'albums' => $albums_successful_counter, 'medias' => $medias_successful_counter);

		}

		private function wrap_cdata( $string ) {
		    if ( seems_utf8( $string ) == false )
		        $string = utf8_encode( $string );

		    return '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', $string ) . ']]>';
		}

	}
}

//init Fancy Gallery Tools
if(class_exists('FancyGalleryTools')) {
	$fg_tools = new FancyGalleryTools();
}

?>
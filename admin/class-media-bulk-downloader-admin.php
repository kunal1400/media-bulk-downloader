<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/kunal1400/media-bulk-downloader
 * @since      1.0.0
 *
 * @package    Media_Bulk_Downloader
 * @subpackage Media_Bulk_Downloader/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Media_Bulk_Downloader
 * @subpackage Media_Bulk_Downloader/admin
 * @author     Kunal Malviya <lucky.kunalmalviya@gmail.com>
 */
class Media_Bulk_Downloader_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Media_Bulk_Downloader_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Media_Bulk_Downloader_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/media-bulk-downloader-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Media_Bulk_Downloader_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Media_Bulk_Downloader_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/media-bulk-downloader-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	 
	public function add_plugin_admin_menu() {
	
	    /*
	     * Add a settings page for this plugin to the Settings menu.
	     *
	     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
	     *
	     *        Administration Menus: http://codex.wordpress.org/Administration_Menus
	     *
	     */
	    // add_media_page( 'Media Selector', 'Media Selector', 'manage_options', $this->plugin_name.'-selector', array($this, 'display_plugin_setup_page') );

	    add_media_page( 'Media Bulk Downloader Archives', 'All Archives', 'manage_options', $this->plugin_name.'-downloader', array($this, 'display_plugin_download_page') );
	}
		
	
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	 
	public function display_plugin_setup_page() {
	    include_once( 'partials/wp-bilmar-admin-display.php' );
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	 
	public function display_plugin_download_page() {
	    include_once( 'partials/wp-bilmar-admin-download.php' );
	}

	public function add_files_to_archive() {
		$zipfile = $_POST["zipfile"];
		$attachmentIds = $_POST["attachment_ids"];

		if ( $zipfile && is_array($attachmentIds) ) {
			$zip = new ZipArchive();
			$zip->open( $zipfile );
			foreach($attachmentIds as $id) {				
				$file = removeBackSlashes( get_attached_file( $id ) );
				$zip->addFile( $file, basename($file) );
			}
			$zip->deleteName('test/');
			$zip->close();
			echo $this->format_size( filesize($zipfile) );
		} else {
			echo "Either zipfile or attachmentIds array is empty";
		}
		die;
	}

	public function format_size( $size ) {
		$sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
		if ($size == 0) { return('n/a'); } else {
		return (round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]); }
	}

	/**
	* This is calling when we delete archive file and then redirect
	*
	* @since    1.0.0
	*/
	public function msdb_init_actions() {        
		if ( !empty($_GET['deletefile']) ) {
			$fileToDelete = removeBackSlashes( BILMAR_ABSOLUTE_FILE_PATH ).'archives/'.$_GET['deletefile'];
			unlink($fileToDelete);
			wp_redirect( '?page=media-bulk-downloader-downloader' );
			exit;
		}
	 }

	/**
	* Add Add to archive action link to the plugins page.
	*
	* @since    1.0.0
	*/	 
	public function add_upload_actions( $actions ) {		
		$actions['add_to_archive'] = "Add To Archive";		
		return $actions;
	}

	public function manage_upload_actions(  $redirect_to, $doaction, $post_ids ) {

		if ( $doaction === 'add_to_archive' && is_array($post_ids) && count($post_ids) > 0 ) {
			/**
			* Creating the ziparchive class
			**/
			$zip = new ZipArchive();
			$date = date("m-d-y-h-i-s");
			$filename = dirname( plugin_dir_path(__FILE__) )."/archives/".$date.".zip";
			$filePath = removeBackSlashes($filename);

			// Deleting the file if already exists
			if( file_exists($filePath) ) {
				unlink($filePath);
			}

			// Creating the file
			if ( $zip->open($filePath, ZipArchive::CREATE) !== TRUE ) {
			    exit("cannot open <$filename>\n");
			}

			foreach($post_ids as $file){
				$attachedFilepath = removeBackSlashes( get_attached_file( $file ) );					
				try {
					$zip->addFile( $attachedFilepath, basename( $attachedFilepath ) );
				} catch (Exception $err) {
					println("ERROR!! Could not add ".$file."!\t".$err->getMessage());
				}
			}

			$zip->close();

			return $redirect_to;

			add_flash_notice( __("My notice message, this is a warning and is dismissible"), "warning", true );
		}
		else {
			return $redirect_to;
		}
	}

	public function media_list_custom_columns( $columns ) {
		$columns["filesize"] = "File Size";
    	return $columns;
	}

	public function media_list_custom_column_cell( $colname, $cptid ) {
		if ( 'filesize' != $colname || !wp_attachment_is_image( $cptid ) ) {
			return;
		}
		
		if ( $colname == 'filesize') {
			$filesize = filesize( get_attached_file( $cptid ) );
			$filesize = size_format($filesize, 2);
			echo $filesize;
		} 
		else {
			return;
		}
	}
}

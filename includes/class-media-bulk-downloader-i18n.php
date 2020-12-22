<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/kunal1400/media-bulk-downloader
 * @since      1.0.0
 *
 * @package    Media_Bulk_Downloader
 * @subpackage Media_Bulk_Downloader/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Media_Bulk_Downloader
 * @subpackage Media_Bulk_Downloader/includes
 * @author     Kunal Malviya <lucky.kunalmalviya@gmail.com>
 */
class Media_Bulk_Downloader_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'media-bulk-downloader',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

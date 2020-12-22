<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/kunal1400/media-bulk-downloader
 * @since             1.0.0
 * @package           Media_Bulk_Downloader
 *
 * @wordpress-plugin
 * Plugin Name:       Media Bulk Downloader
 * Plugin URI:        https://sites.google.com/view/media-bulk-downloader
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Kunal Malviya
 * Author URI:        https://github.com/kunal1400/media-bulk-downloader
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       media-bulk-downloader
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MEDIA_BULK_DOWNLOADER_VERSION', '1.0.0' );

define( 'BILMAR_ABSOLUTE_FILE_PATH', plugin_dir_path(__FILE__) );

function removeBackSlashes( $str ) {
	return str_replace('\\', '/', $str);
}

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-media-bulk-downloader-activator.php
 */
function activate_media_bulk_downloader() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-media-bulk-downloader-activator.php';
	Media_Bulk_Downloader_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-media-bulk-downloader-deactivator.php
 */
function deactivate_media_bulk_downloader() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-media-bulk-downloader-deactivator.php';
	Media_Bulk_Downloader_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_media_bulk_downloader' );
register_deactivation_hook( __FILE__, 'deactivate_media_bulk_downloader' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-media-bulk-downloader.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_media_bulk_downloader() {

	$plugin = new Media_Bulk_Downloader();
	$plugin->run();

}
run_media_bulk_downloader();

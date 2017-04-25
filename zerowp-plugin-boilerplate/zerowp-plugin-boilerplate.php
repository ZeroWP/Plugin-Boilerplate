<?php
/* 
 * Plugin Name: ZeroWP Plugin Boilerplate
 * Plugin URI:  http://zerowp.com/plugin-boilerplate
 * Description: A short description of this plugin.
 * Author:      ZeroWP Team
 * Author URI:  http://zerowp.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: zerowp-plugin-boilerplate
 * Domain Path: /languages
 *
 * Version:     1.0
 * 
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* Define the current version of this plugin. Must be the version number  
 * from plugin header(of this file).
-----------------------------------------------------------------------------*/
define( 'ZPB_VERSION', '1.0' );
define( 'ZPB_PLUGIN_FILE', __FILE__ );
define( 'ZPB_PLUGIN_BASENAME', plugin_basename( ZPB_PLUGIN_FILE ) );

define( 'ZPB_PATH', plugin_dir_path( __FILE__ ) );
define( 'ZPB_URL', plugin_dir_url( __FILE__ ) );
define( 'ZPB_URI', ZPB_URL ); // Alias

define( 'ZPB_MIN_PHP_VERSION', '5.3' );

/* Check if current environment meets the minimum PHP version
------------------------------------------------------------------*/
if ( version_compare( PHP_VERSION, ZPB_MIN_PHP_VERSION, '<' ) ) {

	// The version of installed PHP is lower. 
	// Create a page in admin area and notice the user about this.
	require_once ZPB_PATH . 'php-warning.php';
	new ZPB_PHP_Warning;

}
else{

	// All is OK. Execute the plugin
	require_once ZPB_PATH . 'plugin.php';

}
<?php
/* 
 * Plugin Name: _PLUGIN_NAME_
 * Plugin URI:  _PLUGIN_URI_
 * Description: _DESCRIPTION_
 * Author:      _AUTHOR_
 * Author URI:  _AUTHOR_URI_
 * License:     _LICENSE_
 * License URI: _LICENSE_URI_
 * Text Domain: _TEXT_DOMAIN_
 * Domain Path: /languages
 *
 * Version:     _VERSION_
 * 
 */

/* No direct access allowed!
---------------------------------*/
if ( ! defined( 'ABSPATH' ) ) exit;

/* Plugin configuration
----------------------------*/
function zpb_config( $key = false ){
	$settings = apply_filters( 'zpb:config_args', array(
		
		// Plugin data
		'version'          => '_VERSION_',
		'min_php_version'  => '_MIN_PHP_VERSION_',
		
		// The list of required plugins. 'slug' => array 'name and uri'
		'required_plugins' => array(
			// 'test' => array(
			// 	'plugin_name' => 'Test',
			// 	'plugin_uri' => 'http://example.com/'
			// ),
			// 'another-test' => array(
			// 	'plugin_name' => 'Another Test',
			// ),
		),

		// The priority in plugins loaded. Only if has required plugins
		'priority'         => 10,

		// Main action. You may need to change it if is an extension for another plugin.
		'action_name'      => 'init',

		// Plugin branding
		'plugin_name'      => __( '_PLUGIN_NAME_', '_TEXT_DOMAIN_' ),
		'id'               => '_TEXT_DOMAIN_',
		'namespace'        => '_NAMESPACE_',
		'uppercase_prefix' => 'ZPB',
		'lowercase_prefix' => 'zpb',
		
		// Access to plugin directory
		'file'             => __FILE__,
		'lang_path'        => plugin_dir_path( __FILE__ ) . 'languages',
		'basename'         => plugin_basename( __FILE__ ),
		'path'             => plugin_dir_path( __FILE__ ),
		'url'              => plugin_dir_url( __FILE__ ),
		'uri'              => plugin_dir_url( __FILE__ ),//Alias

	));

	// Make sure that PHP version is set to 5.3+
	if( version_compare( $settings[ 'min_php_version' ], '5.3', '<' ) ){
		$settings[ 'min_php_version' ] = '5.3';
	}

	// Get the value by key
	if( !empty($key) ){
		if( array_key_exists($key, $settings) ){
			return $settings[ $key ];
		}
		else{
			return false;
		}
	}

	// Get settings
	else{
		return $settings;
	}
}

/* Define the current version of this plugin.
-----------------------------------------------------------------------------*/
define( 'ZPB_VERSION',         zpb_config( 'version' ) );
 
/* Plugin constants
------------------------*/
define( 'ZPB_PLUGIN_FILE',     zpb_config( 'file' ) );
define( 'ZPB_PLUGIN_BASENAME', zpb_config( 'basename' ) );

define( 'ZPB_PATH',            zpb_config( 'path' ) );
define( 'ZPB_URL',             zpb_config( 'url' ) );
define( 'ZPB_URI',             zpb_config( 'url' ) ); // Alias

/* Minimum PHP version required
------------------------------------*/
define( 'ZPB_MIN_PHP_VERSION', zpb_config( 'min_php_version' ) );

/* Plugin Init
----------------------*/
final class ZPB_Plugin_Init{

	public function __construct(){
		
		$required_plugins = zpb_config( 'required_plugins' );
		$missed_plugins   = $this->missedPlugins();

		/* The installed PHP version is lower than required.
		---------------------------------------------------------*/
		if ( version_compare( PHP_VERSION, ZPB_MIN_PHP_VERSION, '<' ) ) {

			require_once ZPB_PATH . 'warnings/php-warning.php';
			new ZPB_PHP_Warning;

		}

		/* Required plugins are not installed/activated
		----------------------------------------------------*/
		elseif( !empty( $required_plugins ) && !empty( $missed_plugins ) ){

			require_once ZPB_PATH . 'warnings/noplugin-warning.php';
			new ZPB_NoPlugin_Warning( $missed_plugins );

		}

		/* We require some plugins and all of them are activated
		-------------------------------------------------------------*/
		elseif( !empty( $required_plugins ) && empty( $missed_plugins ) ){
			
			add_action( 
				'plugins_loaded', 
				array( $this, 'getSource' ), 
				zpb_config( 'priority' ) 
			);

		}

		/* We don't require any plugins. Include the source directly
		----------------------------------------------------------------*/
		else{

			$this->getSource();

		}

	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Get plugin source
	 *
	 * @return void 
	 */
	public function getSource(){
		require_once ZPB_PATH . 'plugin.php';
		
		$components = glob( ZPB_PATH .'components/*', GLOB_ONLYDIR );
		foreach ($components as $component_path) {
			require_once trailingslashit( $component_path ) .'component.php';
		}
	
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Missed plugins
	 *
	 * Get an array of missed plugins
	 *
	 * @return array 
	 */
	public function missedPlugins(){
		$required = zpb_config( 'required_plugins' );
		$active   = $this->activePlugins();
		$diff     = array_diff_key( $required, $active );

		return $diff;
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Active plugins
	 *
	 * Get an array of active plugins
	 *
	 * @return array 
	 */
	public function activePlugins(){
		$active = get_option('active_plugins');
		$slugs  = array();

		if( !empty($active) ){
			$slugs = array_flip( array_map( array( $this, '_filterPlugins' ), (array) $active ) );
		}

		return $slugs;
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Filter plugins callback
	 *
	 * @return string 
	 */
	protected function _filterPlugins( $value ){
		$plugin = explode( '/', $value );
		return $plugin[0];
	}

}

new ZPB_Plugin_Init;
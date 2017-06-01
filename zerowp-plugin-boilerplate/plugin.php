<?php 
final class ZPB_Plugin{

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $version;

	/**
	 * This is the only instance of this class.
	 *
	 * @var string
	 */
	protected static $_instance = null;
	
	//------------------------------------//--------------------------------------//
	
	/**
	 * Plugin instance
	 *
	 * Makes sure that just one instance is allowed.
	 *
	 * @return object 
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Cloning is forbidden.
	 *
	 * @return void 
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', '_TEXT_DOMAIN_' ), '1.0' );
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @return void 
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', '_TEXT_DOMAIN_' ), '1.0' );
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Plugin configuration
	 *
	 * @param string $key Optional. Get the config value by key.
	 * @return mixed 
	 */
	public function config( $key = false ){
		return zpb_config( $key );
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Build it!
	 */
	public function __construct() {
		$this->version = ZPB_VERSION;
		
		/* Include core
		--------------------*/
		include_once $this->rootPath() . "autoloader.php";
		include_once $this->rootPath() . "functions.php";
		
		/* Activation and deactivation hooks
		-----------------------------------------*/
		register_activation_hook( ZPB_PLUGIN_FILE, array( $this, 'onActivation' ) );
		register_deactivation_hook( ZPB_PLUGIN_FILE, array( $this, 'onDeactivation' ) );

		/* Init core
		-----------------*/
		add_action( $this->config( 'action_name' ), array( $this, 'init' ), 0 );
		add_action( 'widgets_init', array( $this, 'initWidgets' ), 0 );

		/* Register and enqueue scripts and styles
		-----------------------------------------------*/
		add_action( 'wp_enqueue_scripts', array( $this, 'frontendScriptsAndStyles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'backendScriptsAndStyles' ) );

		/* Load components, if any...
		----------------------------------*/
		$this->loadComponents();

		/* Plugin fully loaded and executed
		----------------------------------------*/
		do_action( 'zpb:loaded' );
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Init the plugin.
	 * 
	 * Attached to `init` action hook. Init functions and classes here.
	 *
	 * @return void 
	 */
	public function init() {
		do_action( 'zpb:before_init' );

		$this->loadTextDomain();

		// Call plugin classes/functions here.
		do_action( 'zpb:init' );
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Init the widgets of this plugin
	 *
	 * @return void 
	 */
	public function initWidgets() {
		do_action( 'zpb:widgets_init' );
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Localize
	 *
	 * @return void 
	 */
	public function loadTextDomain(){
		load_plugin_textdomain( 
			'_TEXT_DOMAIN_', 
			false, 
			$this->config( 'lang_path' ) 
		);
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Load components
	 *
	 * @return void 
	 */
	public function loadComponents(){
		$components = glob( ZPB_PATH .'components/*', GLOB_ONLYDIR );
		foreach ($components as $component_path) {
			require_once trailingslashit( $component_path ) .'component.php';
		}
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Frontend scripts & styles
	 *
	 * @return void 
	 */
	public function frontendScriptsAndStyles(){
		
		$id = $this->config( 'id' );

		$this->addStyles(array(
			$id . '-styles' => array(
				'src'     => $this->assetsURL( 'css/styles.css' ),
				'enqueue' => false,
			),
		));

		$this->addScripts(array(
			$id . '-config' => array(
				'src'     => $this->assetsURL( 'js/config.js' ),
				'deps'    => array( 'jquery' ),
				'enqueue' => false,
			),
		));

	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Backend scripts & styles
	 *
	 * @return void 
	 */
	public function backendScriptsAndStyles(){
		
		$id = $this->config( 'id' );

		$this->addStyles(array(
			$id . '-styles-admin' => array(
				'src'     => $this->assetsURL( 'css/styles-admin.css' ),
				'enqueue' => false,
			),
		));

		$this->addScripts(array(
			$id . '-config-admin' => array(
				'src'     => $this->assetsURL( 'js/config-admin.js' ),
				'deps'    => array( 'jquery' ),
				'enqueue' => false,
				// 'localizedConfig' => array(
				// 	'test_key' => 'test_key',
				// 	'nice' => $this->config(),
				// ),
			),
		));


	}

	//------------------------------------//--------------------------------------//

	/**
	 * Actions when the plugin is activated
	 *
	 * @return void
	 */
	public function onActivation() {
		// Code to be executed on plugin activation
		do_action( 'zpb:on_activation' );
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Actions when the plugin is deactivated
	 *
	 * @return void
	 */
	public function onDeactivation() {
		// Code to be executed on plugin deactivation
		do_action( 'zpb:on_deactivation' );
	}

	/*
	-------------------------------------------------------------------------------
	Styles
	-------------------------------------------------------------------------------
	*/
	protected function addStyles( $styles ){
		if( !empty( $styles ) ){

			foreach ($styles as $handle => $s) {
				// If just calling an already registered style
				if( is_numeric( $handle ) && !empty($s) ){
					wp_enqueue_style( $s );
					continue;
				}

				// Merge with defaults
				$s = wp_parse_args( $s, array(
					'deps'    => array(),
					'ver'     => $this->version,
					'media'   => 'all',
					'enqueue' => true,
				));
				
				// Register style
				wp_register_style( $handle, $s['src'], $s['deps'], $s['ver'], $s['media'] );
				
				// Enqueue style
				if( $s['enqueue'] ){
					wp_enqueue_style( $handle );
				}
			}

		}
	}

	/*
	-------------------------------------------------------------------------------
	Scripts
	-------------------------------------------------------------------------------
	*/
	protected function addScripts( $scripts ){
		if( !empty( $scripts ) ){

			foreach ($scripts as $handle => $s) {
				
				/* If just calling an already registered script
				----------------------------------------------------*/
				if( is_numeric( $handle ) && !empty($s) ){
					wp_enqueue_script( $s );
					continue;
				}

				/* Register
				----------------*/
				// Merge with defaults
				$s = wp_parse_args( $s, array(
					'deps'      => array( 'jquery' ),
					'ver'       => $this->version,
					'in_footer' => true,
					'enqueue'   => true,
				));
				
				wp_register_script( $handle, $s['src'], $s['deps'], $s['ver'], $s['in_footer'] );
				
				/* Enqueue
				---------------*/
				if( $s['enqueue'] ){
					wp_enqueue_script( $handle );
				}

				/* Localize 
				-----------------*/
				// Remove all keys that that has a false value
				// $s = array_filter( $s );

				// Remove known keys 
				unset( $s['src'], $s['deps'], $s['ver'], $s['in_footer'], $s['enqueue'] );

				// Probably we have loclization strings
				if( !empty( $s ) ){

					// Get first key from array. This may contain the strings for wp_localize_script
					$localize_key = key( $s );

					// Get strings
					$localization_strings = $s[ $localize_key ];

					// Localize strings
					if( !empty( $localization_strings ) && is_array( $localization_strings ) ){
						wp_localize_script( $handle, $localize_key, $localization_strings );
					}

				}
			}

		}
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Get Root URL
	 *
	 * @return string
	 */
	public function rootURL(){
		return ZPB_URL;
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Get Root PATH
	 *
	 * @return string
	 */
	public function rootPath(){
		return ZPB_PATH;
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Get assets url.
	 * 
	 * @param string $file Optionally specify a file name
	 *
	 * @return string
	 */
	public function assetsURL( $file = false ){
		$path = ZPB_URL . 'assets/';
		
		if( $file ){
			$path = $path . $file;
		}

		return $path;
	}

}


/*
-------------------------------------------------------------------------------
Main plugin instance
-------------------------------------------------------------------------------
*/
function ZPB() {
	return ZPB_Plugin::instance();
}

/*
-------------------------------------------------------------------------------
Rock it!
-------------------------------------------------------------------------------
*/
ZPB();
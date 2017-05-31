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
		
		// Include core
		include_once $this->rootPath() . "autoloader.php";
		include_once $this->rootPath() . "functions.php";
		
		// Build the plugin
		$this->buildPlugin();

		// Load components, if any...
		$this->loadComponents();

		// Plugin fully loaded and executed
		do_action( 'zpb:loaded' );
	}
	
	//------------------------------------//--------------------------------------//
	
	/**
	 * Build the plugin
	 *
	 * @return void 
	 */
	private function buildPlugin() {
		register_activation_hook( ZPB_PLUGIN_FILE, array( $this, 'onActivation' ) );
		register_deactivation_hook( ZPB_PLUGIN_FILE, array( $this, 'onDeactivation' ) );

		add_action( $this->config( 'action_name' ), array( $this, 'init' ), 0 );
		add_action( 'widgets_init', array( $this, 'initWidgets' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'frontendScriptsAndStyles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'backendScriptsAndStyles' ) );
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
		do_action( 'zpb:before_widgets_init' );

		// register_widget( 'ExampleWidget' );

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

		wp_register_style( 
			$id . '-styles', 
			$this->assetsURL( 'css/styles.css' ), 
			'', 
			$this->version 
		);

		wp_register_script( 
			$id .'-config', 
			$this->assetsURL( 'js/config.js' ), 
			array( 'jquery' ), 
			$this->version, 
			true 
		);
		
		// wp_enqueue_style( $id . '-styles' );
		// wp_enqueue_script( $id .'-config' );

	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Backend scripts & styles
	 *
	 * @return void 
	 */
	public function backendScriptsAndStyles(){
		
		$id = $this->config( 'id' );

		wp_register_style( 
			$id . '-styles-admin', 
			$this->assetsURL( 'css/styles-admin.css' ), 
			'', 
			$this->version 
		);

		wp_register_script( 
			$id .'-config-admin', 
			$this->assetsURL( 'js/config-admin.js' ), 
			array( 'jquery' ), 
			$this->version, 
			true 
		);
		
		// wp_enqueue_style( $id . '-styles-admin' );
		// wp_enqueue_script( $id .'-config-admin' );

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
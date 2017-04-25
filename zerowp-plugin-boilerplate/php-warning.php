<?php 
class ZPB_PHP_Warning{

	public $page_slug = 'zpb-php-fail-notice';

	public function __construct(){
		add_action( 'admin_menu', array( $this, 'register' ) );
		add_action( 'admin_head', array( $this, 'style' ) );
	}

	function register(){
		add_menu_page( 
			__( 'ZeroWP Plugin Boilerplate', 'zerowp-plugin-boilerplate' ),
			__( 'ZeroWP Plugin Boilerplate', 'zerowp-plugin-boilerplate' ),
			'manage_options',
			$this->page_slug,
			array( $this, 'notice' ),
			'dashicons-warning',
			60
		); 
	}

	// Thanks to Yoast SEO for text.
	function notice(){
		
		$output = '<div class="'. $this->page_slug .'">';
		
		$output .= '<h1>' . sprintf( 
			__( 'This plugin requires PHP version %s or higher!', 'zerowp-plugin-boilerplate' ), 
			ZPB_MIN_PHP_VERSION 
		) .'</h1>';

		$output .= '<h3>'. __( 'Your site could be faster and more secure with a newer PHP version.', 'zerowp-plugin-boilerplate' ) . '</h3>';

		$output .= '<p>'. __( 'Hey, we\'ve noticed that you\'re running an outdated version of PHP. PHP is the programming language that WordPress and this plugin are built on. The version that is currently used for your site is no longer supported. Newer versions of PHP are both faster and more secure. In fact, your version of PHP no longer receives security updates, which is why we\'re sending you to this notice.', 'zerowp-plugin-boilerplate' ) .'</p>';

		$output .= '<p>'. __( 'Hosts have the ability to update your PHP version, but sometimes they don\'t dare to do that because they\'re afraid they\'ll break your site.', 'zerowp-plugin-boilerplate' ) .'</p>';

		$output .= '<h3>'. __( 'To which version should I update?', 'zerowp-plugin-boilerplate' ) . '</h3>';

		$output .= '<p>'. sprintf( __( 'While this plugin requires at least %s, you should update your PHP version to either 5.6 or to 7.0 or 7.1. On a normal WordPress site, switching to PHP 5.6 should never cause issues. We would however actually recommend you switch to PHP7. There are some plugins that are not ready for PHP7 though, so do some testing first. PHP7 is much faster than PHP 5.6. It\'s also the only PHP version still in active development and therefore the better option for your site in the long run.', 'zerowp-plugin-boilerplate' ), ZPB_MIN_PHP_VERSION ) .'<p>';

		$output .= '</div>';
		
		echo $output;
		
	}

	public function style(){
		if( is_admin() && isset( $_GET['page'] ) && $this->page_slug === $_GET['page'] ){
			echo '<style>
				.'. $this->page_slug .'{
					display: block;
					position: relative;
					max-width: 800px;
					margin: 50px auto;
					padding: 40px;
					border-radius: 7px;
					background: #fff;
					border: 1px solid #f00505;
				}
				.'. $this->page_slug .' p{
					font-size: 16px;
					color: #444;
					margin-bottom: 30px;
				}
				.'. $this->page_slug .' h3{
					margin-top: 30px;
				}
			</style>';
		}
	}

}
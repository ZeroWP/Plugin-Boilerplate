s<?php 
require_once ZPB_PATH . 'warnings/abstract-warning.php';

class ZPB_NoPlugin_Warning extends ZPB_Astract_Warning{

	public function notice(){
		
		$output = '';
		
		if( count( $this->data ) > 1 ){
			$message = __( 'Please install and activate the following plugins:', '_TEXT_DOMAIN_' );
		}
		else{
			$message = __( 'Please install and activate this plugin:', '_TEXT_DOMAIN_' );
		}

		$output .= '<h2>' . $message .'</h2>';


		$output .= '<ul class="zpb-required-plugins-list">';
			foreach ($this->data as $plugin_slug => $plugin) {
				$plugin_name = '<div class="zpb-plugin-info-title">'. $plugin['plugin_name'] .'</div>';

				if( !empty( $plugin['plugin_uri'] ) ){
					$button = '<a href="'. esc_url_raw( $plugin['plugin_uri'] ) .'" class="zpb-plugin-info-button" target="_blank">'. __( 'Get the plugin', '_TEXT_DOMAIN_' ) .'</a>';
				}
				else{
					$button = '<a href="#" class="zpb-plugin-info-button disabled" target="_blank">'. __( 'Get the plugin', '_TEXT_DOMAIN_' ) .'</a>';
				}

				$output .= '<li>'. $plugin_name . $button .'</li>';
			}
		$output .= '</ul>';

		return $output;
	}

}
<?php
/**
 * Plugin Name: SG Check
 * Plugin URI: https://nasimnet.ir/
 * Description: With this plugin you can check the version of your server's Source Guardian loader.
 * Version: 1.3
 * Author: NasimNet
 * Author URI: https://nasimnet.ir
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit;

require_once( dirname(__FILE__).'/core.php' );

add_action( 'init', 'sourceguardian_load_textdomain' );
function sourceguardian_load_textdomain() {
    load_plugin_textdomain( 'sg-check', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'admin_enqueue_scripts', 'sourceguardian_admin_style' );
function sourceguardian_admin_style($hook) {
    if ( $hook == 'tools_page_source-guardian-check' ) {
        wp_enqueue_style( 'sgcheck_admin_css', plugins_url('assets/css/admin-style.css', __FILE__) );
    }
}

register_deactivation_hook( __FILE__, 'deactivation_sourceguardian' );
function deactivation_sourceguardian() {
	delete_option( 'source_guardian_check' );
}

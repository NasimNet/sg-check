<?php
/**
 * Plugin Name: SG Check
 * Plugin URI: https://nasimnet.ir/
 * Description: با این افزونه می توانید نسخه لودر سورس گاردین سرور خود را بررسی کنید.
 * Version: 1.2
 * Author: NasimNet
 * Author URI: http://nasimnet.ir
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SG_CHECK' , 'sg-check' );

require_once(dirname(__FILE__).'/core.php');

add_action( 'init', 'sourceguardian_load_textdomain' );
function sourceguardian_load_textdomain() {
    load_plugin_textdomain( SG_CHECK, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'admin_enqueue_scripts', 'sgcheck_wp_admin_style' );
function sgcheck_wp_admin_style($hook) {
    if( $hook != 'tools_page_source-guardian-check' ) return;
    wp_enqueue_style( 'sgcheck_admin_css', plugins_url('assets/css/admin-style.css', __FILE__) );
}

register_deactivation_hook( __FILE__, 'deactivation_sourceguardian' );
function deactivation_sourceguardian() {
	delete_option( 'source_guardian_check' );
}

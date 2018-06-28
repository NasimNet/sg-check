<?php
// Source Guardian Check Core
if ( ! defined( 'ABSPATH' ) ) exit;

class SourceGuardianCheck {

	const version = '11.2';

	function __construct() {
		add_action( 'admin_menu', array( $this, 'source_guardian_check_page' ) );
		add_action( 'admin_notices', array( $this, 'source_guardian_check_notice') );
	}

	public function source_guardian_check_page() {
		add_management_page(
			__( 'تستر سورس گاردین' , SG_CHECK ),
			__( 'تستر سورس گاردین' , SG_CHECK ),
			'manage_options',
			'source-guardian-check',
			array( $this, 'admin_page' )
		);
	}

	public function source_guardian_content() {

		$extensions = get_loaded_extensions();
		$ver        = self::version;
		$content    = '';

		// Access to the server is limited !
		if ( @!$extensions ) {

			$content .= '<span class="dashicons dashicons-hidden icon-sg red"></span>';
			$content .= '<p class="red">';
			$content .= __( 'متاسفانه دسترسی به اطلاعات سرور محدود شده است !', SG_CHECK ) . '<br>';
			$content .= __( 'برای اطمینان از لودرها از هاستینگ خود سوال بفرمایید.', SG_CHECK ) . '<br>';
			$content .= sprintf( __( 'آخرین نسخه فعلی نسخه %s می باشد.', SG_CHECK ) , $ver );
			$content .= '</p>';

			return $content;
		}

		// The Source Guardian Loader not installed !
		if ( !in_array('SourceGuardian', $extensions) ) {

			$content .= '<span class="dashicons dashicons-dismiss icon-sg red"></span>';
			$content .= '<p class="red">';
			$content .= __( 'لودرهای سورس گاردین نصب نشده اند !', SG_CHECK ) . '<br>';
			$content .= __( 'با هاستینگ خود تماس بگیرید', SG_CHECK ) .'<br>';
			$content .= '<a href="https://www.sourceguardian.com/loaders.html" target="_balnk">' . __( 'اطلاعات بیشتر', SG_CHECK ) . '</a>' ;
			$content .= '</p>';

			return $content;
		}

		$version = phpversion("SourceGuardian");
		$content .= __('نسخه سورس گاردین:', SG_CHECK ) .' <b>'. $version .'</b><br>';

		// The version of Source Guardian Loader is old !
		if ( version_compare( $version, $ver, '<' ) ) {

			$content .= '<span class="dashicons dashicons-warning icon-sg orange"></span>';
			$content .= '<p class="orange">';
			$content .= __( 'نسخه لودر سورس گاردین قدیمی است !', SG_CHECK ) .'<br>';
			$content .= __( 'پیشنهاد می شود لودر سورس گاردین را بروزرسانی کنید.', SG_CHECK ) .'<br>';
			$content .= __( 'با هاستینگ خود تماس بگیرید', SG_CHECK ) .'<br>';
			$content .= sprintf( __( 'آخرین نسخه فعلی نسخه %s می باشد.', SG_CHECK ) , $ver ) . '<br>';
			$content .= '<a href="https://www.sourceguardian.com/loaders.html" target="_balnk">' . __( 'اطلاعات بیشتر', SG_CHECK ) . '</a>' ;
			$content .= '</p>';

			return $content;
		}

		// The Source Guardian Loader is up to date
		$content .= '<span class="dashicons dashicons-smiley icon-sg green"></span>';
		$content .= '<p class="green">';
		$content .= __( 'لودرهای سورس گاردین بروز هستند', SG_CHECK ) .'<br>';
		$content .= __( 'موفق باشید !', SG_CHECK );
		$content .= '</p>';

		return $content;
	}

	public function server_info() {
		global $wpdb;

		$server_info_fields = array(
			array(
				'title' => __( 'نرم افزار سرور', SG_CHECK ),
				'desc'  => $_SERVER['SERVER_SOFTWARE']
			),
			array(
				'title' => __( 'نسخه PHP', SG_CHECK ),
				'desc'  => ( function_exists( 'phpversion' ) ) ? phpversion() : __( 'تابع phpversion() در دسترس نیست !', SG_CHECK )
			),
			array(
				'title' => __( 'PHP Post Max Size', SG_CHECK ),
				'desc'  => ( function_exists( 'ini_get' ) ) ? size_format( wp_convert_hr_to_bytes( ini_get( 'post_max_size' ) ) ) : __( 'تابع ini_get() در دسترس نیست !', SG_CHECK )
			),
			array(
				'title' => __( 'PHP Time Limit', SG_CHECK ),
				'desc'  => ( function_exists( 'ini_get' ) ) ? ini_get( 'max_execution_time' ) . __( ' ثانیه') : __( 'تابع ini_get() در دسترس نیست !', SG_CHECK )
			),
			array(
				'title' => __( 'PHP Max Input Vars', SG_CHECK ),
				'desc'  => ( function_exists( 'ini_get' ) ) ? number_format_i18n( ini_get( 'max_input_vars' ) ) : __( 'تابع ini_get() در دسترس نیست !', SG_CHECK )
			),
			array(
				'title' => __( 'SUHOSIN', SG_CHECK ),
				'desc'  => ( extension_loaded( 'suhosin' ) ) ? '&#10004;' : '&ndash;'
			),
			array(
				'title' => __( 'نسخه MySQL', SG_CHECK ),
				'desc'  => $wpdb->db_version()
			),
			array(
				'title' => __( 'Max Upload Size', SG_CHECK ),
				'desc'  => size_format( wp_max_upload_size() )
			),
			array(
				'title' => __( 'Display Errors', SG_CHECK ),
				'desc'  => ( function_exists( 'ini_get' ) ) ? ( ini_get( 'display_errors' ) ? '&#10004;' : '&ndash;' ) : '<span class="notice-error">' . __( 'تابع ini_get() در دسترس نیست !', SG_CHECK ) . '</span>'
			),
		);

		return apply_filters( 'sgcheck_server_info_default_fields', $server_info_fields );
	}

	public function admin_page() { ?>

		<?php
			if ( get_option( 'source_guardian_check' ) != 1 ) {
				update_option( 'source_guardian_check', 1 );
			}
		?>

		<div class="postbox">
			<button type="button" class="handlediv button-link" aria-expanded="true">
				<span class="toggle-indicator" aria-hidden="true"></span>
			</button>
			<h2 class="hndle ui-sortable-handle pad10 margin-0">
				<span><?php _e( 'وضعیت سورس گاردین', SG_CHECK ); ?></span>
			</h2>
			<div class="inside">
				<div class="main">
					<div id="sg-content">
						<?php echo $this->source_guardian_content(); ?>
					</div>
				</div>
			</div>
		</div>

		<table class="sgcheck-status-table widefat">
			<thead>
				<tr>
					<th colspan="3"><h2>اطلاعات سرور</h2></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$server_info = $this->server_info();
					foreach ( $server_info as $info ) {
						printf('<tr><td>%s</td><td>%s</td></tr>', $info['title'], $info['desc'] );
					}
				?>
			</tbody>
		</table><?php
	}

	public function source_guardian_check_notice() {
		if ( get_option( 'source_guardian_check' ) != 1 ) {

			$class       = 'notice notice-success';
			$message     = __( 'The Source Guardian Check plugin has been successfully activated', SG_CHECK );
			$url         = admin_url('tools.php?page=source-guardian-check');
			$url_message = __( 'Click to view status', SG_CHECK );

			printf( '<div class="%1$s"><p>%2$s . <a href="%3$s">%4$s</a></p></div>',
			$class, $message, $url, $url_message );

		}
	}
}

if ( is_admin() )
	$source_guardian_check = new SourceGuardianCheck;

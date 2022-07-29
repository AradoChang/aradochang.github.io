<?php


if ( ! class_exists( 'Ele_Fonts_Kr_Lisence' ) ) {

	class Ele_Fonts_Kr_Lisence  {
		public static $_instance = null;
		public static $_cron_hook = null;
		public static $file;
		protected $license_key;
		protected $status;
		protected $item_id;
		protected $store_url;
		protected $plugin_page;
		protected $plugin_label;

		private static $slug = ELE_FONTS_KR_SLUG;

		function __construct()	{
			$this->store_url = ELE_FONTS_KR_STORE;
			$this->item_id = ELE_FONTS_KR_ID;
			$this->plugin_label = __( '엘리멘토 한글폰트 라이센스', ELE_FONTS_KR_SLUG);
			$this->plugin_page = self::$slug.'_license';
			self::$file = plugin_dir_path(ELE_FONTS_KR_PLUGIN_FILE).'includes/'.ELE_FONTS_KR_SLUG.'.php';
			self::$_cron_hook = self::$slug.'_hook';

			if( !class_exists( 'Ele_Fonts_Kr_Lisence_Updater' ) ) {
				include( dirname( __FILE__ ) . '/updater.php' );
			}
			add_action(self::$_cron_hook, array($this, 'daily'));
			add_action( 'admin_init', array ( $this, 'admin_init' ), 0 );
			add_action( 'admin_init', array ( $this, 'register_option' ) );
			add_action( 'admin_menu', array ( $this, 'menu' ) );
			add_action( 'admin_init', array ( $this, 'activate_license' ) );
			add_action( 'admin_init', array ( $this, 'deactivate_license' ) );
			add_action( 'admin_notices', array ( $this, 'admin_notices' ) );
		}

		public static function activate(){
			self::setup();
		}

		public static function deactivate(){
			self::unset();
		}

		public static function setup(){
			$timestamp = wp_next_scheduled( self::$_cron_hook );
			if( $timestamp === false ){
				//Schedule the event for right now, then to repeat daily using the hook 'update_whatToMine_api'
				wp_schedule_event( time(), 'daily', self::$_cron_hook );
			}
		}

		public static function unset(){
			$timestamp = wp_next_scheduled( self::$_cron_hook );
			wp_unschedule_event( $timestamp, self::$_cron_hook );
		}

		public function daily(){
			$this->check_license();
		}

		public function admin_init(){
			// retrieve our license key from the DB
			$this->license_key = trim( get_option( self::$slug.'_key' ) );

			// setup the updater
			$edd_updater = new Ele_Fonts_Kr_Lisence_Updater( $this->store_url, ELE_FONTS_KR_PLUGIN_FILE,
			array(
				'version' => ELE_FONTS_KR_VER,                    // current version number
				'license' => $this->license_key,             // license key (used get_option above to retrieve from DB)
				'item_id' => $this->item_id,       // ID of the product
				'author'  => 'WPTEAM', // author of this plugin
				'beta'    => false,
				'wp_override' => true
			)
		);
	}

	public function menu(){
		add_plugins_page( $this->plugin_label, $this->plugin_label, 'manage_options', $this->plugin_page, array($this, 'page') );
	}

	public function page(){
		$this->license_key = get_option( self::$slug.'_key' );
		$this->status  = get_option( self::$slug.'_status' );
		?>
		<div class="wrap">
			<h2><?php _e('라이센스', ELE_FONTS_KR_SLUG); ?></h2>
			<form method="post" action="options.php">

				<?php settings_fields(self::$slug.'_license'); ?>

				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('라이센스 키', ELE_FONTS_KR_SLUG); ?>
							</th>
							<td>
								<input id="<?php echo self::$slug ?>_key" name="<?php echo self::$slug ?>_key" type="text" class="regular-text" value="<?php esc_attr_e( $this->license_key ); ?>" />
								<label class="description" for="<?php echo self::$slug ?>_key"><?php _e('라이센스 키를 넣어 저장해주세요', ELE_FONTS_KR_SLUG); ?></label>
							</td>
						</tr>
						<?php if( false !== $this->license_key ) { ?>
							<tr valign="top">
								<th scope="row" valign="top">
									<?php _e('라이센스 활성화',ELE_FONTS_KR_SLUG); ?>
								</th>
								<td>
									<?php if( $this->status !== false && $this->status == 'valid' ) { ?>
										<span style="color:green;"><?php _e('활성화!',ELE_FONTS_KR_SLUG); ?></span>
										<?php wp_nonce_field( self::$slug.'_nonce', self::$slug.'_nonce' ); ?>
										<input type="submit" class="button-secondary" name="<?php echo self::$slug ?>_deactivate" value="<?php _e('라이센스 비활성화',ELE_FONTS_KR_SLUG); ?>"/>
									<?php } else {
										wp_nonce_field( self::$slug.'_nonce', self::$slug.'_nonce' ); ?>
										<?php if (empty($this->license_key )): ?>
											<?php _e('라이센스 키를 넣고 변경 사항 저장 버튼을 눌러주세요.',ELE_FONTS_KR_SLUG); ?>
											<?php else: ?>
										<input type="submit" class="button-secondary" name="<?php echo self::$slug ?>_activate" value="<?php _e('라이센스 활성화',ELE_FONTS_KR_SLUG); ?>"/>
										<?php _e('활성화 버튼을 클릭해주세요.',ELE_FONTS_KR_SLUG); ?>
									<?php endif; ?>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php submit_button(); ?>

			</form>
			<?php
		}

		public function register_option(){
			register_setting(self::$slug.'_license', self::$slug.'_key', array($this,'sanitize_license') );
		}

		public function sanitize_license( $new ) {
			$old = get_option( self::$slug.'_key' );
			if( $old && $old != $new ) {
				delete_option( self::$slug.'_status' ); // new license has been entered, so must reactivate
			}
			return $new;
		}

		public function activate_license() {

			// listen for our activate button to be clicked
			if( isset( $_POST[self::$slug.'_activate'] ) ) {

				// run a quick security check
				if( ! check_admin_referer( self::$slug.'_nonce', self::$slug.'_nonce' ) )
				return; // get out if we didn't click the Activate button

				// retrieve the license from the database
				$this->license_key = trim( get_option( self::$slug.'_key' ) );


				// data to send in our API request
				$api_params = array(
					'edd_action' => 'activate_license',
					'license'    => $this->license_key,
					'item_id'    => $this->item_id, // the name of our product in EDD
					'url'        => home_url()
				);

				// Call the custom API.
				$response = wp_remote_post( $this->store_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

				// make sure the response came back okay
				if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

					if ( is_wp_error( $response ) ) {
						$message = $response->get_error_message();
					} else {
						$message = __( '에러가 발생했습니다. 다시 시도 해주세요.',ELE_FONTS_KR_SLUG );
					}

				} else {

					$license_data = json_decode( wp_remote_retrieve_body( $response ) );

					if ( false === $license_data->success ) {

						switch( $license_data->error ) {

							case 'expired' :

							$message = sprintf(
								__( '만료된 라이센스키입니다. on %s.',ELE_FONTS_KR_SLUG ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
							);
							break;

							case 'disabled' :
							case 'revoked' :

							$message = __( '라이센스가 비활성화 되었습니다.',ELE_FONTS_KR_SLUG );
							break;

							case 'missing' :

							$message = __( '허가되지 않은 라이센스입니다.',ELE_FONTS_KR_SLUG );
							break;

							case 'invalid' :
							case 'site_inactive' :

							$message = __( '이 도메인에 대한 라이센스가 활성화 되어 있지 않습니다.',ELE_FONTS_KR_SLUG );
							break;

							case 'item_name_mismatch' :

							$message = sprintf( __( '유효하지 않은 라이센스키 입니다. for %s.',ELE_FONTS_KR_SLUG ), $this->item_id );
							break;

							case 'no_activations_left':

							$message = __( '최대 활성화 가능한 라이센스를 초과하였습니다.',ELE_FONTS_KR_SLUG );
							break;

							default :

							$message = __( '에러가 발생했습니다. 다시 시도 해주세요.',ELE_FONTS_KR_SLUG );
							break;
						}

					}

				}

				// Check if anything passed on a message constituting a failure
				if ( ! empty( $message ) ) {
					$base_url = admin_url( 'plugins.php?page=' . $this->plugin_page );
					$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

					wp_redirect( $redirect );
					exit();
				}

				// $license_data->license will be either "valid" or "invalid"

				update_option( self::$slug.'_status', $license_data->license );
				wp_redirect( admin_url( 'plugins.php?page=' . $this->plugin_page ) );
				exit();
			}
		}

		public function deactivate_license() {

			// listen for our activate button to be clicked
			if( isset( $_POST[self::$slug.'_deactivate'] ) ) {

				// run a quick security check
				if( ! check_admin_referer( self::$slug.'_nonce', self::$slug.'_nonce' ) )
				return; // get out if we didn't click the Activate button

				// retrieve the license from the database
				$this->license_key = trim( get_option( self::$slug.'_key' ) );


				// data to send in our API request
				$api_params = array(
					'edd_action' => 'deactivate_license',
					'license'    => $this->license_key,
					'item_id'    => $this->item_id, // the name of our product in EDD
					'url'        => home_url()
				);

				// Call the custom API.
				$response = wp_remote_post( $this->store_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

				// make sure the response came back okay
				if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

					if ( is_wp_error( $response ) ) {
						$message = $response->get_error_message();
					} else {
						$message = __( '에러가 발생했습니다. 다시 시도 해주세요.',ELE_FONTS_KR_SLUG );
					}

					$base_url = admin_url( 'plugins.php?page=' . $this->plugin_page );
					$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

					wp_redirect( $redirect );
					exit();
				}

				// decode the license data
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				// $license_data->license will be either "deactivated" or "failed"
				if( $license_data->license == 'deactivated' || $license_data->license == 'failed'  ) {
					delete_option( self::$slug.'_status' );
				}

				wp_redirect( admin_url( 'plugins.php?page=' . $this->plugin_page ) );
				exit();

			}
		}

		public function check_license() {

			global $wp_version;

			$this->license_key = trim( get_option( self::$slug.'_key' ) );

			$api_params = array(
				'edd_action' => 'check_license',
				'license' => $this->license_key,
				'item_id'    => $this->item_id,
				'url'       => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( $this->store_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			if ( is_wp_error( $response ) )
			return false;

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if( $license_data->license == 'valid' ) {
				update_option(self::$slug.'_status','valid');
			} else {
				update_option(self::$slug.'_status','invalid');
			}
		}

		public function admin_notices() {
			if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {

				switch( $_GET['sl_activation'] ) {

					case 'false':
					$message = urldecode( $_GET['message'] );
					?>
					<div class="error">
						<p><?php echo $message; ?></p>
					</div>
					<?php
					break;

					case 'true':
					default:
					// Developers can put a custom success message here for when activation is successful if they way.
					break;

				}
			}
		}

		public function is_Access(){
			$status = get_option(self::$slug.'_status') ? get_option(self::$slug.'_status') : 'invalid';
			return ($status === 'valid') ? true : false;
		}

		public function load(){
			if (file_exists(self::$file)) {
				if ($this->is_Access())
				require_once( self::$file );
			}
		}

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

	}

	$_instance = Ele_Fonts_Kr_Lisence::instance();
	$_instance->load();

}

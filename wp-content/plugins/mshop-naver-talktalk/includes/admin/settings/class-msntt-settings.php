<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MSNTT_Settings' ) ) :
	class MSNTT_Settings {
		static function update_settings() {
			include_once MSNTT()->plugin_path() . '/includes/admin/setting-manager/mshop-setting-helper.php';

			$_REQUEST = array_merge( $_REQUEST, msntt_clean( json_decode( stripslashes( $_REQUEST['values'] ), true ) ) );

			MSNTT_SettingHelper::update_settings( self::get_setting_fields() );

			wp_send_json_success();
		}

		static function get_setting_fields() {
			return array(
				'type'     => 'Tab',
				'id'       => 'msntt-setting-tab',
				'elements' => array(
					self::get_setting_naver_talktalk_tab(),
					self::get_setting_plus_friends_tab(),
                    self::get_manual_link(),
				)
			);
		}

		static function get_setting_naver_talktalk_tab() {
			return array(
				'type'     => 'Page',
				'title'    => __( '네이버 톡톡 설정', 'mshop-naver-talktalk' ),
				'class'    => 'active',
				'elements' => array(
					array(
						'type'     => 'Section',
						'title'    => __( '상품 상세 페이지 배너 설정', 'mshop-naver-talktalk' ),
						'elements' => array(
							array(
								'id'        => 'msntt_product_naver_talktalk',
								'title'     => __( '배너 활성화', 'mshop-naver-talktalk' ),
								'className' => '',
								'type'      => 'Toggle',
								'default'   => 'no',
								'desc'      => __( '상품 상세 페이지에서 네이버 톡톡 배너를 사용합니다.', 'mshop-naver-talktalk' )
							),
							array(
								"showIf"      => array( 'msntt_product_naver_talktalk' => 'yes' ),
								"id"          => "msntt_pc_product_key",
								"className"   => " seven wide column fluid",
								"title"       => __( "PC용 배너 아이디", 'mshop-naver-talktalk' ),
								"type"        => "Text",
								'placeholder' => __( '상품 상세 페이지 네이버 톡톡 배너(PC) 아이디 값을 입력하세요.', 'mshop-naver-talktalk' ),
							),
							array(
								"showIf"      => array( 'msntt_product_naver_talktalk' => 'yes' ),
								"id"          => "msntt_mobile_product_key",
								"className"   => " seven wide column fluid",
								"title"       => __( "모바일용 배너 아이디", 'mshop-naver-talktalk' ),
								"type"        => "Text",
								'placeholder' => __( '상품 상세 페이지 네이버 톡톡 배너(Mobile) 아이디 값을 입력하세요.', 'mshop-naver-talktalk' ),
							),
							array(
								'showIf'      => array( 'msntt_product_naver_talktalk' => 'yes' ),
								'id'          => 'msntt_product_naver_talktalk_button',
								'title'       => __( '상품 상세 페이지 배너 위치', 'mshop-naver-talktalk' ),
								"className"   => " seven wide column fluid",
								'default'     => 'woocommerce_product_meta_start',
								'type'        => 'Text',
								'placeholder' => __( '상품 상세 페이지 배너 위치를 지정합니다.', 'mshop-naver-talktalk' ),
								'desc2'       => __( '테마 구조에 따라 네이버 톡톡 배너의 위치가 달라집니다. 테마 파일을 참고해서 위치를 지정하세요.
ex) 테마 파일 내 do_action( \'woocommerce_product_meta_start\' ); 코드가 있는 위치에 네이버 톡톡 배너을 출력하려면, woocommerce_product_meta_start 을 입력합니다.', '##PKGNAME' )
							)
						)
					),
					array(
						'type'     => 'Section',
						'title'    => __( '고정형 배너 설정', 'mshop-naver-talktalk' ),
						'elements' => array(
							array(
								'id'        => 'msntt_naver_talktalk_banner',
								'title'     => __( '고정형 배너 활성화', 'mshop-naver-talktalk' ),
								'className' => '',
								'type'      => 'Toggle',
								'default'   => 'no',
								'desc'      => __( '모든 페이지 우측 하단에 표시되는 네이버 톡톡 고정형 배너를 사용합니다.', 'mshop-naver-talktalk' )
							),
							array(
								'showIf'      => array( 'msntt_naver_talktalk_banner' => 'yes' ),
								'id'          => 'msntt_naver_talktalk_pc_banner_id',
								'title'       => __( 'PC용 고정형 배너 아이디', 'mshop-naver-talktalk' ),
								"className"   => " seven wide column fluid",
								'type'        => 'Text',
								'placeholder' => __( '네이버 톡톡 PC용 고정형 배너 아이디를 입력하세요.', 'mshop-naver-talktalk' ),
							),
							array(
								'showIf'      => array( 'msntt_naver_talktalk_banner' => 'yes' ),
								'id'          => 'msntt_naver_talktalk_mobile_banner_id',
								'title'       => __( '모바일용 고정형 배너 아이디', 'mshop-naver-talktalk' ),
								"className"   => " seven wide column fluid",
								'type'        => 'Text',
								'placeholder' => __( '네이버 톡톡 모바일용 고정형 배너 아이디를 입력하세요.', 'mshop-naver-talktalk' ),
							)
						)
					)
				)
			);
		}

		static function get_setting_plus_friends_tab() {
			return array(
				'type'     => 'Page',
				'title'    => __( '플러스친구 설정', 'mshop-naver-talktalk' ),
				'elements' => array(
					array(
						'type'     => 'Section',
						'title'    => __( '기본 설정', 'mshop-naver-talktalk' ),
						'elements' => array(
							array(
								'id'        => 'msntt_plus_friends_app_key',
								'title'     => __( 'JavaScript key', 'mshop-naver-talktalk' ),
								'className' => 'fluid',
								'type'      => 'Text'
							),
							array(
								'id'        => 'msntt_plus_friends_id',
								'title'     => __( '플러스친구 ID', 'mshop-naver-talktalk' ),
								'className' => 'fluid',
								'type'      => 'Text',
								"desc2"     => __( '<div class="desc2">플러스친구 홈 URL에 명시된 id로 설정합니다.</div>', 'mshop-naver-talktalk' ),
							)
						)
					),
					array(
						'type'     => 'Section',
						'title'    => __( '상품 상세 페이지 설정', 'mshop-naver-talktalk' ),
						'elements' => array(
							array(
								'id'        => 'msntt_use_plus_friends_product_talk',
								'title'     => __( '플러스친구 1:1 채팅하기 활성화', 'mshop-naver-talktalk' ),
								'className' => '',
								'type'      => 'Toggle',
								'default'   => 'no'
							),
							array(
								'id'        => 'msntt_plus_friends_product_talk_button_type',
								'title'     => __( '버튼 타입', 'mshop-naver-talktalk' ),
								'showIf'    => array( 'msntt_use_plus_friends_product_talk' => 'yes' ),
								'className' => '',
								'type'      => 'Select',
								'default'   => 'default',
								"options"   => array(
									"default" => __( "기본이미지", 'mshop-naver-talktalk' ),
									"custom"  => __( "커스텀이미지", 'mshop-naver-talktalk' )
								)
							),
							array(
								'id'        => 'msntt_plus_friends_product_talk_type',
								'title'     => __( '버튼 타입', 'mshop-naver-talktalk' ),
								'showIf'    => array( array( 'msntt_use_plus_friends_product_talk' => 'yes' ), array( 'msntt_plus_friends_product_talk_button_type' => 'default' ) ),
								'className' => '',
								'type'      => 'Select',
								'default'   => 'consult',
								"options"   => array(
									"consult"  => __( "톡상담", 'mshop-naver-talktalk' ),
									"question" => __( "톡문의", 'mshop-naver-talktalk' )
								)
							),
							array(
								'id'          => 'msntt_plus_friends_product_talk_size',
								'title'       => __( '버튼 크기', 'mshop-naver-talktalk' ),
								'showIf'      => array( array( 'msntt_use_plus_friends_product_talk' => 'yes' ), array( 'msntt_plus_friends_product_talk_button_type' => 'default' ) ),
								'className'   => '',
								'type'        => 'Select',
								'default'     => 'small',
								'placeHolder' => __( "버튼 크기를 선택하세요.", 'mshop-naver-talktalk' ),
								"options"     => array(
									"small" => __( "Small", 'mshop-naver-talktalk' ),
									"large" => __( "Large", 'mshop-naver-talktalk' )
								)
							),
							array(
								'id'        => 'msntt_plus_friends_product_talk_color',
								'title'     => __( '버튼 색상', 'mshop-naver-talktalk' ),
								'showIf'    => array( array( 'msntt_use_plus_friends_product_talk' => 'yes' ), array( 'msntt_plus_friends_product_talk_button_type' => 'default' ) ),
								'className' => '',
								'type'      => 'Select',
								'default'   => 'yellow',
								"options"   => array(
									"yellow" => __( "Yellow", 'mshop-naver-talktalk' ),
									"mono"   => __( "Mono", 'mshop-naver-talktalk' )
								)
							),
							array(
								'id'          => 'msntt_plus_friends_product_talk_pc_image_url',
								'title'       => __( '커스텀 이미지 URL', 'mshop-naver-talktalk' ),
								'showIf'      => array( array( 'msntt_use_plus_friends_product_talk' => 'yes' ), array( 'msntt_plus_friends_product_talk_button_type' => 'custom' ) ),
								"className"   => " seven wide column fluid",
								'default'     => '',
								'type'        => 'Text',
								'placeholder' => __( 'PC에서 보여질 커스텀 이미지 URL을 입력해주세요.', 'mshop-naver-talktalk' ),
							),
							array(
								'id'          => 'msntt_plus_friends_product_talk_mobile_image_url',
								'title'       => __( '커스텀 이미지 URL', 'mshop-naver-talktalk' ),
								'showIf'      => array( array( 'msntt_use_plus_friends_product_talk' => 'yes' ), array( 'msntt_plus_friends_product_talk_button_type' => 'custom' ) ),
								"className"   => " seven wide column fluid",
								'default'     => '',
								'type'        => 'Text',
								'placeholder' => __( '모바일에서 보여질 커스텀 이미지 URL을 입력해주세요.', 'mshop-naver-talktalk' ),
							),
							array(
								'id'          => 'msntt_plus_friends_product_talk_hook',
								'title'       => __( '상품 상세 페이지 배너 위치', 'mshop-naver-talktalk' ),
								'showIf'      => array( 'msntt_use_plus_friends_product_talk' => 'yes' ),
								"className"   => " seven wide column fluid",
								'default'     => 'woocommerce_product_meta_start',
								'type'        => 'Text',
								'placeholder' => __( '상품 상세 페이지의 버튼 위치를 지정합니다.', 'mshop-naver-talktalk' ),
								"desc2"       => __( '<div class="desc2">테마 구조에 따라 카카오플러스 친구 1:1 채팅하기 버튼의 위치가 달라집니다. 테마 파일을 참고해서 위치를 지정하세요.<br>ex) 테마 파일 내 do_action( \'woocommerce_product_meta_start\' ); 코드가 있는 위치에 버튼을 출력하려면, woocommerce_product_meta_start 을 입력합니다.</div>', 'mshop-naver-talktalk' ),
							)
						)
					),
					array(
						'type'     => 'Section',
						'title'    => __( '고정형 버튼 설정', 'mshop-naver-talktalk' ),
						'elements' => array(
							array(
								'id'        => 'msntt_use_plus_friends_global_talk',
								'title'     => __( '플러스친구 1:1 채팅하기 활성화', 'mshop-naver-talktalk' ),
								'className' => '',
								'type'      => 'Toggle',
								'default'   => 'no',
								'desc'      => __( '모든 페이지 우측 하단에 플러스친구 1:1 채팅하기 버튼을 표시합니다.', 'mshop-naver-talktalk' )
							),
							array(
								'id'        => 'msntt_plus_friends_global_talk_button_type',
								'title'     => __( '버튼 타입', 'mshop-naver-talktalk' ),
								'showIf'    => array( 'msntt_use_plus_friends_global_talk' => 'yes' ),
								'className' => '',
								'type'      => 'Select',
								'default'   => 'default',
								"options"   => array(
									"default" => __( "기본이미지", 'mshop-naver-talktalk' ),
									"custom"  => __( "커스텀이미지", 'mshop-naver-talktalk' )
								)
							),
							array(
								'id'        => 'msntt_plus_friends_global_talk_type',
								'title'     => __( '버튼 타입', 'mshop-naver-talktalk' ),
								'showIf'    => array( array( 'msntt_use_plus_friends_global_talk' => 'yes' ), array( 'msntt_plus_friends_global_talk_button_type' => 'default' ) ),
								'className' => '',
								'type'      => 'Select',
								'default'   => 'consult',
								"options"   => array(
									"consult"  => __( "톡상담", 'mshop-naver-talktalk' ),
									"question" => __( "톡문의", 'mshop-naver-talktalk' )
								)
							),
							array(
								'id'          => 'msntt_plus_friends_global_talk_size',
								'title'       => __( '버튼 크기', 'mshop-naver-talktalk' ),
								'showIf'      => array( array( 'msntt_use_plus_friends_global_talk' => 'yes' ), array( 'msntt_plus_friends_global_talk_button_type' => 'default' ) ),
								'className'   => '',
								'type'        => 'Select',
								'default'     => 'small',
								'placeHolder' => __( "버튼 크기를 선택하세요.", 'mshop-naver-talktalk' ),
								"options"     => array(
									"small" => __( "Small", 'mshop-naver-talktalk' ),
									"large" => __( "Large", 'mshop-naver-talktalk' )
								)
							),
							array(
								'id'        => 'msntt_plus_friends_global_talk_color',
								'title'     => __( '버튼 색상', 'mshop-naver-talktalk' ),
								'showIf'    => array( array( 'msntt_use_plus_friends_global_talk' => 'yes' ), array( 'msntt_plus_friends_global_talk_button_type' => 'default' ) ),
								'className' => '',
								'type'      => 'Select',
								'default'   => 'yellow',
								"options"   => array(
									"yellow" => __( "Yellow", 'mshop-naver-talktalk' ),
									"mono"   => __( "Mono", 'mshop-naver-talktalk' )
								)
							),
							array(
								'id'          => 'msntt_plus_friends_global_talk_pc_image_url',
								'title'       => __( '커스텀 이미지 URL', 'mshop-naver-talktalk' ),
								'showIf'      => array( array( 'msntt_use_plus_friends_global_talk' => 'yes' ), array( 'msntt_plus_friends_global_talk_button_type' => 'custom' ) ),
								"className"   => " seven wide column fluid",
								'default'     => '',
								'type'        => 'Text',
								'placeholder' => __( 'PC에서 보여질 커스텀 이미지 URL을 입력해주세요.', 'mshop-naver-talktalk' ),
							),
							array(
								'id'          => 'msntt_plus_friends_global_talk_mobile_image_url',
								'title'       => __( '커스텀 이미지 URL', 'mshop-naver-talktalk' ),
								'showIf'      => array( array( 'msntt_use_plus_friends_global_talk' => 'yes' ), array( 'msntt_plus_friends_global_talk_button_type' => 'custom' ) ),
								"className"   => " seven wide column fluid",
								'default'     => '',
								'type'        => 'Text',
								'placeholder' => __( '모바일에서 보여질 커스텀 이미지 URL을 입력해주세요.', 'mshop-naver-talktalk' ),
							),
						)
					)
				)
			);
		}

        static function get_manual_link() {
            return array(
                'type'     => 'Page',
                'class'    => 'manual_link',
                'title'    => __( '매뉴얼', 'mshop-naver-talktalk' ),
                'elements' => array()
            );
        }

		static function enqueue_scripts() {
			wp_enqueue_style( 'mshop-setting-manager', MSNTT()->plugin_url() . '/includes/admin/setting-manager/css/setting-manager.min.css' );
			wp_enqueue_script( 'mshop-setting-manager', MSNTT()->plugin_url() . '/includes/admin/setting-manager/js/setting-manager.min.js', array(
				'jquery',
				'jquery-ui-core'
			) );
		}

		public static function output() {
			require_once MSNTT()->plugin_path() . '/includes/admin/setting-manager/mshop-setting-helper.php';

			self::enqueue_scripts();

			$settings = self::get_setting_fields();

			wp_localize_script( 'mshop-setting-manager', 'mshop_setting_manager', array(
				'element'  => 'mshop-setting-wrapper',
				'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				'action'   => MSNTT()->slug() . '-update_settings',
				'settings' => $settings
			) );

			?>
            <script>
                jQuery( document ).ready( function ( $ ) {
                    $( this ).trigger( 'mshop-setting-manager', ['mshop-setting-wrapper', '100', <?php echo json_encode( MSNTT_SettingHelper::get_settings( $settings ) ); ?>, null, null] );
                    $( '.ui.top.attached .manual_link' ).off( 'click' ).on( 'click', function () {
                        window.open( 'https://manual.codemshop.com/docs/social_talk/', '_blank' );
                        e.preventDefault();
                        e.stopPropagation();
                    } )
                } );
            </script>

            <div id="mshop-setting-wrapper"></div>
			<?php
		}
	}

endif;

return new MSNTT_Settings();
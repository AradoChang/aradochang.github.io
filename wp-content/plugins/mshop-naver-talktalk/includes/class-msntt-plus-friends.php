<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'MSNTT_Plus_Friends' ) ) :

	class MSNTT_Plus_Friends {
		static $output_script = true;

		public static function init() {

			if ( 'yes' == get_option( 'msntt_use_plus_friends_product_talk' ) ) {
				$hook = get_option( 'msntt_plus_friends_product_talk_hook', 'woocommerce_product_meta_start' );
				add_action( $hook, array( __CLASS__, 'output_chat_button' ) );
			}

			if ( 'yes' == get_option( 'msntt_use_plus_friends_global_talk' ) ) {
				add_action( 'wp_footer', array( __CLASS__, 'output_global_chat_button' ) );
			}

			add_shortcode( 'msntt_add_plus_friends', array( __CLASS__, 'add_plus_friends' ) );
			add_shortcode( 'msntt_add_plus_talk', array( __CLASS__, 'add_plus_talk' ) );
		}

		public static function maybe_enqueue_script() {
			if ( self::$output_script ) {
				wp_enqueue_script( 'mshop-kakao', '//developers.kakao.com/sdk/js/kakao.min.js', array(), MSHOP_NAVER_TALKTALK_VERSION );
			}
        }
		public static function output_chat_button() {
			if ( ! defined( 'ICL_LANGUAGE_CODE' ) || 'ko' == ICL_LANGUAGE_CODE ) {
				$type  = get_option( 'msntt_plus_friends_product_talk_type', 'consult' );
				$size  = get_option( 'msntt_plus_friends_product_talk_size', 'small' );
				$color = get_option( 'msntt_plus_friends_product_talk_color', 'yellow' );

				if ( 'default' == get_option( 'msntt_plus_friends_product_talk_button_type', 'default' ) ) {
					$button_image_url = sprintf( '%s/assets/images/plusfriend_btn/%s_%s_%s_%s.png', MSNTT()->plugin_url(), $type, $size, $color, wp_is_mobile() ? 'mobile' : 'pc' );
				} else {
					if ( wp_is_mobile() ) {
						$button_image_url = get_option( 'msntt_plus_friends_product_talk_mobile_image_url' );
					} else {
						$button_image_url = get_option( 'msntt_plus_friends_product_talk_pc_image_url' );
					}
				}

                self::maybe_enqueue_script();
                ob_start();
                ?>
                <script>
                    jQuery(document).ready(function ($) {
                        <?php if( self::$output_script ) : ?>
                        Kakao.init('<?php echo get_option('msntt_plus_friends_app_key'); ?>');
                        <?php endif; ?>
                    });
                    function plusFriendProductChat() {
                        Kakao.PlusFriend.chat({
                            plusFriendId: '<?php echo get_option('msntt_plus_friends_id');?>'
                        });
                    }
                </script>
                <?php
                $scripts = ob_get_clean();
                $scripts = trim(preg_replace('#<script[^>]*>(.*)</script>#is', '$1', $scripts));
                wp_add_inline_script('mshop-kakao', $scripts);

                ?>
                <a href="javascript:void plusFriendProductChat()">
                    <img src="<?php echo $button_image_url; ?>" alt="mshop plus friend talk" />
                </a>
                <?php
                self::$output_script = false;
            }
		}

		public static function output_plus_friend_button() {
			$type  = get_option( 'msntt_plus_friends_global_talk_type', 'consult' );
			$size  = get_option( 'msntt_plus_friends_global_talk_size', 'small' );
			$color = get_option( 'msntt_plus_friends_global_talk_color', 'yellow' );

			if ( 'default' == get_option( 'msntt_plus_friends_global_talk_button_type', 'default' ) ) {
				$button_image_url = sprintf( '%s/assets/images/plusfriend_btn/%s_%s_%s_%s.png', MSNTT()->plugin_url(), $type, $size, $color, wp_is_mobile() ? 'mobile' : 'pc' );
			} else {
				if ( wp_is_mobile() ) {
					$button_image_url = get_option( 'msntt_plus_friends_global_talk_mobile_image_url' );
				} else {
					$button_image_url = get_option( 'msntt_plus_friends_global_talk_pc_image_url' );
				}
			}
			?>
            <style>
                a.plus-friend-global-button {
                    position: fixed;
                    bottom: 0;
                    right: 0;
                    z-index: 9999;
                }
            </style>
            <?php
            self::maybe_enqueue_script();
            ob_start();
            ?>
            <script>
                jQuery(document).ready(function ($) {
                    <?php if( self::$output_script ) : ?>
                    Kakao.init('<?php echo get_option('msntt_plus_friends_app_key'); ?>');
                    <?php endif; ?>
                });
                function  plusFriendGlobalChat() {
                    Kakao.PlusFriend.chat({
                        plusFriendId: '<?php echo get_option('msntt_plus_friends_id');?>'
                    });
                }

            </script>
            <?php
            $scripts = ob_get_clean();
            $scripts = trim(preg_replace('#<script[^>]*>(.*)</script>#is', '$1', $scripts));
            wp_add_inline_script('mshop-kakao', $scripts);

            ?>
            <a class="plus-friend-global-button" href="javascript:void plusFriendGlobalChat()">
                <img src="<?php echo $button_image_url; ?>" alt="mshop plus friend talk" />
            </a>
            <?php

            self::$output_script = false;
        }

		public static function output_global_chat_button() {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$is_active = is_plugin_active( 'woocommerce/woocommerce.php' );

			if ( ! defined( 'ICL_LANGUAGE_CODE' ) || 'ko' == ICL_LANGUAGE_CODE ) {
				if ( ! $is_active || 'no' == get_option( 'msntt_use_plus_friends_product_talk' ) ) {
					self::output_plus_friend_button();
				} elseif ( ! is_product() || 'no' == get_option( 'msntt_use_plus_friends_product_talk' ) ) {
					self::output_plus_friend_button();
				}
			}
		}

		public static function add_plus_friends( $attrs, $content = null ) {
			$params = shortcode_atts( array(
				'id'    => strtoupper( bin2hex( openssl_random_pseudo_bytes( 8 ) ) ),
				'size'  => 'small',
				'color' => 'yellow',
				'shape' => 'round',
				'label' => ''
			), $attrs );

			self::maybe_enqueue_script();
            ob_start();
			?>
            <script>
                jQuery(document).ready(function ( $ ) {
		            <?php if( self::$output_script ) : ?>
                    Kakao.init( '<?php echo get_option( 'msntt_plus_friends_app_key' ); ?>' );
		            <?php endif; ?>
                });

                function kakao_<?php echo $params['id']; ?> () {
                    Kakao.PlusFriend.addFriend( {
                        plusFriendId: '<?php echo get_option( 'msntt_plus_friends_id' );?>'
                    } );
                }
            </script>
            <?php
            $scripts = ob_get_clean();
			$scripts = trim( preg_replace( '#<script[^>]*>(.*)</script>#is', '$1', $scripts ) );
			wp_add_inline_script( 'mshop-kakao', $scripts );

			ob_start();

			$button_image_url = sprintf( '%s/assets/images/plusfriend_btn/friendadd_%s_%s_%s.png', MSNTT()->plugin_url(), $params['size'], $params['color'], $params['shape'] );
			?>
            <a class="<?php echo $params['id']; ?>" href="javascript:void kakao_<?php echo $params['id']; ?>()">
				<?php if ( empty( $params['label'] ) ) : ?>
                    <img src="<?php echo $button_image_url; ?>" alt="mshop plus friend talk" />
				<?php else: ?>
					<?php echo $params['label']; ?>
				<?php endif; ?>
            </a>
			<?php
			self::$output_script = false;

			return ob_get_clean();
		}

		public static function add_plus_talk( $attrs, $content = null ) {
			$params = shortcode_atts( array(
				'id'    => strtoupper( bin2hex( openssl_random_pseudo_bytes( 8 ) ) ),
				'type'  => 'consult',
				'size'  => 'small',
				'color' => 'yellow',
				'label' => ''
			), $attrs );

			self::maybe_enqueue_script();
			ob_start();
			?>
            <script>
                jQuery(document).ready(function ( $ ) {
		            <?php if( self::$output_script ) : ?>
                    Kakao.init( '<?php echo get_option( 'msntt_plus_friends_app_key' ); ?>' );
		            <?php endif; ?>
                });

                function kakao_<?php echo $params['id']; ?> () {
                    Kakao.PlusFriend.chat( {
                        plusFriendId: '<?php echo get_option( 'msntt_plus_friends_id' );?>'
                    } );
                }
            </script>
			<?php
			$scripts = ob_get_clean();
			$scripts = trim( preg_replace( '#<script[^>]*>(.*)</script>#is', '$1', $scripts ) );
			wp_add_inline_script( 'mshop-kakao', $scripts );

			ob_start();

			$button_image_url = sprintf( '%s/assets/images/plusfriend_btn/%s_%s_%s_%s.png', MSNTT()->plugin_url(), $params['type'], $params['size'], $params['color'], wp_is_mobile() ? 'mobile' : 'pc' );

			?>
			<a class="<?php echo $params['id']; ?>" href="javascript:void kakao_<?php echo $params['id']; ?>()">
				<?php if ( empty( $params['label'] ) ) : ?>
                    <img src="<?php echo $button_image_url; ?>" alt="mshop plus friend talk" />
				<?php else: ?>
					<?php echo $params['label']; ?>
				<?php endif; ?>
            </a>
			<?php
			self::$output_script = false;

			return ob_get_clean();
		}
	}

endif;

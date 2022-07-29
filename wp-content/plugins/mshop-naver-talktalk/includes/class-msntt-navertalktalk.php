<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MSNTT_Navertalktalk' ) ) {

	class MSNTT_Navertalktalk {
		static function init() {
			add_action( 'wp_footer', array ( __CLASS__, 'mshop_open_graph_code' ), 1 );
//			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'mshop_naver_talktalk_script' ), 1 );

			if ( get_option( 'msntt_product_naver_talktalk', 'no' ) == 'yes' ) {
				add_action( get_option( 'msntt_product_naver_talktalk_button', 'woocommerce_product_meta_start' ), array (
					__CLASS__,
					'mshop_naver_talktalk_code'
				) );
			}
		}
		public static function mshop_naver_talktalk_code() {
			if ( ! defined( 'ICL_LANGUAGE_CODE' ) || 'ko' == ICL_LANGUAGE_CODE ) {
				if ( 'yes' == get_option( 'msntt_product_naver_talktalk', 'no' ) ) {
					global $post;

					$pc_button_id     = get_option( 'msntt_pc_product_key' );
					$mobile_button_id = get_option( 'msntt_mobile_product_key' );

					if ( ! empty( $pc_button_id ) || ! empty( $mobile_button_id ) ) {
						wp_enqueue_script( 'msntt', MSNTT()->plugin_url() . '/assets/js/frontend.js', array( 'jquery' ), MSNTT()->version );
						wp_localize_script( 'msntt', '_msntt', array(
							'pc_button_id'     => $pc_button_id,
							'mobile_button_id' => $mobile_button_id,
							'product_url'      => urlencode( get_permalink( $post->ID ) )
						) );

						wp_enqueue_script( 'msntt_banner', 'https://partner.talk.naver.com/banners/script', array(
							'jquery',
							'msntt'
						) );

						ob_start();
						include( MSNTT()->plugin_path() . '/includes/templates/naver_talktalk_button.php' );
						echo ob_get_clean();
					}
				}
			}
		}
		public static function mshop_open_graph_code() {
			if ( 'yes' == get_option( 'msntt_product_naver_talktalk', 'no' ) && function_exists( 'is_product' ) && is_product() ) {
				ob_start();
				include( MSNTT()->plugin_path() . '/includes/templates/open_graph.php' );
				echo ob_get_clean();
			}
		}
		public static function mshop_naver_talktalk_script() {
			if ( 'yes' == get_option( 'msntt_product_naver_talktalk', 'no' )  ) {
				wp_register_script( 'naver_talk_talk_script', 'https://partner.talk.naver.com/banners/script' );
				wp_enqueue_script( 'naver_talk_talk_script', 'https://partner.talk.naver.com/banners/script', array (
					'jquery',
					'naver_talktalk'
				) );
			}

		}
	}

	MSNTT_Navertalktalk::init();
}
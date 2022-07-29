<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class MSNTT_Shortcodes {
	public static function init() {
		$shortcodes = array (
			'mshop_naver_talktalk' => __CLASS__ . '::mshop_naver_talktalk',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( $shortcode, $function );
		}
	}
	public static function mshop_naver_talktalk( $attrs ) {
		$params = shortcode_atts( array (
			'product_id' => '',
			'banner_id'  => '',
		), $attrs );

		$naver_talktalk = $params['banner_id'];

		if ( 'yes' != get_option( 'msntt_naver_talktalk_banner', 'no' ) ) {
			wp_register_script( 'naver_talk_talk_script', 'https://partner.talk.naver.com/banners/script' );
			wp_enqueue_script( 'naver_talk_talk_script', 'https://partner.talk.naver.com/banners/script' );
		}

		ob_start();

		if ( ! empty( $naver_talktalk ) ) {
			if ( empty( $params['product_id'] ) ) {
				echo "<div class=\"talk_banner_div\" data-id=\"$naver_talktalk\"></div>";
			} else {
				$url = urlencode( get_permalink( $params['product_id'] ) );
				echo "<div class=\"talk_banner_div\" data-id=\"$naver_talktalk\" data-ref=\"$url\" ></div>";
			}
		}

		return ob_get_clean();
	}
}
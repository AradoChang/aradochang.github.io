<?php
/*
Plugin Name: 코드엠샵 소셜톡 - 네이버톡톡, 카톡상담
Plugin URI: 
Description: 쇼핑몰의 회원 확보와 매출 증대를 위해 고객이 가장 익숙한 네이버와 카톡상담으로 고객과의 소통을 진행 해 보세요.
Version: 1.1.12
Author: CodeMShop
Author URI: www.codemshop.com
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'MShop_Naver_TalkTalk' ) ) {

	class MShop_Naver_TalkTalk {

		protected static $_instance = null;

		protected $slug;
		public $version = '1.1.12';
		public $plugin_url;
		public $plugin_path;
		public function __construct() {
			// Define version constant
			define( 'MSHOP_NAVER_TALKTALK_VERSION', $this->version );

			$this->slug = 'mshop-naver-talktalk';

			$this->define( 'MSHOP_NAVER_TALKTALK_PLUGIN_FILE', __FILE__ );

			add_action( 'init', array ( $this, 'init' ), 0 );
			add_filter( 'plugin_row_meta', array ( $this, 'plugin_row_meta' ), 10, 4 );
			add_filter( "plugin_action_links", array ( $this, 'plugin_action_links' ), 10, 4 );
		}

		public function slug() {
			return $this->slug;
		}

		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		public function plugin_url() {
			if ( $this->plugin_url ) {
				return $this->plugin_url;
			}

			return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		public function plugin_path() {
			if ( $this->plugin_path ) {
				return $this->plugin_path;
			}

			return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		function includes() {
			if ( is_admin() ) {
				$this->admin_includes();
			}
			if ( defined( 'DOING_AJAX' ) ) {
				$this->ajax_includes();
			}
		}

		public function admin_includes() {
			include_once 'includes/admin/class-msntt-admin.php';
		}

		public function ajax_includes() {
			include_once( 'includes/class-msntt-ajax.php' );
		}

		public function init() {
			include_once 'includes/class-msntt-navertalktalk.php';
			include_once 'includes/class-msntt-shortcodes.php';
			include_once 'includes/class-msntt-banner.php';
			include_once 'includes/class-msntt-plus-friends.php';
			include_once 'includes/msntt-functions.php';

			$this->includes();

			MSNTT_Plus_Friends::init();
		}
		public function plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {
			if ( ! empty( $plugin_data['slug'] ) && $this->slug == $plugin_data['slug'] ) {
				$actions['settings'] = '<a href="' . admin_url( 'admin.php?page=mshop_social_talk_settings' ) . '">설정</a>';
				$actions['manual']   = '<a target="_blank" href="https://manual.codemshop.com/docs/social_talk/">매뉴얼</a>';
			}

			return $actions;
		}
		public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
			if ( ! empty( $plugin_data['slug'] ) && $this->slug == $plugin_data['slug'] ) {
                $plugin_meta[] = '<a target="_blank" href="https://manual.codemshop.com/docs/social_talk/faq/">FAQ</a>';
				$plugin_meta[] = '<a target="_blank" href="https://wordpress.org/plugins/mshop-naver-talktalk/#reviews">별점응원하기</a>';
				$plugin_meta[] = '<a target="_blank" href="https://wordpress.org/plugins/search/codemshop/">함께 사용하면 좋은 플러그인</a>';
			}

			return $plugin_meta;
		}

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}
	}

	function MSNTT() {
		return MShop_Naver_TalkTalk::instance();
	}

	return MSNTT();

}
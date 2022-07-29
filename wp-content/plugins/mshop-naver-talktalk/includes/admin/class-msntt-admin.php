<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'MSNTT_Admin' ) ) :

    class MSNTT_Admin {

        function __construct(){
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        }

        function admin_menu(){
            add_menu_page( __( '엠샵 소셜톡', 'mshop-naver-talktalk' ), __( '엠샵 소셜톡', 'mshop-naver-talktalk' ),'manage_options', 'mshop_social_talk_settings', array( $this, 'mshop_social_talk_settings' ), MSNTT()->plugin_url() . '/assets/images/mshop-icon.png', '20.876642' );
        }

        function mshop_social_talk_settings(){
            include_once 'settings/class-msntt-settings.php';
            MSNTT_Settings::output();
        }
    }

    return new MSNTT_Admin();

endif;

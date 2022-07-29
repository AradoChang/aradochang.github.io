<?php

/*
Plugin Name: 엘리멘토 한글폰트
Plugin URI: https://wpteam.dev
Description: 엘리멘토에 사용 가능한 한글 폰트 모음입니다.
Author: WPTEAM
Author URI: https://wpteam.dev
Version: 1.0.8
License: GNU General Public License v2.0 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

define('ELE_FONTS_KR_STORE', 'https://wpteam.dev' );
define('ELE_FONTS_KR_PLUGIN_FILE', __FILE__ );
define('ELE_FONTS_KR_SLUG', 'ele-fonts-kr' );
define('ELE_FONTS_KR_ID', 2704 );
define('ELE_FONTS_KR_VER', '1.0.8' );

require_once( 'includes/update/lisence.php' );

register_activation_hook( __FILE__, array(Ele_Fonts_Kr_Lisence::class, 'activate') );
register_deactivation_hook( __FILE__, array(Ele_Fonts_Kr_Lisence::class, 'deactivate') );

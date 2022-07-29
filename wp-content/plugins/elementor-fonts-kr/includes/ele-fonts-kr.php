<?php
add_filter( 'elementor/fonts/groups', function( $font_groups ) {
	$font_groups['wpteam_kr'] = __( 'WPTEAM.KR' );
	return $font_groups;
} );

add_filter( 'elementor/fonts/additional_fonts', function( $additional_fonts ) {
	//Font name/font group
	$additional_fonts['LAB디지털'] = 'wpteam_kr';
	// $additional_fonts['fromdamiM'] = 'wpteam_kr';
	$additional_fonts['Black Han Sans'] = 'wpteam_kr';
	$additional_fonts['잘난체'] = 'wpteam_kr';
	$additional_fonts['지마켓B'] = 'wpteam_kr';
	$additional_fonts['지마켓L'] = 'wpteam_kr';
	$additional_fonts['레코체'] = 'wpteam_kr';
	$additional_fonts['몬소리체'] = 'wpteam_kr';
	$additional_fonts['neo둥근모'] = 'wpteam_kr';
	$additional_fonts['IBMPlexSansKR-Light'] = 'wpteam_kr';
	$additional_fonts['마포꽃섬'] = 'wpteam_kr';
	$additional_fonts['마포금빛나루'] = 'wpteam_kr';
	$additional_fonts['쿠키런R'] = 'wpteam_kr';
	$additional_fonts['쿠키런B'] = 'wpteam_kr';
	$additional_fonts['교보손글씨'] = 'wpteam_kr';
	$additional_fonts['경기청년바탕'] = 'wpteam_kr';
	$additional_fonts['조선일보명조체'] = 'wpteam_kr';
	$additional_fonts['제주명조'] = 'wpteam_kr';
	$additional_fonts['리디바탕'] = 'wpteam_kr';
	$additional_fonts['어비마이센체'] = 'wpteam_kr';
	$additional_fonts['아리따부리'] = 'wpteam_kr';
	$additional_fonts['나눔고딕'] = 'wpteam_kr';
	$additional_fonts['NanumSquare'] = 'wpteam_kr';
	$additional_fonts['나눔명조'] = 'wpteam_kr';
	$additional_fonts['배민한나체pro'] = 'wpteam_kr';
	$additional_fonts['배민한나체air'] = 'wpteam_kr';
	$additional_fonts['배민을지로체'] = 'wpteam_kr';
	$additional_fonts['배민연성체'] = 'wpteam_kr';
	$additional_fonts['배민주아체'] = 'wpteam_kr';
	$additional_fonts['배민도현체'] = 'wpteam_kr';
	$additional_fonts['배민기랑해랑체'] = 'wpteam_kr';
	$additional_fonts['스포카한산스'] = 'wpteam_kr';
	$additional_fonts['코트라볼드체'] = 'wpteam_kr';
	return $additional_fonts;
} );

add_action( 'elementor/fonts/print_font_links/wpteam_kr', function( $font_name ) {
	$font_name = str_replace(' ','',$font_name);
	$file_path = plugin_dir_path(ELE_FONTS_KR_PLUGIN_FILE).'css/'.$font_name.'.css';
	$file = plugin_dir_url(ELE_FONTS_KR_PLUGIN_FILE).'css/'.$font_name.'.css';
	if (file_exists($file_path)) {
		wp_enqueue_style( $font_name.'-font', $file );
	}
} );

add_action( 'elementor/preview/enqueue_styles', function() {
$dir = plugin_dir_path(ELE_FONTS_KR_PLUGIN_FILE).'css';

$handle  = opendir($dir);

$files = array();

while (false !== ($filename = readdir($handle))) {
    if($filename == "." || $filename == ".."){
        continue;
    }

    if(is_file($dir . "/" . $filename)){
				$file = plugin_dir_url(ELE_FONTS_KR_PLUGIN_FILE).'css/'.$filename;
				wp_enqueue_style( $filename, $file );
    }
}
closedir($handle);
} );

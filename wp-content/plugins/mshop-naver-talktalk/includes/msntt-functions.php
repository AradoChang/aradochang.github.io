<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function msntt_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'msntt_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

MSNTT_Shortcodes::init();

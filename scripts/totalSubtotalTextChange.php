<?php
add_filter( 'gettext', function( $translated_text ) {
	if ( 'Subtotal' === $translated_text ) {
		$translated_text = 'Total pris';
	}
	return $translated_text;
} );


add_filter( 'gettext', function( $translated_text ) {
	if ( 'Total' === $translated_text ) {
		$translated_text = 'At betale nu';
	}
	return $translated_text;
} );
<?php
/**
 * Various small helper functions.
 *
 * @package ZF_Theme
 */
 
 /**
 * Truncate $text to a max of $chars. Finds the previous whitespace so we don't cut in the middle of a word.
 *
 * @param string $text
 * @param int $chars
 * @return string the truncated string.
 */
function zf_theme_truncate_to_whitespace( $text, $chars = 25 ) {

	$text = $text . ' ';
	$text = substr( $text, 0, $chars );
	$text = substr( $text, 0, strrpos( $text, ' ' ) );
	$text = $text . '...';

	return $text;
}

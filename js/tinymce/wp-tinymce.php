<?php
/**
 * Disable error reporting
 *
 * Set this to error_reporting( -1 ) for debugging.
 */
error_reporting(0);

$basepath = dirname(__FILE__);

function get_file($path) {

	if ( function_exists('realpath') )
		$path = realpath($path);

	if ( ! $path || ! @is_file($path) )
		return false;

	return @file_get_contents($path);
}

$expires_offset = 31536000; // 1 year

header('Content-Type: application/javascript; charset=UTF-8');
header('Vary: Accept-Encoding'); // Handle proxies
header('Expires: ' . gmdate( "D, d M Y H:i:s", time() + $expires_offset ) . ' GMT');
header("Cache-Control: public, max-age=$expires_offset");

if ( isset($_GET['c']) && 1 == $_GET['c'] && isset($_SERVER['HTTP_ACCEPT_ENCODING'])
	&& false !== stripos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') && ( $file = get_file($basepath . '/wp-tinymce.js.gz') ) ) {

	header('Content-Encoding: gzip');
	echo $file;
} else {
	// Back compat. This file shouldn't be used if this condition can occur (as in, if gzip isn't accepted).
	echo "tinyMCEPreInit.mceInit.content.toolbar1 = tinyMCEPreInit.mceInit.content.toolbar1.replaceAll(',', ' ');";
	echo "tinyMCEPreInit.mceInit.content.toolbar2 = tinyMCEPreInit.mceInit.content.toolbar2.replaceAll(',', ' ');";
	echo "tinyMCEPreInit.mceInit.content.toolbar3 = tinyMCEPreInit.mceInit.content.toolbar3.replaceAll(',', ' ');";
	echo "tinyMCEPreInit.mceInit.content.toolbar4 = tinyMCEPreInit.mceInit.content.toolbar4.replaceAll(',', ' ');";
	echo get_file( $basepath . '/tinymce.min.js' );
	echo get_file( $basepath . '/plugins/compat3x/plugin.min.js' );
	echo get_file( $basepath . '/plugins/compat4x/plugin.min.js' );
}
exit;
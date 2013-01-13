<?php
	if ( !defined('EQDKP_INC') ){
		header('HTTP/1.0 404 Not Found');exit;
	}

	global $conf_plus;

	$plang = array_merge($plang, array(
		'counter' 		=> '간단한 사용자 카운터',
		'ga-code'		=> "구글 애널리스틱 코드:"
		));
?>

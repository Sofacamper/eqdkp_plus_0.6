<?php
	if ( !defined('EQDKP_INC') ){
		header('HTTP/1.0 404 Not Found');exit;
	}

	global $conf_plus;

	$plang = array_merge($plang, array(
		'counter'								=> 'Besucherz&auml;hler',
		'ga-code'								=> 'Google Analytics Code:',
		'counter_visitors_summ'	=> 'Besucher gesamt:',
		'counter_visitors_tody'	=> 'Besucher heute:',
		'counter_pimpr_today'		=> 'Aufrufe gesamt:',
		));
?>

<?php
	if ( !defined('EQDKP_INC') ){
		header('HTTP/1.0 404 Not Found');exit;
	}

	global $conf_plus;
$plang = array_merge($plang, array(
  'advancedcounter'               		=> 'Besucherz&auml;hler',
  'ga-code'     						=> 'Google Analytics Code:',
  'portal_counter_visitors'   	 		=> 'Spieler',
  'portal_counter_visitors_total'     	=> 'Besucher gesamt:',
  'portal_counter_visitors_today'     	=> 'Besucher heute:',
  'portal_counter_visitors_yesterday'	=> 'Besucher gestern:',
  'portal_counter_visitors_per_day'     => 'max. Besucher pro Tag:',
  'portal_counter_visitors_online'     	=> 'gerade online:',
  'portal_counter_pagecalls_total'     	=> 'Seitenaufrufe gesamt:',
  'portal_counter_pagecalls_this'     	=> 'Seitenaufrufe diese Seite:'
));

?>

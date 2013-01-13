<?php
	if ( !defined('EQDKP_INC') ){
		header('HTTP/1.0 404 Not Found');exit;
	}

	global $conf_plus;
$plang = array_merge($plang, array(
  'advancedcounter'               		=> 'Visitor counter',
  'ga-code'     						=> 'Google Analytics Code:',
  'portal_counter_visitors'    			=> 'visitors',
  'portal_counter_visitors_total'     	=> 'visitors total:',
  'portal_counter_visitors_today'     	=> 'visitors today:',
  'portal_counter_visitors_yesterday'	=> 'visitors yesterday:',
  'portal_counter_visitors_per_day'     => 'max. visitors per day:',
  'portal_counter_visitors_online'     	=> 'now online:',
  'portal_counter_pagecalls_total'    	=> 'page calls total:',
  'portal_counter_pagecalls_this'     	=> 'page calls this page:'
));

?>

<?php
	if ( !defined('EQDKP_INC') ){
		header('HTTP/1.0 404 Not Found');exit;
	}

	global $conf_plus;
$plang = array_merge($plang, array(
  'advancedcounter'               		=> '�湮�� ī����',
  'ga-code'     						=> '���� �ֳθ�ƽ�� �ڵ�:',
  'portal_counter_visitors'    			=> '�湮��',
  'portal_counter_visitors_total'     	=> '��ü �湮��:',
  'portal_counter_visitors_today'     	=> '���� �湮��:',
  'portal_counter_visitors_yesterday'	=> '���� �湮��:',
  'portal_counter_visitors_per_day'     => '���� �ִ� �湮��:',
  'portal_counter_visitors_online'     	=> '���� ������:',
  'portal_counter_pagecalls_total'    	=> '��ü ������ ��:',
  'portal_counter_pagecalls_this'     	=> '���� ������ ��:'
));

?>

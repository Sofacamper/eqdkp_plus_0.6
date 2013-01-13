<?php
	if ( !defined('EQDKP_INC') ){
		header('HTTP/1.0 404 Not Found');exit;
	}

	global $conf_plus;
$plang = array_merge($plang, array(
  'advancedcounter'               		=> '방문자 카운터',
  'ga-code'     						=> '구글 애널리틱스 코드:',
  'portal_counter_visitors'    			=> '방문자',
  'portal_counter_visitors_total'     	=> '전체 방문자:',
  'portal_counter_visitors_today'     	=> '오늘 방문자:',
  'portal_counter_visitors_yesterday'	=> '어제 방문자:',
  'portal_counter_visitors_per_day'     => '일일 최대 방문자:',
  'portal_counter_visitors_online'     	=> '현재 접속중:',
  'portal_counter_pagecalls_total'    	=> '전체 페이지 뷰:',
  'portal_counter_pagecalls_this'     	=> '현재 페이지 뷰:'
));

?>

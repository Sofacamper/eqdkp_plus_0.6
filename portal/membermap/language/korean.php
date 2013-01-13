<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-07-31 05:24:40 +0900 (2009-07-31, 금) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 5387 $
 * 
 * $Id: english.php 5387 2009-07-30 20:24:40Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$plang = array_merge($plang, array(
  'membermap'             => '멤버 지도',
  'pk_membermap_no_data'  => '당신의 사용자 프로필에서 설정된 주소가 없습니다. 맴버 지도는 프로필 정보를 설정했을 경우에만 보여집니다.',
  'pk_mmp_googlekey'      => '구글 맵 API 키<br/><a href="http://code.google.com/intl/en/apis/maps/signup.html" target="blank">무료 API 키 받기</a>',
  'pk_membermap_no_gmapi' => '구글 맵 API 키를 입력하지 않았습니다. 무료로 다음주소에서 받을 수 있습니다. <a href="http://code.google.com/intl/en/apis/maps/signup.html" target="blank">받기</a>',
  'pk_membermap_window'   => '맴버 지도',
  'pk_membermap_noaccess'	=> '접근 불가, 로그인 하세요.',
  'pk_mmp_shwusername'    => '실제 이름 대신 사용자이름 보이기',
  'pk_mmp_useziptown'     => '주소 대신 우편번호 사용 (일부 국가만 적용가능)',
));
?>

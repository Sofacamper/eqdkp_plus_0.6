<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2010-02-17 08:10:15 +0100 (Wed, 17 Feb 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: Dallandros $
 * @copyright   (c) 2008 by Aderyn
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 7268 $
 *
 * $Id: korean.php 7268 2010-02-17 07:10:15Z Dallandros $
 */

if (!defined('EQDKP_INC'))
{
  header('HTTP/1.0 404 Not Found');exit;
}

$plang = array_merge($plang, array(

  // Title
  'realmstatus'           => '��������',

  //  Settings
  'rs_realm'              => '���� ��� (��ǥ�� ����)',
  'rs_realm_help'         => 'Replace whitespace with _ on servers with 2 words. Like die_todeskrallen.',
  'rs_us'                 => 'US ���� �Դϱ�?',
  'rs_us_help'            => 'This setting has only effects if WoW is set as game.',
  'rs_gd'                 => 'GD Lib �߰�. ����Ͻðڽ��ϱ�? ',
  'rs_gd_help'            => 'This setting has only effects if WoW is set as game.',

  // Portal Modul
  'rs_no_realmname'       => '������ �������� �ʾҽ��ϴ�.',
  'rs_realm_not_found'    => 'Realm not found',
  'rs_game_not_supported' => '�������°� ���� ������ �������� �ʽ��ϴ�.',
  'rs_realm_status_error' => "Errors occured while determing realmstatus for %1\$s",
));

?>

<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2008-08-08 15:16:22 +0200 (Fr, 08 Aug 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 2503 $
 * 
 * $Id: init_vars.php 2503 2008-08-08 13:16:22Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

function cm_tooltip($membe){
  global $user, $rpclass, $CharTools, $memisop, $conf;
  
  $myskills = ($membe['skill_1']['data'] or $membe['skill_2']['data'] or $membe['skill_3']['data']) ? '<b>'.$membe['skill_1']['data'].'</b>-<b>'.$membe['skill_2']['data'].'</b>-<b>'.$membe['skill_3']['data'].'</b>' : '--';
  
  $uc_tooltip = $user->lang['uc_tab_skills'].': <b>'.$CharTools->SkilltoSpec(array($membe['skill_1']['data'],$membe['skill_2']['data'],$membe['skill_3']['data']), $membe['class_name']).'</b>';
  $uc_tooltip .= '&nbsp;('.$rpclass->DataOrNull($myskills, false).')<br/>';
  if($user->check_auth('a_raidplan_update', false) || is_array($memisop)){
    if($attendee['cm_notes'] && $conf['rp_disable_cmnotes'] != 1){
      $uc_tooltip .= '<br/><b>'.$user->lang['uc_cm_notes'].'</b><br/>';
      $uc_tooltip .= $attendee['cm_notes'].'<br/>';
    }
  }
  return $uc_tooltip;
}

?>

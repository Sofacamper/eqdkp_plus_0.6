<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2011-02-09 14:49:26 +0100 (Wed, 09 Feb 2011) $
 * -----------------------------------------------------------------------
 * @author      $Author: Godmod $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 9691 $
 * 
 * $Id: logs.php 9691 2011-02-09 13:49:26Z Godmod $
 */

define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../../';
include_once('../includes/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }

// Check user permission
$user->check_auth('a_raidplan_logs');

  $sort_order = array(
      0 => array('log_date desc', 'log_date'),
      1 => array('log_tag', 'log_tag desc'),
      2 => array('username', 'username dsec'),
      3 => array('log_result', 'log_result desc'),
      4 => array('log_attachto', 'log_attachto desc'),
  );
  
  $current_order = switch_order($sort_order);

  // Obtain var settings
  $log_id = ( !empty($_GET['logid']) ) ? intval($_REQUEST['logid']) : false;
  $action = ( $log_id ) ? 'view' : 'list';
  
  // Delete old...
  if($_POST['rp_dellogdays']){
    $logmanager->Clean($_POST['rp_dellogdays']);
  }
  
  switch ( $action ){
    case 'view':
    
      // Get log info
        $tmplog = $logmanager->GetList($log_id);
        $log = $tmplog[$log_id];
        
        eval($log['value']);
        
        if ( !empty($log_action['header']) ){
          $log_header = $logmanager->lang_replace($log_action['header']);
        }

        foreach ( $log_action as $k => $v ){
          if ( $k != 'header' ){
            $tpl->assign_block_vars('log_row', array(
                    'ROW_CLASS'   => $eqdkp->switch_row_class(),
                    'KEY'         => $logmanager->lang_replace(stripslashes($k)).':',
                    'VALUE'       => $logmanager->lang_replace(stripslashes($v)))
            );
          }
        }

        $tpl->assign_vars(array(
            'S_LIST'          => false,

            'L_LOG_VIEWER'    => $user->lang['viewlogs_title'],
            'L_DATE'          => $user->lang['date'],
            'L_USERNAME'      => $user->lang['username'],
            'L_IP_ADDRESS'    => $user->lang['ip_address'],
            'L_SESSION_ID'    => $user->lang['session_id'],

            'LOG_DATE'        => ( !empty($log['date']) ) ? date($user->style['date_time'], $log['date']) : '&nbsp;',
            'LOG_USERNAME'    => ( !empty($log['user']) ) ? $log['user'] : '&nbsp;',
            'LOG_IP_ADDRESS'  => $log['ip'],
            'LOG_SESSION_ID'  => $log['sid'],
            'LOG_ACTION'      => ( !empty($log_header) ) ? $log_header : '&nbsp;')
        );
        
      break;
    case 'list':
      // Array with raid information
      $rpsql = 'SELECT raid_id, raid_name, raid_date, raid_note
                FROM ' . RP_RAIDS_TABLE . ';';
      $rpraids_result = $db->query($rpsql);
      while ($rprow = $db->fetch_record($rpraids_result)){
        $raidinfo[$rprow['raid_id']] = array(
            'name'    => $rprow['raid_name'],
            'date'    => $rprow['raid_date'],
            'note'    => $rprow['raid_note'],
        );
      }
    
      // the char addition Data
      $lgtmarray = $logmanager->GetList();
      if(is_array($lgtmarray)){
        foreach($lgtmarray as $row){
    		  $tpl->assign_block_vars('logs_row', array(
    		          'ROW_CLASS' 			=> $eqdkp->switch_row_class(),
    		          'U_VIEW_LOG'      => 'logs.php?logid='.$row['id'],
    		          
    				      'DATE'            => $stime->DoDate($conf['timeformats']['medium'], $row['date']),
    				      'RESULT'          => $row['result'],
    				      'USER'            => $row['user'],
    				      'TAG'             => $row['tag'],
    				      'RAID_NAME'       => $raidinfo[$row['attached']]['name'],
    				      'RAID_DATE'       => $stime->DoDate($conf['timeformats']['medium'], $raidinfo[$row['attached']]['date']),
    				      'C_RESULT'        => ($row['result'] == $user->lang['success']) ? 'positive' : 'negative',
            ));
    		  }
    		}
        $db->free_result($result);
        
        $start			= ( isset($_GET['start']) ) ? $_GET['start'] : 0;
        $total_logs		= $logmanager->logs_total();
        $tpl->assign_vars(array(
                  'S_LIST'        => true,
                  'F_DELLOG'      => 'logs.php'.$SID,
                  'RPDELLOGDAYS'  => '30',
                  
                  'L_USER'        => $user->lang['user'],
                  'L_DATE'        => $user->lang['date'],
                  'L_ATTACHED'    => $user->lang['rp_log_attached'],
                  'L_RESULT'      => $user->lang['result'],
                  'L_RAIDINFO'    => $user->lang['rp_log_raidinfo'],
                  'L_TAG'         => $user->lang['rp_log_tag'],
                  'L_DELETE'      => $user->lang['rp_log_deleteb'],
                  'L_DEL_LOG'     => $user->lang['rp_log_delete'],
                  'L_DAYS'        => $user->lang['rp_log_days'],
                  
                  'O_DATE'        => $current_order['uri'][0],
                  'O_TAG'         => $current_order['uri'][1],
                  'O_USER'        => $current_order['uri'][2],
                  'O_RESULT'      => $current_order['uri'][3],
                  'O_ATTACHED'    => $current_order['uri'][4],
                  
                  'U_LOGS'        => 'logs.php'.$SID.'&amp;start='.$start.'&amp;',
                  
                  'VL_FOOTCOUNT'  => sprintf($user->lang['viewlogs_footcount'], $total_logs, 100),
                  'VL_PAGINATION' => generate_pagination('logs.php'.$SID.'&amp;o='.$current_order['uri']['current'], $total_logs, '100', $start)
    		));
      break;
}  
    $eqdkp->set_vars(array(
	    'page_title'             => $rpclass->GeneratePageTitle($user->lang['viewlogs_title']),
			'template_path' 	       => $pm->get_data('raidplan', 'template_path'),
			'template_file'          => 'admin/logs.html',
			'display'                => true)
    );  
?>

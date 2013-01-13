<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-07-04 10:53:00 +0200 (Sa, 04 Jul 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 5156 $
 * 
 * $Id: admin_tasks.php 5156 2009-07-04 08:53:00Z wallenium $
 */

define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = './../../../';
include_once('../include/common.php');

$user->check_auth('a_charmanager_');

// Delete Char
if($in->get('delete_id', false)){
	$CharTools->DeleteChar($in->get('delete_id'));
	echo "<script>parent.window.location.href = 'admin_tasks.php';</script>";
}

// Rewoke Char
if($in->get('rewoke_id', false)){
	$CharTools->RewokeChar($in->get('rewoke_id'));
	echo "<script>parent.window.location.href = 'admin_tasks.php';</script>";
}

// Confirm Char
if($in->get('confirm_id', false)){
	$CharTools->ConfirmChar($in->get('confirm_id'));
	echo "<script>parent.window.location.href = 'admin_tasks.php';</script>";
}

// Confirm all Chars
if($in->get('confirmall', false)){
	$CharTools->ConfirmAllChars();
	echo "<script>parent.window.location.href = 'admin_tasks.php';</script>";
}

// Delete all Chars
if($in->get('deleteall', false)){
	$CharTools->DeleteAllChars();
	echo "<script>parent.window.location.href = 'admin_tasks.php';</script>";
}
	
// Build member drop-down
$sort_order = array(
    0 => array('member_name', 'member_name desc'),
    6 => array('member_level desc', 'member_level'),
    7 => array('member_class', 'member_class desc')
);

$current_order = switch_order($sort_order);

$member_count = 0;
$sort_index = explode('.', $current_order['uri']['current']);
$previous_source = preg_replace('/( (asc|desc))?/i', '', $sort_order[$sort_index[0]][$sort_index[1]]);               

// The Confirm Member Part
$sql = "SELECT m.member_id, m.member_name, m.member_level, c.class_name, 
        m.member_class_id, m.member_race_id
        FROM __classes c, __members m
        LEFT JOIN __member_additions ma ON ma.member_id = m.member_id
        WHERE m.member_class_id = c.class_id
        AND ma.require_confirm = '1'
        AND (ma.requested_del = '0' OR ma.requested_del IS NULL)
        ORDER BY m.member_name";
$members_result = $db->query($sql);
while ( $row = $db->fetch_record($members_result) ){
	$member_count++;
	$tpl->assign_block_vars('confirm_row', array(
        'ROW_CLASS'     => $eqdkp->switch_row_class(),
        'ID'            => $row['member_id'],
        'CLASSID'       => $row['member_class_id'],
        'COUNT'         => $member_count,
        'NAME'          => $row['member_name'],
        'LEVEL'         => $row['member_level'],
        'CLASS_ICON'		=> get_classImgListmembers($row['class_name'],$row['member_class_id']),
        'RACE_ICON'     => get_RaceIcon($row['member_race_id'],$row['member_name']),
        )
    );
}
$footcount_confirm = sprintf($user->lang['listmembers_footcount'], $db->num_rows($members_result));
$db->free_result($members_result);

// The Delete Member Part
$sql = "SELECT m.member_id, m.member_name, m.member_level, c.class_name, 
        m.member_class_id, m.member_race_id
        FROM __classes c, __members m
        LEFT JOIN __member_additions ma ON ma.member_id = m.member_id
        WHERE m.member_class_id = c.class_id
        AND ma.requested_del = '1'
        ORDER BY m.member_name";
$members_result = $db->query($sql);
while ( $row = $db->fetch_record($members_result) ){
	$member_count++;
	$tpl->assign_block_vars('delete_row', array(
        'ROW_CLASS'     => $eqdkp->switch_row_class(),
        'ID'            => $row['member_id'],
        'CLASSID'       => $row['member_class_id'],
        'COUNT'         => $member_count,
        'NAME'          => $row['member_name'],
        'LEVEL'         => $row['member_level'],
        'CLASS_ICON'		=> get_classImgListmembers($row['class_name'],$row['member_class_id']),
        'RACE_ICON'     => get_RaceIcon($row['member_race_id'],$row['member_name']),
        )
    );
}
$footcount_delete = sprintf($user->lang['listmembers_footcount'], $db->num_rows($members_result));
$db->free_result($members_result);

  $tpl->assign_vars(array(
    'F_UPDATE'        => 'admin_tasks.php',

    'DELETE_CHARS'		=> ( $user->check_auth('u_charmanager_delete', false)) ? true : false,
    'JS_DELETECHAR'		=> $jquery->Dialog_Confirm('DeleteChar', $user->lang['uc_del_warning'], "document.data.delete_id.value=v;document.data.submit();", "editid"),
    'JS_DELETEALL'		=> $jquery->Dialog_Confirm('DeleteAllChars', $user->lang['uc_del_msg_all'], "document.data.deleteall.value='true';document.data.submit();"),
    'JS_CONFIRMALL'		=> $jquery->Dialog_Confirm('ConfirmAllChars', $user->lang['uc_confirm_msg_all'], "document.data.confirmall.value='true';document.data.submit();"),
    
    'L_NAME' 					=> $user->lang['name'],
    'L_LEVEL' 				=> $user->lang['level'],
    'L_CLASS' 				=> $user->lang['class'],
    'L_ARMOR'         => $user->lang['armor'],
    'L_EDIT_CHARS' 		=> $user->lang['uc_edit_char'],
    'L_DELETE_CHARS'	=> $user->lang['uc_delete_char'],
    'L_DEL_ALLCHARS'	=> $user->lang['uc_delete_allchar'],
    'L_REWOKE_CHARS'	=> $user->lang['uc_rewoke_char'],
    'L_CONFIRM_CHARS'	=> $user->lang['uc_confirm_char'],
    'L_CNFRM_ALLCHRS'	=> $user->lang['uc_confirm_allchar'],
    'L_CONFIRM_LIST'	=> $user->lang['uc_confirm_list'],
    'L_DELETE_LIST'		=> $user->lang['uc_delete_list'],
    
    'O_NAME' 					=> $current_order['uri'][0],
    'O_LEVEL' 				=> $current_order['uri'][6],
    'O_CLASS' 				=> $current_order['uri'][7],
    'O_ARMOR'      		=> $current_order['uri'][9],
    'FC_DELETE'    		=> $footcount_delete,
    'FC_CONFIRM'			=> $footcount_confirm,
    )
);

$eqdkp->set_vars(array(
    'page_title'    => $CharTools->GeneratePageTitle($user->lang['uc_delete_manager']),
    'template_file' => 'admin/delete_manager.html',
    'template_path' => $pm->get_data('charmanager', 'template_path'),
    'display'       => true)
);
?>
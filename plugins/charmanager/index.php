<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-12-20 11:35:56 +0100 (So, 20. Dez 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 6779 $
 * 
 * $Id: index.php 6779 2009-12-20 10:35:56Z wallenium $
 */

define('EQDKP_INC', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = './../../';
include_once('include/common.php');

if (!$user->check_auth('u_charmanager_manage', false) && !$user->check_auth('u_charmanager_add', false)) {
	 message_die($user->lang['uc_no_prmissions']);
}

if (!$pm->check(PLUGIN_INSTALLED, 'charmanager')) { message_die($user->$lang['uc_not_installed']); }
if ($user->data['username']=="") { message_die($user->lang['uc_not_loggedin']); }
$raidplan = $pm->get_plugin('charmanager');

// save Connection
if(isset($_POST['member_id']) && is_array($_POST['member_id'])){	
  $CharTools->updateConnection($in->getArray('member_id', 'int'));	
  echo "<script>parent.window.location.href = 'index.php';</script>";
}

// Delete Char
if($user->check_auth('u_charmanager_delete', false) && $in->get('delete_id', 0) > 0){
	$CharTools->SuspendChar($in->get('delete_id', 0));
	echo "<script>parent.window.location.href = 'index.php';</script>";
}
	
// Build member drop-down
$sort_order = array(
    0 => array('member_name', 'member_name desc'),
    1 => array('member_level desc', 'member_level'),
    2 => array('member_class', 'member_class desc'),
    3 => array('member_rank', 'member_rank desc'),
    3 => array('guild', 'guild desc')
);

$current_order = switch_order($sort_order);

$member_count = 0;
$sort_index = explode('.', $current_order['uri']['current']);
$previous_source = preg_replace('/( (asc|desc))?/i', '', $sort_order[$sort_index[0]][$sort_index[1]]);               

$sql = "SELECT m.*, ma.*, c.class_name AS member_class, m.member_id as myid,
        c.class_armor_type AS armor_type, r.rank_name
        FROM __classes c, __member_ranks r, __members m
        LEFT JOIN __member_user mu ON mu.member_id = m.member_id
        LEFT JOIN __member_additions ma ON ma.member_id = m.member_id
        WHERE mu.user_id = ".$db->sql_escape($user->data['user_id'])."
        AND (m.member_class_id = c.class_id)
        AND (m.member_rank_id = r.rank_id)
        AND (ma.requested_del != '1' OR ma.requested_del IS NULL)
        GROUP BY m.member_name
        ORDER BY m.member_name";
if ( !($members_result = $db->query($sql)) )
{
    message_die($user->lang['uc_error_memberinfos'], '', __FILE__, __LINE__, $sql);
}

while ( $row = $db->fetch_record($members_result) ){
	$last_update = ($row['last_update']) ? date($user->lang['uc_changedate'],$row['last_update']) : '--';
	$spec1 = get_wow_talent_spec(ucwords(renameClasstoenglish($row['member_class'])), $row['skill_1'],$row['skill_2'] ,$row['skill_3'], $row['member_name'], $last_update, true);
	$spec2 = get_wow_talent_spec(ucwords(renameClasstoenglish($row['member_class'])), $row['skill2_1'],$row['skill2_2'] ,$row['skill2_3'], $row['member_name'], $last_update, true);
	$member_count++;
	
	// Action Menu
	$cm_actions= array(
                0 => array(
                    //'name'    => $user->lang['uc_edit_char'],
                    //'link'    => "javascript:EditChar('".$row['myid']."')",
                    //'img'     => 'edit.png',
                    //'perm'    => $user->check_auth('u_charmanager_', false),
                    ),
                1 => array(
                    'name'    => $user->lang['uc_delete_char'],
                    'link'    => "javascript:DeleteChar('".$row['myid']."')",
                    'img'     => 'delete.png',
                    'perm'    => $user->check_auth('u_charmanager_delete', false),
                    ),
                2 => array(
                    'name'    => $user->lang['uc_updat_armory'],
                    'link'    => "javascript:UpdateChar('".$row['myid']."')",
                    'img'     => 'update.png',
                    'perm'    => $CharTools->ImportAuth('u_charmanager_'),
                    ),
	);
	
  $tpl->assign_block_vars('members_row', array(
        'ROW_CLASS'     => $eqdkp->switch_row_class(),
        'ID'            => $row['myid'],
        'COUNT'         => $member_count,
        'NAME'          => $row['member_name'],
        'LEVEL'         => $row['member_level'],
        
        'CLASSID'				=> $row['member_class_id'],
        'CLASS_ICON'		=> get_classImgListmembers($row['member_class'],$row['member_class_id']),
        'RACE_ICON'			=> get_RaceIcon($row['member_race_id'],$row['member_name']),
        'SPEC'					=> $spec1[icon],
        'SPEC2'					=> $spec2[icon],
        'RANK'					=> $row['rank_name'],
        'GUILD'					=> $row['guild'],
        'TBA'						=> ($row['require_confirm']) ? ' cm_confirm"' : '',
        'MBCONFIRMED'		=> ($row['require_confirm']) ? $user->lang['uc_need_confirmation'] : '',
        
        'ACTIONMENU'		=> $jquery->DropDownMenu('actionmenu'.$row['myid'], 'actionmenu', $cm_actions, 'plugins/charmanager/images','<img src="images/edit.png" />'),
        )
    );
}
$footcount_text = sprintf($user->lang['listmembers_footcount'], $db->num_rows($members_result));

// Build member drop-down
$sql = "SELECT m.member_id, m.member_name, mu.user_id
				FROM __members m
				LEFT JOIN __member_user mu ON m.member_id = mu.member_id
				LEFT JOIN __member_additions ma ON ma.member_id = m.member_id
				WHERE (mu.user_id IS NULL OR mu.user_id = ".$db->sql_escape($user->data['user_id']).") 
				AND (ma.requested_del != '1' OR ma.requested_del IS NULL)
				GROUP BY m.member_name
				ORDER BY m.member_name";
$result = $db->query($sql);
$mselect_list = $mselect_selected = array();
while ( $row = $db->fetch_record($result) ){
	$mselect_list[$row['member_id']] = $row['member_name'];
	if($row['user_id'] == $user->data['user_id']){
		$mselect_selected[] = $row['member_id'];
	}
}
$db->free_result($result);

// Action Menu
	$cm_addmenu = array(
                0 => array(
                    //'name'    => $user->lang['uc_add_char_plain'],
                    //'link'    => "javascript:AddChar()",
                    //'img'     => 'add.png',
                    //'perm'    => $user->check_auth('u_charmanager_add', false),
                    ),
                1 => array(
                    'name'    => $user->lang['uc_add_char_armory'],
                    'link'    => "javascript:AddCharArmory()",
                    'img'     => 'armory.gif',
                    'perm'    => $CharTools->ImportAuth('u_charmanager_add'),
                    ),
                2 => array(
                    'name'    => $user->lang['uc_add_massupdate'],
                    'link'    => "javascript:MassUpdateChars()",
                    'img'     => 'armory.gif',
                    'perm'    => $CharTools->ImportAuth('a_charmanager_'),
                    ),
	);
	
	// Import Folder
	$import_folder = $eqdkp_root_path.'plugins/charmanager/games/'.$conf['real_gamename'].'/import/';
	
  $tpl->assign_vars(array(
    'F_UPDATE'        => 'index.php',
    'U_CHARACTERS'		=> 'index.php' . $SID . '&amp;',
    'CLOSE_WINDOWS' 	=> ( $closeWindows == true ) ? true : false,
    'IS_ADMIN'				=> ( $user->check_auth('a_plugins_man', false) ) ? true : false,
    
    'NEW_CHARS'				=> ( $user->check_auth('u_charmanager_add', false)) ? true : false,
    'CONNECT_CHARS'		=> ( $user->check_auth('u_charmanager_connect', false)) ? true : false,
    'DELETE_CHARS'		=> ( $user->check_auth('u_charmanager_delete', false)) ? true : false,
    
    // JS Code
    'JS_ABOUT'        => $jquery->Dialog_URL('About', $user->lang['about_header'], 'about.php', '380', '320'),
    'JS_ADDCHARS'     => $jquery->Dialog_URL('CMAddChars', $user->lang['uc_add_char'], 'addchar.php', '640', '450', 'index.php'),
    'JS_IMPORTCHAR'		=> $jquery->Dialog_URL('UC_ImportChars', $user->lang['uc_ext_import_sh'], $import_folder."u_import.php", '450', '180', 'index.php'),
    'JS_UPDATECHAR'		=> $jquery->Dialog_URL('UC_UPDATEChars', $user->lang['uc_ext_import_sh'], $import_folder."u_import.php?member_id='+memberid+'", '450', '180', 'index.php'),
    'JS_MASSUPDATECH'	=> $jquery->Dialog_URL('UCCacheWindow',  $user->lang['uc_cache_update'], $import_folder."a_import.php?step=1", '500', '130', 'index.php'),
    'JS_EDITCHAR'     => $jquery->Dialog_URL('CMEditChars'.rand(), $user->lang['uc_edit_char'], "addchar.php?editid='+editid+'", '640', '450', 'index.php'),
    'JS_DELETECHAR'		=> $jquery->Dialog_Confirm('DeleteChar', $user->lang['uc_del_warning'], "document.data.delete_id.value=v;document.data.submit();", "editid"),
    'JS_CONNECTIONS'  => $jquery->MultiSelect('member_id', $mselect_list, $mselect_selected, '150'),
    'ADD_MENU'				=> $jquery->DropDownMenu('colortab', 'colortab', $cm_addmenu, 'plugins/charmanager/images','<span style="color:black;"><img border="0" src="images/add.png"/> '.$user->lang['uc_add_char'].'</span>'),
    
    'L_NAME' 					=> $user->lang['name'],
    'L_LEVEL' 				=> $user->lang['level'],
    'L_RANK'          => $user->lang['rank'],
    'L_SPEC'          => $user->lang['uc_tab_skills'],
    'L_GUILD'					=> $user->lang['uc_guild'],
    'L_EDIT_CHARS' 		=> $user->lang['uc_edit_char'],
    'L_DELETE_CHARS'	=> $user->lang['uc_delete_char'],
    'L_SUBMIT'        => $user->lang['uc_connectme'],
    'L_CONN_MEMBERS'  => $user->lang['associated_members'],
    'L_ADD_CHAR'			=> $user->lang['uc_add_char'],
    'L_EDIT_MEMBER'		=> $user->lang['uc_edit_char'],
    'L_CONNECT_MEM'		=> $user->lang['uc_conn_members'],
    'L_CHAR_CONN'			=> $user->lang['uc_connections'],
    'L_CREDIT_NAME'		=> $user->lang['uc_credit_name'],
    
    'L_TOOLTIP3'			=> $user->lang['uc_tt_n3'],
    'L_TOOLTIP2'			=> $user->lang['uc_tt_n2'],
    
    'O_NAME' 					=> $current_order['uri'][0],
    'O_LEVEL' 				=> $current_order['uri'][1],
    'O_CLASS' 				=> $current_order['uri'][2],
    'O_RANK'					=> $current_order['uri'][3],
    'O_GUILD'					=> $current_order['uri'][4],
    
    'CM_COPYRIGHT'    => $CharTools->Copyright(),
    'ICON_INFO'				=> 'images/info.png',
    'US_FOOTCOUNT'    => $footcount_text,
    )
);

$eqdkp->set_vars(array(
    'page_title'    => sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['listmembers_title'],
    'template_file' => 'main.html',
    'template_path' => $pm->get_data('charmanager', 'template_path'),
    'display'       => true)
);
?>

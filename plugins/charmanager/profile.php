<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2012-09-30 22:05:50 +0200 (Sun, 30 Sep 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 12157 $
 *
 * $Id: profile.php 12157 2012-09-30 20:05:50Z wallenium $
 */

// temporary variables
$myMaxProffession = 450;

define('EQDKP_INC', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = './../../';
include_once('include/common.php');

global $table_prefix;
$CharTools = new CharTools();

// LOad Itemstats
$uc_itemstats_file = $eqdkp_root_path . 'itemstats/eqdkp_itemstats.php';
if (@file_exists($uc_itemstats_file)) {
	include_once($uc_itemstats_file);
	$uc_itemstats_enabled = true;
}else{
	$uc_itemstats_enabled = false;
}

$user->check_auth('u_charmanager_view');

if ( $in->get(URI_NAME) != '' )
{
    $sort_order = array(
        0 => array('raid_name', 'raid_name desc'),
        1 => array('raid_count desc', 'raid_count')
    );

    $current_order = switch_order($sort_order);

$sql = "SELECT ma.*, m.member_id, m.member_name, m.member_earned, m.member_spent, m.member_adjustment, (m.member_earned-member_spent+m.member_adjustment) AS member_current,
        m.member_firstraid, m.member_lastraid, m.member_class_id, m.member_race_id, m.member_level
        FROM __members m
        LEFT JOIN __member_additions ma ON (ma.member_id=m.member_id)
        WHERE member_name='".$db->sql_escape(unsanitize($in->get(URI_NAME)))."'";

    if ( !($member_result = $db->query($sql)) ){
      message_die('Could not obtain member information', '', __FILE__, __LINE__, $sql);
    }

    // Make sure they provided a valid member name
    if ( !$member = $db->fetch_record($member_result) )
    {
        message_die($user->lang['error_invalid_name_provided']);
    }

    // Find the percent of raids they've attended in the last 30, 60 and 90 days
    $percent_of_raids = array(
        '30'       => raid_count(mktime(0, 0, 0, date('m'), date('d')-30, date('Y')), time(), $member['member_name']),
        '60'       => raid_count(mktime(0, 0, 0, date('m'), date('d')-60, date('Y')), time(), $member['member_name']),
        '90'       => raid_count(mktime(0, 0, 0, date('m'), date('d')-90, date('Y')), time(), $member['member_name']),
        'lifetime' => raid_count($member['member_firstraid'], $member['member_lastraid'], $member['member_name'])
    );

		$sql = 'SELECT race_name
    				FROM __races
    				WHERE race_id = '.$member['member_race_id'];
		$result = $db->query($sql);
		$race_name = $db->fetch_record($result) ;
	  $db->free_result($result);

		// Find the class name
		$sql = 'SELECT class_name, class_id
    				FROM __classes
    				WHERE class_id = ' . $member['member_class_id'] ;
		$result = $db->query($sql);
		$class_name = $db->fetch_record($result) ;
	  $db->free_result($result);

    //
    // Raid Attendance
    //
    $rstart = $in->get('rstart', 0);

    // Find $current_earned based on the page.  This prevents us having to pass the
    // current earned as a GET variable which could result in user error
    if ( (!isset($rstart)) || ($rstart == '0') )
    {
        $current_earned = $member['member_earned'];
    }
    else
    {
        $current_earned = $member['member_earned'];
        $sql = "SELECT raid_value
                FROM __raids r, __raid_attendees ra
                WHERE (ra.raid_id = r.raid_id)
                AND (ra.member_name='" .$db->sql_escape($member['member_name'])."')
                ORDER BY r.raid_date DESC
                LIMIT " . $rstart;
        if ( !($earned_result = $db->query($sql)) )
        {
            message_die($user->lang['uc_error_raidinfos'], '', __FILE__, __LINE__, $sql);
        }
        while ( $ce_row = $db->fetch_record($earned_result) )
        {
            $current_earned -= $ce_row['raid_value'];
        }
        $db->free_result($earned_result);
    }

    $sql = 'SELECT r.raid_id, r.raid_name, r.raid_date, r.raid_note, r.raid_value
            FROM __raids r, __raid_attendees ra
            WHERE (ra.raid_id = r.raid_id)
            AND (ra.member_name=\'' .$db->sql_escape($member['member_name']). '\')
            ORDER BY r.raid_date DESC
            LIMIT 5';
    if ( !($raids_result = $db->query($sql)) )
    {
        message_die($user->lang['uc_error_raidinfos'], '', __FILE__, __LINE__, $sql);
    }
    while ( $raid = $db->fetch_record($raids_result) )
    {
        $tpl->assign_block_vars('raids_row', array(
            'ROW_CLASS'      => $eqdkp->switch_row_class(),
            'DATE'           => ( !empty($raid['raid_date']) ) ? date($user->style['date_notime_short'], $raid['raid_date']) : '&nbsp;',
            'U_VIEW_RAID'    => $eqdkp_root_path.'viewraid.php'.$SID.'&amp;' . URI_RAID . '='.$raid['raid_id'],
            'NAME'           => ( !empty($raid['raid_name']) ) ? stripslashes($raid['raid_name']) : '&lt;<i>Not Found</i>&gt;',
            'NOTE'           => ( !empty($raid['raid_note']) ) ? stripslashes($raid['raid_note']) : '&nbsp;',
            'EARNED'         => $raid['raid_value'],
            'CURRENT_EARNED' => sprintf("%.2f", $current_earned))
        );
        $current_earned -= $raid['raid_value'];
    }

    $db->free_result($raids_result);

    $sql = "SELECT count(*)
            FROM __raids r, __raid_attendees ra
            WHERE (ra.raid_id = r.raid_id)
            AND (ra.member_name='" . addslashes($member['member_name']) . "')";
    $total_attended_raids = $db->query_first($sql);

    //
    // Item Purchase History
    //
    $istart = $in->get('istart', 0);

    if ( (!isset($istart)) || ($istart == '0') )
    {
        $current_spent = $member['member_spent'];
    }
    else
    {
        $current_spent = $member['member_spent'];
        $sql = "SELECT item_value
                FROM __items
                WHERE (item_buyer='" . $member['member_name'] . "')
                ORDER BY item_date DESC
                LIMIT " . $istart;
        if ( !($spent_result = $db->query($sql)) )
        {
            message_die($user->lang['uc_error_iteminfos'], '', __FILE__, __LINE__, $sql);
        }
        while ( $cs_row = $db->fetch_record($spent_result) )
        {
            $current_spent -= $cs_row['item_value'];
        }
        $db->free_result($spent_result);
    }

    $sql = 'SELECT i.item_id, i.item_name, i.item_value, i.item_date, i.raid_id, i.game_itemid, r.raid_name
            FROM ( __items i
            LEFT JOIN __raids r
            ON r.raid_id = i.raid_id )
            WHERE (i.item_buyer=\'' . $member['member_name'] . '\')
            ORDER BY i.item_date DESC
            LIMIT 5';
    if ( !($items_result = $db->query($sql)) )
    {
        message_die($user->lang['uc_error_itemraidi'], 'Database error', __FILE__, __LINE__, $sql);
    }
    while ( $item = $db->fetch_record($items_result) )
    {
        $tpl->assign_block_vars('items_row', array(
            'ROW_CLASS'     => $eqdkp->switch_row_class(),
            'DATE'          => ( !empty($item['item_date']) ) ? date($user->style['date_notime_short'], $item['item_date']) : '&nbsp;',
            'U_VIEW_ITEM'   => $eqdkp_root_path.'viewitem.php'.$SID.'&amp;' . URI_ITEM . '=' . $item['item_id'],
            'U_VIEW_RAID'   => $eqdkp_root_path.'viewraid.php'.$SID.'&amp;' . URI_RAID . '=' . $item['raid_id'],
            'NAME'          => ($uc_itemstats_enabled) ? itemstats_decorate_name(stripslashes($item['item_name']), $item['game_itemid']) : $item['item_name'],
            'RAID'          => ( !empty($item['raid_name']) ) ? stripslashes($item['raid_name']) : '&lt;<i>Not Found</i>&gt;',
            'SPENT'         => $item['item_value'],
            'CURRENT_SPENT' => sprintf("%.2f", $current_spent))
        );
        $current_spent -= $item['item_value'];
    }
    $db->free_result($items_result);

    $total_purchased_items = $db->query_first("SELECT count(*) FROM __items WHERE item_buyer='" . $member['member_name'] . "' ORDER BY item_date DESC");

		// Profile Extensions
	if($member['second_name'] == 'r'){
		$sendondbar['name'] = $user->lang['uc_bar_rage'];
		$sendondbar['css']	= 'rage';
	}elseif($member['second_name'] == 'e'){
		$sendondbar['name'] = $user->lang['uc_bar_energy'];
		$sendondbar['css']	= 'energy';
	}else{
		$sendondbar['name'] = $user->lang['uc_bar_mana'];
		$sendondbar['css']	= 'mana';
	}

      if($conf['uc_showarmlink'] == 1){
          $armory = new ArmoryChars("âîâ");
          $cm_alinks = array(
                0 => array(
                    'name'    => $user->lang['uc_armorylink1'],
                    'link'    => $armory->Link($conf['uc_server_loc'], $member['member_name'], $CharTools->convertEncoding(stripslashes($conf['uc_servername'])), 'char'),
                    'img'     => 'armory.gif',
                    'perm'    => true,
                    ),
                1 => array(
                    'name'    => $user->lang['uc_armorylink2'].' 1',
                    'link'    => $armory->Link($conf['uc_server_loc'], $member['member_name'], $CharTools->convertEncoding(stripslashes($conf['uc_servername'])), 'talent1'),
                    'img'     => 'armory.gif',
                    'perm'    => true,
                    ),
								2 => array(
                    'name'    => $user->lang['uc_armorylink2'].' 2',
                    'link'    => $armory->Link($conf['uc_server_loc'], $member['member_name'], $CharTools->convertEncoding(stripslashes($conf['uc_servername'])), 'talent2'),
                    'img'     => 'armory.gif',
                    'perm'    => true,
                    ),
                3 => array(
                    'name'    => $user->lang['uc_armorylink3'],
                    'link'    => $armory->Link($conf['uc_server_loc'], $member['member_name'], $CharTools->convertEncoding(stripslashes($conf['uc_servername'])), 'reputation'),
                    'img'     => 'armory.gif',
                    'perm'    => true,
                    ),
        				4 => array(
                    'name'    => $user->lang['uc_armorylink4'],
                    'link'    => $armory->Link($conf['uc_server_loc'], $member['member_name'], $CharTools->convertEncoding(stripslashes($conf['uc_servername'])), 'achievements'),
                    'img'     => 'armory.gif',
                    'perm'    => true,
                    ),
                5 => array(
                    'name'    => $user->lang['uc_armorylink5'],
                    'link'    => $armory->Link($conf['uc_server_loc'], $member['member_name'], $CharTools->convertEncoding(stripslashes($conf['uc_servername'])), 'statistics'),
                    'img'     => 'armory.gif',
                    'perm'    => true,
                    ),
                6 => array(
                    'name'    => $user->lang['uc_armorylink6'],
                    'link'    => $armory->Link($conf['uc_server_loc'], $member['member_name'], $CharTools->convertEncoding(stripslashes($conf['uc_servername'])), 'guild', $CharTools->convertEncoding(stripslashes($$member['guild']))),
                    'img'     => 'armory.gif',
                    'perm'    => (($member['guild']) ? true : false),
                    ),
	         );
          }


	// Spec
	$last_update = ($member['last_update']) ? date($user->lang['uc_changedate'],$member['last_update']) : '--';
	$spec1 = get_wow_talent_spec(ucwords(renameClasstoenglish($class_name['class_name'])), $member['skill_1'],$member['skill_2'] ,$member['skill_3'], $member['member_name'], $last_update, true);
	$spec2 = get_wow_talent_spec(ucwords(renameClasstoenglish($class_name['class_name'])), $member['skill2_1'],$member['skill2_2'] ,$member['skill2_3'], $member['member_name'], $last_update, true);
  $customNoPic  = (is_file('games/'.$conf['real_gamename'].'/images/no_pic.png')) ? 'games/'.$conf['real_gamename'].'/images/no_pic.png' : 'images/no_pic.png';

    $tpl->assign_vars(array(
        // JS Code
        'JS_ABOUT'        => $jquery->Dialog_URL('About', $user->lang['about_header'], 'about.php', '380', '320'),
        'TEMPLATENAME'    => $user->style['template_path'],

    		'SHOW_ARMLINK'										=> ($conf['uc_showarmlink'] == 1) ? true : false,
    		'IS_WOW'										      => ($conf['real_gamename'] == 'wow') ? true : false,
	      'GUILDTAG' 												=> $eqdkp->config['guildtag'],
	      'NAME'     												=> $member['member_name'],
		    'LEVEL'														=> $member['member_level'],
		    'RACENAME'     										=> $race_name['race_name'],
		    'CLASSNAME'     									=> $class_name['class_name'],
				'PROFILE_PICTURE'									=> ($member['picture']) ? $pcache->FolderPath('upload', 'charmanager').$member['picture'] : $customNoPic,
				'GUILD'														=> $member['guild'],

				'BLASC_ID'												=> $member['blasc_id'],
				'CTPROFILE_ID'										=> $member['ct_profile'],
				'ALLA_ID'													=> $member['allakhazam'],
				'CURSE_ID'												=> $member['curse_profiler'],
				'TALENT_URL'											=> $member['talentplaner'],
				'SHOW_BLASC'											=> ( $member['blasc_id'] ) ? true : false,
				'SHOW_CTPROFILE'									=> ( $member['ct_profile'] ) ? true : false,
				'SHOW_ALLA'												=> ( $member['allakhazam'] ) ? true : false,
				'SHOW_CURSE'											=> ( $member['curse_profiler'] ) ? true : false,
				'SHOW_TALENT'											=> ( $member['talentplaner'] ) ? true : false,
				'SHOW_PROFESSIONS'								=> ($member['prof1_name'] || $member['prof2_name']) ? true : false,

				//tab shit
				'TAB_HEAD_CM1'										=> $jquery->Tab_header('char1_tabs'),
				'TAB_HEAD_CM2'										=> $jquery->Tab_header('char2_tabs'),
				'TAB1_NAME1'											=> $user->lang['uc_tab_Character'],
				'TAB1_NAME2'											=> $user->lang['uc_tab_raidinfo'],
				'TAB1_NAME3'											=> $user->lang['uc_tab_notes'],
				'TAB2_NAME1'											=> $user->lang['uc_tab_raids'],
				'TAB2_NAME2'											=> $user->lang['uc_tab_items'],

        'ARMORY_LINKS'	                  => ($conf['uc_showarmlink'] == 1) ? $jquery->DropDownMenu('alinks'.$row['mid'], 'actionmenu', $cm_alinks, 'plugins/charmanager/images','<img src="images/power.png" />') : '',
				'CSSSECONDBAR'										=> $sendondbar['css'],
				'FACTION'													=> strtolower($member['faction']),
				'PROF_FIRST_I'										=> $member['prof1_name'],
				'PROF_FIRST_V'										=> $member['prof1_value'],
				'PROF_FIRST_VMAX'									=> $myMaxProffession,
				'PROF_FIRST_PERC'									=> round((($member['prof1_value']/$myMaxProffession)*100)),
				'PROF_FIRST_N'										=> $user->lang['professionsarray'][$member['prof1_name']],
				'PROF_SECOND_I'										=> $member['prof2_name'],
				'PROF_SECOND_V'										=> $member['prof2_value'],
				'PROF_SECOND_VMAX'								=> $myMaxProffession,
				'PROF_SECOND_PERC'								=> round((($member['prof2_value']/$myMaxProffession)*100)),
				'PROF_SECOND_N'										=> $user->lang['professionsarray'][$member['prof2_name']],
				'HEALTHBAR'												=> $member['health_bar'],
				'SECONDBAR'												=> $member['second_bar'],
				'LAST_UPDATE'											=> (($member['last_update']) ? date($user->lang['uc_changedate'], $member['last_update']) : ' -- '),
				'NOTES'                           => stripslashes($member['notes']),
				'FIRE'														=> ( $member['fir'] ) ? $member['fir'] : 0,
       	'ARCANE'													=> ( $member['ar'] ) ? $member['ar'] : 0,
       	'FROST'														=> ( $member['frr'] ) ? $member['frr'] : 0,
       	'NATURE'													=> ( $member['nr'] ) ? $member['nr'] : 0,
       	'SHADOW'													=> ( $member['sr'] ) ? $member['sr'] : 0,
        'SPEC1'														=> $spec1['icon'].' ('.$spec1['spec'].')',
        'SPEC2'														=> (($spec2['spec']) ? '<br/>'.$spec2['icon'].' ('.$spec2['spec'].')' : ''),
        'EARNED'         									=> $member['member_earned'],
        'SPENT'         									=> $member['member_spent'],
        'ADJUSTMENT'     									=> $member['member_adjustment'],
        'CURRENT'        									=> $member['member_current'],
        'RAIDS_30_DAYS'  									=> sprintf($user->lang['of_raids'], $percent_of_raids['30']),
        'RAIDS_60_DAYS'  									=> sprintf($user->lang['of_raids'], $percent_of_raids['60']),
        'RAIDS_90_DAYS'  									=> sprintf($user->lang['of_raids'], $percent_of_raids['90']),
        'RAIDS_LIFETIME' 									=> sprintf($user->lang['of_raids'], $percent_of_raids['lifetime']),
				'C_ADJUSTMENT'     								=> color_item($member['member_adjustment']),
        'C_CURRENT'        								=> color_item($member['member_current']),
        'C_RAIDS_30_DAYS'  								=> color_item($percent_of_raids['30'], true),
        'C_RAIDS_60_DAYS'  								=> color_item($percent_of_raids['60'], true),
        'C_RAIDS_90_DAYS'  								=> color_item($percent_of_raids['90'], true),
        'C_RAIDS_LIFETIME' 								=> color_item($percent_of_raids['lifetime'], true),

        'L_EARNED'                        => $user->lang['earned'],
        'L_SPENT'                         => $user->lang['spent'],
        'L_ADJUSTMENT'                    => $user->lang['adjustment'],
        'L_CURRENT'                       => $user->lang['current'],
        'L_HEALTHBAR'											=> $user->lang['uc_bar_health'],
        'L_SECOND_BAR'										=> $sendondbar['name'],
        'L_UNKNOWN'												=> $user->lang['uc_unknown'],
        'L_LAST_UPDATE' 									=> $user->lang['uc_lastupdate'],
        'L_RAIDS_30_DAYS'                 => sprintf($user->lang['raids_x_days'], 30),
        'L_RAIDS_60_DAYS'                 => sprintf($user->lang['raids_x_days'], 60),
        'L_RAIDS_90_DAYS'                 => sprintf($user->lang['raids_x_days'], 90),
        'L_RAIDS_LIFETIME'                => sprintf($user->lang['raids_lifetime'],
                                                date($user->style['date_notime_short'], $member['member_firstraid']),
                                                date($user->style['date_notime_short'], $member['member_lastraid'])),
    		'L_LAST_5_RAIDS'									=> $user->lang['uc_last_5_raids'],
    		'L_LAST_5_ITEMS'									=> $user->lang['uc_last_5_items'],
    		'L_CLASS'													=> $user->lang['class'],
    		'L_RACE'													=> $user->lang['race'],
    		'L_LEVEL'													=> $user->lang['uc_level_out'],
    		'L_GUILD'													=> $user->lang['uc_guild'],
    		'L_EXT_PROFILE'										=> $user->lang['uc_ext_profile'],
    		'L_RAID_INFOS'										=> $user->lang['uc_raid_infos'],
        'L_RAID_ATTENDANCE_HISTORY'       => $user->lang['raid_attendance_history'],
        'L_DATE'                          => $user->lang['date'],
        'L_NAME'                          => $user->lang['name'],
        'L_SKILL'													=> $user->lang['uc_tab_skills'],
        'L_NOTE'                          => $user->lang['note'],
        'L_EARNED'                        => $user->lang['earned'],
        'L_CURRENT'                       => $user->lang['current'],
        'L_ITEM_PURCHASE_HISTORY'         => $user->lang['item_purchase_history'],
        'L_RAID'                          => $user->lang['raid'],
        'L_INDIVIDUAL_ADJUSTMENT_HISTORY' => $user->lang['individual_adjustment_history'],
        'L_REASON'                        => $user->lang['reason'],
        'L_ADJUSTMENT'                    => $user->lang['adjustment'],
        'L_ATTENDANCE_BY_EVENT'           => $user->lang['attendance_by_event'],
        'L_EVENT'                         => $user->lang['event'],
        'L_PERCENT'                       => $user->lang['percent'],
        'L_CHAR_INFO'											=> $user->lang['uc_char_info'],
        'L_BUFFED'												=> $user->lang['uc_buffed'],
        'L_ALLAKHAZAM'										=> $user->lang['uc_allakhazam'],
        'L_CTPROFILES'										=> $user->lang['uc_ctprofiles'],
        'L_CURSEPROFILES'									=> $user->lang['uc_curse_profiler'],
        'L_TALENTPLANER'									=> $user->lang['uc_talentplaner'],
        'L_ARMORY_LINK1'									=> $user->lang['uc_armorylink1'],
   			'L_ARMORY_LINK2'									=> $user->lang['uc_armorylink2'],
    		'L_ARMORY_LINK3'									=> $user->lang['uc_armorylink3'],
        'L_RECEIVES'											=> $user->lang['uc_receives'],
        'CM_COPYRIGHT'                    => $CharTools->Copyright(),
    		'ICON_INFO'												=> 'images/info.png',
    		'L_CREDIT_NAME'										=> $user->lang['uc_credit_name'],

        'U_VIEW_MEMBER' 									=> 'viewmember.php' . $SID . '&amp;' . URI_NAME . '=' . $member['member_name'] . '&amp;')
    );

    $db->free_result($adjustments_result);

    $pm->do_hooks('/viewmember.php');

    $eqdkp->set_vars(array(
        'page_title'    => $CharTools->GeneratePageTitle(sprintf($user->lang['viewmember_title'], $member['member_name'])),
        'template_file' => 'profile.html',
        'template_path' => $pm->get_data('charmanager', 'template_path'),
        'display'       => true)
    );
}
else
{
    message_die($user->lang['error_invalid_name_provided']);
}

// ---------------------------------------------------------
// Page-specific functions
// ---------------------------------------------------------
function raid_count($start_date, $end_date, $member_name){
    global $db;
    $raid_count = $db->query_first('SELECT count(*) FROM __raids WHERE (raid_date BETWEEN ' . $start_date . ' AND ' . $end_date . ')');
    $sql = "SELECT count(*)
            FROM __raids r, __raid_attendees ra
            WHERE (ra.raid_id = r.raid_id)
            AND (ra.member_name='" . $db->sql_escape($member_name) . "')
            AND (r.raid_date BETWEEN " . $db->sql_escape($start_date) . ' AND ' . $db->sql_escape($end_date) . ')';
    $individual_raid_count = $db->query_first($sql);

    $percent_of_raids = ( $raid_count > 0 ) ? round(($individual_raid_count / $raid_count) * 100) : 0;
    $raid_count_stats = array(
        'percent'     => $percent_of_raids,
        'total_count' => $raid_count,
        'indiv_count' => $individual_raid_count);

    return $raid_count_stats['percent']; // Only thing needed ATM
}
?>

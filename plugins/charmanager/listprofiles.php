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
 * $Id: listprofiles.php 12157 2012-09-30 20:05:50Z wallenium $
 */

define('EQDKP_INC', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = './../../';
include_once('include/common.php');
global $table_prefix;

$user->check_auth('u_charmanager_view');

$sort_order = array(
    0 => array('member_name', 'member_name desc'),
    1 => array('member_current desc', 'member_current'),
    2 => array('member_lastraid desc', 'member_lastraid'),
    3 => array('member_level desc', 'member_level'),
    4 => array('member_class', 'member_class desc'),
    5 => array('rank_name', 'rank_name desc'),
    
    6 => array('frr desc', 'frr'),
    7 => array('ar desc', 'ar'),
    8 => array('sr desc', 'sr'),
    9 => array('nr desc', 'nr'),
    10 => array('fir desc', 'fir'),
    11 => array('guild', 'guild desc'),
);

$current_order = switch_order($sort_order);

//
// Normal member display
//
    $member_count = 0;
    $previous_data = '';

    // Figure out what data we're comparing from member to member
    // in order to rank them
    $sort_index = explode('.', $current_order['uri']['current']);
    $previous_source = preg_replace('/( (asc|desc))?/i', '', $sort_order[$sort_index[0]][$sort_index[1]]);

    $show_all = ( $in->get('show') == 'all' ) ? true : false;

    //
    // Filtering
    //

    $filter = urldecode($in->get('filter', 'none'));
    $filter = ( preg_match('#\-{1,}#', $filter) ) ? 'none' : $filter;


    // Grab class_id

    if ( $in->get('filter') != '' ) {

    $temp_filter = $in->get('filter');

       // Just because filter is set doesn't mean its valid - clear it if its set to none

       if ( preg_match('/ARMOR_/', $temp_filter) ) {
    				$temp_filter = preg_replace('/ARMOR_/', '', $temp_filter);
    				$query_by_armor = 1;
        		$query_by_class = 0;
        		$id = $temp_filter;
    } elseif ( $temp_filter == "none" ) {
            $temp_filter = "";
        		$query_by_armor = 0;
            $query_by_class = 0;
       } else {
            $query_by_class = 1;
            $query_by_armor = 0;
            $id = $temp_filter;
       }
}

    $tpl->assign_block_vars('filter_row', array(
        'VALUE'    => strtolower("None"),
        'SELECTED' => ( $filter == strtolower("None") ) ? ' selected="selected"' : '',
        'OPTION'   => str_replace('_', ' ', "None"))
    );

    // Add in the cute ---- line, filter on None if some idiot selects it

    $tpl->assign_block_vars('filter_row', array(
        'VALUE'    => strtolower("None"),
        'SELECTED' => ( $filter == strtolower("NULL") ) ? ' selected="selected"' : '',
        'OPTION'   => str_replace('_', ' ', "--------"))
    );

    // Grab generic armor information

    $sql = 'SELECT class_armor_type FROM __classes';
    $sql .= ' GROUP BY class_armor_type';
    $result = $db->query($sql);

        while ( $row = $db->fetch_record($result) )
        {
          $tpl->assign_block_vars('filter_row', array(
              'VALUE'    => "ARMOR_" . strtolower($row['class_armor_type']),
              'SELECTED' => ( $temp_filter == strtolower($row['class_armor_type']) ) ? ' selected="selected"' : '',
              'OPTION'   => str_replace('_', ' ', $row['class_armor_type']))
          );
        }

    // Add in the cute ---- line, filter on None if some idiot selects it

    $tpl->assign_block_vars('filter_row', array(
        'VALUE'    => strtolower("None"),
        'SELECTED' => ( $filter == strtolower("NULL") ) ? ' selected="selected"' : '',
        'OPTION'   => str_replace('_', ' ', "--------"))
    );

    // Moved the class/race/faction information to the database

        $sql = 'SELECT class_name, class_id, class_min_level, class_max_level FROM __classes';
        $sql .= ' GROUP BY class_name';
        $result = $db->query($sql);

        while ( $row = $db->fetch_record($result) )
        {
            $tpl->assign_block_vars('filter_row', array(
                'VALUE' => $row['class_name'],
                'SELECTED' => ( strtolower($filter) == strtolower($row['class_name']) ) ? ' selected="selected"' : '',
                'OPTION'   => ( !empty($row['class_name']) ) ? stripslashes($row['class_name']) : '(None)' )
                );
        }

        $db->free_result($result);

    // end database move of race/class/faction

    // Build SQL query based on GET options
    $sql = 'SELECT m.*, ma.*, (m.member_earned-m.member_spent+m.member_adjustment) AS member_current, m.member_id AS mid,
           member_status, m.member_race_id,  r.rank_name, r.rank_hide, r.rank_prefix, r.rank_suffix,
           c.class_name AS member_class,
           c.class_armor_type AS armor_type,
           c.class_min_level AS min_level,
           c.class_max_level AS max_level
        	FROM __member_ranks r, __classes c, __members m
        	LEFT JOIN __member_additions ma ON (ma.member_id=m.member_id)
        	WHERE c.class_id = m.member_class_id
            AND (m.member_rank_id = r.rank_id)';
    if ( $in->get('rank') != '' )
    {
        $sql .= " AND r.rank_name='" . $db->sql_escape(urldecode($in->get('rank'))) . "'";
    }

    if ( $query_by_class == '1' )
    {
        $sql .= " AND c.class_name =  '".$db->sql_escape($id)."'";
    }

    if ( $query_by_armor == '1' )
    {
        $sql .= " AND c.class_armor_type =  '".$db->sql_escape($temp_filter)."'";
    }

    $sql .= ' ORDER BY '.$current_order['sql'];

    if ( !($members_result = $db->query($sql)) )
    {
        message_die($user->lang['uc_error_memberinfos'], '', __FILE__, __LINE__, $sql);
    }
    
    if($conf['uc_showarmlink'] == 1){
      $armory = new ArmoryChars("âîâ");
    }
    
    while ( $row = $db->fetch_record($members_result) )
    {
        // Figure out the rank search URL based on show and filter
        $u_rank_search  = 'listprofiles.php' . $SID . '&amp;rank=' . urlencode($row['rank_name']);
        $u_rank_search .= ( ($eqdkp->config['hide_inactive'] == 1) && (!$show_all) ) ? '&amp;show=' : '&amp;show=all';
        $u_rank_search .= ( $filter != 'none' ) ? '&amp;filter=' . $filter : '';
				
        if ( member_display($row) )
        {
            $member_count++;

						if(function_exists('get_classNameImgListmembers')){
							$row['member_class2'] 	= $row['member_class'];
             	$row['member_class']		= get_classNameImgListmembers($row['member_class']);
            }else{
            	$row['member_class'] 	= $row['member_class'];
            	$row['member_class2'] = $row['member_class'];
            }
             $race_img = $eqdkp_root_path.'images/races/'.strtolower($row['member_race_id']).'.gif';

             if(file_exists($race_img))
             {
                 $row['member_class'] = '<img src='.$race_img.'>'.$row['member_class'];
             }


             $rank_img = $eqdkp_root_path.'images/rank/'.strtolower($row['rank_name']).'.gif';
             if(file_exists($rank_img))
             {
                 $rankimg = '<img src='.$rank_img.' alt="Rank='.$row['rank_name'].'">';
             }
             else
             {
                 $rankimg = '';
             }
						
					// Specs
					$last_update = ($row['last_update']) ? date($user->lang['uc_changedate'],$row['last_update']) : '--';
					$spec1 = get_wow_talent_spec(ucwords(renameClasstoenglish($row['member_class2'])), $row['skill_1'],$row['skill_2'] ,$row['skill_3'], $row['member_name'], $last_update, true);
					$spec2 = get_wow_talent_spec(ucwords(renameClasstoenglish($row['member_class2'])), $row['skill2_1'],$row['skill2_2'] ,$row['skill2_3'], $row['member_name'], $last_update, true);
          
          // Armory Menu
          if($conf['uc_showarmlink'] == 1){
          $cm_alinks = array(
                0 => array(
                    'name'    => $user->lang['uc_armorylink1'],
                    'link'    => $armory->Link($conf['uc_server_loc'], $CharTools->convertEncoding($row['member_name']), $CharTools->convertEncoding(stripslashes($conf['uc_servername'])), 'char'),
                    'img'     => 'armory.gif',
                    'perm'    => true,
                    ),
                1 => array(
                    'name'    => $user->lang['uc_armorylink2'].' 1',
                    'link'    => $armory->Link($conf['uc_server_loc'], $CharTools->convertEncoding($row['member_name']), $CharTools->convertEncoding(stripslashes($conf['uc_servername'])), 'talent1'),
                    'img'     => 'armory.gif',
                    'perm'    => true,
                    ),
                2 => array(
                    'name'    => $user->lang['uc_armorylink2'].' 2',
                    'link'    => $armory->Link($conf['uc_server_loc'], $CharTools->convertEncoding($row['member_name']), $CharTools->convertEncoding(stripslashes($conf['uc_servername'])), 'talent2'),
                    'img'     => 'armory.gif',
                    'perm'    => true,
                    ),
                3 => array(
                    'name'    => $user->lang['uc_armorylink3'],
                    'link'    => $armory->Link($conf['uc_server_loc'], $CharTools->convertEncoding($row['member_name']), $CharTools->convertEncoding(stripslashes($conf['uc_servername'])), 'reputation'),
                    'img'     => 'armory.gif',
                    'perm'    => true,
                    ),
        				4 => array(
                    'name'    => $user->lang['uc_armorylink4'],
                    'link'    => $armory->Link($conf['uc_server_loc'], $CharTools->convertEncoding($row['member_name']), $CharTools->convertEncoding(stripslashes($conf['uc_servername'])), 'achievements'),
                    'img'     => 'armory.gif',
                    'perm'    => true,
                    ),
                5 => array(
                    'name'    => $user->lang['uc_armorylink5'],
                    'link'    => $armory->Link($conf['uc_server_loc'], $CharTools->convertEncoding($row['member_name']), $CharTools->convertEncoding(stripslashes($conf['uc_servername'])), 'statistics'),
                    'img'     => 'armory.gif',
                    'perm'    => true,
                    ),
                6 => array(
                    'name'    => $user->lang['uc_armorylink6'],
                    'link'    => $armory->Link($conf['uc_server_loc'], $CharTools->convertEncoding($row['member_name']), $CharTools->convertEncoding(stripslashes($conf['uc_servername'])), 'guild', $CharTools->convertEncoding(stripslashes($row['guild']))),
                    'img'     => 'armory.gif',
                    'perm'    => (($row['guild']) ? true : false),
                    ),
	         );
          }
          
            $tpl->assign_block_vars('members_row', array(
                'ROW_CLASS'     => $eqdkp->switch_row_class(),
                'ID'            => $row['mid'],
                
                'ARMORY_LINKS'	=> ($conf['uc_showarmlink'] == 1) ? $jquery->DropDownMenu('alinks'.$row['mid'], 'actionmenu', $cm_alinks, 'plugins/charmanager/images','<img src="images/armory.gif" />') : '',
       			'SPEC1'					=> $spec1[icon],
        		'SPEC2'					=> $spec2[icon],
                
                'GUILD'         => $row['guild'],
                'COUNT'         => $member_count,
                'NAME'          => $row['rank_prefix'] . (( $row['member_status'] == '0' ) ? '<i>' . $row['member_name'] . '</i>' : $row['member_name']) . $row['rank_suffix'],
                'RANK'          => ( !empty($row['rank_name']) ) ? (( $row['rank_hide'] == '1' ) ? '<i>' . '<a href="'.$u_rank_search.'">' .$rankimg. stripslashes($row['rank_name']) . '</a>' . '</i>'  : '<a href="'.$u_rank_search.'">' .$rankimg.stripslashes($row['rank_name']). '</a>') : '&nbsp;',
                'LEVEL'         => ( $row['member_level'] > 0 ) ? $row['member_level'] : '&nbsp;',
                'CLASS'         => ( !empty($row['member_class']) ) ? $row['member_class'] : '&nbsp;',
                'CURRENT'       => $row['member_current'],
                'LASTRAID'      => ( !empty($row['member_lastraid']) ) ? date($user->style['date_notime_short'], $row['member_lastraid']) : '&nbsp;',
                'C_CURRENT'     => color_item($row['member_current']),
                'U_VIEW_MEMBER' => 'profile.php' . $SID . '&amp;' . URI_NAME . '='.$row['member_name'])
            );
            $u_rank_search = '';
            unset($last_loot);

            // So that we can compare this member to the next member,
            // set the value of the previous data to the source
            $previous_data = $row[$previous_source];
        }
    }

    $uri_addon  = ''; // Added to the end of the sort links
    $uri_addon .= '&amp;filter=' . urlencode($filter);
    $uri_addon .= ( $in->get('show') != '' ) ? '&amp;show=' . $in->get('show') : '';

    if ( ($eqdkp->config['hide_inactive'] == 1) && (!$show_all) )
    {
        $footcount_text = sprintf($user->lang['listmembers_active_footcount'], $member_count,
                                  '<a href="listprofiles.php' . $SID . '&amp;' . URI_ORDER . '=' . $current_order['uri']['current'] . '&amp;show=all" class="rowfoot">');
    }
    else
    {
        $footcount_text = sprintf($user->lang['listmembers_footcount'], $member_count);
    }
    $db->free_result($members_result);

$tpl->assign_vars(array(
		'F_MEMBERS' 			=> 'listprofiles.php'.$SID,
		'IS_EDITOR'				=> ($user->check_auth('a_charmanager_edit', false)) ? true : false,
    'V_SID'     			=> str_replace('?' . URI_SESSION . '=', '', $SID),
    
     // JS Code
    'JS_ABOUT'        => $jquery->Dialog_URL('About', $user->lang['about_header'], 'about.php', '380', '320'),
    'JS_EDITCHAR'     => $jquery->Dialog_URL('CMEditChars'.rand(), $user->lang['uc_edit_char'], "addchar.php?mode=edit&secrethash=blubber&editid='+editid+'", '640', '450', 'listprofiles.php'),
    
    'SHOW_ARMLINK'		=> ($conf['uc_showarmlink'] == 1) ? true : false,
    'SHOW_RESIS'      => ($conf['uc_lphideresi'] != 1) ? true : false,
    'L_FILTER'        => $user->lang['filter'],
    'L_NAME'          => $user->lang['name'],
    'L_RANK'          => $user->lang['rank'],
    'L_LEVEL'         => $user->lang['level'],
    'L_CLASS'         => $user->lang['class'],
    'L_ARMOR'         => $user->lang['armor'],
    'L_EARNED'        => $user->lang['earned'],
    'L_SPENT'         => $user->lang['spent'],
    'L_ADJUSTMENT'    => $user->lang['adjustment'],
    'L_CURRENT'       => $user->lang['current'],
    'L_LASTRAID'      => $user->lang['lastraid'],
    'L_LASTLOOT'      => $user->lang['lastloot'],
    'L_BUTTON_EDIT'		=> $user->lang['uc_button_edit'],
    'L_EDIT_CHAR'			=> $user->lang['uc_edit_char'],
    'L_SKILL'					=> $user->lang['uc_tab_skills'],
    'L_ARMORY_LINK1'	=> $user->lang['uc_armorylink1'],
    'L_ARMORY_LINK2'	=> $user->lang['uc_armorylink2'],
    'L_ARMORY_LINK3'	=> $user->lang['uc_armorylink3'],
    'L_GUILD'         => $user->lang['uc_guild'],

    'O_NAME'       		=> $current_order['uri'][0],
    'O_CURRENT'    		=> $current_order['uri'][1],
    'O_LASTRAID'   		=> $current_order['uri'][2],
    'O_LEVEL'      		=> $current_order['uri'][3],
    'O_CLASS'      		=> $current_order['uri'][4],
    'O_RANK'       		=> $current_order['uri'][5],
    'O_FROST'       	=> $current_order['uri'][6],
    'O_ARCANE'     		=> $current_order['uri'][7],
    'O_SHADOW'     		=> $current_order['uri'][8],
    'O_NATURE'     		=> $current_order['uri'][9],
    'O_FIRE'       		=> $current_order['uri'][10],
    'O_GUILD'         => $current_order['uri'][11],

    'URI_ADDON'      	=> $uri_addon,
    'U_LIST_PROFILES' => 'listprofiles.php' . $SID . '&amp;',

    'S_NOTMM'   			=> true,

		'CM_COPYRIGHT'    => $CharTools->Copyright(),
    'ICON_INFO'				=> 'images/info.png',
    'L_CREDIT_NAME'		=> $user->lang['uc_credit_name'],

    
    'LISTMEMBERS_FOOTCOUNT' => $footcount_text
    )
);

$eqdkp->set_vars(array(
    'page_title'    => $CharTools->GeneratePageTitle($user->lang['uc_enu_profiles']),
    'template_file' => 'listprofiles.html',
    'template_path' => $pm->get_data('charmanager', 'template_path'),
    'display'       => true)
);

function member_display(&$row)
{
    global $eqdkp;
    global $query_by_armor, $query_by_class, $filter, $filters, $show_all, $id;

    // Replace space with underscore (for array indices)
    // Damn you Shadow Knights!
    $d_filter = ucwords(str_replace('_', ' ', $filter));
    $d_filter = str_replace(' ', '_', $d_filter);

    $member_display = null;

    // We're filtering based on class

    if ( $filter != 'none'  ) {

       if ( $query_by_class == 1  )
       {

       // Check for valid level ranges
       //if ( $row['member_level'] > $row['min_level'] && $row['member_level'] <= $row['max_level'] ) {

              $member_display = ( ($row['member_class'] == $id ) ) ? true : false;

      // }

       } elseif ( $query_by_armor == 1 ) {

       $rows = strtolower($row['armor_type']);

       // Check for valid level ranges
       if ( $row['member_level'] > $row['min_level'] && $row['member_level'] <= $row['max_level'] ) {

             $member_display = ( $rows == $id  ) ? true : false;

       }

       }
      } else {
           // Are we showing all?
           if ( $show_all )
           {
               $member_display = true;
           }
           else
           {
               // Are we hiding inactive members?
               if ( $eqdkp->config['hide_inactive'] == '0' )
               {
                   //Are we hiding their rank?
                   $member_display = ( $row['rank_hide'] == '0' ) ? true : false;
               }
               else
               {
                   // Are they active?
                   if ( $row['member_status'] == '0' )
                   {
                       $member_display = false;
                   }
                   else
                   {
                       $member_display = ( $row['rank_hide'] == '0' ) ? true : false;
                   } // Member inactive
               } // Not showing inactive members
           } // Not showing all
       } // Not filtering by class

    return $member_display;
}
?>

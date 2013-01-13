<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2010-06-23 12:39:33 +0200 (Wed, 23 Jun 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 8161 $
 * 
 * $Id: wow_macro.class.php 8161 2010-06-23 10:39:33Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$rpexport_plugin['wow_macro.class.php'] = array(
			'name'			    => 'WoW Macro',
			'function'      => 'WoWMacroexport',
			'contact'		    => 'webmaster@wallenium.de',
			'version'		    => '1.0.0');

class WoWMacroexport extends RaidplanExport {
  
  function WoWMacroexport($raid_id){
    global $db, $eqdkp_root_path, $user, $SID, $conf, $group_structure;
    // Set table names
    $text = "<b>".$group_structure[$_GET['group']]."</b><br/>";
  
    $sql = 'SELECT raid_id, member_id, `group`
  	   			FROM ' . RP_ATTENDEES_TABLE . "
  	   			WHERE raid_id='" .(int) $raid_id . "'";
  	if($_GET['group'] == 99 ){
  		$sql .= " AND (attendees_subscribed ='0' OR attendees_subscribed ='1' OR attendees_subscribed ='3')";
  	}elseif($_GET['group'] == 100 ){
  		$sql .= " AND attendees_subscribed ='0'";
  		$sql .= " AND `group`='' OR `group`='0'";
  	}else{
  		$sql .= " AND attendees_subscribed ='0'";
  		$sql .= ($_GET['group']) ? " AND `group`='".$_GET['group']."'": "";
  	}
  	$sql .=	" GROUP BY member_id";
  	$result = $db->query($sql);
    
    // the guests
    $sql = "SELECT tempm.*, class_name
						FROM (" . RP_TEMPMEMBERS_TABLE . " as tempm, " . CLASS_TABLE . " as classes)
						WHERE raid_id='" . (int) $raid_id . "'
						AND classes.class_id=tempm.class";

  	if($_GET['group'] == 100 ){
  		$sql .= " AND `group`='' OR `group`='0'";
  	}else{
  		$sql .= ($_GET['group']) ? " AND `group`='".$_GET['group']."'": "";
  	}
  	$gresult = $db->query($sql);
  	
    $text .= "<textarea name='group".rand()."' cols='60' rows='10' onfocus='this.select()' readonly>";
    while($data = $db->fetch_record($result)){
  	  $sql2 = "SELECT member_name, member_race_id, member_class_id FROM ".MEMBERS_TABLE." WHERE member_id=".$data['member_id']." LIMIT 1";
  	  $result2 = $db->query($sql2);
  	  while($data2 = $db->fetch_record($result2)){
        $membername =  $data2['member_name'];
  		  $text .= "/i ".$membername."\n";
  	  }
    }
    $db->free_result($result);
    while ($row = $db->fetch_record($gresult)){
  		  $text .= "/i ".$row['membername']."\n";
    }
    $db->free_result($gresult);
    
  	$text .= "</textarea>";
  
    $text .= '<br/>'.$user->lang['rp_copypaste_ig']."</b>";
    $this->output = $text;
  } // end MacroList Function 
}
?>

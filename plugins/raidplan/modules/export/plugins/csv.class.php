<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2008-11-03 13:24:11 +0100 (Mon, 03 Nov 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 2963 $
 * 
 * $Id: csv.class.php 2963 2008-11-03 12:24:11Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$rpexport_plugin['csv.class.php'] = array(
			'name'			    => 'CSV',
			'function'      => 'CSVexport',
			'contact'		    => 'webmaster@wallenium.de',
			'version'		    => '1.1.0');

class CSVexport extends RaidplanExport {
  
  function CSVexport($raid_id){
    global $db, $user, $rpclass, $rpconvert, $conf;
    
    $output = "<br/><br/><b>".$user->lang['rp_cvs_output']."</b><br/>";
        
    $sql = 'SELECT rpa.raid_id, rpa.member_id, rpa.attendees_subscribed, rpa.attendees_random, rpa.attendees_note, rpa.`group`, 
            m.member_name, m.member_race_id, m.member_level, c.class_name
    	      FROM ' . RP_ATTENDEES_TABLE . " rpa, ".MEMBERS_TABLE." m, ".CLASS_TABLE." c
    	      WHERE raid_id='" . $raid_id . "'
    	      AND rpa.member_id = m.member_id
    	      AND m.member_class_id = c.class_id
            AND (attendees_subscribed ='0' OR attendees_subscribed ='1' OR attendees_subscribed='3')";
    $result = $db->query($sql);

    while($data = $db->fetch_record($result)){
      $output .= $data['member_name'].":";                                        // Member Name
      $output .= strtoupper($rpconvert->classname($data['class_name'])).":";      // Class
      $output .= $data['member_level'].":";                                       // Level
      $output .= ($data['attendees_subscribed'] == '0')? "1:" : "0:";             // inGroup
        
      // Group of the Member
      if($data['group']>=1 && $conf['rp_enable_groups'] == '1'){                  // Group
        $output .= $data['group'].":";
      }else{
        $output .= "-:";
      }
      
      // replace \r\n with <br/> so multiline comments
      // are displayed in auto_invite
      if($data['attendees_note']){                                                // Note
        $output .= ($data['attendees_note']) ? str_replace("\r\n","&lt;br&gt;",$data['attendees_note'])."&lt;br&gt;" : '';
      }
      
      // Random Value
      if($conf['rp_roll_systm'] == '1'){                                         // Random Value
        $output .= $user->lang['rp_csv_random'].': |CFFB700B7'.$data['attendees_random']."|r&lt;br&gt;";
      }
      $output .= "\n";
    }
    $db->free_result($result);
    $output.="\n";
  
    $output = str_replace("\n","<br/>",$output);
    $output = str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;",$output);
    $this->output = $output;
  }
}
?>

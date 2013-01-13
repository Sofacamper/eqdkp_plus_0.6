<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-08-13 23:33:57 +0200 (Do, 13 Aug 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 5637 $
 * 
 * $Id: chartools.class.php 5637 2009-08-13 21:33:57Z wallenium $
 */

class CharTools
{
  
  /**
  * Check if Server is Online
  **/
  function CheckUptime($sitename){
    if(function_exists('fsockopen')){
      $fp = fsockopen($sitename, 80, $errno, $errstr, 10);
      $upout = ($fp) ? 'Online' : 'Offline';
    }else{
      $upout = 'n/a';
    }
    return $upout;
  }
  
  /**
  * Update Character Connection
  **/
	function updateConnection($member_id){
    global $db, $user, $config;
    // Users -> Members associations
    $sql = 'DELETE FROM __member_user
            WHERE user_id = ' . $user->data['user_id'];
    $db->query($sql);

    if ((isset($_POST['member_id'])) && (is_array($_POST['member_id']))){
      $sql = 'INSERT INTO __member_user
              (member_id, user_id)
              VALUES ';
      $query = array();
      foreach ( $_POST['member_id'] as $member_id ){
        $query[] = '(' . $member_id . ', ' . $user->data['user_id'] . ')';
      }

      $sql .= implode(', ', $query);
      $db->query($sql);
      return true;
    }else{
      return false;
    }   
	}
	
	/**
  * Delete the Character
  **/
	function DeleteChar($member_id){
		global $db;
		// get the member name
		$member_name = $db->query_first('SELECT member_name FROM __members WHERE member_id = '.$member_id);
		$db->query("DELETE FROM __raid_attendees WHERE member_name='".$member_name."'");
		$db->query("DELETE FROM __items WHERE item_buyer='".$member_name."'");
		$db->query("DELETE FROM __adjustments WHERE member_name='".$member_name."'");
		$db->query('DELETE FROM __member_user WHERE member_id='.$member_id);
		$db->query('DELETE FROM __member_additions WHERE member_id='.$member_id);
		$db->query('DELETE FROM __members WHERE member_id='.$member_id);
		return true;
	}
	
	/**
  * Check Armory Permissions..
  **/
	function ImportAuth($myperm){
		global $db, $cmHasImport, $user;
		return ($user->check_auth($myperm, false) && $cmHasImport) ? true : false;
	}
	
	/**
  * Mark the char for deletion for an admin
  **/
	function SuspendChar($member_id){
		global $db;
		$this->chkMemAddTable($member_id);
		$db->query("UPDATE __members SET member_status='0' WHERE member_id=".$member_id);
		$db->query("UPDATE __member_additions SET requested_del='1' WHERE member_id=".$member_id);
	}
	
	/**
  * Rewoke a char (un-delete!)
  **/
	function RewokeChar($member_id){
		global $db;
		$this->chkMemAddTable($member_id);
		$db->query("UPDATE __members SET member_status='1' WHERE member_id=".$member_id);
		$db->query("UPDATE __member_additions SET requested_del='0' WHERE member_id=".$member_id);
	}
	
	/**
  * Confirm a Char
  **/
	function ConfirmChar($member_id){
		global $db;
		$this->chkMemAddTable($member_id);
		$db->query("UPDATE __members SET member_status='1' WHERE member_id=".$member_id);
		$db->query("UPDATE __member_additions SET requested_del='0' WHERE member_id=".$member_id);
		$db->query("UPDATE __member_additions SET require_confirm='0' WHERE member_id=".$member_id);
	}
	
	/**
  * Confirm all chars
  **/
	function ConfirmAllChars(){
		global $db;
		$result = $db->query("SELECT member_id FROM __member_additions WHERE require_confirm='1'");
		while ( $row = $db->fetch_record($result) ){
			$this->ConfirmChar($row['member_id']);
		}
		$db->free_result($result);
	}
	
	/**
  * Delete all chars
  **/
	function DeleteAllChars(){
		global $db;
		$result = $db->query("SELECT member_id FROM __member_additions WHERE requested_del='1'");
		while ( $row = $db->fetch_record($result) ){
			$this->DeleteChar($row['member_id']);
		}
		$db->free_result($result);
	}
	
	/**
  * Check if the Memner additions entry is available for that member
  **/
	function chkMemAddTable($member_id){
		global $db;
		if ($db->query_first("SELECT member_id FROM __member_additions WHERE member_id=".$db->sql_escape($member_id)) > 0){
			return true;
		}else{
			$db->query("INSERT INTO __member_additions (`member_id`) VALUES ('".$member_id."')");
			return true;
		}
	}
	
	/**
  * Take the Character
  **/
  function TakeOverChar($membername){
		global $db, $user, $table_prefix;
    $sql = "SELECT member_id FROM __members WHERE member_name = '".$db->sql_escape($membername)."'";
    $member_id = $db->query_first($sql);
    $sql = 'INSERT INTO __member_user
            (member_id, user_id)
            VALUES ('.$member_id.', '.$user->data['user_id'].')';
    $db->query($sql);
  }
  
  /**
   * Return the WoW Talent Text
   *
   * @param Array $skill array($skill1,$skill2,$skill3)
   * @param String $class
   * @return String
   */
  function SkilltoSpec($skills, $class_id){
    global $user;
    if (!is_array($skills)) {	return "";}
 		asort($skills); //sort the Arry to get the highest skill

 		//get the highest skill
 		foreach ( $skills as $key => $row){
			$spec_number = $key;
		}
		
 		// If no 41 Talent is given, i think its a hybrid
 		if ( ($skills[0] < 40) and ($skills[1] < 40) and ($skills[2] < 40)  ){
 			$spec =	$user->lang['uc_hybrid'] ;
 		}else{
 			$spec =  $user->lang['cm_talents'][$class_id][$spec_number] ;
 		}
 		return $spec;
	}
  
  /**
  * Update the Profile fields
  **/
  function updateChar($memberid='', $membername='', $dataarray = '', $isImport=false){
		global $db, $user, $table_prefix, $conf, $cmapi;
		
		// Import or $_POST?
    $impvar         = ($dataarray && is_array($dataarray)) ? $dataarray : $_POST;
		
    // Make sure that each member's name is properly capitalized
    $membername     = ($membername) ? $membername : $impvar['member_name'];
    $member_name    = strtolower(preg_replace('/[[:space:]]/i', ' ', $membername));
    $member_name    = ucwords($member_name);
    
    // Check for existing member name
    $mySQLsentence  = ($memberid) ? "member_id='".$db->sql_escape($memberid)."'" : "member_name='".$db->sql_escape($member_name)."'";
    $member_id      = $db->query_first("SELECT member_id FROM __members WHERE ".$mySQLsentence);
    $isaddmember    = ( isset($member_id) && $member_id > 0 ) ? false : true;
    if(!$isaddmember){
      $cmmember_id    = $db->query_first("SELECT member_id FROM __member_additions WHERE member_id='".$db->sql_escape($member_id)."'");
      $isaddcmmember  = ( isset($cmmember_id) && $cmmember_id > 0 ) ? false : true;
    }else{
      $isaddcmmember = $isaddmember = true;    //There's no Member ID, we should add the member..
    }
		$updinsert_cm   = ($isaddcmmember) ? 'INSERT' : 'UPDATE';
		
		/**
    * EQDKP Member Table
    **/
		if($isaddmember){
      $query = array(
        'member_name'       => $member_name,
        'member_level'      => $this->ValueorNull($impvar['member_level']),
        'member_race_id'    => $impvar['member_race_id'],
        'member_class_id'   => $impvar['member_class_id'],
        'member_rank_id'		=> $this->ValueorNull($conf['uc_defaultrank']),
        'member_earned'     => '0',
        'member_spent'      => '0',
        'member_adjustment' => '0',
        'member_firstraid'  => '0',
        'member_lastraid'   => '0',
        'member_raidcount'  => '0'
      );
      $myquery = $db->build_query('INSERT', $query);
      $db->query('INSERT INTO __members'. $myquery);
      $memberid = $db->sql_lastid();
    }else{
      $query['member_level']      = (isset($impvar['member_level'])) ? $impvar['member_level'] : 0;
      
      // if the new ID is != 0 && different to the saved one..
      $tmpmemberraceid = $db->query_first("SELECT member_race_id FROM __members WHERE ".$mySQLsentence);
      if($tmpmemberraceid != $impvar['member_race_id'] && $impvar['member_race_id'] != '0'){
        $query['member_race_id']    = $impvar['member_race_id'];
      }
      $tmpmemberclassid = $db->query_first("SELECT member_class_id FROM __members WHERE ".$mySQLsentence);
      if($tmpmemberclassid != $impvar['member_class_id'] && $impvar['member_class_id'] != '0'){
        $query['member_class_id']   = $impvar['member_class_id'];
      }
      
      // Should we update the Member name?
    	if($conf['uc_updaterc']){
        $query['member_name']     = $member_name;
    	}
    	// Build & Insert into __members
      $myQuery = $db->build_query('UPDATE', $query);
      $blubb = $db->query('UPDATE __members SET '.$myQuery." WHERE member_id='".$member_id."'");
    }
    
    /**
    * CHARMANAGER Member Additions Table
    **/
    if($memberid){
      // Start the Member Additions Array
      $myMemAddition = array();
      if($isaddcmmember){
        $myMemAddition['member_id'] = $memberid;
      }

      // Default Data
      $myMemAddition['guild']       = $this->ValueorNull($impvar['guild'], 'string');
      $myMemAddition['last_update'] = ($impvar['last_update']) ? $impvar['last_update'] : time();
  		$myMemAddition['gender']      = $this->ValueorNull($impvar['gender'], 'string');
      if(!$dataarray){
        $myMemAddition['notes']     = $this->ValueorNull(htmlspecialchars($impvar['notes'], ENT_QUOTES), 'string');
      }
      if(!$isImport){
        $myMemAddition['picture']   = $impvar['member_pic'];
      }
      // WOW Only...
      if($conf['real_gamename'] == 'wow'){
        $myMemAddition['skill_1']         = $this->ValueorNull($impvar['skill_1']);
        $myMemAddition['skill_2']         = $this->ValueorNull($impvar['skill_2']);
        $myMemAddition['skill_3']         = $this->ValueorNull($impvar['skill_3']);
        $myMemAddition['skill2_1']        = $this->ValueorNull($impvar['skill2_1']);
        $myMemAddition['skill2_2']        = $this->ValueorNull($impvar['skill2_2']);
        $myMemAddition['skill2_3']        = $this->ValueorNull($impvar['skill2_3']);
        $myMemAddition['blasc_id']        = $this->ValueorNull($impvar['blasc_id'], 'string');
        $myMemAddition['ct_profile']      = $this->ValueorNull($impvar['ct_profile'], 'string');
        $myMemAddition['curse_profiler']  = $this->ValueorNull($impvar['curse_profiler'], 'string');
        $myMemAddition['allakhazam']      = $this->ValueorNull($impvar['allakhazam'], 'string');
        $myMemAddition['talentplaner']    = $this->ValueorNull($impvar['talentplaner'], 'string');
        $myMemAddition['health_bar']      = $this->ValueorNull($impvar['health_bar'], 'string');
        $myMemAddition['second_bar']      = $this->ValueorNull($impvar['second_bar'], 'string');
        $myMemAddition['second_name']     = $this->ValueorNull($impvar['second_name'], 'string');
        $myMemAddition['prof1_value']     = $this->ValueorNull($impvar['prof1_value'], 'string');
        $myMemAddition['prof1_name']      = $this->ValueorNull($impvar['prof1_name'], 'string');
        $myMemAddition['prof2_value']     = $this->ValueorNull($impvar['prof2_value'], 'string');
        $myMemAddition['prof2_name']      = $this->ValueorNull($impvar['prof2_name'], 'string');
        $myMemAddition['faction']         = $this->ValueorNull($impvar['faction'], 'string');
        
        if($conf['uc_reqconfirm'] && $isaddmember){
        	$myMemAddition['require_confirm']	= '1';
        }
        
        if($conf['uc_noresisave'] != 1 || !$isImport){
          $myMemAddition['fir']           = $this->ValueorNull($impvar['fire']);
          $myMemAddition['nr']            = $this->ValueorNull($impvar['nature']);
          $myMemAddition['sr']            = $this->ValueorNull($impvar['shadow']);
          $myMemAddition['ar']            = $this->ValueorNull($impvar['arcane']);
          $myMemAddition['frr']           = $this->ValueorNull($impvar['ice']);
        }
      }
      $query = $db->build_query($updinsert_cm, $myMemAddition);
      if($isaddcmmember){
        $blubb = $db->query('INSERT INTO __member_additions' . $query);
      }else{
        $blubb = $db->query('UPDATE __member_additions SET '.$query." WHERE member_id='".$member_id."'");
      }
      $failure_message = array('true','','');
    }else{
      $failure_message = array('false','','');
    }
    
    // Take the cake... oh.. its a char.. :D.. maybe a female char IN a cake? :D
    if ($_POST['overtakeuser']){
	      $this->TakeOverChar($member_name);
    }
    return $failure_message;
	} // end of update
	
	/**
  * Copyright
  **/
	function Copyright(){
    global $pm, $user, $conf;
    $cm_version = ($conf['cm_hideversion']) ? '' : ' '.$pm->get_data('charmanager', 'version');
    return 'Charmanager'.$cm_version.' '.$pm->plugins['charmanager']->vstatus.' &copy; 2006 - '.date("Y", time()).' by '.$pm->plugins['charmanager']->copyright;
  }
  
  /**
  * Page title
  **/
  function GeneratePageTitle($name){
    global $user, $eqdkp;
    return sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['charmanager'].' - '.$name;
  }
  
  /**
  * Value or NULL Helper Class
  **/
  function ValueorNull($inp){
  	if(is_int($inp)){
  		return ($inp) ? $inp : '0';
  	}else{
  		return ($inp) ? $inp : '';
  	}
  }
  
  function convertEncoding($name, $method='encode', $encoding='UTF-8'){
    global $user;
    if($user->lang['ENCODING']){
      if($method == 'encode'){
        return mb_convert_encoding($name,"UTF-8",$user->lang['ENCODING']);
      }else{
        return mb_convert_encoding($name,$user->lang['ENCODING'], "UTF-8");
      }
    }else{
      return $name;
    }
  }
}// end of class
?>
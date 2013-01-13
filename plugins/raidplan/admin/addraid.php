<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2012-06-25 15:28:34 +0200 (Mon, 25 Jun 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 11834 $
 * 
 * $Id: addraid.php 11834 2012-06-25 13:28:34Z wallenium $
 */
 
define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'raidplan');
$eqdkp_root_path = './../../../';
include_once('../includes/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'raidplan')) { message_die('The Raid Planer plugin is not installed.'); }

// Check user permission
$user->check_auth('a_raidplan_');

// Get the plugin
$raidplan = $pm->get_plugin(PLUGIN);

class RPAddRaid extends EQdkp_Admin
{
	var $raid 			= array();
	var $classes 		= array();
	var $attendees 	= array();
	var $members 		= array();
	var $member 		= array();	
	
    // ---------------------------------------------------------
    // Constructor
    // ---------------------------------------------------------
    function RPAddRaid(){
      global $db, $eqdkp, $user, $tpl, $pm, $rpclass, $SID, $stime, $conf, $rpconvert;
      parent::eqdkp_admin();
		  $this->set_vars(array('uri_parameter' => URI_RAID));
      $this->frontendadd = ($_GET['fe'] == '1') ? '1' : '0';
      
      if ((isset($_GET[URI_RAID])) && (intval($_GET[URI_RAID] > 0))){
        $sql = 'SELECT raid_id, raid_name, raid_date, raid_date_invite, raid_date_finish,
  		    			raid_date_subscription, raid_note, raid_leader, 
  		    			raid_value, raid_attendees, raid_added_by, raid_updated_by,
  		    			raid_repeat, delete_id, raid_link, raid_distribution
  							FROM ' . RP_RAIDS_TABLE . "	WHERE raid_id='".(int) $_GET[URI_RAID]."'";
  			if (!($result = $db->query($sql))) { message_die('Could not obtain raid information', '', __FILE__, __LINE__, $sql); }
  		
  			// Check for valid raid
  			if (!$raid = $db->fetch_record($result)){	message_die($user->lang['error_invalid_raid_provided']); }
  			$db->free_result($result);
  			$this->raid = $raid;
		  }

      // Build class list
		  if($_GET['template'] && $_GET['template'] != '0' && !$_GET['deltemplate']){
			  $templateinuse = true;
        $datasql = "SELECT raid_distribution FROM ".RP_RAID_TEMP_TABLE." WHERE template_id='".(int) $_GET['template']."'";
  		  $this->role_distri = $db->query_first($datasql);
		  }elseif((isset($_GET[URI_RAID])) && (intval($_GET[URI_RAID] > 0))){
        $this->role_distri = $this->raid['raid_distribution'];
      }else{
        // maybe set a default value instead of nil...
        $this->role_distri = (isset($_GET['role_distri'])) ? $_GET['role_distri'] : $conf['default_distri'];
        $this->role_distri = ($this->role_distri) ? $this->role_distri : 0;
      }

		// Build class list
    	if($this->role_distri == 1){
		  // generate the role array
		  $sql = 'SELECT class_name, class_count FROM ' . RP_CLASSES_TABLE . " WHERE raid_id='".(int) $_GET[URI_RAID]."'";
		  $result = $db->query($sql);
		  while ($row = $db->fetch_record($result)){
        if(strtolower($row['class_name']) != 'unknown'){
  				$tmpcount[$row['class_name']] = $row['class_count'];
  			}
  		}			
  		$db->free_result($result);
		  
		  $sql = 'SELECT * FROM ' . ROLES_TABLE;
		  $result = $db->query($sql);
		  
		  while ($row = $db->fetch_record($result)){
  		  $this->classes[$row['role_name']] = array(
  		          'id'      => $row['role_id'],
    						'name'		=> $row['role_name'],
    						'name_en'	=> $row['role_image'],
    						'count'   => $tmpcount[$row['role_image']],
    					);
  		}
  		$this->attendees_summ = 0;
	  }elseif($this->role_distri == 2){
      $this->attendees_summ = 1;
    }else{	
	    if($_GET[URI_RAID]){
        // get the count
        $sql = 'SELECT class_name, class_count
                FROM '.RP_CLASSES_TABLE." 
                WHERE raid_id='".(int) $_GET[URI_RAID]."'";
        $result = $db->query($sql);
  		  while ($row = $db->fetch_record($result)){
  		    $attendees4raid[$row['class_name']] = $row['class_count'];
        }
        $db->free_result($result);
	     
        $sql = 'SELECT c.class_name, c.class_id
                FROM '.CLASS_TABLE.' c
                GROUP BY c.class_name 
                ORDER BY c.class_name';
        if (!($result = $db->query($sql))) { message_die('Could not obtain class information', '', __FILE__, __LINE__, $sql); }
        while ($row = $db->fetch_record($result)){
  		    if(strtolower($row['class_name']) != 'unknown'){
  		      $enclassname = $rpconvert->classname($row['class_name']);
    		    $this->classes[$row['class_name']] = array(
        				'id'				=> $row['class_id'],
        				'name'			=> $rpconvert->classname($row['class_name'], true),
        				'name_en'		=> $enclassname,
        				'count'     => $attendees4raid[$enclassname],
            );
          }
  		  }
      }else{
         $sql = 'SELECT class_name, class_id
                FROM '.CLASS_TABLE.'
                GROUP BY class_name 
                ORDER BY class_name';
        if (!($result = $db->query($sql))) { message_die('Could not obtain class information', '', __FILE__, __LINE__, $sql); }
        while ($row = $db->fetch_record($result)){
  		    if(strtolower($row['class_name']) != 'unknown'){
  		      $this->classes[$row['class_name']] = array(
      				'id'				=> $row['class_id'],
      				'name'			=> $row['class_name'],
      				'name_en'		=> $rpconvert->classname($row['class_name']),
      				'count'     => $row['class_count'],
            );
          }
  		  }
      }
  		$db->free_result($class_result);
  		$this->attendees_summ = 0;
		}

        $this->assoc_buttons(array(
            'add' => array(
                'name'    => 'add',
                'process' => 'process_add',
                'check'   => 'a_raidplan_add'),
            'update' => array(
                'name'    => 'update',
                'process' => 'process_update',
                'check'   => 'a_raidplan_update'),
            'template' => array(
                'name'    => 'template',
                'process' => 'process_template',
                'check'   => 'a_raidplan_update'),
            'form' => array(
                'name'    => '',
                'process' => 'display_form',
                'check'   => 'u_raidplan_view')
			));
    }

    // ---------------------------------------------------------
    // Form error check
    // ---------------------------------------------------------
	function error_check(){
        global $user;

        $this->fv->is_number('raid_attendees_count', $user->lang['fv_required_attendees']);
  
        if ( !empty($_POST['raid_value']) )
        {
            $this->fv->is_number('raid_value', $user->lang['fv_number_value']);
        }
    
        if ( (@empty($_POST['raid_name'])) || (@sizeof($_POST['raid_name']) == 0) )
        {
            $this->fv->errors['raid_name'] = $user->lang['fv_required_event_name'];
        }
        return $this->fv->is_error();
    }

    // ---------------------------------------------------------
    // Process Add
    // ---------------------------------------------------------
  function process_add(){
    global $db, $eqdkp, $user, $tpl, $pm, $rpclass, $SID, $conf, $stime, $jquery;    

		$success_message = "";
		
		$this_raid_id = 0;
			
    // Get the raid value
    $raid_value = $rpclass->get_raid_value($_POST['raid_name']);
			
		// Build raid date
		// raid date
		$raid_date_day 						= substr($_POST['RAID_DATE'],0,2);
		$raid_date_month 					= substr($_POST['RAID_DATE'],3,2);
		$raid_date_year 					= substr($_POST['RAID_DATE'],6);
		
		// invite time
		$raid_date_inv_day 				= substr($_POST['RAID_DATE_INV'],0,2);
		$raid_date_inv_month 			= substr($_POST['RAID_DATE_INV'],3,2);
		$raid_date_inv_year 			= substr($_POST['RAID_DATE_INV'],6);
		
		// subscription time
		$raid_date_sub_day 				= substr($_POST['RAID_DATE_SUB'],0,2);
		$raid_date_sub_month 			= substr($_POST['RAID_DATE_SUB'],3,2);
		$raid_date_sub_year 			= substr($_POST['RAID_DATE_SUB'],6);
			
		// finish
		$raid_date_finish_day 		= substr($_POST['RAID_DATE_FINISH'],0,2);
		$raid_date_finish_month 	= substr($_POST['RAID_DATE_FINISH'],3,2);
		$raid_date_finish_year 		= substr($_POST['RAID_DATE_FINISH'],6);
			
		$raid_date 								= $stime->rp_mktime($_POST['start_hour'],$_POST['start_min'],0,$raid_date_month,$raid_date_day,$raid_date_year);
		$raid_date_invite 				= $stime->rp_mktime($_POST['invite_hour'],$_POST['invite_min'],0,$raid_date_inv_month,$raid_date_inv_day,$raid_date_inv_year);
		$raid_subscription_date 	= $stime->rp_mktime($_POST['end_hour'],$_POST['end_min'],0,$raid_date_sub_month,$raid_date_sub_day,$raid_date_sub_year);
		$raid_finish_date 				= $stime->rp_mktime($_POST['finish_hour'],$_POST['finish_min'],0,$raid_date_finish_month,$raid_date_finish_day,$raid_date_finish_year);
		$now 											= $stime->DoTime();
			
    // Insert the raid
    $query = $db->build_query('INSERT', array(
                'raid_name'								=> stripslashes($_POST['raid_name']),
								'raid_date'								=> $raid_date,
								'raid_date_invite'				=> $raid_date_invite,
								'raid_date_subscription'	=> $raid_subscription_date,
								'raid_date_finish' 				=> $raid_finish_date,
								'raid_date_added'					=> $stime->DoTime(),
                'raid_note'								=> stripslashes($_POST['raid_note']),
                'raid_leader'							=> stripslashes($_POST['raid_leader']),
                'raid_repeat'							=> $_POST['raid_repeat'],
                'raid_link'							  => $_POST['raid_link'],
                'raid_value'							=> $raid_value,
                'raid_added_by'						=> $this->admin_user,
								'raid_attendees'					=> stripslashes($_POST['raid_attendees_count']),
								'raid_distribution'       => ($_POST['rp_distri']) ? $_POST['rp_distri'] : '0',
			));
    $db->query('INSERT INTO ' . RP_RAIDS_TABLE . $query);
    $this_raid_id = $db->insert_id();
    if($_POST['raid_repeat'] != 0){
      $query2 = $db->build_query('INSERT', array(
                													'repeat_id'		=> 'NULL',
                													'raid_id'			=> $this_raid_id,
                													'next_date'		=> $raid_date,
                													'job_date'		=> $stime->DoTime(),
                													'delete_id'		=> $this_raid_id
																));
		  $db->query('INSERT INTO ' . RP_REPEAT_TABLE . $query2);
		}
		// Insert class counts
		while (list ($key, $val) = each ($_POST)) {
			if (preg_match('/^(raid_class_.+_count)$/', $key, $match)){
				if ($val > 0){
					$class_name = explode('_', $key);
					// Sanatize the "name" for Javascript.. no spaces & -
					$rname = str_replace("0", " ", $class_name[2]);
					$rname = str_replace("9", "-", $rname);
					$query = $db->build_query('INSERT', array(
							'raid_id'		  => $this_raid_id,
							'class_name'	=> $rname,
							'class_count'	=> $val)
						);
					$db->query('INSERT INTO ' . RP_CLASSES_TABLE . $query);
				}
			}
		}
		// email notifications
    if ($conf['rp_send_email'] == 1) {
      $sql  = 'SELECT user_email, username FROM '.USERS_TABLE;
      $sql .= ($conf['rp_activeonly_sn'] == 1) ? " WHERE user_active='1'" : '';
      if (!($emailresult = $db->query($sql))) { message_die('Could not obtain user email information', '', __FILE__, __LINE__, $sql); }
      
      include_once('../includes/email/email.class.php');
      $mail = new MyMailer();
      $RP_server_url = $mail->BuildLink();
      
      while ($row = $db->fetch_record($emailresult)){        
        // Insert the Content in the body:
        $bodyvars = array(
          'USERNAME'    => $row['username'],
          'RAID_NAME'   => stripslashes($_POST['raid_name']),
          'RAID_NOTE'   => stripslashes($_POST['raid_note']),
          'ADMIN_USER'  => $this->admin_user,
          'RAID_LINK'   => $RP_server_url."plugins/raidplan/viewraid.php?s=&r=".$this_raid_id,
          'DATE'        => $stime->DoDate($conf['timeformats']['short'], $raid_date),
        );

        $mail->SendMailFromAdmin($row['user_email'], $user->lang['rp_sendheader'], 'newraid.html', $bodyvars, $conf['rp_mailmethod']);
      }
      $db->free_result($emailresult);
    }// end of Email notification
		
		// Auto Populate Attendees
		if($conf['rp_autoaddbyrnk'] == 1 && isset($conf['rp_rank_add'])){
		  $postrank = ($conf['rp_autoaddflex']) ? $_POST['rp_autoaddrank'] : $conf['rp_rank_add'];
		  $rpclass->AddMembersByRank($this_raid_id, $_POST['rp_distri'], $postrank);
		}
		// end of Auto Populate
		
		// Logging
    $log_action = array(
            'header'        => '{L_ACTION_RPRAID_ADDED}',
            'id'            => $db->insert_id(),
            '{L_EVENT}'     => $_POST['raid_name'],
            '{L_NOTE}'      => $user->lang['rp_raid_act_add'],
            '{L_VALUE}'     => $raid_value,
            '{L_ADDED_BY}'  => $this->admin_user);
     $this->log_insert(array(
                'log_type'   => $log_action['header'],
                'log_action' => $log_action)
            );
			$db->free_result($result);
			$redir_url = ($_POST['frontend'] == '1') ? '../listraids.php' : 'index.php';
      echo "<script>parent.window.location.href = '".$redir_url."';</script>";
    }

    // ---------------------------------------------------------
    // Process Update
    // ---------------------------------------------------------
    function process_update(){
      global $db, $eqdkp, $user, $tpl, $pm, $SID, $stime, $rpclass;

		  $success_message = "";

		  // Get the raid value
		  $raid_value 							= $rpclass->get_raid_value($_POST['raid_name']);

			// raid date
			$raid_date_day 						= substr($_POST['RAID_DATE'],0,2);
			$raid_date_month 					= substr($_POST['RAID_DATE'],3,2);
			$raid_date_year 					= substr($_POST['RAID_DATE'],6);
			
			// invite time
			$raid_date_inv_day 				= substr($_POST['RAID_DATE_INV'],0,2);
			$raid_date_inv_month 			= substr($_POST['RAID_DATE_INV'],3,2);
			$raid_date_inv_year 			= substr($_POST['RAID_DATE_INV'],6);
			
			// subscription time
			$raid_date_sub_day 				= substr($_POST['RAID_DATE_SUB'],0,2);
			$raid_date_sub_month 			= substr($_POST['RAID_DATE_SUB'],3,2);
			$raid_date_sub_year 			= substr($_POST['RAID_DATE_SUB'],6);
			
				// finish
			$raid_date_finish_day 		= substr($_POST['RAID_DATE_FINISH'],0,2);
			$raid_date_finish_month 	= substr($_POST['RAID_DATE_FINISH'],3,2);
			$raid_date_finish_year 		= substr($_POST['RAID_DATE_FINISH'],6);
			
			$raid_date 								= $stime->rp_mktime($_POST['start_hour'],$_POST['start_min'],0,$raid_date_month,$raid_date_day,$raid_date_year);
			$raid_date_invite 				= $stime->rp_mktime($_POST['invite_hour'],$_POST['invite_min'],0,$raid_date_inv_month,$raid_date_inv_day,$raid_date_inv_year);
			$raid_subscription_date 	= $stime->rp_mktime($_POST['end_hour'],$_POST['end_min'],0,$raid_date_sub_month,$raid_date_sub_day,$raid_date_sub_year);
			$raid_finish_date 				= $stime->rp_mktime($_POST['finish_hour'],$_POST['finish_min'],0,$raid_date_finish_month,$raid_date_finish_day,$raid_date_finish_year);
      $now 											= $stime->DoTime();

		// Update the raid
		$arrange_query1 = array(
			'raid_name'								=> stripslashes($_POST['raid_name']),
			'raid_note'								=> stripslashes($_POST['raid_note']),
			'raid_link'               => $_POST['raid_link'],
			'raid_value'							=> $raid_value,
			'raid_updated_by'					=> $this->admin_user,
			'raid_date_change'				=> $stime->DoTime(),
			'raid_leader'							=> stripslashes($_POST['raid_leader']),
			'raid_attendees'					=> stripslashes($_POST['raid_attendees_count']),
		);
		// generate the class count
		$attendee_count = array();
		$iii = 0;
		while (list ($key, $val) = each ($_POST)) {
      if (preg_match('/^(raid_class_.+_count)$/', $key, $match)){
    	 if ($val > 0){
        $class_name = explode('_', $key);
        // Sanatize the "name" for Javascript.. no spaces & -
				$rname = str_replace("0", " ", $class_name[2]);
				$rname = str_replace("9", "-", $rname);
        $attendee_count[$iii] = array(
          'class_name'	=> $rname,
          'class_count'	=> $val
        );
  		  }
    	}
    	$iii++;
  	}
  	
		if($_POST['rp_edit_all_repeat'] == 1 && $_POST['multiple_id']){
		  $query = $db->build_query('UPDATE', $arrange_query1);
		  $this->MassUpdateRaids($_POST['multiple_id'], $query, $attendee_count);
    }else{
      $arrange_query2 = array(
        'raid_date'								=> $raid_date,
        'raid_date_invite'				=> $raid_date_invite,
        'raid_date_subscription'	=> $raid_subscription_date,
        'raid_date_finish'        => $raid_finish_date,
        'raid_repeat'							=> ($_POST['raid_repeat']) ? $_POST['raid_repeat'] : 0,
      );
      $arrange_query = array_merge($arrange_query1, $arrange_query2);
      $query = $db->build_query('UPDATE', $arrange_query);
		  $this->NormalUpdateRaids($this->url_id, $query, $attendee_count);
		}
    // Logging
    $log_action = array(
            'header'        => '{L_ACTION_RPRAID_CHANGED}',
            'id'            => $db->insert_id(),
            '{L_EVENT}'     => $_POST['raid_name'],
            '{L_NOTE}'      => $user->lang['rp_raid_act_change'],
            '{L_VALUE}'     => $raid_value,
            '{L_ADDED_BY}'  => $this->admin_user);
     $this->log_insert(array(
                'log_type'   => $log_action['header'],
                'log_action' => $log_action)
            );
            
        // Redirect to Main Page
				$redir_url = ($_POST['frontend'] == '1') ? '../listraids.php' : 'index.php';
      echo "<script>parent.window.location.href = '".$redir_url."';</script>";
	}
	  
	  // ---------------------------------------------------------
    // Normal Raid update function
    // ---------------------------------------------------------
	  function NormalUpdateRaids($raid_id, $query, $attendee_count){
	   global $db;
      $db->query('UPDATE ' . RP_RAIDS_TABLE . ' SET ' . $query . " WHERE raid_id='".$raid_id."'");
		  // Delete class counts
		  $db->query('DELETE FROM ' . RP_CLASSES_TABLE . " WHERE raid_id='".$raid_id."'");

		  // Insert class counts
  		foreach($attendee_count as $insertarray1){
  		  $insertarray2 = array('raid_id'		=> $raid_id);
  		  $insertarray = array_merge($insertarray1, $insertarray2);
        $query = $db->build_query('INSERT', $insertarray);
        $db->query('INSERT INTO ' . RP_CLASSES_TABLE . $query);
      }
    }
	  
	  // ---------------------------------------------------------
    // Mass Update for repeatable raids
    // ---------------------------------------------------------
	  function MassUpdateRaids($repeat_id, $query, $attendee_count){
      global $db, $stime;
      $sql = "SELECT raid_id, raid_date, raid_date_invite, 
              raid_date_subscription, raid_date_finish
              FROM `".RP_RAIDS_TABLE."` 
              WHERE (raid_repeat='1' OR raid_repeat='2')
              AND (delete_id=".$repeat_id." OR raid_id=".$repeat_id.")";
      $result = $db->query($sql);
      while ($row = $db->fetch_record($result)){

        // generate the new timestamps & Build an array
        $date_query = array(
    			'raid_date'              => $stime->rp_mktime($_POST['start_hour'],$_POST['start_min'],0,$stime->DoDate('%m', $row['raid_date']),$stime->DoDate('%d', $row['raid_date']),$stime->DoDate('%Y', $row['raid_date'])),
    			'raid_date_invite'       => $stime->rp_mktime($_POST['invite_hour'],$_POST['invite_min'],0,$stime->DoDate('%m', $row['raid_date_invite']),$stime->DoDate('%d', $row['raid_date_invite']),$stime->DoDate('%Y', $row['raid_date_invite'])),
    			'raid_date_subscription' => $stime->rp_mktime($_POST['end_hour'],$_POST['end_min'],0,$stime->DoDate('%m', $row['raid_date_subscription']),$stime->DoDate('%d', $row['raid_date_subscription']),$stime->DoDate('%Y', $row['raid_date_subscription'])),
    			'raid_date_finish'       => $stime->rp_mktime($_POST['finish_hour'],$_POST['finish_min'],0,$stime->DoDate('%m', $row['raid_date_finish']),$stime->DoDate('%d', $row['raid_date_finish']),$stime->DoDate('%Y', $row['raid_date_finish'])),
    		);
        $query2 = $db->build_query('UPDATE', $date_query);
              
        // The Updates...
        $db->query('UPDATE '.RP_RAIDS_TABLE.' SET '.$query." WHERE raid_id='".$row['raid_id']."'");
        $db->query('UPDATE '.RP_RAIDS_TABLE.' SET '.$query2." WHERE raid_id='".$row['raid_id']."'");
        
        // The Class Counts
        $db->query('DELETE FROM '.RP_CLASSES_TABLE." WHERE raid_id='".$row['raid_id']."'");
    		foreach($attendee_count as $insertarray1){
    		  $insertarray2 = array('raid_id'		=> $row['raid_id']);
    		  $insertarray = array_merge($insertarray1, $insertarray2);
          $query2 = $db->build_query('INSERT', $insertarray);
          $db->query('INSERT INTO ' . RP_CLASSES_TABLE . $query2);
        }
      }// end of while row
       $db->free_result($result);
    }
	  
		// ---------------------------------------------------------
    // Process Template 
    // ---------------------------------------------------------
    function process_template(){
      global $db, $eqdkp, $user, $tpl, $pm, $SID, $stime;
        
      $success_message = "";
      $template_name = ($_POST['templatename']) ? $_POST['templatename'] : $_POST['raid_name'];
      
      // Check if the template exists:
      $datasql = "SELECT * FROM " . RP_RAID_TEMP_TABLE." WHERE template_name='" . stripslashes($template_name) . "'";
			$dataresult = $db->query($datasql);
			while ($datarow = $db->fetch_record($dataresult)){
        // Delete old set
  			if($datarow['template_name'] == $template_name && $datarow['raid_distribution'] == $_POST['rp_distri']){
          $sql = 'DELETE FROM ' . RP_RAID_TEMP_TABLE . " WHERE template_id='".(int) $datarow['template_id']. "'";
          $db->query($sql);
        }
			}			
			
			// raid date
			$raid_date_day 		= substr($_POST['RAID_DATE'],0,2);
			$raid_date_month 	= substr($_POST['RAID_DATE'],3,2);
			$raid_date_year 	= substr($_POST['RAID_DATE'],6);
			
			// invite time
			$raid_date_inv_day 		= substr($_POST['RAID_DATE_INV'],0,2);
			$raid_date_inv_month 	= substr($_POST['RAID_DATE_INV'],3,2);
			$raid_date_inv_year 	= substr($_POST['RAID_DATE_INV'],6);
			
			// subscription time
			$raid_date_sub_day 		= substr($_POST['RAID_DATE_SUB'],0,2);
			$raid_date_sub_month 	= substr($_POST['RAID_DATE_SUB'],3,2);
			$raid_date_sub_year 	= substr($_POST['RAID_DATE_SUB'],6);
			
				// finish
			$raid_date_finish_day 		= substr($_POST['RAID_DATE_FINISH'],0,2);
			$raid_date_finish_month 	= substr($_POST['RAID_DATE_FINISH'],3,2);
			$raid_date_finish_year 		= substr($_POST['RAID_DATE_FINISH'],6);
			
			$raid_date 							= $stime->rp_mktime($_POST['start_hour'],$_POST['start_min'],0,$raid_date_month,$raid_date_day,$raid_date_year);
			$raid_date_invite 			= $stime->rp_mktime($_POST['invite_hour'],$_POST['invite_min'],0,$raid_date_inv_month,$raid_date_inv_day,$raid_date_inv_year);
			$raid_finish_date 			= $stime->rp_mktime($_POST['finish_hour'],$_POST['finish_min'],0,$raid_date_finish_month,$raid_date_finish_day,$raid_date_finish_year);
      $raid_subscription_date = $stime->rp_mktime($_POST['end_hour'],$_POST['end_min'],0,$raid_date_sub_month,$raid_date_sub_day,$raid_date_sub_year);
			
						$query = $db->build_query('INSERT', array(
							'template_name'						=> stripslashes($template_name),
							'raid_name'								=> stripslashes($_POST['raid_name']),
							'raid_note'								=> stripslashes($_POST['raid_note']),
							'raid_leader'							=> stripslashes($_POST['raid_leader']),
							'raid_value'							=> $_POST['raid_value'],
							'raid_attendees'					=> $_POST['raid_attendees_count'],
							'raid_date'								=> $raid_date,
							'raid_date_invite'				=> $raid_date_invite,
							'raid_date_subscription'	=> $raid_subscription_date,
							'raid_date_finish'        => $raid_finish_date,
							'raid_link'               => $_POST['raid_link'],
							'raid_distribution'       => ($_POST['rp_distri']) ? $_POST['rp_distri'] : '0',
							)
						);
						$db->query('INSERT INTO ' . RP_RAID_TEMP_TABLE . $query);
						$template_id = $db->insert_id();
						
						//Class Distribution
		if($_POST['raid_name']){

			$sql = 'DELETE FROM ' . RP_CLASS_DIST_TABLE . " WHERE template_id='" .(int) $template_id . "'";
			$db->query($sql);
			while (list ($key, $val) = each ($_POST)) {
				if (preg_match('/^(raid_class_.+_count)$/', $key, $match))
				{
					if ($val > 0)
					{
						$class_name = explode('_', $key);
						// Sanatize the "name" for Javascript.. no spaces & -
            $rname = str_replace("0", " ", $class_name[2]);
            $rname = str_replace("9", "-", $rname);
						$query = $db->build_query('INSERT', array(
							'event_name'	=> stripslashes($_POST['raid_name']),
							'template_id' => $template_id,
							'class_name'	=> $rname,
							'class_count'	=> $val)
						);
						$db->query('INSERT INTO ' . RP_CLASS_DIST_TABLE . $query);
					}
				}
			}

		  $success_message = $user->lang['rp_class_distribution_set'];
    }else{
      $success_message = $user->lang['rp_class_distribution_notset'];
    }
				 // Logging
    $log_action = array(
            'header'        => '{L_ACTION_RPTEMPL_ADDED}',
            'id'            => $db->insert_id(),
            '{L_EVENT}'     => $template_name,
            '{L_NOTE}'      => $user->lang['rp_raid_templ_change'],
            '{L_VALUE}'     => stripslashes($raid_name),
            '{L_ADDED_BY}'  => $this->admin_user);
     $this->log_insert(array(
                'log_type'   => $log_action['header'],
                'log_action' => $log_action)
            );

			redirect('plugins/'.PLUGIN.'/admin/addraid.php');
	}

    // ---------------------------------------------------------
    // Process Delete (confirmed)
    // ---------------------------------------------------------
    function process_delete(){
      global $db, $eqdkp, $user, $tpl, $pm, $SID, $stime, $jquery;
        
      $this->get_old_data();    // Get the old data
			if($user->check_auth('a_raidplan_delete', false)){
				if($_POST['rp_del_all_repeat'] == 1){
					$rpsql = 'SELECT raid_id, delete_id, raid_repeat FROM ' . RP_RAIDS_TABLE . ' WHERE delete_id='.$this->old_raid['delete_id'].' OR raid_id='.$this->old_raid['delete_id'].';';
					$rpraids_result = $db->query($rpsql);
					while($rprow = $db->fetch_record($rpraids_result)){
            if($rprow['raid_repeat']){
  						$db->query('DELETE FROM ' . RP_ATTENDEES_TABLE . " WHERE raid_id='" . $rprow['raid_id'] . "'");	// Delete attendees
  						$db->query('DELETE FROM ' . RP_CLASSES_TABLE . " WHERE raid_id='" . $rprow['raid_id'] . "'"); 	// Delete classes
  						$db->query('DELETE FROM ' . RP_RAIDS_TABLE . " WHERE raid_id='" . $rprow['raid_id'] . "'");			// Delete raid
  						if($delraidtmp = false){
  							$db->query('DELETE FROM ' . RP_REPEAT_TABLE . " WHERE delete_id='" . $rprow['delete_id'] . "'");// Delete raid-repeat
  							$delraidtmp = true;
  						}
  					}
					}
				}else{
					$db->query('DELETE FROM ' . RP_ATTENDEES_TABLE . " WHERE raid_id='" . (int) $this->url_id . "'");				// Delete attendees
					$db->query('DELETE FROM ' . RP_CLASSES_TABLE . " WHERE raid_id='" . (int) $this->url_id . "'");					// Delete classes
					$db->query('DELETE FROM ' . RP_RAIDS_TABLE . " WHERE raid_id='" . (int) $this->url_id . "'");						// Delete raid
				}
				
		// Logging
    			$log_action = array(
            'header'        => '{L_ACTION_RPRAID_DEL}',
            'id'            => $this->url_id,
            '{L_EVENT}'     => $user->lang['rp_raid_act_del'],
            '{L_NOTE}'      => $user->lang['rp_raid_act_del'],
            '{L_VALUE}'     => '',
            '{L_ADDED_BY}'  => $this->admin_user);
     			$this->log_insert(array(
                'log_type'   => $log_action['header'],
                'log_action' => $log_action)
            );
      // Close Window & reload Mainpage
      $redir_url = ($_POST['frontend'] == 1) ? '../listraids.php' : 'index.php';
      echo "<script>parent.window.location.href = '".$redir_url."';</script>";
		}else{
			message_die('No Permissions to delete');
		}
	}

    // ---------------------------------------------------------
    // Display form
    // ---------------------------------------------------------
    function display_form(){	
		   	global $db, $eqdkp, $user, $tpl, $pm, $conf, $rpclass, $eqdkp_root_path, $SID, $stime, $jquery, $khrml, $rpconvert;

				$status = array("0" => array(), "1" => array(), "2" => array(), "3" => array(), "4" => array(), "5" => array());
				$ICLASS = 0;
				$templateinuse = false;
				$edittempname = '';
				
				//Delete
				if($_POST['rdelete'] == 'deleteit'){
					$this->process_delete();
				}
				
				// the Template Data
				if($_GET['template'] && $_GET['template'] != '0' && !$_GET['deltemplate']){
					$templateinuse = true;
					$datasql = "SELECT *	FROM " . RP_RAID_TEMP_TABLE." WHERE template_id='".(int) $_GET['template']."'";
					$dataresult = $db->query($datasql);
					while ($datarow = $db->fetch_record($dataresult)){
						$tmpltdata = array(
								'template_name'						=> $datarow['template_name'],
								'raid_name'								=> $datarow['raid_name'],
								'raid_note'								=> $datarow['raid_note'],
								'raid_leader'							=> $datarow['raid_leader'],
								'raid_value'							=> $datarow['raid_value'],
								'raid_attendees'					=> $datarow['raid_attendees'],
								'raid_date'								=> $datarow['raid_date'],
								'raid_date_invite'				=> $datarow['raid_date_invite'],
								'raid_date_subscription'	=> $datarow['raid_date_subscription'],
								'raid_date_finish'        => $datarow['raid_date_finish'],
								'raid_link'               => $datarow['raid_link'],
								'raid_distribution'       => $datarow['raid_distribution'],
						);
					}
				}
				
				// Delete Template
				if($_GET['deltemplate'] && $_GET['template'] != '0'){
					$sql = 'DELETE FROM ' . RP_RAID_TEMP_TABLE . " WHERE template_id='" . (int) stripslashes($_GET['template']) . "'";
					$db->query($sql);
					
					// Logging
    			$log_action = array(
            'header'        => '{L_ACTION_RPTEMPL_DEL}',
            'id'            => $db->insert_id(),
            '{L_EVENT}'     => $user->lang['rp_raid_templ_del2'],
            '{L_NOTE}'      => $user->lang['rp_raid_templ_del'],
            '{L_VALUE}'     => htmlspecialchars(strip_tags($_GET['template']), ENT_QUOTES),
            '{L_ADDED_BY}'  => $this->admin_user);
     			$this->log_insert(array(
                'log_type'   => $log_action['header'],
                'log_action' => $log_action)
            );
				}

				//
				// Build javascript for distribution sets
				//
        			$sql = 'SELECT *	FROM ' . RP_CLASS_DIST_TABLE;
							if (!($result = $db->query($sql))) { message_die('Could not obtain class information', '', __FILE__, __LINE__, $sql); }
							$class_distribution_set = array();
							while ($row = $db->fetch_record($result))
							{
								$class_distribution_set[$row['template_id']][$row['class_name']] = $row['class_count'];
							}
							$db->free_result($class_result);

							while (list ($key, $val) = each ($class_distribution_set))
							{
								$tpl->assign_block_vars('events', array(
											'NAME'		=> $key));		// Name of the event
								while (list ($key2, $val2) = each ($val))
								{
								  // Sanatize the "name" for Javascript.. no spaces & -
                  $rname = str_replace(" ", "0", $key2);
                  $rname = str_replace("-", "9", $rname);
									$tpl->assign_block_vars('events.classes', array(
												'NAME'		=> $rname,			// Name of the class
												'COUNT'		=> $val2));			// Count of the class
								}
							}
					// Build Raid Template List
						$tmpsql = 'SELECT template_id, template_name, raid_distribution FROM ' . RP_RAID_TEMP_TABLE;
						$templresult = $db->query($tmpsql);
						$templvalarray = array(0 => '----');
						if($templresult){
							while ($templrow = $db->fetch_record($templresult)){
							  if($templrow['raid_distribution'] == 1){
                  $rcdistri_os = '['.$user->lang['rp_addition_role'].']';
                }elseif($templrow['raid_distribution'] == 2){
                  $rcdistri_os = '['.$user->lang['rp_addition_no'].']';
                }else{
                  $rcdistri_os = '['.$user->lang['rp_addition_class'].']';
                }

							  $templvalarray[$templrow['template_id']] = $templrow['template_name'].' '.$rcdistri_os;

							  if($templrow['template_id'] == $_GET['template']){
								  $edittempname = $templrow['template_name'];
							  }
							  
							}
							$db->free_result($templresult);
						}
					
					// class template
							$sql = "SELECT * FROM " . RP_CLASS_DIST_TABLE." WHERE event_name='".stripslashes($tmpltdata['raid_name'])."'";
							if (!($result = $db->query($sql))) { message_die('Could not obtain class information', '', __FILE__, __LINE__, $sql); }
							$class_distribution_set = array();
							while ($row = $db->fetch_record($result))
							{
								$class_distribution_set[$row['event_name']][$row['class_name']] = $row['class_count'];
							}
							$db->free_result($class_result);
					
					//
					// Build class list
					//
							foreach($this->classes as $class){
								// the class count
								$tempcount = ($templateinuse) ? $class_distribution_set[stripslashes($tmpltdata['raid_name'])][$rpconvert->classname($row['class_name'])] : $class['count'];
								
								// Sanatize the "name" for Javascript.. no spaces & -
								$rname = str_replace(" ", "0", $class['name_en']);
								$rname = str_replace("-", "9", $rname);
								
                $tpl->assign_block_vars('raid_classes', array(
									'LABEL'			=> $rpconvert->classname($class['name'], true),
									'NAME'			=> "raid_class_" . $rname . "_count",
									'NAME_EN'		=> strtolower($class['name_en']),
									'COUNT'			=> ( $tempcount ) ? $tempcount : 0,
									'CLASS_ICON'	=> $rpclass->ClassIcon($class['name'], $class['id'], $this->role_distri),
									));
							}

        	//
        	// Build event drop-down
        	//
        			$max_value = $db->query_first('SELECT max(event_value) FROM ' . EVENTS_TABLE);
        			$float = @explode('.', $max_value);
        			$format = '%0' . @strlen($float[0]) . '.2f';

        			$sql = 'SELECT event_id, event_name, event_value FROM '.EVENTS_TABLE.' ORDER BY event_name';
        			$result = $db->query($sql);
        			while ( $row = $db->fetch_record($result) ){
        					/*if($templateinuse){
            				$select_check = stripslashes($row['event_name']) == $tmpltdata['raid_name'];
									}else{
										$select_check = ( is_array($this->raid['raid_name']) ) ? in_array(stripslashes($row['event_name']), $this->raid['raid_name']) : stripslashes($row['event_name']) == $this->raid['raid_name'];
									}*/
								$events_selected = ($templateinuse) ? $tmpltdata['raid_name'] : $this->raid['raid_name'];
								$drpdwn_events[stripslashes($row['event_name'])] = '(' . sprintf($format, $row['event_value']) . ') - ' . stripslashes($row['event_name']);
        			}
        					$db->free_result($result);

				// Build DropDown Menues
				$drpdwn_hours = array(
						'00'						=> '00',
						'01'						=> '01',
						'02'						=> '02',
						'03'						=> '03',
						'04'						=> '04',
						'05'						=> '05',
						'06'						=> '06',
						'07'						=> '07',
						'08'						=> '08',
						'09'						=> '09',
						'10'						=> '10',
						'11'						=> '11',
						'12'						=> '12',
						'13'						=> '13',
						'14'						=> '14',
						'15'						=> '15',
						'16'						=> '16',
						'17'						=> '17',
						'18'						=> '18',
						'19'						=> '19',
						'20'						=> '20',
						'21'						=> '21',
						'22'						=> '22',
						'23'						=> '23',	
				);
				
				$drpdwn_minutes = array(
						'00'							=> '00',
						'05'							=> '05',
						'10'							=> '10',
						'15'							=> '15',
						'20'							=> '20',
						'25'							=> '25',
						'30'							=> '30',
						'35'							=> '35',
						'40'							=> '40',
						'45'							=> '45',
						'50'							=> '50',
						'55'							=> '55',
				);

				// the repeat array
				$drpdwn_repeat = array(
						'0'								=> '--',
						'1'								=> $user->lang['rp_weekly'],
						'2'								=> $user->lang['rp_14days'],
					);

				// generate the start time:
				$tmp_srt_hours 	= ( $conf['rp_hours_offset'] != 0) ? ($conf['rp_hours_offset'] * 60) : 0;
				$tmp_srt_min		= $conf['rp_min_offset'] + $tmp_srt_hours;
				$tmp_srt_second = $tmp_srt_min * 60;
				
				// the logic behind the hours:minuts...
				if($templateinuse){
					$tmp_invtime		= ( $tmpltdata['raid_date_invite']) ? $tmpltdata['raid_date_invite'] : $stime->DoTime();
					$tmp_starttime	= ( $tmpltdata['raid_date'] ) ? $tmpltdata['raid_date'] : ($stime->DoTime()+ $tmp_srt_second);
					$tmp_subtime		= ( $tmpltdata['raid_date_subscription'] ) ? $tmpltdata['raid_date_subscription'] : $stime->DoTime();
					$tmp_finishtime	= ( $tmpltdata['raid_date_finish'] ) ? $tmpltdata['raid_date_finish'] : $stime->DoTime()+(4*60*60);
				}elseif($_GET['tstamp']){
          $tmp_invtime		= $_GET['tstamp'];
					$tmp_starttime	= $_GET['tstamp']+ $tmp_srt_second;
					$tmp_subtime		= $_GET['tstamp'];
					$tmp_finishtime	= $_GET['tstamp']+(4*60*60);
        }else{
					$tmp_invtime		= ( $this->raid['raid_date_invite']) ? $this->raid['raid_date_invite'] : $stime->DoTime();
					$tmp_starttime	= ( $this->raid['raid_date'] ) ? $this->raid['raid_date'] : ($stime->DoTime()+ $tmp_srt_second);
					$tmp_subtime		= ( $this->raid['raid_date_subscription'] ) ? $this->raid['raid_date_subscription'] : $stime->DoTime();
					$tmp_finishtime	= ( $this->raid['raid_date_finish'] ) ? $this->raid['raid_date_finish'] : $stime->DoTime()+(4*60*60);
				}

				$tmp_days_offset 	= ( $conf['rp_days_offset'] ) ? $conf['rp_days_offset'] : 2;
        
        if($this->raid['raid_repeat'] && !$this->raid['delete_id']){
          $mutliple_rep_id = $this->url_id;
        }elseif($this->raid['delete_id']){
          $mutliple_rep_id = $this->raid['delete_id'];
        }else{
          $mutliple_rep_id = 0;
        }
        
        // Rank Dropdown
        if($conf['rp_autoaddflex'] == 1){
  				$rasql = 'SELECT rank_id, rank_name
                    FROM ' . MEMBER_RANKS_TABLE . '
                    WHERE rank_id > 0
                    ORDER BY rank_id';
          $result2 = $db->query($rasql);
          while ( $row2 = $db->fetch_record($result2) ){
            $rankdropdwn[$row2['rank_id']] = stripslashes($row2['rank_name']);
          }
          $db->free_result($result2);
        }
        
        // raid/ role distri
        $role_sel_array = array(
              '0'   => $user->lang['rp_class_distribution'],
              '1'   => $user->lang['rp_role_distribution'],
              '2'   => $user->lang['rp_no_distributon']
        );
        
        // Raid Leader Dropdown
        $rlsql =  " SELECT m.member_name, m.member_id FROM ".MEMBERS_TABLE." m, ".MEMBER_USER_TABLE." mu, ".USERS_TABLE." u 
                    WHERE m.member_id=mu.member_id
                    AND mu.user_id=u.user_id
                    AND u.user_active ='1'
                    ORDER BY m.member_name";
        $rlresult = $db->query($rlsql);
			  while ($lrrow = $db->fetch_record($rlresult)){
			     $raidleaders[$lrrow['member_id']] = $lrrow['member_name'];
			  }
			  
			  if(empty($_GET['r'])){
          $ssql = "SELECT member_id FROM ".RP_SAVEDUSER_TABLE." WHERE user_id='".$user->data['user_id']."'";
          $result = $db->query($ssql);
          $defrow = $db->fetch_record($result);
        }else{
          $defrow['member_id'] = $this->raid['member_id'];
        }
        if($this->role_distri == 1){
          $header_class = $user->lang['rp_role_distribution'];
        }elseif($this->role_distri == 2){
          $header_class = $user->lang['rp_no_distributon'];
        }else{
          $header_class = $user->lang['rp_class_distribution'];
        }
        
        // load the proper Raid Leader...
        $raid_lead_val = ($tmpltdata['raid_leader']) ? $tmpltdata['raid_leader'] : $this->raid['raid_leader'];
        $raid_lead_val = ($raid_lead_val) ? $raid_lead_val : $defrow['member_id'];
        
        $tpl->assign_vars(array(
 						'RP_IS_EDIT'						=> (!empty($_GET['r'])) ? true : false,
 						'RP_ISNOT_EDIT'         => (empty($_GET['r'])) ? true : false,
 						'RP_IS_REPEATBLE'				=> ($mutliple_rep_id != 0) ? true : false,
 						'RP_IS_NOTEMPLATE'			=> ($templateinuse || !empty($_GET['r'])) ? false : true,
 						'RP_SHOW_DELBUTTON'			=> ($user->check_auth('a_raidplan_delete', false)) ? true : false,
 						
 						'V_START_ADD_HOUR'			=> ($conf['rp_hours_offset'])    ? intval($conf['rp_hours_offset']) : 0,
 						'V_START_ADD_MIN'				=> ($conf['rp_min_offset'])      ? intval($conf['rp_min_offset']) : 0,
 						'V_END_ADD_HOUR'        => ($conf['rp_end_hour_offset']) ? intval($conf['rp_end_hour_offset']) : 0,
 						'V_END_ADD_MIN'					=> ($conf['rp_end_min_offset'])  ? intval($conf['rp_end_min_offset']) : 0,
 						'V_FINISH_ADD_HOUR'			=> ($conf['rp_raid_duration'])   ? $rpclass->php2JSfloat($conf['rp_raid_duration']) : 4,
 						
 						//JS
 						'JS_CONFIRM_DEL'        => $jquery->Dialog_Confirm('deleteRaid', $user->lang['rp_confirm_delete_raid'], "document.post.rdelete.value='deleteit';document.post.submit();"),
 						'JS_ALERTDIAG'          => $jquery->Dialog_Alert2(),
 						
        		// Dropdown Menues
        		'DR_INV_HOURS'					=> $khrml->DropDown('invite_hour', $drpdwn_hours, $stime->DoDate('%H', $tmp_invtime)),
        		'DR_INV_MINUTES'				=> $khrml->DropDown('invite_min', $drpdwn_minutes, $stime->DoDate('%M', $tmp_invtime)),
        		'DR_START_HOURS'				=> $khrml->DropDown('start_hour', $drpdwn_hours, $stime->DoDate('%H', $tmp_starttime), '', 'onchange="javascript:update_times();"', 'input'),
        		'DR_START_MINUTES'			=> $khrml->DropDown('start_min', $drpdwn_minutes, $stime->DoDate('%M', $tmp_starttime), '', 'onchange="javascript:update_times();"', 'input'),
        		'DR_SUB_HOURS'					=> $khrml->DropDown('end_hour', $drpdwn_hours, $stime->DoDate('%H', $tmp_subtime)),
        		'DR_SUB_MINUTES'				=> $khrml->DropDown('end_min', $drpdwn_minutes, $stime->DoDate('%M', $tmp_subtime)),
        		'DR_FINISH_HOURS'				=> $khrml->DropDown('finish_hour', $drpdwn_hours, $stime->DoDate('%H', $tmp_finishtime)),
        		'DR_FINISH_MINUTES'			=> $khrml->DropDown('finish_min', $drpdwn_minutes, $stime->DoDate('%M', $tmp_finishtime)),
        		
        		'DR_AUTOADDRANK'        => $khrml->DropDown('rp_autoaddrank', $rankdropdwn, $conf['rp_rank_add']),
        		'DR_TEMPLATE'           => $khrml->DropDown('template', $templvalarray, $_GET['template'], '', 'onchange="javascript:form.submit();"', 'input'),
        		'DR_ROLE_SELECT'  			=> $khrml->DropDown('role_distri', $role_sel_array, $this->role_distri, '', 'onchange="javascript:form.submit();"', 'input'),
        		'DR_REPEAT'							=> $khrml->DropDown('raid_repeat', $drpdwn_repeat, $this->raid['raid_repeat']),
        		'DR_RAIDLEADER' 				=> $khrml->DropDown('raid_leader', $raidleaders, $raid_lead_val),
        		'DR_EVENT'							=> $khrml->DropDown('raid_name', $drpdwn_events, $events_selected),
            'REPEAT_ID'             => $mutliple_rep_id,
            
            // Date Picker
            'DATEPICK_START'        => $jquery->Calendar('RAID_DATE', $stime->GenRaidDate($this->raid['raid_date'], $_GET['tstamp'], 30, $tmp_days_offset), 'onchange="javascript:ChangeDays();"'),
            'DATEPICK_INVITE'       => $jquery->Calendar('RAID_DATE_INV', $stime->GenRaidDate($this->raid['raid_date_invite'], $_GET['tstamp'], 00, $tmp_days_offset)),
            'DATEPICK_DEADLINE'     => $jquery->Calendar('RAID_DATE_SUB', $stime->GenRaidDate($this->raid['raid_date_subscription'], $_GET['tstamp'], 00, $tmp_days_offset)),
            'DATEPICK_FINISH'       => $jquery->Calendar('RAID_DATE_FINISH', $stime->GenRaidDate($this->raid['raid_date_finish'], $_GET['tstamp'], 00, $tmp_days_offset)),
            
            //CSS
            'RP_TEMPLATEPATH'       => $user->style['template_path'],
            'S_CSSGAME'             => $rpclass->SelectedGame(),
            'RPTEMPL_ADMIN'         => true,
            'ENABLE_ATTSUMM'        => ($this->attendees_summ == 1) ? false : true,
            'ENABLE_AUTOADDFLEX'    => ($conf['rp_autoaddflex'] == 1 && $conf['rp_autoaddbyrnk'] == 1) ? true : false,
            
            // Form vars
            'F_ADD_RAID'						=> 'addraid.php' . $SID,
            'F_ISDIRECTDATE'        => (isset($_GET['tstamp'])) ? $_GET['tstamp'] : false,
            
						'F_ATTENDEES_COUNT'			=> ($templateinuse) ? $tmpltdata['raid_attendees'] : $this->raid['raid_attendees'],
            'RAID_ID'								=> $this->url_id,
            'FRONTEND'              => $this->frontendadd,
            
            'TCHANGE_VALUE'         => ($_GET['template'] && $this->role_distri != '2')? $_GET['template'] : false,
            'RAID_VALUE'						=> ($templateinuse) ? $tmpltdata['raid_value'] : stripslashes($this->raid['raid_value']),
            'RAID_NOTE'							=> ($templateinuse) ? stripslashes(htmlspecialchars($tmpltdata['raid_note'])) : stripslashes(htmlspecialchars($this->raid['raid_note'])),
						'RAID_LEADER'						=> ($templateinuse) ? stripslashes(htmlspecialchars($tmpltdata['raid_leader'])) : stripslashes(htmlspecialchars($this->raid['raid_leader'])),
						'RAID_LINK'             => ($templateinuse) ? stripslashes(htmlspecialchars($tmpltdata['raid_link'])) : stripslashes(htmlspecialchars($this->raid['raid_link'])),
						'RP_DISTRI'             => $this->role_distri,
						
						'DELBUTTON_VISABLE'			=> ($templateinuse) ? true : false,
						'TEMPLATE_NAME' 				=> ($edittempname) ? $edittempname : $this->raid['raid_name'],
			
			      'U_ADD_EVENT'						=> $eqdkp_root_path . 'admin/addevent.php',

			     // Submit Buttons
			      'B_ADD_RAID'						=> $user->lang['add_raid'],
	          'B_DELETE_RAID'					=> $user->lang['delete_raid'],
			      'B_DISTRIBUTE'					=> $user->lang['rp_distribute_class_set'],
			      'B_RESET'								=> $user->lang['reset'],
		      	'B_UPDATE_RAID'					=> $user->lang['update_raid'],
		      	'B_ADDTEMPLATE'					=> $user->lang['rp_saveas_templ'],
		      	'B_DEL_TRMPLATE'				=> $user->lang['rp_del_template'],

		      	// Switches
		       	'S_ADD'									=> (!$this->url_id) ? true : false,
		      	'S_CLASSVIEW'						=> (!$this->url_id) ? true : false,
		      	'S_SHOW_RANKS'					=> ( $conf['rp_show_ranks'] == 1) ? true : false,
		      	'S_REPEAT_OFF'					=> ( $conf['rp_rep_enable'] != 1) ? true : false,
		      	
		      	// Tooltips
		      	'HELP_VALUE'            => $khrml->HelpTooltip($user->lang['addraid_value_note']),
		      	'HELP_TEMPLATE'         => $khrml->HelpTooltip($user->lang['rp_templatename2']),
		      	'WARN_REPEATOFF'			  => $khrml->WarnTooltip($user->lang['rp_repeat_disabled']),
		      	'WARN_NOTUPDATE'			  => $khrml->WarnTooltip($user->lang['rp_not_updating']),
		      
		      	// Lables
		      	'L_ADD_EVENT'						=> $user->lang['add_event'],
		      	'L_ATTENDEES'						=> $user->lang['attendees'],
		      	'L_CLASSES'							=> $header_class,
		      	'L_START_TIME'					=> $user->lang['rp_start_time'],
		      	'L_INVITE_TIME'					=> $user->lang['rp_invite_time'],
		      	'L_RTEMPLATE'						=> $user->lang['rp_use_template'],
		      	'L_RAID_FINISH'					=> $user->lang['rp_finish_time'],
			
		      	'L_EVENT'								=> $user->lang['event'],
		      	'L_NOTE'								=> $user->lang['note'],
		      	'L_LEADER'							=> $user->lang['rp_leader'],
		      	'L_LINK'                => $user->lang['rp_link'],
		      	'L_SIGNUP_DEADLINE'			=> $user->lang['rp_signup_deadline'],
						'L_TEMPLATE_NAME'				=> $user->lang['rp_templatename'],
						'L_FLEXADDRANK'         => $user->lang['rp_flexaddrank'],
						
						'L_BUTTON_ACTION'				=> $user->lang['rp_button_delete'],
						'L_BUTTON_CANCEL'				=> $user->lang['rp_button_cancel'],
						'L_BUTTON_CLOSE'        => $user->lang['rp_button_close'],
			
		      	'L_VALUE'								=> $user->lang['value'],
		      	'L_ADDALL'							=> $user->lang['rp_add_all'],
		
		      	//Calendar Settings
		      	'L_CAL_LANG'						=> ($user->lang['rp_calendar_lang'] != 'en') ? $user->lang['rp_calendar_lang'] : false,
		      	'L_REPEAT_EVERY'				=> $user->lang['rp_repeat_every'],
		      	'L_NO_END_DATE'					=> $user->lang['rp_noenddate'],
		      	'L_REPEAT_UNTIL'				=> $user->lang['rp_repeatuntil'],
						'L_DEL_REPEAT'					=> $user->lang['rp_delete_repeat'],
						'L_EDIT_REPEAT'         => $user->lang['rp_edit_repeat'],
						'L_REPEAT_DESCR'        => $user->lang['rp_repeat_descr'],
						'L_DATE_NOT_UPDATING'   => $user->lang['rp_not_updating'],

          	// Javascript messages
           	'MSG_ATTENDEES_EMPTY'		=> $user->lang['fv_required_attendees'],
           	'MSG_NAME_EMPTY'				=> $user->lang['fv_required_event_name'],
		));

		$eqdkp->set_vars(array(
			'page_title'         => $rpclass->GeneratePageTitle($user->lang['rp_addraid']),
			'template_file'      => 'admin/addraid.html',
			'template_path'      => $pm->get_data('raidplan', 'template_path'),
			'gen_simple_header'  => true,
      'display'            => true)
    );
	}


	// ---------------------------------------------------------
	// Process helper methods
	// ---------------------------------------------------------

	//Populate the old_raid array
	function get_old_data(){
		global $db, $eqdkp, $user, $tpl, $pm, $SID;

		$sql = "SELECT raid_name, raid_value, raid_note, raid_date, raid_attendees, delete_id, raid_repeat
						FROM " . RP_RAIDS_TABLE . "
						WHERE raid_id=" . (int) $this->url_id . "";
		$result = $db->query($sql);
		while ( $row = $db->fetch_record($result) ){
			$this->old_raid = array(
				'raid_name'				=> addslashes($row['raid_name']),
				'raid_value'			=> addslashes($row['raid_value']),
				'raid_note'				=> addslashes($row['raid_note']),
				'raid_date'				=> addslashes($row['raid_date']),
				'raid_attendees'	=> addslashes($row['raid_attendees']),
				'delete_id'				=> ($row['raid_repeat'] && !$row['delete_id']) ? $this->url_id : $row['delete_id'],
				'raid_repeat'			=> $row['raid_repeat']
			);
		}
		$db->free_result($result);
	}
}

$myRaid = new RPAddRaid;
$myRaid->process();
?>

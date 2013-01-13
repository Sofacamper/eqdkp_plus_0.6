<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-05-03 14:23:03 +0200 (Sun, 03 May 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 4729 $
 * 
 * $Id: guild_importer.php 5938 2009-09-24 15:05:08Z wallenium $
 */

	define('EQDKP_INC', true);
	define('PLUGIN', 'charmanager');
	$eqdkp_root_path = './../../../../../../';
	include_once($eqdkp_root_path .'/plugins/charmanager/include/common.php');	

	if(!$conf['uc_servername'] or !$conf['uc_server_loc']){
		echo $user->lang['uc_imp_novariables'];
		die();
	}
	if(!$in->get('step')){
		// CSS
		echo "<style>
					.uc_infotext{
						margin:4px;
						padding: 4px;
						color: grey;
						border: 1px dotted grey;
						font-size: 13px;
					}
					.uc_headerload{
						font-size: 13px;
						text-align:center;
					}
					.uc_headerfinish{
						font-size: 14px;
						vertical-align: middle;
					}
					.uc_notetext{
						vertical-align: top;
						font-size: 14px;
						color: red;
						border: 1px dotted red;
						background: #FFEFEF;
						margin:4px;
						padding: 4px;
					}
					.uc_headtxt2{
						margin:4px;
						margin-bottom: 10px;
					}
					</style>";
		$output .= '<div id="uc_load_message">
		  					</div>
	  						<div id="uc_load_notice">
	  						</div>
								<iframe src="guild_importer.php?step=1" width="100%" height="200px" name="uc_guildimport" frameborder=0 border=0 framespacing=0 scrolling="auto"></iframe>';
		echo $output;
	}elseif($in->get('step',0) == '1'){
		// Build the Class Array
		$result = $db->query("SELECT class_name, class_id FROM __classes WHERE class_id<>0 ORDER BY class_name");
	  $classarray = array(0	=> $user->lang['uc_class_nofilter']);
	  while($row = $db->fetch_record($result)) {
	    $classarray[$row['class_id']] = $row['class_name'];
	  }
	  $db->free_result($result);
	  
		$output = ' <form name="settings" method="post" action="guild_importer.php?step=2">';
		$output .= $user->lang['uc_guild_name'].': <input name="guildname" size="30" maxlength="50" value="" class="input" type="text"><br/>';
		$output .= $user->lang['uc_class_filter'].': '.$khrml->DropDown('uc_classid', $classarray, '', '', '', 'input').'<br/>';
		$output .= $user->lang['uc_level_filter'].': <input name="level" size="2" maxlength="3" value="0" class="input" type="text"><br/>';
		$ranksortarry = array(1=>$user->lang['uc_rank_filter1a'], 2=>$user->lang['uc_rank_filter1b']);
		$output .= $user->lang['uc_rank_filter2a'].' '.$khrml->DropDown('rank_sort', $ranksortarry, '1', '', '', 'input').' '.$user->lang['uc_rank_filter2b'].': <input name="uc_rank" size="2" maxlength="3" value="0" class="input" type="text"><br/>';
		$output .= $user->lang['uc_startdkp'].': <input name="startdkp" size="5" maxlength="5" value="0" class="input" type="text"><br/>';
		$output .= '<input type="submit" name="submiti" value="'.$user->lang['uc_import_forw'].'" class="mainoption" />';
	  $output .= '</form>';
	  echo $output;
	}elseif($in->get('step',0) == '2'){
		
		// set the import-start message
		$load_mssg		= '<div class="uc_headerload"><img src="../../../../images/loading.gif" alt="loading..." /><div class="uc_headtxt2">'.$user->lang['uc_gimp_header_load'].'</div></div>';
		$load_notice	= '<div class="uc_infotext">'.$user->lang['uc_gimp_infotxt'].'</div>';
		echo "<script>parent.document.getElementById('uc_load_message').innerHTML='".$load_mssg."';</script>";
		echo "<script>parent.document.getElementById('uc_load_notice').innerHTML='".$load_notice."';</script>";
		
		include_once('classes/ArmoryChars.class.php');
	  $armory = new ArmoryChars("вов");
	  
	  // Read Member kist from Armory
	  $myClassId	= ($in->get('uc_classid',0)) ? $armory->ConvertID($in->get('uc_classid',0), 'int', 'classes', true) : '';
	  
	  // Limit by Rank
	  $myRankFilter = array('value' => $in->get('uc_rank',0), 'sort' => $in->get('rank_sort',0));
	  
	  // Error Reporting..
	  if(!$_POST['guildname']){
	  	die($user->lang['uc_imp_noguildname']);
	  }
	  
	  // Fetch the Data
	  $xml = $armory->GetGuildMembers($_POST['guildname'],stripslashes($conf['uc_servername']),$conf['uc_server_loc'], $_POST['level'], $myClassId, $myRankFilter, 'de_de');
	  $myheadout = '<table width="400">';
		echo $myheadout;
		
	  // generate array with member names
	  $result = $db->query("SELECT member_name FROM __members ORDER BY member_name");
	  $memberarray = array();
	  while($row = $db->fetch_record($result)) {
	    $memberarray[] = strtolower($row['member_name']);
	  }
	  $db->free_result($result);
	  
	  if(is_array($xml)){
		  foreach($xml as $chars){
		    if(in_array(strtolower(utf8_decode($chars['name'])),$memberarray)){
		      // member is in Database! Do not import again!
		      $setstatus = '<span>'.$user->lang['uc_armory_impduplex'].'</span>';
		    }else{
		      // member is not in database, import!	      
		      $myStatus = $db->query("INSERT INTO __members :params", array(
	        			'member_name'       => utf8_decode($chars['name']),
	        			'member_level'			=> $armory->ValueOrNull($chars['level']),
	        			'member_class_id'		=> $chars['eqdkp_classid'],
	        			'member_race_id'    => $chars['eqdkp_raceid'],
	        			'member_adjustment'	=> $armory->ValueOrNull($_POST['startdkp']),
	        			'member_rank_id'		=> $armory->ValueOrNull($conf['uc_defaultrank']),
	        ));
		      if($myStatus){
		        $setstatus = '<span syle="color:green">'.$user->lang['uc_armory_imported'].'</span>';
		      }else{
		        $setstatus = '<span style="color:red">'.$user->lang['uc_armory_impfailed'].'</span>';
		      }
		    }
		    $output  = '<tr>';
		    $output .= '<td width="200">'.utf8_decode($chars['name']).'</td>';
				$output .= '<td width="50">'.$chars['level'].'</td>';
				$output .= '<td width="150">'.$setstatus.'</td>';
				$output .= "</tr>";
				echo $output;
			}
		}
		echo "</table>";
		
		// Set the finish message...
		$load_mssg		= '<div class="uc_headerfinish"><img src="../../../../images/ok.png" alt="finished" align="middle" />'.$user->lang['uc_gimp_header_fnsh'].'</div>';
		$load_notice	= '<div class="uc_notetext" id="import_finished"><img src="../../../../images/false.png" alt="finished" align="left" style="padding-right:3px;" />'.$user->lang['uc_gimp_finish_note'].'</div>';
		echo "<script>parent.document.getElementById('uc_load_message').innerHTML='".$load_mssg."';</script>";
		echo "<script>parent.document.getElementById('uc_load_notice').innerHTML='".$load_notice."';</script>";
	}
?>

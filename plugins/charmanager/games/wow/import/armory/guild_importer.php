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
 * $Id: guild_importer.php 11314 2011-09-22 19:55:41Z wallenium $
 */

	define('EQDKP_INC', true);
	define('PLUGIN', 'charmanager');
	$eqdkp_root_path = './../../../../../../';
	include_once($eqdkp_root_path .'/plugins/charmanager/include/common.php');
	$user->check_auth('a_charmanager_config');

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

	  $armory = new bnet_armory($conf['uc_server_loc'], 'de_de');
		if($_HMODE){
			$armory->setSettings(array('apiKeyPrivate'=>$bnetapi_private, 'apiKeyPublic'=>$bnetapi_public));
		}
	  
	  // Error Reporting..
	  if(!$_POST['guildname']){
	  	die($user->lang['uc_imp_noguildname']);
	  }
	  
	  // Fetch the Data
	  $guilddata 	= $armory->guild($_POST['guildname'], stripslashes($conf['uc_servername']), true);
	  $myheadout = '<table width="400">';
		echo $myheadout;
		
	  // generate array with member names
	  $result = $db->query("SELECT member_name FROM __members ORDER BY member_name");
	  $memberarray = array();
	  while($row = $db->fetch_record($result)) {
	    $memberarray[] = strtolower($row['member_name']);
	  }
	  $db->free_result($result);
	  if(!isset($guilddata['status'])){
		  foreach($guilddata['members'] as $guildchars){
			#filter: class
			if($in->get('uc_classid', 0) > 0 && $armory->ConvertID($guildchars['character']['class'], 'int', 'classes') != $in->get('uc_classid', 0)){
				continue;
			}

			// filter: level
			if($_POST['level'] > 0 && $guildchars['character']['level'] < $_POST['level']){
				continue;
			}

		    if(in_array(strtolower(utf8_decode($guildchars['character']['name'])),$memberarray)){
		      // member is in Database! Do not import again!
		      $setstatus = '<span>'.$user->lang['uc_armory_impduplex'].'</span>';
		    }else{
		      // member is not in database, import!	      
		      $myStatus = $db->query("INSERT INTO __members :params", array(
	        			'member_name'       => utf8_decode($guildchars['character']['name']),
	        			'member_level'			=> $guildchars['character']['level'],
	        			'member_class_id'		=> $armory->ConvertID($guildchars['character']['class'], 'int', 'classes'),
	        			'member_race_id'    => $armory->ConvertID($guildchars['character']['race'], 'int', 'races'),
	        			'member_adjustment'	=> ($_POST['startdkp'] > 0) ? $_POST['startdkp'] : 0,
	        			'member_rank_id'		=> ($conf['uc_defaultrank'] > 0) ? $conf['uc_defaultrank'] : 0,
	        ));
		      if($myStatus){
		        $setstatus = '<span syle="color:green">'.$user->lang['uc_armory_imported'].'</span>';
		      }else{
		        $setstatus = '<span style="color:red">'.$user->lang['uc_armory_impfailed'].'</span>';
		      }
		    }
		    $output  = '<tr>';
		    $output .= '<td width="200">'.utf8_decode($guildchars['character']['name']).'</td>';
				$output .= '<td width="50">'.$guildchars['character']['level'].'</td>';
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

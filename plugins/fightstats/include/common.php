<?php
/*************************************************\
*             fightstats 4 EQdkp plus              *
* ----------------------------------------------- *
* Project Start: 05/2009                          *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 0.0.1a                                 *
* ----------------------------------------------- *
* Based on EQdkp-Plus Gallery by Badtwin & Lunary *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/

  if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
  } 
  
  if (!isset($eqdkp_root_path) ){
    $eqdkp_root_path = './';
  }
  include_once($eqdkp_root_path . 'common.php');
  
   /**
  * Load the global Configuration
  */
	
	//Cache: plugin.fightstats.config
	
  	$config_cache = $pdc->get('plugin.fightstats.config',false,true);
	if (!$config_cache) {
  		$sql = 'SELECT * FROM __fightstats_config';
  		if (!($settings_result = $db->query($sql))) { message_die('Could not obtain configuration data', '', __FILE__, __LINE__, $sql); }
  		$roww = $db->fetch_record_set($settings_result);
		$pdc->put('plugin.fightstats.config',$roww,86400,false,true);
	} else{
		$roww = $config_cache;
	};
  
  foreach($roww as $elem) {
    $conf[$elem['config_name']] = $elem['config_value'];

  }
  
  /**
  * Framework include
  **/
  require_once($eqdkp_root_path . 'plugins/fightstats/include/libloader.inc.php');
	

  /**
  * Load rest of Libraries
  */
  include_once($eqdkp_root_path . '/plugins/fightstats/include/fightstats.class.php');
  $fsclass = new fightstatsClass();
  
    /**
	* Alpha/Beta Markup
	**/
	if(strtolower($pm->plugins['fightstats']->vstatus) == 'alpha'){
		$tpl->assign_vars(array(
			'FS_STATUS'   => '<table class="errortable" border="0" cellpadding="2" cellspacing="0" width="100%">
          								<tbody><tr>
          								 
          								  <td class="row1">'.$user->lang['fs_alpha_status'].'</td>
          								</tr>
                        </tbody></table>'
		));
	} elseif(strtolower($pm->plugins['fightstats']->vstatus) == 'beta'){
		$tpl->assign_vars(array(
			'FS_STATUS'   => '<table class="errortable" border="0" cellpadding="2" cellspacing="0" width="100%">
          								<tbody><tr>
          								  
          								  <td class="row1">'.$user->lang['fs_beta_status'].'</td>
          								</tr>
                        </tbody></table>'
		));
	}
    
	/**
	* Common things that should be available for ever 
	**/
 	
	$footer = '
	<script>
	function aboutDialog(){
 		'.$jquery->Dialog_URL('About', $user->lang['fs_about_header'], $eqdkp_root_path.'plugins/fightstats/about.php', '500', '350').'
	}
	</script>
	<center>
  <span class="copyis">
    <a onclick="javascript:aboutDialog()" style="cursor:pointer;" onmouseover="style.textDecoration=\'underline\';" onmouseout="style.textDecoration=\'none\';">
      <img src="'.$eqdkp_root_path.'plugins/fightstats/images/credits/info.png" alt="Credits" border="0" />Credits</a>
  </span><br />
  <span class="copy">'.$fsclass->Copyright().'
  </span>
</center>';
	
	$tpl->assign_vars(array(
		'SID'								=> $SID,
		'FS_FOOTER'					=> $footer,
	));
  
  
?>

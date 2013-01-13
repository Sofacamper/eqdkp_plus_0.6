<?php
/*************************************************\
*             Downloads 4 EQdkp plus              *
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
	
	//Cache: plugin.downloads.config
	
  	$config_cache = $pdc->get('plugin.downloads.config',false,true);
	if (!$config_cache) {
  		$sql = 'SELECT * FROM __downloads_config';
  		if (!($settings_result = $db->query($sql))) { message_die('Could not obtain configuration data', '', __FILE__, __LINE__, $sql); }
  		$roww = $db->fetch_record_set($settings_result);
		$pdc->put('plugin.downloads.config',$roww,86400,false,true);
	} else{
		$roww = $config_cache;
	};
  
  foreach($roww as $elem) {
    $conf[$elem['config_name']] = $elem['config_value'];

  }
  
  /**
  * Framework include
  **/
  require_once($eqdkp_root_path . 'plugins/downloads/include/libloader.inc.php');
	

  /**
  * Load rest of Libraries
  */
  include_once($eqdkp_root_path . '/plugins/downloads/include/downloads.class.php');
  $dlclass = new DownloadsClass();
  
    /**
	* Alpha/Beta Markup
	**/
	if(strtolower($pm->plugins['downloads']->vstatus) == 'alpha'){
		$tpl->assign_vars(array(
			'DL_STATUS'   => '<table class="errortable" border="0" cellpadding="2" cellspacing="0" width="100%">
          								<tbody><tr>
          								 
          								  <td class="row1">'.$user->lang['dl_alpha_status'].'</td>
          								</tr>
                        </tbody></table>'
		));
	} elseif(strtolower($pm->plugins['downloads']->vstatus) == 'beta'){
		$tpl->assign_vars(array(
			'DL_STATUS'   => '<table class="errortable" border="0" cellpadding="2" cellspacing="0" width="100%">
          								<tbody><tr>
          								  
          								  <td class="row1">'.$user->lang['dl_beta_status'].'</td>
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
 		'.$jquery->Dialog_URL('About', $user->lang['dl_about_header'], $eqdkp_root_path.'plugins/downloads/about.php', '500', '350').'
	}
	</script>
	<center>
  <span class="copyis">
    <a onclick="javascript:aboutDialog()" style="cursor:pointer;" onmouseover="style.textDecoration=\'underline\';" onmouseout="style.textDecoration=\'none\';">
      <img src="'.$eqdkp_root_path.'plugins/downloads/images/credits/info.png" alt="Credits" border="0" />Credits</a>
  </span><br />
  <span class="copy">'.$dlclass->Copyright().'
  </span>
</center>';
	
	$tpl->assign_vars(array(
	
		'SID'								=> $SID,
		'DL_FOOTER'							=> $footer,
		'ROW_CLASS'                			=> $eqdkp->switch_row_class(),
	));
  
  
?>

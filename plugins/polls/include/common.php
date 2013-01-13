<?PHP
/*********************************************************************************\
* Project:	EQdkp-Plus																														*
* License:	Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported *
* Link:			http://creativecommons.org/licenses/by-nc-sa/3.0/											*
* --------------------------------------------------------------------------------*
* Polls 4 EQdkp plus																															*
* --------------------------------------------------------------------------------*
* Project Start: 10/2009																													*
* Author: BadTwin																																	*
* Copyright: Andreas (BadTwin) Schrottenbaum																			*
* Link: http://badtwin.dyndns.org																									*
* Version: 0.0.1																																	*
\*********************************************************************************/

  if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
  }

  if (!isset($eqdkp_root_path)){
    $eqdkp_root_path = './';
  }
  include_once($eqdkp_root_path . 'common.php');

  /**
  * Caching
  **/
	$config_cache = $pdc->get('plugin.polls.settings', false, true);
	if (!$config_cache){
		$config_query = $db->query("SELECT * FROM __polls_settings");
		$config_data = $db->fetch_record_set($config_query);
		$db->free_result($config_query);
		$pdc->put('plugin.polls.settings',$config_data,86400, false, true);
	} else{
		$config_data = $config_cache;
	};

	if (is_array($config_data)){
		foreach ($config_data as $sett){
			$conf[$sett['config_name']] = $sett['config_value'];
		};
	};

  /**
  * Framework include
  **/
  require_once($eqdkp_root_path . 'plugins/polls/include/libloader.inc.php');

	/**
	* Alpha/Beta Markup
	**/
	if(strtolower($pm->plugins['polls']->vstatus) == 'alpha'){
		$tpl->assign_vars(array(
			'STATUS'   => '<div style="width: 100%; background: white; border: red 2px solid;">
											<table width="100%">
												<tr>
													<td>
														<image src="'.$eqdkp_root_path.'plugins/polls/images/warn.png" />
													</td>
													<td align="center" valign="middle">
														<h2><font color="red">
															DIES IST EINE ALPHA-VERSION UND NOCH IM ENTWICKLUNGSSTADIUM!<br />
															DIESE VERSION IST KEINESFALLS F&Uuml;R DEN PRODUKTIVEN EINSATZ GEDACHT!
														</font></h2>
													</td>
													<td align="right">
														<image src="'.$eqdkp_root_path.'plugins/polls/images/warn.png" />
													</td>
												</tr>
											</table>
										</div>'
		));
	} elseif(strtolower($pm->plugins['polls']->vstatus) == 'beta'){
		$tpl->assign_vars(array(
			'STATUS'   => '<div style="width: 100%; background: white; border: red 2px solid;">
											<table width="100%">
												<tr>
													<td>
														<image src="'.$eqdkp_root_path.'plugins/polls/images/warn.png" />
													</td>
													<td align="center" valign="middle">
														<h2><font color="red">
															DIES IST EINE BETA-VERSION!<br />
															WENN DU BUGS ENTDECKST, MELDE DIESE BITTE AUF <a style="color: red; text-decoration: underline;" href="http://www.eqdkp-plus.com/forum">EQDKP-PLUS.COM</a>!
														</font></h2>
													</td>
													<td align="right">
														<image src="'.$eqdkp_root_path.'plugins/polls/images/warn.png" />
													</td>
												</tr>
											</table>
										</div>'
		));
	}


	/**
	* The Footer with Credits
	**/
	$tpl->assign_vars(array(
		'PO_FOOTER'	=> '<script language="JavaScript" type="text/javascript">
											function aboutDialog(){
												'.$jquery->Dialog_URL('POAbout', $user->lang['po_about_header'], $eqdkp_root_path . 'plugins/polls/about.php', '400', '342').'
											}
										</script>
										<div style="width: 100%; text-align: center; padding-top: 20px; padding-bottom: 20px;">
											<a onclick="javascript:aboutDialog()" style="cursor:pointer;" onmouseover="style.textDecoration=\'underline\';" onmouseout="style.textDecoration=\'none\';">
   											<img src="'.$eqdkp_root_path . 'plugins/polls/images/info.png" alt="Credits" border="0" /> Credits
											</a>
											<br />Polls '.$pm->get_data('polls', 'version').' &copy; 2009 BadTwin
										</div>'
	));

	/**
	* Include the Class
	**/
	require_once ('polls.class.php');
	$polls = new polls;
?>
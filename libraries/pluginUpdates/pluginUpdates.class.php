<?php
 /*
 * Project:     eqdkpPLUS Libraries: pluginUpdates
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2009
 * Date:        $Date: 2009-10-16 23:49:33 +0200 (Fri, 16 Oct 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2009 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     libraries:pluginUpdates
 * @version     $Rev: 6118 $
 * 
 * $Id: pluginUpdates.class.php 6118 2009-10-16 21:49:33Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

if (!class_exists("pluginUpdates")) {
  class pluginUpdates
  {
  	var $show_warn = false;
  	var $version   = '1.1.0';
  	
  	public function __construct(){
  		global $pm, $db, $table_prefix, $eqdkp_root_path, $user;
  		
  		// Check Pluginversions
  		require_once($eqdkp_root_path.'libraries/pluginUpdates/pluginlist.php');
  		$this->plugin_array = array();
  		if(is_array($plugin_names)){
    		foreach($plugin_names as $plugg=>$dbfields){
    		  $myvfiles = array();
    		  $last_entry = '';
    			if($pm->check(PLUGIN_INSTALLED, $plugg)){
    				$sql = "SELECT * FROM ".$table_prefix.$dbfields['table'];
    				$tmpconf = $db->query($sql);
    				while($rowcc = $db->fetch_record($tmpconf)){
               if($rowcc['config_name'] == $dbfields['fieldprefix'].'inst_version'){
                  $myvfiles['version'] = $rowcc['config_value'];
               }elseif($rowcc['config_name'] == $dbfields['fieldprefix'].'inst_build'){
                  $myvfiles['build'] = $rowcc['config_value'];
               }
            }
    				
    				// Check if the Versions are equal
    				if($this->compareVersion($pm->get_data($plugg, 'version'),$myvfiles['version']) == 1){
    				
    				  // Load the version file array
    				  $up_options = '';
    				  $vfile_1  = $eqdkp_root_path.'plugins/'.$plugg.'/include/updates/versions.php';
    				  $vfile_2  = $eqdkp_root_path.'plugins/'.$plugg.'/includes/updates/versions.php';
    				  $vfile    = (is_file($vfile_2)) ? $vfile_2 : $vfile_1;
    				  if(is_file($vfile)){
    				    include($vfile);
    				    if(count($up_updates) > 0){
                  $last_entry = max(array_keys($up_updates));
                }
    				    
      				  // Check if there's a database upgrade file
      				  //if($last_entry != '' && $last_entry >= $myvfiles['version']){
      				  if($last_entry != '' && $this->compareVersion($last_entry, $myvfiles['version']) == 1){
        					$this->plugin_array[$plugg]  = array(
        							'version'		=> (($myvfiles['version']) ? $myvfiles['version'] : $user->lang['cl_unknown']),
        							'redirect'	=> (($up_options['redirect']) ? $up_options['redirect'] : 'settings.php'),
        					);
        					$this->show_warn       = true;
      					}
    					}
    				}
    			}
    		}
  		}
  	}
  	
  	public function CheckStatus(){
      return ($this->show_warn) ? true : false;
    }
  	
  	private function compareVersion($version1, $version2){
      $result = 0;
      $match1 = explode('.', $version1);
      $match2 = explode('.', $version2);
      $int1 = sprintf( '%d%02d%02d%02d', $match1[0], $match1[1], intval($match1[2]),intval($match1[3]));
      $int2 = sprintf( '%d%02d%02d%02d', $match2[0], $match2[1], intval($match2[2]), intval($match2[3]) );
      
      if($int1 < $int2){ $result = -1;}
      if($int1 > $int2){ $result = 1;}
      return $result;
    }
  	
  	public function OutputHTML(){
  		global $user, $pm, $eqdkp_root_path;
  		if ($this->show_warn && $user->check_auth('a_', false)){
  		$out_htm = '<table width="100%" border="0" cellspacing="0" cellpadding="2" class="errortable">
  								  <tr>
  								    <td class="row1" width ="48px"><img src="'.$eqdkp_root_path.'libraries/pluginUpdates/images/false.png" /></td>
  								    <td class="row1">
  								    	<table width="100%" border="0" cellspacing="1" cellpadding="2" class="errortable_inner">';
  		$out_htm .= '				<tr>
  													<td>'.$user->lang['puc_perform_intro'].'</td>
  												</tr>
                          <tr>
                          <td> </td>
                          </tr>
                          ';
      foreach($this->plugin_array as $pluginname=>$pluginversion){
  		  $sentence = sprintf($user->lang['puc_pluginneedupdate'], $user->lang[$pluginname], $pluginversion['version'], $pm->get_data($pluginname, 'version'));
  			$out_htm .= '    	<tr>
  								    		<td>
  								    			'.$sentence.'
  								    		</td>
  								    		<td>
  								    		<a href="'.$eqdkp_root_path.'plugins/'.$pluginname.'/admin/'.$pluginversion['redirect'].'" target="blank">'.$user->lang['puc_solve_dbissues'].'</a>
  								    		</td>
  								    		</tr>';
  		}
  		$out_htm .= '			</table>
  								    </td>
  								  </tr>
  								  <tr>
  								</table>
  								<br/>';
  			}else{
  				$out_htm .= '<table width="100%" border="0" cellspacing="0" cellpadding="2" class="greentable">
          								<tr>
          								  <td class="row1" width ="60px"><img src="'.$eqdkp_root_path.'libraries/PluginUpdater/images/ok.png" /></td>
          								  <td class="row1">'.$user->lang['lib_pupd_nodbupdates'].'</td>
          								</tr>
                        </table>';
  			}
  			return $out_htm;
  	}
  }
}
?>

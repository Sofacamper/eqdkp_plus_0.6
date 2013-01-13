<?php
 /*
 * Project:     eqdkpPLUS Libraries: PlusUpdCheck
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-11-24 16:09:27 +0100 (Tue, 24 Nov 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     libraries:PluginUpdCheck
 * @version     $Rev: 6451 $
 * 
 * $Id: PlusUpdCheck.class.php 6451 2009-11-24 15:09:27Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
if (!class_exists("PlusUpdCheck")) {
  class PlusUpdCheck
  {
  	// Plugin Version
  	var $PlusUpdateClassVersion  = '2.0.1';
  	var $PlusUpdateClassBuild    = '2411200901';
  
  	public function __construct(){
      global $eqdkp_root_path, $user, $_HMODE;
      $this->host_mode				= ($_HMODE) ? true : false;
      $this->rootpath					= $eqdkp_root_path;
    	$this->server_link 			= "http://rss.eqdkp-plus.com";
    	$this->server_name			= substr($this->server_link, 7);
      $this->plusversion      = EQDKPPLUS_VERSION;
      $this->user_auth        = $user->check_auth('a_', false);
      $this->colortable       = array('Security Update' => 'red');
      $this->update_offset    = 48; // Re-Check after xxx hours
  	}

    private function BuildVersionArray(){
      global $db, $user;
      if(!$this->host_mode){
  		  $last_check = $this->LoadCacheTime();
        if (!$last_check || ($last_check > 0 &&$last_check < (time()-($this->update_offset*60*60)))){
          $filename = ($this->vstatus == 'beta' || $this->vstatus == 'alpha') ? '/beta_versions.xml' : '/versions.xml';
          if(!$this->WriteCache($this->server_link.$filename)){
            $this->updchk_output = $user->lang['cl_noserver'];
            $this->updchk_window =true;
    		  } // end if availability check
			 } // end Update Cache

        // Load the Cache Data to the Version Array
        $this->CheckCacheTable();
        $result = $db->query("SELECT * FROM __updates");
        $versions = '';
      	while ($ddd = $db->fetch_record($result)){
					$versions[$ddd['plugin']]['name']      = $ddd['realname'];
					$versions[$ddd['plugin']]['version']   = $ddd['version'];
					$versions[$ddd['plugin']]['level']     = $ddd['level'];
					$versions[$ddd['plugin']]['changelog'] = $ddd['changelog'];
					$versions[$ddd['plugin']]['download']  = $ddd['download'];
					$versions[$ddd['plugin']]['release']   = date($user->style['date_notime_short'], $ddd['releasedate']);
				}
        return $versions;
  		}else{
  			return 0;
  		}
    }
    
    // Build the Array with the available Updates
  	private function BuildUpdateArray(){
  		global $db, $pm;
  		$pluginscheck = '';
      $this->update_available = false;
  		$versions = $this->BuildVersionArray();
  		if(is_array($versions)){
  			foreach ($versions as $key => $value){
  		    // Check for PLUS Updates
  				if(($pm->check(PLUGIN_INSTALLED, $key) && ($this->compareVersion(trim($value['version']),$pm->get_data($key, 'version'))==1))
  				|| ($key == 'pluskernel' && $this->compareVersion(trim($value['version']), $this->plusversion)==1)){
						$pluginscheck[$key]['plugin']     = ($key == 'pluskernel') ? $this->plusversion : $pm->get_data($key, 'version');
						$pluginscheck[$key]['version']    = $versions[$key]['version'];
						$pluginscheck[$key]['changelog']  = $versions[$key]['changelog'];
						$pluginscheck[$key]['level']      = $versions[$key]['level'];
						$pluginscheck[$key]['release']    = $versions[$key]['release'];
						$pluginscheck[$key]['download']   = $versions[$key]['download'];
						$pluginscheck[$key]['name']       = $versions[$key]['name'];
						$this->update_available = true;
					}
  			}
        return $pluginscheck;
  		}else{
  			return false;
  		}
    }
    
    public function CheckStatus(){
      return ($this->update_available) ? true : false;
    }
  	
    // Output the Update Form
    public function OutputHTML(){
  		global $user, $pm, $jqueryp, $jquery;
      $out_htm  = '';
  		$updates = $this->BuildUpdateArray();
  		if($this->user_auth && is_array($updates) && $this->update_available){
        $out_htm .= '<script>
  	  									function lib_changelog(plugin){
  	  										'.$jqueryp->Dialog_URL('lib_changelog', $user->lang['cl_changelog_url'], $this->rootpath."libraries/PluginUpdCheck/changelog.php?plugin='+plugin+'", '500', '320').'
  	  									} 
  	  								</script>
                      <table width="100%" border="0" cellspacing="0" cellpadding="2" class="errortable">
        								  <tr>
        								    <td class="row1" width ="48px"><img src="'.$this->rootpath.'libraries/pluginUpdates/images/false.png" /></td>
        								    <td class="row1">
        								    	<table width="100%" border="0" cellspacing="1" cellpadding="2" class="errortable_inner">';
        $out_htm .= '				<tr>
        													<td>'.$user->lang['lib_pupd_intro'].'</td>
        												</tr>
                                <tr>
                                <td> </td>
                                </tr>';
        foreach($updates as $pluginname=>$data){
          $sentence      = sprintf($user->lang['lib_pupd_updtxt'], (($user->lang[$pluginname]) ? $user->lang[$pluginname] : $data['name']), $data['version'], $data['plugin'], $data['release']);
        	$download_link = ($data['download']) ? '<a href="'.$data['download'].'" target="_blank"><img src="'.$this->rootpath.'pluskernel/images/download.png" border="0" alt="'.$user->lang['lib_pupd_download'].'"/></a>' : '';
          $out_htm .= '    	<tr>
        								    		<td>'.$sentence.'</td>
        								    		<td>
        								    		  <a onclick="lib_changelog(\''.$pluginname.'\');"><img src="'.$this->rootpath.'pluskernel/images/changelog.png" border="0" alt="'.$user->lang['lib_pupd_changelog'].'22"/></a>
                                  '.$download_link.'
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
          								  <td class="row1" width ="60px"><img src="'.$this->rootpath.'libraries/PluginUpdater/images/ok.png" /></td>
          								  <td class="row1">'.$user->lang['lib_pupd_noupdates'].'</td>
          								</tr>
                        </table><br/>';
      }
      return $out_htm;
  	}
    
    // check if the Update Check is available to show
    public function ShowUpdateChecker(){
      if($this->user_auth && !$this->host_mode){
        return 1;
      }else{
        return 0;
      }
    }
    
    // Reset Update Cache
  	public function ResetUpdater(){
  		global $db;
  		$db->query('TRUNCATE TABLE __updates');
  	}
    
    // Write Update Cache
    private function WriteCache($url){
      global $db;
      if($xml = simplexml_load_file($url)){
        $this->ResetUpdater();
  			foreach($xml->channel->item as $item){
  				$db->query("INSERT INTO __updates :params", array(
  	                'plugin'			=> $item->plugin,
  	                'realname'		=> $item->realname,
  									'last_check'	=> time(),
  									'version'			=> $item->version,
  									'build'				=> $item->build,
  									'changelog'		=> addslashes($item->changelog),
  									'releasedate'	=> $item->releasedate,
  									'category'		=> $item->category,
  									'level'				=> $item->level,
  									'shortdesc'		=> $item->shortdesc,
  									'download'		=> $item->enclosure['url'],
  	        ));
  			}
  		  return 1;
			}else{
        return 0;
      }
    }
    
    // Load last Cache Update Time
    private function LoadCacheTime(){
    	global $db;
    	$this->CheckCacheTable();
    	return $db->query_first("SELECT last_check FROM __updates WHERE plugin='pluskernel'");
    }
    
    // Create Updates table
    private function CheckCacheTable(){
    	global $db;
    	if(!$db->query_first("show tables like '__updates'")){
	    	$db->query("CREATE TABLE IF NOT EXISTS `__updates` (
									  `plugin` varchar(255) NOT NULL DEFAULT '',
									  `realname` varchar(255) NOT NULL DEFAULT '',
									  `last_check` varchar(255) NOT NULL DEFAULT '0',
									  `version` varchar(255) NOT NULL DEFAULT '0',
									  `build` varchar(255) NOT NULL DEFAULT '0',
									  `changelog` text NOT NULL DEFAULT '',
									  `releasedate` varchar(255) NOT NULL DEFAULT '0',
									  `category` varchar(255) NOT NULL DEFAULT '',
									  `level` varchar(255) NOT NULL DEFAULT '',
									  `download` varchar(255) NOT NULL DEFAULT '',
									  `shortdesc` text NOT NULL DEFAULT '',
									  PRIMARY KEY (`plugin`)
									)");
    	}
    }
    
    // compare Versions
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
}// end of class
}
?>

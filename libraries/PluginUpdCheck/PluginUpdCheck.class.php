<?php
 /*
 * Project:     eqdkpPLUS Libraries: PluginUpdCheck
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-08-24 15:08:38 +0200 (Mon, 24 Aug 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     libraries:PluginUpdCheck
 * @version     $Rev: 5757 $
 * 
 * $Id: PluginUpdCheck.class.php 5757 2009-08-24 13:08:38Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

if (!class_exists("PluginUpdCheck")) {
  class PluginUpdCheck
  {
  	public function __construct($plugininfo, $cachedb=false){
  	  global $eqdkp_root_path, $pm, $_HMODE;
  	  $this->host_mode				= ($_HMODE) ? true : false;
  	  $this->rootpath					= $eqdkp_root_path;
  		$this->server_link 			= "http://rss.eqdkp-plus.com";
  		$this->server_name			= substr($this->server_link, 7);
  		$this->plugin_name 			= $plugininfo['name'];
  		$this->act_version			= $plugininfo['version'];
  		$this->act_build        = $plugininfo['build'];
  		$this->updcheck_on			= $plugininfo['enabled'];
  		$this->imagePath        = $this->rootpath.'libraries/PluginUpdCheck/images/';
  		$this->vstatus          = ($plugininfo['vstatus']) ? strtolower($plugininfo['vstatus']) : 'stable';
  		$this->update_offset    = 48; // Re-Check after xxx hours
  		$this->table_installed  = false;
  	}
  	
  	public function PerformUpdateCheck(){
  		global $user;
  		if($this->updcheck_on && !$this->host_mode){
  			// Load the Update for that plugin
  			$row = $this->LoadCache();
  		  // When was the last update?
        if (!$row['last_check'] || ($row['last_check'] && $row['last_check'] < (time()-($this->update_offset*60*60)))){
    		  // Check if the Server is online
    		  $filename = ($this->vstatus == 'beta' || $this->vstatus == 'alpha') ? '/beta_versions.xml' : '/versions.xml';
    			if($this->WriteCache($this->server_link.$filename)){
    				$row = $this->LoadCache();
    			}else{ // Server is offline
            $this->updchk_output = $user->lang['cl_noserver'];
            $this->updchk_window =true;
    			} // end if availability check
    		} // end if time check
        if($this->compareVersion($row['version'], $this->act_version) == 1 || ($this->act_build && $row['build'] > $this->act_build)){
          $myTmpVersion1  = ($this->act_build) ?  $this->act_version.' [#'.$this->act_build.']' : $this->act_version;
          $myTmpVersion2  = ($row['build']) ? $row['version'].' [#'.$row['build'].']' : $row['version'];
          $myDLlink       = ($row['download']) ? "<br/><a target='_blank' href='".$row['plugin']."'>".$user->lang['cl_update_url']."</a>" : '';
          $myCLlink       = ($row['changelog']) ? '<br/><div onclick="lib_changelog(\''.$this->plugin_name.'\');" class="updcheck_link">'.$user->lang['cl_changelog_url'].'</div>' : '';
          $this->updchk_output  = sprintf($user->lang['cl_update_available'], $this->plugin_name, $myTmpVersion1, $myTmpVersion2, date($user->lang['cl_timeformat'], $row['releasedate']), $row['level'], $myDLlink, $myCLlink);
          $this->updchk_window = true;
        }else{
          $this->updchk_output = "";
          $this->updchk_window = false;
        }
  		} // end updatecheck on
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
  		global $user, $jquery;
  		$out_htm = '';
  		if ( $this->updchk_window){
	  		$out_htm  = $this->myCSS();
	  		$out_htm .= '<script>
	  									function lib_changelog(plugin){
	  										'.$jquery->Dialog_URL('lib_changelog', $user->lang['cl_changelog_url'], $this->rootpath."libraries/PluginUpdCheck/changelog.php?plugin='+plugin+'", '500', '320').'
	  									} 
	  								</script>
	  									<table width="100%" border="0" cellspacing="1" cellpadding="2" class="upchcktable">
	    								<tr>
	      								<th align="center" colspan="2">'.$user->lang['cl_ucpdate_box'].'</th>
	   	 								</tr>
	    								<tr>
	      								<td class="row1" width ="60px"><img src="'.$this->imagePath.'false.png" align="absmiddle" /></td>
	      								<td class="row1"><font color="red">'.$this->updchk_output.'</font></td>
	    								</tr>
	    								<tr>
	  								</table>
	  								<br/>';
  			}
  			return $out_htm;
  	}
  	
  	public function CheckStatus(){
      return ($this->updchk_window) ? true : false;
    }
  	
  	private function myCSS(){
      $csss = '<style type="text/css">
      						updcheck_link{
  									cursor: pointer;
  									text-decoration: none;
  									color: black;
  								}
  								.updcheck_link:hover{
  									text-decoration: underline;	
  								}
                  TABLE.upchcktable { 
                    border-top: 1px;
                    border-bottom: 1px;
                    border-left: 1px;
                    border-right: 1px;
                    border-color: red; 
                    border-style: solid;
                    background: #FFEFEF;
                  }
                  TABLE.upchcktable td{
                  	color:red;
                  	background: #FFEFEF;
                  	padding: 5px;
                  }
                  TABLE.upchcktable th{
                  	color:white;
                  	background: red;
                  }
        </style>';
      return $csss;
    }
  	
  	private function WriteCache($url){
      global $db;
      if($xml = simplexml_load_file($url)){
        $db->query('TRUNCATE TABLE __updates');
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
    
    private function LoadCache(){
    	global $db;
    	$this->CheckCacheTable();
    	return $db->fetch_record($db->query("SELECT * FROM __updates WHERE plugin='".$this->plugin_name."'"));
    }
    
    // Create Updates table...
    private function CheckCacheTable(){
    	global $db;
    	if(!$this->table_installed){
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
          $this->table_installed = true;
        }
      }
    }
  } // END OF CLASS
}
?>

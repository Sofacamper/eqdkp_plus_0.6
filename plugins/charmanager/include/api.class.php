<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2012-09-30 22:05:50 +0200 (Sun, 30 Sep 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 12157 $
 * 
 * $Id: api.class.php 12157 2012-09-30 20:05:50Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

class cmAPI
{
  var $max_version  = '1.9.9';
  var $version      = '1.2.0';
	
	function cmAPI(){
    global $table_prefix, $eqdkp_root_path;
    
    include($eqdkp_root_path.'plugins/charmanager/include/chartools.class.php');
    $this->CharTools  = new CharTools();
    
    if($this->isInstalled()){
      $this->profiles = $this->FetchProfiles();
    }else{
      $this->profiles = array();
    }
  }
	
	function isInstalled(){
    global $pm;
    if($this->max_version){
      return ($pm->check(PLUGIN_INSTALLED, 'charmanager') && $pm->get_data('charmanager', 'version') < $this->max_version) ? true : false;
    }else{
      return ($pm->check(PLUGIN_INSTALLED, 'charmanager')) ? true : false;
    }
  }
	
	function FetchProfiles(){
    global $db, $table_prefix;
    $sql = "SELECT * FROM __member_additions";
    $result = $db->query($sql);
    $profiledata = array();
		while ($memdata = $db->fetch_record($result)){
	   	$profiledata[$memdata['member_id']] = array(
				//  Skills
				'skill_1'			=> array(
                            'name'      => $user->lang['uc_tab_skills'].' 1',
                            'data'      => $memdata['skill_1'],
                            'category'  => 'Skills',
                            'list'      => '1',
                            'image'     => false
                          ),
        'skill_2'			=> array(
                            'name'      => $user->lang['uc_tab_skills'].' 2',
                            'data'      => $memdata['skill_2'],
                            'category'  => 'Skills',
                            'list'      => '1',
                            'image'     => false
                          ),
        'skill_3'			=> array(
                            'name'      => $user->lang['uc_tab_skills'].' 3',
                            'data'      => $memdata['skill_3'],
                            'category'  => 'Skills',
                            'list'      => '1',
                            'image'     => false
                          ),
        'skill2_1'			=> array(
                            'name'      => $user->lang['uc_tab_skills'].' 1',
                            'data'      => $memdata['skill2_1'],
                            'category'  => 'Skills',
                            'list'      => '1',
                            'image'     => false
                          ),
        'skill2_2'			=> array(
                            'name'      => $user->lang['uc_tab_skills'].' 2',
                            'data'      => $memdata['skill2_2'],
                            'category'  => 'Skills',
                            'list'      => '1',
                            'image'     => false
                          ),
        'skill2_3'			=> array(
                            'name'      => $user->lang['uc_tab_skills'].' 3',
                            'data'      => $memdata['skill2_3'],
                            'category'  => 'Skills',
                            'list'      => '1',
                            'image'     => false
                          ),
			  
        // Other
        'mem_notes'   => $memdata['notes'],
      );
		}
		$db->free_result($result);
		return $profiledata;
  }
	
	function MemberProfile($member_id){
    return $this->profiles[$member_id];
  }
  
  function Game($name=''){
    global $rpclass, $eqdkp_root_path;
    if(is_object($rpclass)){
      return ($rpclass->SelectedGame() == 'wow') ? 'wow' : '';
    }else{
      return ($name) ? $name : '';
    }
  }
  
  /* this is the API class for p.e. Raid Plan to use
  
  To Do:
  This API should generate the Charmanager TT and all Charmanager relevant things, and
  returns an array with the data in it.
  
  Needs:
  - multigame support
    --> this could be done by parsing the XML back to an array and return it to the API client
  - easy to use
  - easy to support
  - CM should use it as well... its easier to support
  - maybe let the api edit profiles...
  */
}
?>

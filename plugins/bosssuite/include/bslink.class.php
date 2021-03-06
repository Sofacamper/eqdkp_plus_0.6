<?php
 /*
 * Project:     BossSuite v4 MGS
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2008-11-22 17:50:27 +0100 (Sat, 22 Nov 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2006-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     bosssuite
 * @version     $Rev: 3234 $
 *
 * $Id: bslink.class.php 3234 2008-11-22 16:50:27Z sz3 $
 */

if ( !defined('EQDKP_INC') )
{
     die('Do not access this file directly.');
}

if ( !class_exists( "BSLINK" ) ) {

  class BSLINK{
  
    var $linklist;
    var $baselink;
    var $length;
    var $source;
    
    function BSLINK($source, $length){      
      global $eqdkp;
      $this->source = $source;
      
      if ($length == ''){
        $this->length = 'short';
      }else{
        $this->length = $length;
      }
      
      if ($source == 'bossloot'){
        $this->source = 'bossloot';
      }else if( !($source == '') ){
        $game_arr = explode('_', $eqdkp->config['default_game']);
        include(dirname(__FILE__).'/../games/'.$game_arr[0].'/linklist.php');
        $this->baselink = $sources[$source]['baseurl'];
        $this->linklist = $idlist[$sources[$source]['idlist']];
      }
    }
      
    function get_boss_link($bossid){
      global $SID, $user, $eqdkp_root_path;
      
      if($this->source == 'bossloot'){
        return '<a href="' . $eqdkp_root_path . 'plugins/bosssuite/bossloot.php'.$SID.'&amp;bossid='.$bossid.'">'.$user->lang[$bossid][$this->length].'</a>';
      }else if ( (isset($this->linklist)) ){
        return '<a href="' . $this->baselink . $this->linklist[$bossid] . '">'.$user->lang[$bossid][$this->length].'</a>';
      }
      return $user->lang[$bossid][$this->length];
    }
    
    function get_sources(){
    global $eqdkp;
        $game_arr = explode('_', $eqdkp->config['default_game']);
        include(dirname(__FILE__).'/../games/'.$game_arr[0].'/linklist.php');
        $sources['none'] = array('name' => 'none', 'idlist' => 'default', 'baseurl' => 'http://wow.allakhazam.com/db/mob.html?wmob=');
        $sources['bossloot'] = array('name' => 'BossLoot', 'idlist' => 'default', 'baseurl' => 'http://wow.buffed.de/?n=');
        return $sources;
    }
    
  }  
}
?>

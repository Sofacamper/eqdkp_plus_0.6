<?php
 /*
 * Project:     eqdkpPLUS Libraries: AdditionalDB
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-08-03 20:37:14 +0200 (Mon, 03 Aug 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     libraries:AdditionalDB
 * @version     $Rev: 5418 $
 * 
 * $Id: AdditionalDB.class.php 5418 2009-08-03 18:37:14Z wallenium $
 */
 
if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

if (!class_exists("AdditionalDB")) {
  class AdditionalDB {
  	
  	function __construct($tablename){
  		global $table_prefix;
  		$this->table = $table_prefix.$tablename;	
  	}
  	
  	// the "Save my Data to the Database" :D
    public function UpdateConfig($dataarray, $confarray, $userid=false){
          global $eqdkp_root_path, $user, $SID, $db;
          foreach($dataarray as $key=>$value) {
          	if(!in_array($key, $confarray)){
          		$this->InsertConfig($key, $userid);
          	}
        $value = (is_array($value)) ? implode("|", $value) : strip_tags(htmlspecialchars($value));
        if($userid){
        	$sql = "UPDATE `".$this->table."` SET config_value='".$value."' WHERE config_name='".$key."' AND user_id='".$userid."';";
        }else{
        	$sql = "UPDATE `".$this->table."` SET config_value='".$value."' WHERE config_name='".$key."';";
        }
        $db->query($sql);
  		}
    	return true;
  	}
      
    public function InsertConfig($name, $userid=false){
      global $db;
      if($userid){
        $sql = "INSERT INTO `" . $this->table . "` ( `config_name`, `user_id` ) VALUES ('".$name."', '".$userid."');";
      }else{
        $sql = "INSERT INTO `" . $this->table . "` ( `config_name` ) VALUES ('".$name."');";
      }
      $blubb = $db->query($sql);
    }
      
    public function CheckDBFields($field, $userid=false){
      global $db;
      $il = 1;
      $output = array();
      if($userid){
      	$sql = "SELECT ".$field." FROM `" . $this->table . "` WHERE user_id='".$userid."';";
      }else{
      	$sql = "SELECT ".$field." FROM `" . $this->table . "`;";
      }
      $blubb = $db->query($sql);
      while ( $blubber = $db->fetch_record($blubb) ){
        $output[$il] = $blubber['config_name'];
        $il++;
      }
      $db->free_result($blubb);
      return $output;
    }
      
    // add the item strip function (a-z, A-Z, 0-9)
  	public function stripName($string) { 
   		return(preg_replace("/[^A-Za-z\s]/",'', $string));
  	}
  		
  	public function isChecked($value){
      return ( $value == 1 ) ? ' checked="checked"' : '';
    }
  }// end of class
}
?>

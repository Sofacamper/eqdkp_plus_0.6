<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-07-04 13:06:09 +0200 (Sat, 04 Jul 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2007-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 5159 $
 * 
 * $Id: cache_mysql.class.php 5159 2009-07-04 11:06:09Z sz3 $
 */
 
if ( !defined('EQDKP_INC') )
{
     die('Do not access this file directly.');
}

if ( !interface_exists( "plus_datacache" ) ) {
  require_once($eqdkp_root_path . 'pluskernel/cache/cache.iface.php');
}

if ( !class_exists( "cache_mysql" ) ) {
  class cache_mysql implements plus_datacache{
    
    var $enable_compress = false;
    var $enable_md5 = false;
    var $default_ttl = 86400;
    var $cache_table = '__data_cache';
    
    function init($param_array){
      $this->cache_table = $param_array['cache_table'];
    }
    
    function put($key, $data, $ttl=null){
    global $db;
      if($ttl == null)
        $ttl = $this->default_ttl;
            
      $hash = $this->encode_key($key);  
      $sql =  "SELECT entity FROM ".$this->cache_table." WHERE entity='".$hash."';";

    	if (!($result = $db->query($sql))){
    		message_die('Could not contact cache', '', __FILE__, __LINE__, $sql);
    	}
      
      if ( 0 === $db->num_rows($result) ){	
        $sql = "INSERT INTO ".$this->cache_table." (entity, date, ttl, data) VALUES('".$hash."','".time()."','$ttl','".$db->escape($this->compress($data))."');";
        $db->query($sql);
      }else{
        $sql = "UPDATE ".$this->cache_table." SET date='".time()."', ttl='$ttl', data='".$db->escape($this->compress($data))."' WHERE entity='".$hash."';";
        $db->query($sql);
    	}
    	$db->free_result($result);
    }
    
    function get($key){
    global $db;
      $sql = "SELECT * FROM ".$this->cache_table." WHERE entity='".$this->encode_key($key)."'";
      $result = $db->query($sql);
      $entity_set = $db->fetch_record($result);
      $expiry_date = $entity_set['date'] + $entity_set['ttl'];
      $db->free_result();
      if( $expiry_date < time()){
        return NULL;
      }else{
        return $this->uncompress($entity_set['data']);
      }      
    }
    
    function del($key){
    global $db;
      $db->query("DELETE FROM ".$this->cache_table." WHERE entity='".$this->encode_key($key)."'");
    }

    function del_prefix($prefix){
    global $db;
      $db->query("DELETE FROM ".$this->cache_table." WHERE entity LIKE '".$prefix."%'");
    }
        
    function del_suffix($suffix){
    global $db;
      $db->query("DELETE FROM ".$this->cache_table." WHERE entity LIKE '%".$suffix."'");
    }
        
    function cleanup(){
      global $db;
      $time = time();
      
      $sql = 'SELECT entity, date, ttl FROM '.$this->cache_table;
      if (!($result = $db->query($sql))){
    		message_die('Could not contact cache', '', __FILE__, __LINE__, $sql);
    	}
      while($entity_set = $db->fetch_record($result)) {
        $expiry_date = $entity_set['date'] + $entity_set['ttl'];
        if( $expiry_date < $time){
          $this->del($entity_set['entity']);
        }
      }
      $db->free_result();	     
    }
    
    function flush(){
    global $db;
      $db->query('TRUNCATE TABLE '.$this->cache_table);
    }
    
    function listing(){
    global $db;
      //get cache data
      $sql = "SELECT entity, LENGTH(data) AS size, ttl, date FROM ".$this->cache_table;
      $result = $db->query($sql);
      
      //create cache listing
      $cache_arr = array();
      
      while ($row = $db->fetch_record($result)){    
        $cache_arr[$row['entity']]['size'] = $row['size']; //size in bytes
        $cache_arr[$row['entity']]['exp_date'] = $row['date']+$row['ttl']; //expiry_date
      }
      return $cache_arr;
    }
    
    function encode_key($key){
    global $db;
      if($this->enable_md5)
        return md5($key);
      else
        return $db->escape($key);
    }    
    
    function compress(&$data){
      if($this->enable_compress == true){
        return gzcompress(serialize($data),9);
      }else{
        return serialize($data);
      }
    }
    
    function uncompress(&$data){
      if($this->enable_compress == true){
        return unserialize(gzuncompress($data));
      }else{
        return unserialize($data);
      }
    }
        
  }//end class
}//end if
?>
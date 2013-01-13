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
 * $Id: cache_mysql_sq.class.php 5159 2009-07-04 11:06:09Z sz3 $
 */
 
if ( !defined('EQDKP_INC') )
{
     die('Do not access this file directly.');
}

if ( !interface_exists( "plus_datacache" ) ) {
  require_once($eqdkp_root_path . 'pluskernel/cache/cache.iface.php');
}

if ( !class_exists( "cache_mysql_sq" ) ){
  class cache_mysql_sq implements plus_datacache{
  
    var $enable_compress = false;
    var $enable_md5 = false;
    var $default_ttl = 86400;
    var $cache = null;
    var $cache_table = '__data_cache';
        
    function init($param_array){
      $this->cache_table = $param_array['cache_table'];
    } 
    
    function put($key, $data, $ttl=null){
    global $db;
      if($ttl == null)
        $ttl = $this->default_ttl;
            
      $hash = $this->encode_key($key);   
      $time = time();
      
      $sql =  "DELETE FROM ".$this->cache_table." WHERE entity='".$hash."';";
      $db->query($sql);
      $sql = "INSERT INTO ".$this->cache_table." (entity, date, ttl, data) VALUES('".$hash."','".$time."','$ttl','".$db->escape($this->compress($data))."');";
      $db->query($sql);    	
    	$db->free_result($result);
    	
    	//write to our local cache object if this is already initialised
    	if(isset($this->cache)){
        $this->cache[$hash]['data'] = $data;
        $this->cache[$hash]['exp_date'] = $time+$ttl;
      }
    }
    
    function get($key){
    global $db;
    
      $hash = $this->encode_key($key);
      if(isset($this->cache)){
        if($this->cache[$hash]['exp_date'] < time()){
          return null;
        }
      }else{
        $sql = "SELECT * FROM ".$this->cache_table;
        $result = $db->query($sql);
        while($entity_set = $db->fetch_record($result)){
          $expiry_date = $entity_set['date'] + $entity_set['ttl'];
          $this->cache[$entity_set['entity']]['exp_date'] = $expiry_date;
          if( $expiry_date < time()){
            $this->cache[$entity_set['entity']]['data'] = null;  
          }else{
            $this->cache[$entity_set['entity']]['data'] = $entity_set['data'];
          }
        }
        $db->free_result();
      }
      if(isset($this->cache[$hash]['data']))
        $retval = $this->uncompress($this->cache[$hash]['data']);
      else $retval = null;
      return  $retval;
    }
    
    function del($key){
    global $db;
      $hash = $this->encode_key($key);
      unset($this->cache[$hash]);
      $db->query("DELETE FROM ".$this->cache_table." WHERE entity='".$hash."'");
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
      unset($this->cache);
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
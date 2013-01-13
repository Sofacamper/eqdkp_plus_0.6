<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-07-04 17:10:09 +0200 (Sat, 04 Jul 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: osr-corgan $
 * @copyright   2007-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 5168 $
 * 
 * $Id: cache_apc.class.php 5168 2009-07-04 15:10:09Z osr-corgan $
 */
 
if ( !defined('EQDKP_INC') )
{
     die('Do not access this file directly.');
}

if ( !interface_exists( "plus_datacache" ) ) {
  require_once($eqdkp_root_path . 'pluskernel/cache/cache.iface.php');
}

if ( !class_exists( "cache_apc" ) ) 
{
	class cache_apc implements plus_datacache
	{
		var $default_ttl = 360;
		var $prefix;
		
		function init($param_array)
		{
			$this->prefix = $param_array['prefix'];
		}
					
		function put($key, $value, $ttl='',$noprefix=false,$compress=false)
		{
			
			$key = ($noprefix) ? $key : $this->prefix.'.'.$key ;			
			$value = ($compress) ? $this->compress($value) : $value ;
			$ttl = ($ttl == '') ?  $this->default_ttl : $ttl ;			        								
			
			if(function_exists(apc_store) )
			{			
			  return apc_store($key, $value, $ttl);
      		}else{
        		return false;
      		}			
	   }
				
		function get($key,$noprefix=false,$compress=false)
		{
			$res = null;
			$key = ($noprefix) ? $key : $this->prefix.'.'.$key;
						
			if(function_exists(apc_fetch) )
			{			  
			  $res  = apc_fetch($key);							
			}
			
			if ($compress){
				$res = $this->uncompress($res);
			}			
			
			return $res;		
		}
		
		function del($key){
			if(function_exists(apc_delete)){
				$res  = apc_delete($this->prefix.'.'.$key);			
			}		
			return $res;
		}

  	function del_prefix($prefix){
			$usercache = apc_cache_info('user');
			$searchstring = $this->prefix.'.'.$prefix ;					
			foreach ($usercache['cache_list'] as $key){				
				if (strpos("_".$key['info'],$searchstring) > 0 ){
					apc_delete($key['info']);
				}				  	
			}
		}		
				
  	function del_suffix($suffix){
			$usercache = apc_cache_info('user');
			$searchstring = $this->prefix.'.'.$suffix ;					
			foreach ($usercache['cache_list'] as $key){				
				if (strpos("_".$key['info'],$searchstring) > 0 ){
					apc_delete($key['info']);
				}				  	
			}
		}		
		
    function cleanup(){
      //implement method to delete outdated cache entries
    }
    
    function flush(){
      //implement method to flush all entries 
    }
    
    function listing(){
      //implement method which returns a listing (key, size, ttl) of all cache entries
    }
    
    function compress(&$data){      	
      return gzcompress(serialize($data),9);
  	}
  
  	function uncompress(&$data){
      return unserialize(gzuncompress($data));      
    }
	    
  }//end class
}//end if
?>
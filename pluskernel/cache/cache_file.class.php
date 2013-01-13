<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-07-01 00:46:35 +0200 (Mi, 01 Jul 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: osr-corgan $
 * @copyright   2007-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 5132 $
 * 
 * $Id: cache_apc.class.php 5132 2009-06-30 22:46:35Z osr-corgan $
 */
 
if ( !defined('EQDKP_INC') )
{
     die('Do not access this file directly.');
}

if ( !interface_exists( "plus_datacache" ) ) {
  require_once($eqdkp_root_path . 'pluskernel/cache/cache.iface.php');
}

if ( !class_exists( "cache_file" ) ) 
{
  class cache_file implements plus_datacache
  {

		var $default_ttl = 360;
		
    function init($param_array){
    }
    	
		function put($key, $value, $ttl='',$noprefix=false,$compress=false)
		{
/*
        $f=@fopen(dirname(__FILE__) . '/../'.path_cache .$filename ,"w");
        if (!$f)
        {
          return false;
        } else
        {
          fwrite($f,serialize($item));
          fclose($f);
        }			
			*/

			
			if ($compress) {
				$value = $this->compress($value);
			}			
			
				if ($ttl == '')
				{
					if ($noprefix == true) 
					{
						#apc_store($key, $value);	
					}else 
					{
						#apc_store($this->getPrefix().'.'.$key, $value);
					}														
				}
				else
				{
					if ($noprefix == true) 
					{
						#apc_store($key, $value,$ttl);	
					}else
					{
						#apc_store($this->getPrefix().'.'.$key, $value,$ttl);
					}													
				}			
			
			
			return true;				
		}
				
		function get($key,$noprefix=false,$compress=false)
		{
			#$file = dirname(__FILE__) . '/../'.path_cache . $search_name;
	        #if (file_exists($file) && (time()-filemtime($file)<=86400)) 

			#d($key);	
			$res = null;
						
				if ($noprefix == true) 
				{
					$res  = apc_fetch($key);
				}else {
					$res  = apc_fetch($this->getPrefix().'.'.$key);	
				}		

#			$item = file_get_contents($file);                  
#           if (!$item)
#              return false;
#           $item = unserialize($item);
				
			
			if ($compress) 
			{
				$res = $this->uncompress($res);
			}			
			
			return $res;		
		}
		
		function del($key)
		{
			if(function_exists(apc_delete))
			{
				#$res  = apc_delete($this->getPrefix().$key);			
			}		
			return $res;
		}
		function del_prefix($prefix)
		{
			//
		}	
		
  		function del_suffix($suffix)
		{
			//
		}	
		
		function checkttl($file, $ttl)
		{
			//
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
	    
      	function compress(&$data)
      	{      	
	        return gzcompress(serialize($data),9);
    	}
    
    	function uncompress(&$data)
    	{
	        return unserialize(gzuncompress($data));      
      	}
    	    
    
  }//end class
}//end if
?>
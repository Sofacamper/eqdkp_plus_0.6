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
 * $Id: cache.iface.php 5159 2009-07-04 11:06:09Z sz3 $
 */
 
if ( !defined('EQDKP_INC') ){
     die('Do not access this file directly.');
}

if ( !interface_exists( "plus_datacache" ) ){
  interface plus_datacache{
    
  	function init($param_array);
    function put($key, $value, $ttl='');
    function get($key);
    function del($key);
    function del_prefix($prefix);
    function del_suffix($suffix);
    function cleanup();
    function flush();
    function listing();
    
  }//end interface
}//end if
?>
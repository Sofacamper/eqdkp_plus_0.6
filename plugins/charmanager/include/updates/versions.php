<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-04-27 00:02:00 +0200 (Mo, 27 Apr 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 4670 $
 * 
 * $Id: versions.php 4670 2009-04-26 22:02:00Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$up_updates 	= array(
      '1.0.4'   => array(
                      'file'  => '100_to_104.php',
                      'old'   => '1.0.0',
      ),
      '1.1.0'   => array(
                      'file'  => '104_to_110.php',
                      'old'   => '1.0.4',
      ),
      '1.2.0'   => array(
                      'file'  => '115_to_120.php',
                      'old'   => '1.1.5',
      ),
      '1.5.0'   => array(
                      'file'  => '149_to_150.php',
                      'old'   => '1.4.9',
      ),
);
												
 ?>

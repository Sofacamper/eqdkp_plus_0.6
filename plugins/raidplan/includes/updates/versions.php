<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2008-11-03 13:24:11 +0100 (Mon, 03 Nov 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 2963 $
 * 
 * $Id: versions.php 2963 2008-11-03 12:24:11Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$up_updates 	= array(
      '2.0.0'   => array(
                      'file'  => '100_to_200.php',
                      'old'   => '1.0.0',
      ),
      '2.1.0'   => array(
                      'file'  => '203_to_210.php',
                      'old'   => '2.0.3',
      ),
      '3.0.0'   => array(
                      'file'  => '210_to_300.php',
                      'old'   => '2.1.0',
      ),
      '3.0.4'   => array(
                      'file'  => '300_to_304.php',
                      'old'   => '3.0.0',
      ),
      '3.1.0'   => array(
                      'file'  => '305_to_310.php',
                      'old'   => '3.0.5',
      ),
      '4.0.0'   => array(
                      'file'  => '310_to_400.php',
                      'old'   => '3.1.0',
      ),
      '4.1.0'   => array(
                      'file'  => '400_to_410.php',
                      'old'   => '4.0.0',
      ),
      '4.1.1'   => array(
                      'file'  => '410_to_411.php',
                      'old'   => '4.1.0',
      ),
);
										
?>

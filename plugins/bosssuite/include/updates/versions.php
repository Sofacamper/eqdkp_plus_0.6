<?php
 /*
 * Project:     BossSuite v4 MGS
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2011-12-04 16:04:49 +0100 (Sun, 04 Dec 2011) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2006-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     bosssuite
 * @version     $Rev: 11499 $
 *
 * $Id: versions.php 11499 2011-12-04 15:04:49Z sz3 $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
											
	$up_updates 	= array(
	      '4.0.6'   => array(
                      'file'  => '405_to_406.php',
                      'old'   => '4.0.5',
        ),
        '4.0.9'   => array(
                      'file'  => '406_to_409.php',
                      'old'   => '4.0.6',
        ),
        '4.1.0'   => array(
                      'file'  => '409_to_410.php',
                      'old'   => '4.0.9',
        ),
        '4.2.0'   => array(
                      'file'  => '410_to_420.php',
                      'old'   => '4.1.0',
        ),
        '4.3.7'   => array(
                      'file'  => '420_to_437.php',
                      'old'   => '4.2.0',
        ),
        '4.3.8'   => array(
                      'file'  => '437_to_438.php',
                      'old'   => '4.3.7',
        ),
        '4.4.0'   => array(
                      'file'  => '439_to_440.php',
                      'old'   => '4.3.9',
        ),
        '4.5.1'   => array(
                      'file'  => '450_to_451.php',
                      'old'   => '4.5.0',
        ),
        '4.5.2'   => array(
                      'file'  => '451_to_452.php',
                      'old'   => '4.5.1',
        ),
        '4.5.3'   => array(
                      'file'  => '452_to_453.php',
                      'old'   => '4.5.2',
        ),
        '4.5.5'   => array(
                      'file'  => '453_to_455.php',
                      'old'   => '4.5.3',
        ),
        '4.5.7'   => array(
                      'file'  => '456_to_457.php',
                      'old'   => '4.5.6',
        ),
        '4.5.8'   => array(
                      'file'  => '457_to_458.php',
                      'old'   => '4.5.7',
        ),
        '4.6.1'   => array(
                      'file'  => '458_to_461.php',
                      'old'   => '4.5.8',
        ),
        '4.7.0'   => array(
                      'file'  => '462_to_470.php',
                      'old'   => '4.6.2',
        ),
        '4.7.1'   => array(
                      'file'  => '470_to_471.php',
                      'old'   => '4.7.0',
        ),
        '4.8.0'   => array(
                      'file'  => '471_to_480.php',
                      'old'   => '4.7.1',
        ),
        '4.8.1'   => array(
                      'file'  => '480_to_481.php',
                      'old'   => '4.8.0',
        ),
        '4.8.2'   => array(
                      'file'  => '481_to_482.php',
                      'old'   => '4.8.1',
        ),
        '4.8.3'   => array(
                      'file'  => '482_to_483.php',
                      'old'   => '4.8.2',
        ),
  );
?>

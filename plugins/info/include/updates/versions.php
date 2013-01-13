<?php
 /*
 * Project:     Infopages
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-02-26 11:15:07 +0100 (Thu, 26 Feb 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2006-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     infopages
 * @version     $Rev: 4004 $
 *
 * $Id: versions.php 4004 2009-02-26 10:15:07Z sz3 $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
											
	$up_updates 	= array(
	      '2.1.0'   => array(
                      'file'  => '2011_to_210.php',
                      'old'   => '2.0.11',
        ),
        '2.1.2'   => array(
                      'file'  => '210_to_212.php',
                      'old'   => '2.1.0',
        ),
        '2.1.4'   => array(
                      'file'  => '212_to_214.php',
                      'old'   => '2.1.2',
        ),
  );
?>

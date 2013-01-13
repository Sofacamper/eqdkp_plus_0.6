<?php
/*
 * Project:     EQdkp Shoutbox
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-12-08 10:13:37 +0100 (Tue, 08 Dec 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: Dallandros $
 * @copyright   2008 Aderyn
 * @link        http://eqdkp-plus.com
 * @package     shoutbox
 * @version     $Rev: 6629 $
 *
 * $Id: versions.php 6629 2009-12-08 09:13:37Z Dallandros $
 */

if (!defined('EQDKP_INC'))
{
  header('HTTP/1.0 404 Not Found');exit;
}

$up_updates   = array(
      '0.0.2'   => array(
                      'file'  => '001_to_002.php',
                      'old'   => '0.0.1',
      ),
      '0.0.7'   => array(
                      'file'  => '006_to_007.php',
                      'old'   => '0.0.6',
      ),
      '0.1.5'   => array(
                      'file'  => '014_to_015.php',
                      'old'   => '0.1.4',
      ),
      '0.1.8'   => array(
                      'file'  => '017_to_018.php',
                      'old'   => '0.1.7',
      )
);

?>

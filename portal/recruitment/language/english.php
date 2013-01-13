<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2010-07-07 16:29:56 +0200 (Wed, 07 Jul 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 8305 $
 * 
 * $Id: english.php 8305 2010-07-07 14:29:56Z sz3 $
 */


if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$plang = array_merge($plang, array(
  'recruitment'             => 'Recruitment',
  'recruitment_open'        => 'Searching Members',
  'recruitment_contact'     => 'Apply',
  'recruitment_noneed'      => 'There are no open slots at the moment.',
));
?>

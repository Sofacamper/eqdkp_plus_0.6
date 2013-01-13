<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2011
 * Date:        $Date: 2012-08-30 01:46:59 +0200 (Thu, 30 Aug 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 12001 $
 * 
 * $Id: convertion.php 12001 2012-08-29 23:46:59Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}

// Convert the Classnames to english
$classconvert_array = array(
	'german'  => array(
		'Mesmer'			=> 'Mesmer',
		'Ingenieur'			=> 'Engineer',
		'Dieb'				=> 'Thief',
		'Wächter'			=> 'Guardian',
		'Nekromant'			=> 'Necromancer',
		'Waldläufer'		=> 'Ranger',
		'Elementarmagier'	=> 'Elementalist',
		'Krieger'			=> 'Warrior',
	)
);

?>
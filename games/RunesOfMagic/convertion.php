<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-09-16 14:18:19 +0200 (Wed, 16 Sep 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: hoofy_leon $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 5857 $
 *
 * $Id: convertion.php 5857 2009-09-16 12:18:19Z hoofy_leon $
 */

if ( !defined('EQDKP_INC') )
{
    header('HTTP/1.0 404 Not Found');
    exit;
}

// Convert the Classnames to english
$classconvert_array = array(
  'german'  => array(
        'Krieger'              	=> 'Warrior',
        'Kundschafter'         	=> 'Scout',
        'Schurke'              	=> 'Rogue',
        'Magier'              	=> 'Mage',
        'Priester'              => 'Scout',
        'Priester'              => 'Priest',
        'Ritter'              	=> 'Knight',
        'Druide'				=> 'Druid',
        'Bewahrer'				=> 'Warden'

  )
);

?>
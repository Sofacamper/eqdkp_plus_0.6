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
 * $Id: init_vars.php 2963 2008-11-03 12:24:11Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

# class color init
$rpclassColorsCSS = array(
        );

# role init
$rproleVariables = array(          
    1   => array(
            'image'   => 'healer',
            'name'    => $user->lang['rp_healer'],
            'classes' => '1',
    ),
    2   => array(
            'image'   => 'tank',
            'name'    => $user->lang['rp_tank'],
            'classes' => '6|7',
    ),
    3   => array(
            'image'   => 'crowdcontrol',
            'name'    => $user->lang['rp_crowdcontrol'],
            'classes' => '4|5',
    ),
    4   => array(
            'image'   => 'damagedealer',
            'name'    => $user->lang['rp_damagedealer'],
            'classes' => '3|7',
    ),
    5   => array(
            'image'   => 'supporter',
            'name'    => $user->lang['rp_supporter'],
            'classes' => '2',
    )
);

?>

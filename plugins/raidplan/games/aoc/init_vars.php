<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2008-07-29 13:09:49 +0200 (Di, 29 Jul 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 2453 $
 * 
 * $Id: raidplan_plugin_class.php 2453 2008-07-29 11:09:49Z wallenium $
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
            'classes' => '',
    ),
    2   => array(
            'image'   => 'tank',
            'name'    => $user->lang['rp_tank'],
            'classes' => '',
    ),
    3   => array(
            'image'   => 'range',
            'name'    => $user->lang['rp_range'],
            'classes' => '',
    ),
    4   => array(
            'image'   => 'melee',
            'name'    => $user->lang['rp_melee'],
            'classes' => '',
    )
);

?>

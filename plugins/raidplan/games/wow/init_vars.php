<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2012-09-30 22:37:02 +0200 (Sun, 30 Sep 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 12160 $
 * 
 * $Id: init_vars.php 12160 2012-09-30 20:37:02Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

# class color init
$rpclassColorsCSS = array(
          'classc_druid'        => '#FF7C0A',
          'classc_warlock'      => '#9382C9',
          'classc_hunter'       => '#AAD372',
          'classc_warrior'      => '#C69B6D',
          'classc_paladin'      => '#F48CBA',
          'classc_mage'         => '#68CCEF',
          'classc_priest'       => '#FFFFFF',
          'classc_shaman'       => '#1a3caa',
          'classc_rogue'        => '#FFF468',
          'classc_deathknight'  => '#C41F3B',
        );

# role init
$rproleVariables = array(          
    1   => array(
            'image'   => 'healer',
            'name'    => $user->lang['rp_healer'],
            'classes' => '6|7|9|13|21',
    ),
    2   => array(
            'image'   => 'tank',
            'name'    => $user->lang['rp_tank'],
            'classes' => '7|12|13|20',
    ),
    3   => array(
            'image'   => 'range',
            'name'    => $user->lang['rp_range'],
            'classes' => '4|6|9|10|11|7|21',
    ),
    4   => array(
            'image'   => 'melee',
            'name'    => $user->lang['rp_melee'],
            'classes' => '2|9|12|13|7|20|21',
    )
);

?>

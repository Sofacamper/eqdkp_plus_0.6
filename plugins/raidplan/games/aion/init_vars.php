<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2009-01-11 14:19:14 +0100 (Sun, 11 Jan 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 3562 $
 * 
 * $Id: init_vars.php 3562 2009-01-11 13:19:14Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

# class color init
$rpclassColorsCSS = array(
          'classc_templar'      => '#4080FF',
          'classc_gladiator'    => '#4080FF',
          'classc_assassin'			=> '#80FF00',
          'classc_ranger'				=> '#80FF00',
          'classc_sorcerer'			=> '#7d5ebc',
          'classc_spiritmaster'	=> '#7d5ebc',
          'classc_cleric'       => '#FFFFFF',
          'classc_chanter'			=> '#FFFFFF',
        );

# role init
$rproleVariables = array(          
    1   => array(
            'image'   => 'healer',
            'name'    => $user->lang['rp_healer'],
            'classes' => '6|7|9|13',
    ),
    2   => array(
            'image'   => 'tank',
            'name'    => $user->lang['rp_tank'],
            'classes' => '7|12|13|20',
    ),
    3   => array(
            'image'   => 'range',
            'name'    => $user->lang['rp_range'],
            'classes' => '4|6|9|10|11|7',
    ),
    4   => array(
            'image'   => 'melee',
            'name'    => $user->lang['rp_melee'],
            'classes' => '2|9|12|13|7|20',
    )
);

?>

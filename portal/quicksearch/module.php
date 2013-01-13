<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2008-12-13 18:54:23 +0100 (Sa, 13 Dec 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: BadTwin $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 3356$
 *
 * $Id: module.php 3356 2008-12-13 21:51:54Z BadTwin $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

// You have to define the Module Information
$portal_module['quicksearch'] = array(                      // the same name as the folder!
			'name'			      => 'QuickSearch',             // The name to show
			'path'			      => 'quicksearch',                     // Folder name again
			'version'		      => '0.0.1',                          // Version
			'author'        	=> 'BadTwin',                      // Author
			'contact'	   	    => 'http://www.eqdkp-plus.com',      // email adress
			'description'   	=> 'search the databases of different WoW-Portals',           // Detailed Description
			'positions'     	=> array('left1', 'left2', 'right'), // Which blocks should be usable? left1 (over menu), left2 (under menu), right, middle
   		'signedin'      	=> '0',                              // 0 = all users, 1 = signed in only
   		'install'       	=> array(
                             'autoenable'        => '0',    // 0 = disable on install , 1 = enable on install
	                           'defaultposition'   => 'left1',// see blocks above
	                           'defaultnumber'     => '4',    // default ordering number
	                           ),
    );

/* Define the Settings

name:       The name of the Database field & Input name
language:   The name of the language string in the language file
property:   What type of field? (text,checkbox,dropdown,textarea)
size:       Size of the field if required (optional)
rows:       Rows for textarea, only needed there!
help:       Shows a "?" Icon after the Settings String wichs show on Mouseover a Help Tooltip with the "Help" String!
options:    If dropdown: array('value'=>'Name')
codeinput:  If true, html code is not striped out of the textarea. only working in textareas until now

There could be unlimited amount of settings
Settings page is created dynamically
*/


$portal_settings['quicksearch'] = array(
  'pm_quicksearch_newwindow'     => array(
        'name'      => 'pm_quicksearch_newwindow',
        'language'  => 'pm_qs_newwindow',
        'property'  => 'checkbox',
      ),
);



if(!function_exists(quicksearch_module)){
  function quicksearch_module(){
  	global $plang, $eqdkp_root_path, $eqdkp, $conf_plus;

  if ($eqdkp->config['default_game'] == 'WoW'){
    $searchlist = '<option value="1">Buffed (de/en)</option>
          <option value="2">WoWHead (de)</option>
          <option value="3">SpeedyDragon (de/en)</option>
          <option value="4">WoW (de/en)</option>
          <option value="5">Thottbot (en)</option>
          <option value="6">WoWDB (en)</option>';

  } elseif ($eqdkp->config['default_game'] == 'Warhammer'){
    $searchlist = '<option value="10">Buffed (de/en)</option>
          <option value="11">WarDB (en)</option>
          <option value="12">Allakhazam (en)</option>';

  } elseif ($eqdkp->config['default_game'] == 'Vanguard-SoH'){
    $qs_not_supp .= $plang['pm_qs_not_supp'];

  } elseif ($eqdkp->config['default_game'] == 'TR'){
    $qs_not_supp .= $plang['pm_qs_not_supp'];

  }  elseif ($eqdkp->config['default_game'] == 'LOTRO'){
    $searchlist = '<option value="41">Glingorn (de)</option>
          <option value="42">Lorebook (en)</option>
          <option value="40">Allakhazam (en)</option>';

//glingorn http://glingorn.de/index.php?page=search_result&searchstring=w%C3%A4chter&select=armor
//wrapper  http://glingorn.de/index.php?page=search_result&searchstring=w%C3%A4chter
  } elseif ($eqdkp->config['default_game'] == 'Everquest 2'){
    $searchlist = '<option value="50">LootDB (en)</option>';

  } elseif ($eqdkp->config['default_game'] == 'RunesOfMagic'){
    $searchlist = '<option value="60">Buffed (de/en)</option>';

  } elseif ($eqdkp->config['default_game'] == 'Aion'){
  $searchlist = '<option value="70">Onlinewelten (de)</option>
  							 <option value="71">AION database(de)</option>
  							 <option value="72">AION Armory(de/en)</option>';

  } elseif ($eqdkp->config['default_game'] == 'Everquest'){
    $qs_not_supp .= $plang['pm_qs_not_supp'];

  } elseif ($eqdkp->config['default_game'] == 'DAoC'){
    $qs_not_supp .= $plang['pm_qs_not_supp'];

  } elseif ($eqdkp->config['default_game'] == 'AoC'){
    $qs_not_supp .= $plang['pm_qs_not_supp'];

  } else {
    $qs_not_supp .= $plang['pm_qs_not_supp'];
  }

  if ($qs_not_supp != ''){
    return $qs_not_supp;
  }

	if ($conf_plus['pm_quicksearch_newwindow'] != 1){
    $quicksearch .= '<form method="GET" action="'.$eqdkp_root_path.'portal/quicksearch/wrapper.php">';
  } else {
    $quicksearch .= '<form method="GET" action="'.$eqdkp_root_path.'portal/quicksearch/wrapper.php" target="_blank">';
  }
    $quicksearch .= '<table width="100%" cellpadding="2" cellspacing="0">
      <tr class="row1">
        <td align="right">'.$plang['pm_qs_search'].'</td>
        <td><input class="input" name="search_item"></td>
      </tr>
      <tr class="row2">
        <td align="right">'.$plang['pm_qs_db'].'</td>
        <td align="left"><select class="input" name="search_db">
            '.$searchlist.'
          </select>
        </td>
      </tr>
      <tr class="row1">
        <td colspan="2" align="center">
          <input class="mainoption" type="submit" value="'.$plang['pm_qs_button'].'">
        </td>
      </tr>
    </table>
    </form>';

		return $quicksearch;
  }
}
?>

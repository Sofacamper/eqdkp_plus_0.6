<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2008-08-02 14:44:02 +0200 (Sa, 02 Aug 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: osr-corgan $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 2476 $
 * 
 * $Id: module.php 2476 2008-08-02 12:44:02Z osr-corgan $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

// You have to define the Module Information
$portal_module['advertising'] = array(                      			// the same name as the folder!
			'name'			    => 'Advertising Modul',             // The name to show
			'path'			    => 'advertising',                   // Folder name again
			'version'		    => '1.0.1',                         // Version
			'author'        	=> 'Corgan',                     // Author
			'contact'		    => 'http://www.eqdkp-plus.com',     // email adress
			'description'   	=> 'Add your own Advertising',          // Detailed Description
			'positions'     	=> array('left1', 'left2', 'right', 'middle'), // Which blocks should be usable? left1 (over menu), left2 (under menu), right, middle
      		'signedin'      	=> '0',                              // 0 = all users, 1 = signed in only
      		'install'       	=> array(
			                            'autoenable'        => '0',    // 0 = disable on install , 1 = enable on install
			                            'defaultposition'   => 'left2',// see blocks above
			                            'defaultnumber'     => '10',    // default ordering number
			                            ),
    );

/* Define the Settings if needed

name:       The name of the Database field & Input name
language:   The name of the language string in the language file
property:   What type of field? (text,checkbox,dropdown,textarea)
size:       Size of the field if required (optional)
rows:       Rows for textarea, only needed there!
help:       Shows a "?" Icon after the Settings String wichs show on Mouseover a Help Tooltip with the "Help" String!
options:    If dropdown: array('value'=>'Name')

There could be unlimited amount of settings
Settings page is created dynamically
*/
$portal_settings['advertising'] = array(
  'pk_advertising_useroutput'     => array(
        'name'      => 'pk_advertising_useroutput',
        'language'  => 'pk_advertising_useroutput',
        'property'  => 'textarea',
        'size'      => '80',
        'cols'      => '80',
        'help'      => '',
        'codeinput' => true,
      ),
  'pk_advertising_headtext'     => array(
        'name'      => 'pk_advertising_headtext',
        'language'  => 'pk_advertising_headtext',
        'property'  => 'text',
        'size'      => '30',
        'help'      => '',
      )
);

// The output function
// the name MUST be FOLDERNAME_module, if not an error will occur
if(!function_exists(advertising_module))
{
	function advertising_module()
  	{
		global $eqdkp , $user , $tpl, $db, $plang, $conf_plus;

  		// Set the output: If custom one is entered in the setting output this one
  		// $conf_plus for config values, $plang for language values
  		$output = ($conf_plus['pk_advertising_useroutput']) ? html_entity_decode(htmlspecialchars_decode($conf_plus['pk_advertising_useroutput'])) : $plang['portal_advertising_text'];
	
		// OPTIONAL
		// If you want to modify the header, use the following
		// JS Script Trick (txt+ID in this case: txthelloworld
		if($conf_plus['pk_advertising_headtext'])
		{
		  $output .= "<script>document.getElementById('txtadvertising').innerHTML = '".addslashes($conf_plus['pk_advertising_headtext'])."'</script>";
		}

    	// return the output for module manager
		return $output;
  	}
}
?>

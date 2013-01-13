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
$portal_module['amazon'] = array(                      			// the same name as the folder!
			'name'			    => 'Amazon Modul',             // The name to show
			'path'			    => 'amazon',                   // Folder name again
			'version'		    => '1.0.0',                         // Version
			'author'        	=> 'Corgan',                     // Author
			'contact'		    => 'http://www.eqdkp-plus.com',     // email adress
			'description'   	=> 'Add your Amazon Shop Widget here',          // Detailed Description
			'positions'     	=> array('left1', 'left2', 'right', 'middle'), // Which blocks should be usable? left1 (over menu), left2 (under menu), right, middle
      		'signedin'      	=> '0',                              // 0 = all users, 1 = signed in only
      		'install'       	=> array(
			                            'autoenable'        => '0',    // 0 = disable on install , 1 = enable on install
			                            'defaultposition'   => 'right',// see blocks above
			                            'defaultnumber'     => '13',    // default ordering number
			                            ),
    );

$portal_settings['amazon'] = array(
  'pk_amazon_useroutput'     => array(
        'name'      => 'pk_amazon_useroutput',
        'language'  => 'pk_amazon_useroutput',
        'property'  => 'textarea',
        'size'      => '80',
        'cols'      => '80',
        'help'      => '',
        'codeinput' => true,
      ),
  'pk_amazon_headtext'     => array(
        'name'      => 'pk_amazon_headtext',
        'language'  => 'pk_amazon_headtext',
        'property'  => 'text',
        'size'      => '30',
        'help'      => '',
      )
);

// The output function
// the name MUST be FOLDERNAME_module, if not an error will occur
if(!function_exists(amazon_module))
{
	function amazon_module()
  	{
		global $eqdkp , $user , $tpl, $db, $plang, $conf_plus;

		$output = "<table align=center><tr><td> ";
  		$output .= ($conf_plus['pk_amazon_useroutput']) ? html_entity_decode(htmlspecialchars_decode($conf_plus['pk_amazon_useroutput'])) : $plang['portal_amazon_text'];
  		$output .= "</td></tr></table> ";

		if($conf_plus['pk_amazon_headtext'])
		{
		  $output .= "<script>document.getElementById('txtguildox').innerHTML = '".addslashes($conf_plus['pk_amazon_headtext'])."'</script>";
		}

    	// return the output for module manager
		return $output;
  	}
}
?>

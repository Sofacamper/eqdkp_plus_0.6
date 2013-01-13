<?php
 /*
 * Project:     InfoPages
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-01-27 04:03:26 +0100 (Di, 27 Jan 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2007-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     info
 * @version     $Rev: 3655 $
 *
 * $Id$
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

// You have to define the Module Information
$portal_module['infopages'] = array(                   // the same name as the folder!
			'name'			    => 'InfoPages Module',             // The name to show
			'path'			    => 'infopages',                     // Folder name again
			'version'		    => '1.0.0',                          // Version
			'author'        => 'sz3',                         // Author
			'contact'		    => 'sz3@gmx.net',          // email adress
			'description'   => 'Infopages Menu Block',           // Detailed Description
			'positions'     => array('middle', 'left1', 'left2', 'right'), // Which blocks should be usable? left1 (over menu), left2 (under menu), right, middle
      'settings'      => '0',                               // 0  = no settings, 1 = settings
      'install'       => array(
                            'autoenable'        => '0',
                            'defaultposition'   => 'right',
                            'defaultnumber'     => '1',
                          ),
    );
    
$portal_settings['infopages'] = array(
  'pk_infopages_headtext'     => array(
        'name'      => 'pk_infopages_headtext',
        'language'  => 'pk_infopages_headtext',
        'property'  => 'text',
        'size'      => '30',
        'help'      => '',
      )
);
if(!function_exists(infopages_module)){
  function infopages_module(){
  	global $eqdkp , $user , $tpl, $db, $plang, $conf_plus, $pm, $wherevalue, $eqdkp_root_path, $SID;
  	// This Module requires EQDKP PLUS 0.6.2.x
    if(EQDKPPLUS_VERSION < '0.6.2.1'){
      return $plang['pk_latestposts_plus2old'];
    }
    $returnme = '';
    // Set the header
		if($conf_plus['pk_infopages_headtext']){
		  $returnme .= "<script>document.getElementById('txtinfopages').innerHTML = '".addslashes($conf_plus['pk_infopages_headtext'])."'</script>";
		}
    include_once($eqdkp_root_path.'/plugins/info/include/functions.php');
    $page_links = getPageLinks();
    $returnme .= '<table width=100% cellspacing="0" cellpadding="0">';
    $info_base_url = $eqdkp_root_path.'plugins/info/info.php'.$SID.'&amp;page=';
    
    if($wherevalue == 'middle'){
      foreach ( $page_links as $page_id => $details ){
        if($details['page_menu_link'] != 99)
         continue;
        // Don't display the link if they don't have permission to view it
        if ($user->check_auth('u_information_view', false)){
          $returnme .= '<a href="'.$info_base_url.$page_id.'" class="copy" target="_top">'.$details['page_title'] . '</a> | ';
        }         
      }
    }else{ 
      $bi = 1; #row counter
      foreach ( $page_links as $page_id => $details ){
        // Don't display the link if they don't have permission to view it
        if ($user->check_auth('u_information_view', false)){
          if($details['page_menu_link'] != 99)
            continue;
          $class = "row".($bi+1) ;
          $returnme .= '<tr class="'.$class.'" nowrap onmouseover="this.className=\'rowHover\';" onmouseout="this.className=\''.$class.'\';">
          <td nowrap>&nbsp;<img src="' .$eqdkp_root_path .'images/arrow.gif" alt="arrow"/> &nbsp;
          <a href="'.$info_base_url.$page_id.'" class="copy" target="_top">' . $details['page_title'] . '</a>
          </td></tr>';
  
          $bi = 1-$bi;
        }         
      }    
    }
    
    $returnme .= '</table>';
    return $returnme;
  }
}
?>

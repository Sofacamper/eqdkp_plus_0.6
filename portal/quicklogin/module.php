<?php
/*
 * Project:     Quicklogin 4 EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2008-11-12 01:42:54 +0100 (Mi, 12 Nov 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: BadTwin $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 3120
 * 
 * $Id: module.php 3120 2008-11-12 01:42:54 BadTwin $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$portal_module['quicklogin'] = array(
			'name'			      => 'Quick Login',
			'path'			      => 'quicklogin',
			'version'		      => '0.0.2',
			'author'        	=> 'BadTwin',
			'contact'		      => 'http://www.eqdkp-plus.com',
			'description'   	=> 'Quick Login Module for EQdkp-Plus',
			'positions'     	=> array('left1', 'left2', 'right'),
  		'signedin'      	=> '0',
      'install'       	=> array(
			                     'autoenable'        => '0',
			                     'defaultposition'   => 'left2',
			                     'defaultnumber'     => '2',
			                     ),
      );

if(!function_exists(quicklogin_module)){
  function quicklogin_module(){
  	global $eqdkp , $user , $tpl, $db, $plang, $conf_plus, $db, $eqdkp_root_path;

		if ( $user->data['user_id'] == ANONYMOUS )
		{
    $quicklogin = '<form method="post" action="'.$eqdkp_root_path.'login.php" name="post">
    <table width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <td class="row1" align="center">
          <table border="0" cellspacing="0" cellpadding="3" align="center" class="borderless">
            <tr>
              <td class="row1" width="40" nowrap="nowrap" align="right">'.$plang['pm_quicklogin_uname'].'</td>
              <td class="row1" width="100" align="center"><input type="text" name="username" size="20" maxlength="30" class="input" /></td>
            </tr>
            <tr>
              <td class="row1" width="40" nowrap="nowrap" align="right">'.$plang['pm_quicklogin_passwd'].'</td>
              <td class="row1" width="100"><input type="password" name="password" size="20" maxlength="32" class="input" /></td>
            </tr>
            <tr>
              <td class="row1" colspan="2" align="center" valign="middle" nowrap="nowrap">
                 '.$plang['pm_quicklogin_autologin'].'<input type="checkbox" name="auto_login" class="input" />
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr align="center">
        <th><input type="submit" name="login" value="'.$plang['pm_quicklogin_signin'].'" class="mainoption" /> <input type="submit" name="lost_password" value="'.$plang['pm_quicklogin_lostpwd'].'" class="liteoption" /></th>
      </tr>
    </table>
    </form>';
    
    //Register Check
      if (!$conf_plus['pk_disable_reg']==1){
       	$register_link = ($conf_plus['pk_bridge_cms_deac_reg']) ? 'wrapper.php?id=register' :  'register.php'. $SID ;
        
        $quicklogin .= '<table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr align="center">
            <td class="row2" align="center"><b><a href="'. $eqdkp_root_path.$register_link .'">'.$plang['pm_quicklogin_register'].'</b></a></td>
          </tr>
        </table>';
      }
    } else {
      $quicklogin = '<table width="100%" border="0" cellspacing="1" cellpadding="2">
        <tr>
          <td class="row1" align="center">
            <span style="color:#008800">'.$plang['pm_quicklogin_loggedin'].' <b>'.$user->data['username'].'</b>.</span>
          </td>
        </tr>
        <tr>
          <td class="row2" align="center">
            <b><a href="'.$eqdkp_root_path.'login.php?logout=true">'.$plang['pm_quicklogin_logout'].'</a></b>
          </td>
        </tr>
      </table>';
    }
    
    // return the output for module manager
		return $quicklogin;
  }
}
?>

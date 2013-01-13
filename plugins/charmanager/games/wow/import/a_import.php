<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-06-13 22:35:55 +0200 (Sa, 13 Jun 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 5076 $
 * 
 * $Id: a_import.php 5076 2009-06-13 20:35:55Z wallenium $
 */
 
// EQdkp required files/vars
define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'charmanager');
$eqdkp_root_path = './../../../../../';
include_once('../../../include/common.php');
$members = array();
$ii = 0;

if(!version_compare(phpversion(), '5.0.0', '>=')){
  die('This Plugin needs at least PHP-Version 5. Your Version is: '.phpversion().'.');
}

$output = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=".$user->lang['ENCODING']."' />";

// Check user permission
$user->check_auth('a_charmanager_config');

if($in->get('gimp') == 'true'){
	// Import the chars from armory
		
}else{
	// Mass Update the Chars
	$result = $db->query("SELECT member_name FROM __members ORDER BY member_name");
	while($row = $db->fetch_record($result)) {
		$members[$ii] = $row['member_name'];
		$ii++;
	}
	echo '<style>
					.uc_headerload{
						font-size: 14px;
						text-align:center;
					}
					.uc_headtxt2{
						margin:4px;
						margin-bottom: 10px;
					}
				</style>';		
	$output .= '<div id="loadingtext" class="uc_headerload">
								<img src="../../../images/loading.gif" alt="update" \>
								<div class="uc_headtxt2">'.$user->lang['uc_profile_updater'].'</div>
								<iframe src="armory/updateprofile.php?count='.$ii.'&actual=0" width="100%" height="60" name="item_update" frameborder=0 scrolling="no">
											</iframe>
</div>';
}
echo $output;
?>

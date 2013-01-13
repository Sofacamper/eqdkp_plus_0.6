<?PHP
/*************************************************\
*             MediaCenter 4 EQdkp plus            *
* ----------------------------------------------- *
* Project Start: 08/2009                          *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 0.0.1                                  *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}


// You have to define the Module Information
$portal_module['votm'] = array(        			    // the same name as the folder!
			'name'			   	=> 'Video of the Moment',   						// The name to show
			'path'			   	=> 'votm',                 // Folder name again
			'version'		   	=> '0.0.1',            							// Version
			'author'       		=> 'GodMod',             						// Author
			'contact'		   	=> 'godmod23@web.de',   		// email adress
			'description'  		=> 'Display a random Video from your MediaCenter',     			// Detailed Description
			'positions'    		=> array('left1', 'left2', 'right'), // Which blocks should be usable? left1 (over menu), left2 (under menu), right, middle
      		'signedin'     		=> '0',								              // 0 = all users, 1 = signed in only
      		'install'      		=> array(
			                   		'autoenable'        => '0',      // 0 = disable on install , 1 = enable on install
			                   		'defaultposition'   => 'left1',  // see blocks above
			                   		'defaultnumber'     => '2',      // default ordering number
			                   	),
);



// Settings
$portal_settings['votm'] = array(
	

	
  'pm_votm_tooltip' => array(
		'name'  		=>	'pm_votm_tooltip',
		'language'	=>	'pm_votm_tooltip',
		'property'	=>	'checkbox',
	),

);


			
// The output function
// the name MUST be FOLDERNAME_module, if not an error will occur
if(!function_exists(votm_module)){
  function votm_module(){
  	global $eqdkp , $db , $eqdkp_root_path , $user , $tpl, $db, $plang, $conf_plus, $comments, $dbname, $pcache, $SID, $html;
	
	include_once($eqdkp_root_path . 'plugins/mediacenter/include/mediacenter.class.php');
	if (!$mcclass){
		$mcclass = new MediaCenterClass();
	}
	
	$sql= "SELECT * FROM __mediacenter_media WHERE status != '0' ORDER BY RAND() LIMIT 1";
	$sql = $db->query($sql);
	$media = $db->fetch_record($sql);
	
	if ($media){
		
		$output = '<style>.mc_tt_help{ width: 300px; min-height:100px; background:#fff url('.$eqdkp_root_path.'plugins/mediacenter/images/bg_info.jpg) bottom right no-repeat; border:1px solid #4f6d81; color: black;
} .mc_tt_help td{
	color: black;
	font-family: Verdana, Tahoma, Arial;
	font-size: 11px;
}
</style>';
	$output .= '<script src="'.$eqdkp_root_path.'plugins/mediacenter/include/js/reflection/reflection.js"></script>';

		
		if ($conf_plus['pm_votm_tooltip'] == 1){
					
					// Get Category Name
					$sql = "SELECT category_name FROM __mediacenter_categories WHERE category_id='" . $db->escape($media['category']) . "';";
					$cat = $db->query($sql);
					$cat_name = $db->fetch_record($cat);
	
			 		// Description
			 		$description_nl = preg_replace("/\r/",'',sanitize($media['description']));
					$description_breaks = preg_replace("/\n/",'<br>',$description_nl);
					$description = $mcclass->wrapText($description_breaks, 55);
	
					//Get Username of Uploader
					$uresult = $db->query("SELECT username FROM __users WHERE user_id=" . $db->escape($media['user_id']) . ";");
  					$uname = $db->fetch_record($uresult);
				

		
					$tooltip = $html->HTMLTooltip('<table cellpadding=\\\'0\\\' border=\\\'0\\\' class=\\\'borderless\\\' width=\\\'300px\\\'><tr><td align=\\\'center\\\'><b> <span style=\\\'font-size:13px;\\\'> ' . sanitize ($media['name']) . '</span> </b><br /><br /><table class=\\\'borderless\\\' width=\\\'290px\\\'><tr><td width=\\\'110\\\' valign=\\\'top\\\' align=\\\'left\\\'>'.$user->lang['mc_category'].':</td><td align=\\\'left\\\'> '.$cat_name['category_name'].'</td></tr><tr><td valign=\\\'top\\\' align=\\\'left\\\'>'.$user->lang['mc_description'].':</td><td align=\\\'left\\\'> '.$description.'</td></tr><tr><td colspan=\\\'2\\\'>&nbsp;</td></tr><tr><td align=\\\'left\\\'>'.$user->lang['mc_uploader'].':</td><td align=\\\'left\\\'>'.$uname['username'].'</td></tr><tr><td align=\\\'left\\\'>'. $user->lang['mc_uploaded'] .':</td><td align=\\\'left\\\'>'.$media['date'].'</td></tr><tr><td colspan=\\\'2\\\'>&nbsp;</td></tr><tr><td align=\\\'left\\\'>'. $user->lang['mc_views'] .':</td><td align=\\\'left\\\'>'.$media['views'].'x</td></tr><tr><td align=\\\'left\\\'>'.$user->lang['mc_duration'].':</td><td align=\\\'left\\\'>'.date("i:s", $media['duration']).'</td></tr><tr><td align=\\\'left\\\'>'.$user->lang['mc_rating'].':</td><td align=\\\'left\\\'><img src=\''.$eqdkp_root_path.'plugins/mediacenter/images/rating_'.$media['rating'].'.png\'></td></tr></table>', 'mc_tt_help','','100');
				} 
				else {
					$tooltip = "";
				};
		
		
		
		$output .= '<table width="100%" border="0" cellspacing="0" cellpadding="2" class="noborder">';
			
				$output .= '<tr class="row1" width="100%"><td align="center">';
				$output .= '<a href="'.$eqdkp_root_path.'plugins/mediacenter/file.php'.$SID.'&id='.sanitize($media['id']).'" '.$tooltip.'><img src="'.$mcclass->show_thumbnail($media['thumbnail'], $media['type'], $media['extension']).'" class="reflect rheight20" width="120">';
				$output .= '</td ></tr>';

		$output .= '</table>';
		
		
	
	} else {
				$output = '<table width="100%" border="0" cellspacing="0" cellpadding="2" class="noborder">';
			
				$output .= '<tr class="row1"></td>';
				$output .= $user->lang['pm_votm_novideos'];
				$output .= '<td width="100%"></tr>';

			$output .= '</table>';
	}
		


	
	
    // return the output for module manager
		return $output;
  }
}
?>

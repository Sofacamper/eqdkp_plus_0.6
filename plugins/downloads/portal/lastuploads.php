<?PHP
/*************************************************\
*             Downloads 4 EQdkp plus              *
* ----------------------------------------------- *
* Project Start: 05/2009                          *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 0.1.0                                 *
* ----------------------------------------------- *
* Based on EQdkp-Plus Gallery by Badtwin & Lunary *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/


if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}


// You have to define the Module Information
$portal_module['lastuploads'] = array(        			    				// the same name as the folder!
			'name'			   	=> 'Last Uploads',   						// The name to show
			'path'			   	=> 'lastuploads',                 			// Folder name again
			'version'		   	=> '0.1.0',            						// Version
			'author'			=> 'GodMod',             					// Author
			'contact'		   	=> 'http://eqdkp-plus.com/forum',   		// email adress
			'description'  		=> 'Displays the latest uploaded Files',  	// Detailed Description
			'positions'    		=> array('left1', 'left2', 'right'), 		// Which blocks should be usable? left1 (over menu), left2 (under menu), right, middle
      		'signedin'     => '0',								            // 0 = all users, 1 = signed in only
      		
			'install'      => array(
				'autoenable'        => '1',      // 0 = disable on install , 1 = enable on install
				'defaultposition'   => 'left1',  // see blocks above
				'defaultnumber'     => '6',      // default ordering number
			),
);



//Portalmodule-Settings
$portal_settings['lastuploads'] = array(
		'pm_lu_maxlinks'	=> array(
		'name'  			=>	'pm_lu_maxlinks',
		'language'			=>	'pm_lu_maxlinks',
		'property'			=>	'text',
		'size'	   			=>	'3',
		),
		
		'pm_lu_tooltip'		=> array(
		'name'  			=>	'pm_lu_tooltip',
		'language'			=>	'pm_lu_tooltip',
		'property'			=>	'checkbox',
		),
	
);


if(!function_exists(lastuploads_module)){
  function lastuploads_module(){
  
  global $eqdkp, $user, $tpl, $db, $plang, $conf_plus, $eqdkp_root_path, $SID, $html, $pdc;
  //Cache: plugin.downloads.portalmodul.lastuploads.{USERID}.{EQDKP_ROOT_PATH}
  $output = $pdc->get('plugin.downloads.portalmodul.lastuploads.'.$user->data['user_id'].$eqdkp_root_path,false,true);
	
	if (!$output)
	{
  		
		// Load the Config
		$sql = 'SELECT * FROM __downloads_config';
		$settings_result = $db->query($sql);
		while($roww = $db->fetch_record($settings_result)){
			$conf[$roww['config_name']] = $roww['config_value'];
		}
		$db->free_result($settings_result);
	
		//Include common download-functions
		include_once($eqdkp_root_path . 'plugins/downloads/include/downloads.class.php');
		$dlclass = new DownloadsClass();
	
		$limit = ($conf_plus['pm_lu_maxlinks']) ? $conf_plus['pm_lu_maxlinks'] : 5;

		
		if ($user->check_auth('u_downloads_view', false) == false){
			
			$output = '<table width="100%" border="0" cellspacing="0" cellpadding="2" class="noborder">';
			
				$output .= '<tr class="row1"><td width="100%">';
				$output .= $user->lang['pm_lu_nolinks'];
				$output .= '</td ></tr>';

			$output .= '</table>';
		}
		else {

			$sql = $db->query("SELECT * FROM __downloads_config WHERE config_name = 'disable_categories'");
			$cats_disabled = $db->fetch_record($sql);
			$cats_disabled = ($cats_disabled['config_value'] == 1) ? true : false;
			//If Categorie are disabled
			if ($cats_disabled == true){
			
				//If user has no permission to see the downloads with permission = 1
				if ( $user->data['user_id'] == ANONYMOUS ){
					
					$links = $db->query("SELECT * FROM __downloads_links WHERE permission='1' ORDER BY date DESC LIMIT ".$db->escape($limit));
				
				} else{
					
					$links = $db->query("SELECT * FROM __downloads_links ORDER BY date DESC LIMIT ".$db->escape($limit));
				};

			//Else they are enabled
			} else {
			
				//If user has no permission to see the downloads with permission = 1
				if ( $user->data['user_id'] == ANONYMOUS ){
					
					$links = $db->query("SELECT * FROM __downloads_links, __downloads_categories WHERE __downloads_links.permission=1 AND __downloads_links.category = __downloads_categories.category_id AND __downloads_categories.category_permission=1 ORDER BY date DESC LIMIT ".$db->escape($limit));
				
				} else{
					
					$links = $db->query("SELECT * FROM __downloads_links ORDER BY date DESC LIMIT ".$db->escape($limit));
				};

			
			};

			$output = '<style>.dl_tt_help{ width: 300px; min-height:100px; background:#fff url('.$eqdkp_root_path.'plugins/downloads/images/bg_info.jpg) bottom right no-repeat; border:1px solid #4f6d81; color: black;
} .dl_tt_help td{
	color: black;
	font-family: Verdana, Tahoma, Arial;
	font-size: 11px;
}</style>';
			$output .= '<table width="100%" border="0" cellspacing="0" cellpadding="2" class="noborder">';
			$dlcount = 0;
			while ($row = $db->fetch_record($links)){
				
				if ($conf_plus['pm_lu_tooltip'] == 1){
					
					// Get Category Name
					$sql = "SELECT category_name FROM __downloads_categories WHERE category_id='" . $db->escape($row['category']) . "';";
					$cat = $db->query($sql);
					$cat_name = $db->fetch_record($cat);
	
			 		// Description
			 		$description_nl = preg_replace("/\r/",'',sanitize($row['description']));
					$description_breaks = preg_replace("/\n/",'<br>',$description_nl);
					$description = $dlclass->wrapText($description_breaks, 55);
	
					//Get Username of Uploader
					$uresult = $db->query("SELECT username FROM __users WHERE user_id=" . $db->escape($row['user_id']) . ";");
  					$uname = $db->fetch_record($uresult);
				
					if ($row['file_size'] != "" && $row['file_size'] != "0"){$filesize = $dlclass->human_size($row['file_size']);} else {$filesize="";};
		
					$download_tt = $html->HTMLTooltip('<table cellpadding=\\\'0\\\' border=\\\'0\\\' class=\\\'borderless\\\' width=\\\'300px\\\'><tr><td align=\\\'center\\\'><b> <span style=\\\'font-size:13px;\\\'> ' . sanitize ($row['name']) . '</span> </b><br /><br /><table class=\\\'borderless\\\' width=\\\'300px\\\'><tr><td width=\\\'110\\\' valign=\\\'top\\\' align=\\\'left\\\'>'.$user->lang['dl_cat_headline'].':</td><td align=\\\'left\\\'> '.$cat_name['category_name'].'</td></tr><tr><td valign=\\\'top\\\' align=\\\'left\\\'>'.$user->lang['dl_desc_headline'].':</td><td align=\\\'left\\\'> '.$description.'</td></tr><tr><td colspan=\\\'2\\\'>&nbsp;</td></tr><tr><td align=\\\'left\\\'>'.$user->lang['dl_l_uploader'].':</td><td align=\\\'left\\\'>'.$uname['username'].'</td></tr><tr><td align=\\\'left\\\'>'. $user->lang['dl_l_upload_date'] .':</td><td align=\\\'left\\\'>'.$row['date'].'</td></tr><tr><td colspan=\\\'2\\\'>&nbsp;</td></tr><tr><td align=\\\'left\\\'>'. $user->lang['dl_l_views'] .':</td><td align=\\\'left\\\'>'.$row['views'].'x</td></tr><tr><td align=\\\'left\\\'>'.$user->lang['dl_filesize_headline'].':</td><td align=\\\'left\\\'>'.$filesize.'</td></tr><tr><td align=\\\'left\\\'>'.$user->lang['dl_rating_headline'].':</td><td align=\\\'left\\\'><img src=\\\''.$eqdkp_root_path.'plugins/downloads/images/rating_'.$row['rating'].'.png\\\'></td></tr></table>', 'dl_tt_help','','100');
				} 
				else {
					$download_tt = "";
				};
				
				//Create the Output
				$class = $eqdkp->switch_row_class();
				$output .= '<tr class="'.$class.'" onmouseover="this.className=\'rowHover\';" onmouseout="this.className=\''.$class.'\';"><td>';
				$output .= $dlclass->extension_image($row['file_type']);
				$output .= '</td><td width="100%"><a href="'.$eqdkp_root_path.'plugins/downloads/downloads.php'.$SID.'&file='.$row['id'].'" '.$download_tt.'>'.sanitize($row['name']). '</td></tr>';
				$dlcount = $dlcount + 1;
			};
		
			if ($dlcount == 0){$output .= '<tr><td class="row1">'.$user->lang['pm_lu_nolinks'].'</td></tr>';}
			$output .= '</table>';
		
		} 
		$pdc->put('plugin.downloads.portalmodul.lastuploads.'.$user->data['user_id'].$eqdkp_root_path,$output,86400,false,true);
		return $output;
	} //END out-if
  
  return $output;
  
  } //END function
}
?>
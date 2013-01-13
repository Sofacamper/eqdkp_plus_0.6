<?PHP
/*
* Project:     EQdkp-Plus
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
* ----------------------------------------------------------------------------------
* Gallery 4 EQdkp plus
* ---------------------------------------------------------------------------------- 
* Project Start: 09/2008
* Author: BadTwin
* Copyright: Andreas (BadTwin) Schrottenbaum
* Link: http:// bloody-midnight.eu
* Version: 2.0.11
* ----------------------------------------------------------------------------------
* Based on the HelloWorld Plugin by Wallenium
*
* $Id: $
*/

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}


// You have to define the Module Information
$portal_module['picofthemoment'] = array(        			    // the same name as the folder!
			'name'			   => 'Pic of the Moment',   						// The name to show
			'path'			   => 'picofthemoment',                 // Folder name again
			'version'		   => '1.0.0',            							// Version
			'author'       => 'BadTwin',             						// Author
			'contact'		   => 'eqdkp-gallery@guensel.net',   		// email adress
			'description'  => 'Display a random Picture from your Gallery',     			// Detailed Description
			'positions'    => array('left1', 'left2', 'right'), // Which blocks should be usable? left1 (over menu), left2 (under menu), right, middle
      'signedin'     => '0',								              // 0 = all users, 1 = signed in only
      'install'      => array(
			                   'autoenable'        => '0',      // 0 = disable on install , 1 = enable on install
			                   'defaultposition'   => 'left1',  // see blocks above
			                   'defaultnumber'     => '2',      // default ordering number
			                   ),
    );


//Generate the Category List


$options_tracker = array(
		'' => $user->lang['gl_ad_selall'],
	);
	
$sql = 'SELECT * FROM __gallery_categories';
$result = $db->query($sql);
while ($catlist = $db->fetch_record($result)){
	$id = $catlist['category_id'];
	$options_tracker[$id] =	$catlist['category_name'];
}

// Settings
$portal_settings['picofthemoment'] = array(
	'pm_potm_maxwidth'	=> array(
		'name'  		=>	'pm_potm_maxwidth',
		'language'	=>	'pm_potm_maxwidth',
		'property'	=>	'text',
		'size'	   	=>	'3',
	),
	
	'pm_potm_maxheight'	=> array(
		'name'  		=>	'pm_potm_maxheight',
		'language'	=>	'pm_potm_maxheight',
		'property'	=>	'text',
		'size'	   	=>	'3',
	),
	
	'pm_potm_catshow' => array(
		'name'  		=>	'pm_potm_catshow',
		'language'	=>	'pm_potm_catshow',
		'property'	=>	'dropdown',
		'options' 	=>	$options_tracker,
	),
	
  'pm_potm_tooltip' => array(
		'name'  		=>	'pm_potm_tooltip',
		'language'	=>	'pm_potm_tooltip',
		'property'	=>	'checkbox',
	),

);


			
// The output function
// the name MUST be FOLDERNAME_module, if not an error will occur
if(!function_exists(picofthemoment_module)){
  function picofthemoment_module(){
  	global $eqdkp , $db , $eqdkp_root_path , $user , $tpl, $db, $plang, $conf_plus, $comments, $dbname, $pcache;
	
	include ($eqdkp_root_path.'config.php');

  	// Set the output: If custom one is entered in the setting output this one
  	// $conf_plus for config values, $plang for language values
	if ($conf_plus['pm_potm_catshow'] == ''){
		$sql= "SELECT * FROM __gallery_pictures ORDER BY RAND() LIMIT 1";
	} else {
		$sql= "SELECT * FROM __gallery_pictures WHERE category = ".$conf_plus['pm_potm_catshow']." ORDER BY RAND() LIMIT 1";
	}
	$picture = $db->fetch_record($pic_out = $db->query($sql));
		
  //START Check if Pics are in DB
    if ($picture != '') {
    // Get Category Name
	$sql = "SELECT category_name FROM __gallery_categories WHERE category_id='" . $picture['category'] . "';";
	$cat = $db->query($sql);
	$cat_name = $db->fetch_record($cat);
	
	 // Commment
	$comment = htmlentities($picture['comment'], ENT_QUOTES);
	$comment_nl = preg_replace("/\r/",'',$comment);
	$comment_breaks = preg_replace("/\n/",'<br>',$comment_nl);
	
	// Image Size
	$picsize = getimagesize($pcache->FilePath('upload/'.$picture['filename'], 'gallery'));
	$pic_width = $picsize[0];
	$pic_height = $picsize[1];
	
	// Check Size
	if ($conf_plus['pm_potm_maxheight'] <= 100){ $conf_plus['pm_potm_maxheight'] = 100; }
	if ($conf_plus['pm_potm_maxheight'] >= 200){ $conf_plus['pm_potm_maxheight'] = 200; }
	if ($conf_plus['pm_potm_maxwidth'] <= 100){ $conf_plus['pm_potm_maxwidth'] = 100; }	
	if ($conf_plus['pm_potm_maxwidth'] >= 200){ $conf_plus['pm_potm_maxwidth'] = 200; }
	
	// Get Size of the Frame
	$div_width = $conf_plus['pm_potm_maxwidth'] + 24;
	
  // read out the Username
  if ($picture['user_id'] == '-1') {
    $uname['username'] = 'Anonymous';
  } else {
    $uresult = $db->query("SELECT username FROM __users WHERE user_id=" . $picture['user_id'] . ";");
  	$uname = $db->fetch_record($uresult);
  }	

  // Picture Description
  if ($picture['description'] == ''){
	 $title = substr($picture['filename'], 0, -strlen($picture['id'])-5);
  } else {
	 $title = $picture['description'];
  }
	
	// Tooltip
	if ($conf_plus['pm_potm_tooltip'] != '1') {
    $picture_tt = '';
  } else {
  	$picture_tt = 'onmouseover="return overlib(\'<div class=\\\'pk_tt_help\\\' style=\\\'display:block; width: 270px;\\\'><div class=\\\'pktooldiv\\\'><table cellpadding=\\\'0\\\' border=\\\'0\\\' class=\\\'borderless\\\'><tr><td align=\\\'center\\\'><b> <span style=\\\'font-size:13px;\\\'> ' . htmlentities  ($title) . '</span> </b> <br />(' . $picture['filename'] . ') <br /><br /><table class=\\\'borderless\\\' width=\\\'100%\\\'><tr><td width=\\\'110\\\' valign=\\\'top\\\' align=\\\'left\\\'>'.$user->lang['gl_up_cat'].'</td><td align=\\\'left\\\'> '.htmlentities(strip_tags($cat_name['category_name']), ENT_QUOTES).'</td></tr><tr><td valign=\\\'top\\\' align=\\\'left\\\'>'.htmlentities(strip_tags($user->lang['gl_up_description']), ENT_QUOTES).'</td><td align=\\\'left\\\'> '.$comment_breaks.'</td></tr><tr><td colspan=\\\'2\\\'>&nbsp;</td></tr><tr><td align=\\\'left\\\'>'.$user->lang['gl_up_from'].'</td><td align=\\\'left\\\'>'.$uname['username'].'</td></tr><tr><td align=\\\'left\\\'>'. $user->lang['gl_up_date'] .'</td><td align=\\\'left\\\'>'.$picture['date'].'</td></tr><tr><td colspan=\\\'2\\\'>&nbsp;</td></tr><tr><td align=\\\'left\\\'>'. $user->lang['gl_index_hits'] .'</td><td align=\\\'left\\\'>'.$picture['views'].'x</td></tr><tr><td align=\\\'left\\\'>'.$user->lang['gl_pic_resolution'].'</td><td align=\\\'left\\\'>'.$pic_width.' x '.$pic_height.'</td></tr></table></td></tr></table></div></div>\', MOUSEOFF, HAUTO, VAUTO,  FULLHTML, WRAP);" onmouseout="return nd();"\'';
  }
  // END Check  Pics are in DB
	}
  	// Start the Output
  	if ($picture['filename'] == '') {
      $output = '<div align="center" >
      <div style="width: '.$div_width.'px; text-align: center; border: 1px solid #999999; margin-top: 2px; margin-bottom: 2px; background: transparent url('.$eqdkp_root_path.'plugins/gallery/images/highlighting.png);"><table height="100%" width="100%" class="borderless" cellpadding="0" cellspacing="0"><tr><td style="text-align: center; vertical align: middle;"><img src="'.$eqdkp_root_path.'plugins/gallery/images/nopic.png'.$picture['filename'].'" style="padding: 10px; max-height: '.$conf_plus['pm_potm_maxheight'].'px; max-width: '.$conf_plus['pm_potm_maxwidth'].'px;"></td></tr></table></div></div>
      ';
    } else {
      $output = '<div align="center" >
      <div style="text-align: center; border: 1px solid #999999; margin-top: 2px; margin-bottom: 2px; background: transparent url('.$eqdkp_root_path.'plugins/gallery/images/highlighting.png);"><table height="100%" width="100%" class="borderless" cellpadding="0" cellspacing="0"><tr><td style="text-align: center; vertical align: middle;"><a href="'.$eqdkp_root_path.'plugins/gallery/picview.php?pid='.$picture['id'].'" '. $picture_tt . '><img src="'.$pcache->FilePath('upload/thumb/'.$picture['filename'], 'gallery').'" style="padding: 10px; max-height: '.$conf_plus['pm_potm_maxheight'].'px; max-width: '.$conf_plus['pm_potm_maxwidth'].'px;"></a></td></tr></table></div></div>
      ';
		}
    // return the output for module manager
		return $output;
  }
}
?>

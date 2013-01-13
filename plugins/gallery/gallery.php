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


// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('PLUGIN', 'gallery');   // Must be set!
$eqdkp_root_path = './../../';    // Must be set!
include_once($eqdkp_root_path . 'common.php');  // Must be set!
include_once('include/libloader.inc.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'gallery')) { message_die('The Gallery plugin is not installed.'); }

// Check if GDLib2 is installed
if (function_exists('ImageCopyResampled')) {
	
// Check if a Category is created
$catcheck_query = $db->query("SELECT * FROM __gallery_categories");
$catcheck = $db->fetch_record($catcheck_query);
if ($catcheck['category_id'] != ''){

// Check user permission
$user->check_auth('u_gallery_view');

// List Categories
$result = $db->query("SELECT * FROM __gallery_categories ORDER BY category_id");

// Check the Config Settings
$cat_rows_query = $db->query("SELECT * FROM __gallery_config WHERE config_name = 'categories'");
$cat_rows = $db->fetch_record($cat_rows_query);

	// Paging
	$persite = $cat_rows['config_value'];
	$linkcount = 3;

	$pagecounter_sql = "SELECT * FROM __gallery_categories ORDER BY category_id";
	$pagecounter_result = $db->query($pagecounter_sql);
	$total = $db->num_rows($pagecounter_result);
	
	if (!isset($_REQUEST['site'])){
		$site = 1;
	} else {
		$site = $_REQUEST['site'];
	}
	if ($site > $total) $site = $total;
	
	$sitecount = ceil($total/$persite);
	if ($linkcount % 2 == 0) $linkcount ++;
	$numericlinks = ($linkcount -1) / 2;
	$url = $_SERVER['PHP_SELF'];
	
	$pagecounter_sql .= " limit " . ($site * $persite - $persite) . ", ".$persite;
	$pagecounter_result = $db->query($pagecounter_sql);
	
	$spacing_front = '';
	$spacing_tail = '';
	$start_link = '';
	$end_link = '';
	$prev_link = '';
	$next_link = '';
	$visible_links = '';

	$cspacing_front = '';
	$cspacing_tail = '';
	$cstart_link = '';
	$cend_link = '';
	$cprev_link = '';
	$cnext_link = '';
	$cvisible_links = '';
	
	$nr = $site - $numericlinks;

	while ($nr <= $sitecount){
		if ($nr < 1){$nr++; continue;}
		elseif ($nr > $site + $numericlinks){break;}
		
		if ($nr == $site){
			$cvisible_links .= ' <b>['.(($nr * $persite)-$persite+1) . '-' . $nr * $persite . ']</b>';
			$visible_links .= " <b>$nr</b>";
		} else {
			$cvisible_links .= ' <a href="'.$url.'?site='.$nr.'">['.(($nr * $persite)-$persite+1) . '-' . $nr * $persite . ']</a>';
			$visible_links .= ' <a href="'.$url.'?site='.$nr.'">'.$nr.'</a>';
		}
		
		$nr++;
	}

	if ($site > 1){
		$start_link = ' <a href="'.$url.'?site=1"><b>&lt;&lt;</b></a>';
		if ($site -1 > 1){
			$prev_link = ' <a href="'.$url.'?&site='.($site-1).'"><b> &lt;</b></a>';
		}
	}
	
	if ($site < $sitecount){
		$end_link = ' <a href="'.$url.'?site='.$sitecount.'"><b>&gt;&gt;</b></a>';
		if ($site + 1 < $sitecount){
			$next_link = '<a href="'.$url.'?site='.($site+1).'"><b>&gt; </b></a>';
		}
	}
	
	if ($site - $numericlinks > 1){
		$spacing_front = ' ...';
	}
	if ($site + $numericlinks < $sitecount){
		$spacing_tail = ' ...';
	}

	$nav_left = $start_link;
	$nav_left .= $prev_link;
	$nav = $spacing_front;
	$nav .= $visible_links;
	$nav .= $spacing_tail;
	$nav_right = $next_link;
	$nav_right .= $end_link;

	$cnav = $spacing_front;
	$cnav .= $cvisible_links;
	$cnav .= $spacing_tail;


	// Paging - END
	

	while ($row = $db->fetch_record($pagecounter_result)){
		$picresult = $db->query("SELECT * FROM __gallery_pictures WHERE category = '".$row['category_id']."' ORDER BY RAND() LIMIT 4");
		
		$firstpic_result = $db->query("SELECT * FROM __gallery_pictures WHERE category = '".$row['category_id']."';");
		$firstpic = $db->fetch_record($firstpic_result);
		
		$randompic1 = $db->fetch_record($picresult);
		if ($randompic1['filename'] == ''){
			if($user->check_auth('u_gallery_upload', false)){
				$catpic1 = $eqdkp_root_path.'plugins/gallery/images/nopic.png';
				$catsite1 = 'upload.php?category='.$row['category_id'];
			} else {
				$catpic1 = $eqdkp_root_path.'plugins/gallery/images/nopic.png';
				$catsite1 = '';
			}
		} else {
				$catpic1 = $pcache->FilePath('upload/thumb/'.$randompic1['filename'], 'gallery');
				$catsite1 = 'picview.php?pid='.$randompic1['id'];
		}
		
		$randompic2 = $db->fetch_record($picresult);
		if ($randompic2['filename'] == ''){
			if($user->check_auth('u_gallery_upload', false)){
				$catpic2 = $eqdkp_root_path.'plugins/gallery/images/nopic.png';
				$catsite2 = 'upload.php?category='.$row['category_id'];
			} else {
				$catpic2 = $eqdkp_root_path.'plugins/gallery/images/nopic.png';
				$catsite2 = '';
			}
		} else {
			$catpic2 = $pcache->FilePath('upload/thumb/'.$randompic2['filename'], 'gallery');
			$catsite2 = 'picview.php?pid='.$randompic2['id'];
		}
		
		$randompic3 = $db->fetch_record($picresult);
		if ($randompic3['filename'] == ''){
			if($user->check_auth('u_gallery_upload', false)){
				$catpic3 = $eqdkp_root_path.'plugins/gallery/images/nopic.png';
				$catsite3 = 'upload.php?category='.$row['category_id'];
			} else {
				$catpic3 = $eqdkp_root_path.'plugins/gallery/images/nopic.png';
				$catsite3 = '';
			}
		} else {
			$catpic3 = $pcache->FilePath('upload/thumb/'.$randompic3['filename'], 'gallery');
			$catsite3 = 'picview.php?pid='.$randompic3['id'];
		}
		
		$randompic4 = $db->fetch_record($picresult);
		if ($randompic4['filename'] == ''){
			if($user->check_auth('u_gallery_upload', false)){
				$catpic4 = $eqdkp_root_path.'plugins/gallery/images/nopic.png';
				$catsite4 = 'upload.php?category='.$row['category_id'];
			} else {
				$catpic4 = $eqdkp_root_path.'plugins/gallery/images/nopic.png';
				$catsite4 = '';
			}
		} else {
			$catpic4 = $pcache->FilePath('upload/thumb/'.$randompic4['filename'], 'gallery');
			$catsite4 = 'picview.php?pid='.$randompic4['id'];
		}

		$countresult = $db->query("SELECT * FROM __gallery_pictures WHERE category = '".$row['category_id']."';");
		$piccount = 0;
		while ($countvar = $db->fetch_record($countresult)){
			$piccount = $piccount+1;
		}

		if ($firstpic['id'] == ""){ 
		$firstpic_link = $catsite1;
    } else {
    $firstpic_link = "picview.php?pid=".$firstpic['id']; 
    }

		$tpl->assign_block_vars('index_list', array(
			'GL_ROW_CLASS'	=> $eqdkp->switch_row_class(),
			'GL_IMAGE1'	=> $catpic1,
			'GL_IMG_SITE1'	=> $catsite1,
			'GL_IMAGE2'	=> $catpic2,
			'GL_IMG_SITE2'	=> $catsite2,
			'GL_IMAGE3'	=> $catpic3,
			'GL_IMG_SITE3'	=> $catsite3,
			'GL_IMAGE4'	=> $catpic4,
			'GL_IMG_SITE4'	=> $catsite4,
			'GL_FIRSTPIC'	=> $firstpic_link,
 			'GL_CAT_NAME'	=> $row['category_name'],
			'GL_CAT_COMMENT'	=>	$row['category_comment'],
			'GL_CAT_PICCOUNT'	=> $piccount,

		));
	}

  //Create the Upload Button & My Pics Link
	if ($user->check_auth('u_gallery_upload', false)){
		$upload_button='<td style="text-align: center; width: 33%"><a href="mypics.php">My Pics</a></td><td style="text-align: right; width: 33%">
					      <form action="upload.php" method="GET">
							<input type="submit" value="'.$user->lang['gl_index_upload'].'" class="mainoption">
						  </form></td>';
	}
	  
// Get the Version
$vers_query = $db->query("SELECT * FROM __gallery_config WHERE config_name='inst_version'");
$vers = $db->fetch_record($vers_query);

// Send the Output to the template Files.

$tpl->assign_vars(array(
	'GL_HEADLINE'       => $user->lang['gl_index_headline'],
	'GL_UPLOAD'         => $upload_button,
	'GL_NAV_LEFT'       => $nav_left,
	'GL_NAV'            => $nav,
	'GL_NAV_RIGHT'      => $nav_right,
	'GL_CNAV'           => $cnav,
	'GL_CATEGORY'       => $user->lang['gl_up_category'],
  'GL_COMMENT'        => $user->lang['gl_up_comment'],
  'GL_CAT_PIC_COUNT'  => $user->lang['gl_cat_picture_count'],	
  'GL_CAT_NOTE'       => $user->lang['gl_cat_note'],
	'GL_ABOUT_HEADER'   => $user->lang['gl_about_header'],
	'L_COPYRIGHT'            => 'EQdkp-Plus Gallery v'.$vers['config_value'].$user->lang['gl_created_devteam'],
	'L_CREDITS'   => $jquery->Dialog_URL('GLAbout', $user->lang['gl_about_header'], 'about.php', '400', '300'),
));

} else {
  $error = $user->lang['gl_no_cat_created'];
  $tpl->assign_vars(array(
    'GL_HEADLINE'     => $error,
  ));
}
}	else {
  $error = $user->lang['gl_no_gdlib2'];
  $tpl->assign_vars(array(
  'GL_HEADLINE'       => $error,
  ));
}
// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'           => $user->lang['gallery'],
	'template_path'        => $pm->get_data('gallery', 'template_path'),
	'template_file'        => 'overview.html',
	'display'              => true)
    );

?>
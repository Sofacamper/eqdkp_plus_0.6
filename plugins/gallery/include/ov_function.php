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

function paging($status = 'admin'){
	global $db, $table_prefix, $tpl, $user, $eqdkp_root_path, $pcomments, $dbname, $pcache, $in;

  // Check the config settings
  $mthumbs_query = $db->query("SELECT config_value FROM __gallery_config WHERE config_name = 'mthumbs'");
  $mthumbs_record = $db->fetch_record($mthumbs_query);
  $persite = $mthumbs_record['config_value'];
  $linkcount = 3;
  
  if (!isset($_GET['pid'])){
    $output = $user->lang['gl_ov_no_pics_in_cat'];
  } else {
    if (!isset ($_GET['cat_id']) OR $_GET['cat_id'] == 'all'){
      if ($status == 'mypics'){
        $paging_string = ('SELECT * FROM __gallery_pictures WHERE user_id='.$user->data['user_id'].' ORDER BY id');
        $selfcat = 'all';
      } else {
        $paging_string = ('SELECT * FROM __gallery_pictures ORDER BY id');
        $selfcat = 'all';
      }
    } else {
      if ($status == 'mypics') {
        $paging_string = ('SELECT * FROM __gallery_pictures WHERE category = ' . $in->get('cat_id', 0).' AND user_id='.$user->data['user_id'].' ORDER BY id');
        $selfcat = $_GET['cat_id'];    
      } else {
        $paging_string = ('SELECT * FROM __gallery_pictures WHERE category = ' . $in->get('cat_id', 0).' ORDER BY id');
        $selfcat = $_GET['cat_id'];
      }    
    }
    
    $total_query = $db->query($paging_string);
    $total = mysql_num_rows($total_query);
    
    if (!isset($_GET['site'])) {
    	$plink_query = $db->query($paging_sting);
    	$sitecounter = 0;
    	while ($plink_loop = $db->fetch_record($plink_query)) {
        if ($plink_loop['id'] <= $_GET['pid']){
     	    $sitecounter = $sitecounter +1;
     	  }
     }
      $site = ceil($sitecounter/$persite);
    } else {
      $site = $_GET['site'];
    }
  
    if ($site > $total) $site = $total;
    
    $sitecount = ceil($total/$persite);
    if ($linkcount % 2 == 0) $linkcount ++;
    $numericlinks = ($linkcount -1) / 2;
    $url = $_SERVER['PHP_SELF'];
  
  	$paging_string .= ' limit ' . ($site * $persite - $persite) . ", ".$persite;
    $pagecounter_query = $db->query($paging_string);

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
			$cvisible_links .= ' <a href="'.$url.'?pid='.$_GET['pid'].'&cat_id='.$selfcat.'&site='.$nr.'">['.(($nr * $persite)-$persite+1) . '-' . $nr * $persite . ']</a>';
			$visible_links .= ' <a href="'.$url.'?pid='.$_GET['pid'].'&cat_id='.$selfcat.'&site='.$nr.'">'.$nr.'</a>';
		}
		
		$nr++;
	}

	if ($site > 1){
		$start_link = ' <a href="'.$url.'?pid='.$_GET['pid'].'&cat_id='.$selfcat.'&site=1"><b>&lt;&lt;</b></a>';
		if ($site -1 > 1){
			$prev_link = ' <a href="'.$url.'?pid='.$_GET['pid'].'&cat_id='.$selfcat.'&site='.($site-1).'"><b> &lt;</b></a>';
		}
	}
	
	if ($site < $sitecount){
		$end_link = ' <a href="'.$url.'?pid='.$_GET['pid'].'&cat_id='.$selfcat.'&site='.$sitecount.'"><b>&gt;&gt;</b></a>';
		if ($site + 1 < $sitecount){
			$next_link = '<a href="'.$url.'?pid='.$_GET['pid'].'&cat_id='.$selfcat.'&site='.($site+1).'"><b>&gt; </b></a>';
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

	while ($resizer_output = mysql_fetch_assoc($pagecounter_query)){
		
		  if ($resizer_output['comment'] == ''){
		      $resizer_output['comment'] = $user->lang['gl_pic_descript_warn'];
	    }
			$thumb_comment = htmlentities($resizer_output['comment'], ENT_QUOTES);
			$thumb_comment_breaks = preg_replace("/\r/",'',$thumb_comment);
			$thumb_comment = preg_replace("/\n/",'<br>',$thumb_comment_breaks);
      
		// Get Image Size
		$picsize = getimagesize($pcache->FilePath('upload/'.$resizer_output['filename'], 'gallery'));
		$tpic_width = $picsize[0];
		$tpic_height = $picsize[1];

		// comment count
		$comm_settings = array('attach_id' => $resizer_output['id'], 'page'=>'gallery');
		$pcomments->SetVars($comm_settings);
		$commcount = $pcomments->Count();

		// Picture Description
    	if ($resizer_output['description'] == ''){
		   $resize_title = substr($resizer_output['filename'], 0, -strlen($resizer_output['id'])-5);
		  } else {
		   $resize_title = $resizer_output['description'];
	    }
     
		// read out the Username
    if ($resizer_output['user_id'] == '-1') {
    	$uname['username'] = 'Anonymous';
    } else {
  		$uresult = $db->query("SELECT username FROM __users WHERE user_id=" . $resizer_output['user_id'] . ";");
	   	$uname = mysql_fetch_assoc($uresult);
	  }

    // Get Category Name
    $cat = $db->query("SELECT category_name FROM __gallery_categories WHERE category_id='" . $resizer_output['category'] . "';");
    $cat_name = $db->fetch_record($cat);


		// Active Picture Highlighting
	    if ($resizer_output['id'] != $_GET['pid']){
/*			$show_lytebox = '<a href="'.$eqdkp_root_path.'/plugins/gallery/upload/'.$resizer_output['filename'].'" rel="lytebox['.$cat_name['category_name'].']" title="'.$resize_title.'&nbsp; &lt;span style=&quot;font-weight: normal;&quot;&gt;('.$tpic_width.' x '.$tpic_height.')&lt;/span&gt;&lt;br /&gt;&lt;span style=&quot;font-weight: normal;&quot;&gt;'.$thumb_comment_breaks.'&lt;/span&gt;"></a>'; */
			$highlighting = 'border: 1px solid #999999;';
			$div_id = 'id="mthumb"';
			// overlib
		    $picture_tt = 'onmouseover="return overlib(\'<div class=\\\'pk_tt_help\\\' style=\\\'display:block; width: 270px;\\\'><div class=\\\'pktooldiv\\\'><table cellpadding=\\\'0\\\' border=\\\'0\\\' class=\\\'borderless\\\'><tr><td align=\\\'center\\\'><b> <span style=\\\'font-size:13px;\\\'> ' . htmlentities ($resize_title, ENT_QUOTES) . '</span> </b><br /> (' . $resizer_output['filename'] . ') <br /><br /><table class=\\\'borderless\\\' width=\\\'100%\\\'><tr><td width=\\\'110\\\' valign=\\\'top\\\' align=\\\'left\\\'>'.$user->lang['gl_up_description'].'</td><td align=\\\'left\\\'> '.$thumb_comment.'</td></tr><tr><td colspan=\\\'2\\\'>&nbsp;</td><tr><td align=\\\'left\\\'>'.$user->lang['gl_up_from'].'</td><td align=\\\'left\\\'>'.$uname['username'].'</td></tr><tr><td align=\\\'left\\\'>'. $user->lang['gl_up_date'] .'</td><td align=\\\'left\\\'>'.$resizer_output['date'].'</td></tr><tr><td colspan=\\\'2\\\'>&nbsp;</td></tr><tr><td align=\\\'left\\\'>'. $user->lang['gl_index_hits'] .'</td><td align=\\\'left\\\'>'.$resizer_output['views'].'x</td></tr><tr><td align=\\\'left\\\'>'. $user->lang['gl_pic_comm_head'].':</td><td align=\\\'left\\\'>'.$commcount.'</td></tr><tr><td align=\\\'left\\\'>'.$user->lang['gl_pic_resolution'].'</td><td align=\\\'left\\\'>'.$tpic_width.' x '.$tpic_height.'</td></tr></table></td></tr></table></div></div>\', MOUSEOFF, HAUTO, VAUTO,  FULLHTML, WRAP);" onmouseout="return nd();"\'';
		    $view_link = '<a href="'.$url.'?pid='.$resizer_output['id'].'&cat_id='.$selfcat.'" '. $picture_tt . '>';
	    	$view_link_close = '</a>';
		} else {
/*			$show_lytebox = '<a href="'.$eqdkp_root_path.'/plugins/gallery/upload/'.$resizer_output['filename'].'"></a>'; */
			$highlighting = ' border: 1px solid #CCCCCC; background: transparent url('.$eqdkp_root_path.'plugins/gallery/images/highlighting.png);';
			$div_id = '';
			$picture_tt = '';
			$view_link = '';
			$view_link_close = '';
		}
    if ($status == 'user'){
      $output .= $show_lytebox.'<div '.$div_id.'  style="float:left; height: 120px; width: 120px; text-align: center; margin: 2px;'.$highlighting.'"><table height="100%" width="100%" class="borderless" cellpadding="0" cellspacing="0"><tr><td style="text-align: center; vertical align: middle;">'.$view_link.'<img src="'.$pcache->FilePath('upload/mthumb/'.$resizer_output['filename'], 'gallery').'" style="padding: 10px;">'.$view_link_close.'</td></tr></table></div>';
    } else {	
  		$output .= $show_lytebox.'<div '.$div_id.'  style="float:left; height: 100px; width: 100px; text-align: center; margin: 2px;'.$highlighting.'"><table height="100%" width="100%" class="borderless" cellpadding="0" cellspacing="0"><tr><td style="text-align: center; vertical align: middle;">'.$view_link.'<img src="'.$pcache->FilePath('upload/mthumb/'.$resizer_output['filename'], 'gallery').'" style="padding: 10px; max-height: 80px; max-width: 80px;">'.$view_link_close.'</td></tr></table></div>';
		}
	}

  }
  
  
  // Send the data to the template
  $tpl->assign_vars(array(
 	'GL_NAVIGATION_LEFT'          => $nav_left,
	'GL_NAVIGATION_CENTER'        => $nav,
	'GL_NAVIGATION_RIGHT'         => $nav_right,
	'GL_RANGE_NAVIGATION_CENTER'	=> $cnav,
  ));

  return $output;
}


// The Category List in the right top Corner
function catselect($category, $mypics = false){
  global $table_prefix, $db, $user;
  $catlist = '<form><select class="input" name="cat_id" onchange="this.form.submit();">';
  $cat_query = $db->query('SELECT * FROM __gallery_categories');
  $catlist .= '<option value="all">'.$user->lang['ad_sel_all_cat'].'</option>';
  while ($cat = $db->fetch_record($cat_query)){
  if ($mypics == 'mypics'){
    $cat_check_query = $db->query("SELECT category FROM __gallery_pictures WHERE category = " . $cat['category_id']." AND user_id=".$user->data['user_id']);
  } else {
    $cat_check_query = $db->query("SELECT category FROM __gallery_pictures WHERE category = " . $cat['category_id']);
  }
    $cat_check = $db->num_rows($cat_check_query);
    if ($cat_check != 0){
      if ($cat['category_id'] == $category){
        $catlist .= '<option value="'.$cat['category_id'].'" selected="selected">'.$cat['category_name'].'</option>';
      } else {
        $catlist .= '<option value="'.$cat['category_id'].'">'.$cat['category_name'].'</option>';
      }
    }
  }
  $catlist .= '</select></form>';
  return $catlist;
}
?>

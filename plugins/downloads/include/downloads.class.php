<?php
/*************************************************\
*             Downloads 4 EQdkp plus              *
* ----------------------------------------------- *
* Project Start: 05/2009                          *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 0.0.1a                                 *
* ----------------------------------------------- *
* Based on EQdkp-Plus Gallery by Badtwin & Lunary *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}


if (!class_exists("DownloadsClass")){
  class DownloadsClass
  {
  
  function DownloadBBcode($text){
		global $pm, $eqdkp_root_path;
		

		$output ="";
		$test = preg_match_all('/\[file\](.*?)\[\/file\]/msi', $text, $matches);

		
		foreach ($matches[1] as $elem){
			$output .= $this->bbcode2html($elem);
		}
		
		$matches = array();
		$test = preg_match_all('/\[datei\](.*?)\[\/datei\]/msi', $text, $matches);

		
		foreach ($matches[1] as $elem){
			$output .= $this->bbcode2html($elem);
		}
		$text = preg_replace('/\[file\](.*?)\[\/file\]/msi', "", $text);
		$text = preg_replace('/\[datei\](.*?)\[\/datei\]/msi', "", $text);
		return $text.$output;
	
	}
  
  
  
  
  function bbcode2html($id){
	 global $pdc, $db, $user, $SID;
	 
	$id = sanitize($id);

	if (is_numeric($id)){
		//Get all information about the download
		//Cache: plugin.downloads.links.{ID}
		$link_cache = $pdc->get('plugin.downloads.links.'.$id,false,true);
		if (!$link_cache) {
			$download_query = "SELECT * from __downloads_links WHERE id = '".$db->escape($id)."'";
			$download_query = $db->query($download_query);
			$download = $db->fetch_record($download_query);
			$pdc->put('plugin.downloads.links.'.$id,$download,86400,false,true);
		} else{
			$download = $link_cache;
		};
		
		if ($download){
			$output = '<table width="100%" class="forumline" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <th colspan="2" style="text-align: left;"><b>'.$user->lang['dl_attachment'].': '.sanitize($download['name']).'</b></th>
  </tr>
  <tr class="row1">
    <td width="60" rowspan="4" nowrap style="text-align: left;"><a href="'.$eqdkp_root_path.'plugins/downloads/download.php'.$SID.'&id='.$id.'"><img src="'.$eqdkp_root_path.'plugins/downloads/images/download_big.png" title="'.sprintf($user->lang['dl_l_download_it'], sanitize($download['name'])).'"></a></td>
    <td style="text-align: left;"><a href="'.$eqdkp_root_path.'plugins/downloads/download.php'.$SID.'&id='.$id.'" title="'.sprintf($user->lang['dl_l_download_it'], sanitize($download['name'])).'">'.$this->extension_image($download['file_type']).sanitize($download['name']).'</a></td>
  </tr>
  <tr class="row1">
    <td style="text-align: left;">'.$this->wrapText(sanitize($download['description']), 80).'</td>
  </tr>
  
  <tr class="row1">
    <td style="text-align: left;">';
	$output .= ($download['file_size'] != "") ? $this->human_size(sanitize($download['file_size'])).'; ' : '';
	
	
	$output .= sprintf($user->lang['dl_times_downloaded'], sanitize($download['views']));
	
	$output .='</td>
  </tr>
  <tr class="row1">
    <td style="text-align: left;"><img src="'.$eqdkp_root_path.'plugins/downloads/images/rating_'.$download['rating'].'.png"></td>
  </tr>
</table>';

return $output;
			
		} else {
			return;
		}
		
	}
} //END function bbcode2html
	
	
  //Function for displaying an image to show the extension of a file
  function extension_image($extension){
	  global $eqdkp_root_path;
	  
	  $extension = sanitize($extension);

	  $extensions = array(
		"doc"		=> "doc.png",
		"docx"		=> "doc.png",
		"xls"		=> "xls.png",
		"xlsx"		=> "xls.png",
		"pps"		=> "ppt.png",
		"ppt"		=> "ppt.png",
		"txt"		=> "txt.png",
		"gif"		=> "jpg.png",
		"jpg"		=> "jpg.png",
		"png"		=> "jpg.png",
		"pdf"		=> "pdf.png",
		"wmv"		=> "wmv.png",
		"wma"		=> "wmv.png",
		"avi"		=> "wmv.png",
		"swf"		=> "swf.png",
		"rar"		=> "rar.png",
		"zip"		=> "zip.png",
		"gz"		=> "rar.png",
		
		"extern"	=> "external.png",
		);
	  
	  if ($extensions[$extension]){
		 $ext_image = '<img src="'.sanitize($eqdkp_root_path).'plugins/downloads/images/ext_images/'.$extensions[$extension].'" alt=".'.$extension.'" title=".'.$extension.'">';
		
	  } else {
			$ext_image = '<img src="'.sanitize($eqdkp_root_path).'plugins/downloads/images/ext_images/unknown.jpg">';

		}
		return $ext_image;
	  		
}
	
	
	// Check the first 256 bytes for forbidden content
	function check_content($fieldname){
		$disallowed = "body|head|html|img|plaintext|a href|pre|script|table|title|<?|php";
		$disallowed_content = explode('|', $disallowed);
		if (empty($disallowed_content))
		{
			return true;
		}

		$fp = @fopen($_FILES[$fieldname]['tmp_name'], 'rb');

		if ($fp !== false)
		{
			$ie_mime_relevant = fread($fp, 256);
			fclose($fp);
			foreach ($disallowed_content as $forbidden)
			{
				//if (stripos($ie_mime_relevant, '<' . $forbidden) !== false)
				if (stripos($ie_mime_relevant, $forbidden) !== false)
				{
					return false;
				}
			}
		}
		return true;
	}	
	
	
	function check_extension($allowed_extensions, $extension){
			
			$allowed_extensions = preg_split('/, */', strtolower($allowed_extensions));
			$extension_check = in_array(strtolower($extension), $allowed_extensions);
			if ($extension_check == true){
				$disallowed_extensions = array('php', 'php3', 'php4', 'php5', 'phtml', 'cgi', 'py', 'pyd', 'pyc', 'pyo');
				$disallowed_check = in_array(strtolower($extension), $disallowed_extensions);
				if ($disallowed_check == true){
					$extension_check = false;
				}
			};
			
			return $extension_check;
	}	


function check_filesize($fieldname, $max_filesize = ""){
	
	if ($max_filesize == ""){
		$max_filesize = @ini_get('upload_max_filesize');
		$unit = 'MB';
		if (!empty($max_filesize)){
			$unit = strtolower(substr($max_filesize, -1, 1));
			$max_filesize = (int) $max_filesize;

			$unit = ($unit == 'k') ? 'KB' : (($unit == 'g') ? 'GB' : 'MB');
			$max_filesize = $this->unhuman_size($max_filesize, $unit);
			
		}
	};

	$size = $_FILES[$fieldname]['size'];
	if ($size < $max_filesize) {
		return true;
	} else {
		return false;
	};
		

}

function check_uploadfolder_size($fieldname, $max_foldersize){
	global $db;
	if ($max_foldersize != ""){
		$query = $db->query("SELECT file_size FROM __downloads_links WHERE local_filename != NULL");
		$data = $db->fetch_record_set($query);
		$foldersize = 0;
		foreach ($data as $elem){
			$foldersize = $foldersize + $elem['file_size'];
		}
		
		$filesize = $_FILES[$fieldname]['size'];
		
		if (($foldersize + $filesize) < $max_foldersize) {
			return true;
		} else {
			return false;
		};
		
	} else {
		return true;
	}
	
	
	
}
	

//Function for uploading files
function upload_file($fieldname, $allowed_extensions, $debug=0, $hash_filename=true, $folder="files/", $max_filesize = "", $max_foldersize = ""){
	global $user, $pcache, $db;

	$user->check_auth('u_downloads_upload');
	
	$filename = $_FILES[$fieldname]['name'];
	
	if($filename != ""){
	
	
			// Transform special filname character
  			$filename = $this->trans_chars($filename);

			$file_type = substr($filename, strrpos($filename, '.') + 1);


			
			//Debug
			if ($debug == 1){

				if ($this->check_extension($allowed_extensions, $file_type) == false) {echo "<br>Status: Extension is not allowed";};
				if ($this->check_content($fieldname) == false) {echo "<br>Status: The File contains disallowed content";};
				if ($this->check_uploadfolder_size($fieldname, $max_foldersize) == false) {echo "<br>Error: The Folder-Sizelimit is reached (Limit is ".$this->human_size($max_foldersize).").";};
				if ($this->check_filesize($fieldname, $max_filesize) == false) {echo "<br>Error: The File-Size is too big. (File has ".$this->human_size($_FILES[$fieldname]['size']).", Limit is ".$this->human_size($max_filesize).")";};
			}
			
			//Check the file
			$extension_check = ($this->check_extension($allowed_extensions, $file_type) == true) ? true : false;
			$forbidden_content_check = ($this->check_content($fieldname) == true) ? true : false;
			$filesize_check = ($this->check_filesize($fieldname, $max_filesize) == true) ? true : false;
			$foldersize_check = ($this->check_uploadfolder_size($fieldname, $max_foldersize) == true) ? true : false;

			
			//If all checks are true
			if ($extension_check == true  && $forbidden_content_check == true && $filesize_check == true && $foldersize_check == true){
			
								
				if ($hash_filename == true){
					$file_hash = $this->generate_hash();
				} else {
					$last_id = $db->query_first("SELECT max(`id`) FROM __downloads_links") + 1;  
					$file_hash  = $last_id."_".$filename;
				}
	
				if (file_exists($pcache->FolderPath('downloads/'.$folder).$file_hash)) {	
					return 1;
				} 
	
				else {
					$pcache->FileMove($_FILES[$fieldname]['tmp_name'], $pcache->FolderPath('downloads/'.$folder).$file_hash);
					return $file_hash;
				}
			}
			else {
				return 2;
			}
	
	
	}
	else {
		return 0;
	}

	
}

function generate_hash(){
	$hash = rand().rand().rand().rand().rand();
	$hash = md5($hash);
	return $hash;
}
	
//Function for deleting a download
function delete_one_link($id){
	
	global $user, $db, $pcache, $lang, $pdc;
	
	$user->check_auth('a_downloads_links');

	//Cache: plugin.downloads.links.{ID}
	$link_cache = $pdc->get('plugin.downloads.links.'.$id,false,true);
	if (!$link_cache){
		$data_query = $db->query('SELECT * FROM __downloads_links WHERE id='.$db->escape((int)$id));
		$data = $db->fetch_record($data_query);
		$pdc->put('plugin.downloads.links.'.$id,$data,86400,false,true);
	} else{
		$data = $link_cache;
	};
	if ($data['local_filename']){
		if (file_exists($pcache->FilePath('files/'.$data['local_filename'], 'downloads'))) {
			unlink($pcache->FilePath('files/'.$data['local_filename'], 'downloads'));
		};
	};
	if ($data['preview_image']){
		if (file_exists($pcache->FilePath('thumbs/'.$data['preview_image'], 'downloads'))) {
			unlink($pcache->FilePath('thumbs/'.$data['preview_image'], 'downloads'));
		};
	};
	
	$del_query = $db->query("DELETE FROM __downloads_links WHERE id='" .$db->escape((int)$id)."'");
	
	//Delete Cache
	$pdc->del_suffix('plugin.downloads');
	
	return $error_msg = $user->lang['dl_ad_delete_success'];
	

}

// Transform special filname character
function trans_chars($text){
  			$text = preg_replace("/[^A-Za-z0-9_.]/", "", str_replace(array("ä","ö","ü","ß","Ä","Ö","Ü"),array("ae","oe","ue","ss","Ae","Oe","Ue"), $text));
return $text;
}



// Create the Category List
function category_list($category_id = 0){
	global $db, $pdc;
  	$catlist_query = $db->query('SELECT * FROM __downloads_categories');
	
	//Cache: plugin.downloads.categories
	$categories_cache = $pdc->get('plugin.downloads.categories',false,true);
	if (!$categories_cache){
		$catlist_query = $db->query('SELECT * FROM __downloads_categories');
		$new_catlist = $db->fetch_record_set($catlist_query);
		$pdc->put('plugin.downloads.categories',$data,86400,false,true);
	} else{
		$new_catlist = $categories_cache;
	};
	
	
 	$cat = '<select class="input" name="dl_category" onChange="dl_check_permission()">';
  	foreach ($new_catlist as $elem){
		$selected = ($elem['category_id'] == $category_id) ? ' selected = "selected "' : '';
        $cat .= '<option value="'.sanitize($elem['category_id']).'"'.$selected.'>'.sanitize($elem['category_name']).'</option>'; 
  	}
  	$cat .= '</select>';
	return $cat;
}


//Writes the file-size in a nicer way than in Bytes...
function human_size( $bytes, $decimal = '2', $return_zero = true) {

	if (($return_zero == false) && (($bytes == ""))){return "";};
	
   if( is_numeric( $bytes )) {
     $position = 0;
     $units = array( " Bytes", " KB", " MB", " GB", " TB" );
     while( $bytes >= 1024 && ( $bytes / 1024 ) >= 1 ) {
         $bytes /= 1024;
         $position++;
     }
     return round( $bytes, $decimal ) . $units[$position];
   }
   else {
     return "";
   }
}


//File-size-field und -unit for edit.php
function human_size2($fieldname, $string,  $return_zero = true) {

	$string = sanitize($this->human_size($string, 2, $return_zero));
	
	$string = split(" ", $string);

	switch ($string[1]){
		
		case "Bytes": $u1 = "selected=\"selected\""; break;
		case "KB": $u2 = "selected=\"selected\""; break;
		case "MB": $u3 = "selected=\"selected\""; break;
		case "GB": $u4 = "selected=\"selected\""; break;
		
	};
	$value = ($string[0] == "") ? "" : round($string[0],2);
	
	$output = '<input name="'.sanitize($fieldname).'" type="text" class="input" id="dl_url_filesize" size="3" value="'.sanitize($value).'">
        <select name="'.sanitize($fieldname).'_unit" class="input">
          <option value="Bytes"'.sanitize($u1).'>Bytes</option>
		  <option value="KB"'.sanitize($u2).'>KB</option>
          <option value="MB"'.sanitize($u3).'>MB</option>
          <option value="GB"'.sanitize($u4).'>GB</option>
        </select>';
	
	return $output;
}


//Just pure Bytes...
function unhuman_size($bytes, $unit, $return_zero = true){

	if (($return_zero == false) && ($bytes == "")){
		if ($bytes == "0"){return 0;} 
		else {return "";}
	};
	
	switch ($unit){
		case "Bytes" : return $bytes;
		case "KB" : return ($bytes*1024); break;
		case "MB" : return ($bytes*1024*1024); break;
		case "GB" : return ($bytes*1024*1024*1024); break;
		default: return "";

	}

}


// Build the CopyRight
  function Copyright(){
      global $pm, $user;
      $dl_version = $pm->get_data('downloads', 'version');
	  
      $dl_status  = (strtolower($pm->plugins['downloads']->vstatus) == 'stable' ) ? ' ' : ' '.$pm->plugins['downloads']->vstatus.' ';
	  $act_year = date("Y", time());
	  $dl_copyyear = ( $act_year == 2009) ? $act_year : '2009 - '.$act_year;
      return $user->lang['downloads'].' '.$dl_version.$dl_status.' &copy; '.$dl_copyyear.' by '.$pm->plugins['downloads']->copyright;
  }

function update_config($config_value, $config_name){
	global $db;
	
	$updatequery = $db->query("UPDATE __downloads_config SET config_value = '".$db->escape($config_value)."' WHERE config_name = '".$db->escape($config_name)."'");
}


function is_checked($value){
	
	return ( $value == 1 ) ? ' checked="checked"' : '';
}
  
function wrapText($text,$len=30)
 {
		$croplen = $len ;
		$ret = "";
		for($i=0;;$i++)
		{
			if ($i == strlen($text)) {
				break;
			}

			$ret .= $text[$i];
			if ($i >= $len)
			{
				if ($text[$i] == " ")
				{
					$ret .= str_replace(' ', '...', $text[$i] );
					break;
					$len = $len+$croplen;
				}

			}
		}
		return $ret ;
}
 
 
 function getDaysOfMonth($month,$year) {
  $schalt = $year % 4 == 0 && $year % 100 != 0;

  if($month <= 7) {
    if($month == 2) {
      return $schalt ? 29 : 28;
    }

    if($month % 2 == 0) {
      return 30;
    }

    return 31;
  }

  if($month % 2 == 0) {
    return 31;
  }
  return 30;
}

 
function getDayOfWeek($year, $weeknr, $day)
{
$offset = date('w', mktime(0,0,0,1,1,$year));
$offset = ($offset < 5) ? 1-$offset : 8-$offset;
$monday = mktime(0,0,0,1,1+$offset+$day,$year);

$test = strtotime('+' . ($weeknr - 1) . 'weeks', $monday);
return strftime("%Y-%m-%d", $test);
} 




function createGraph($a_daten)
	{	
	global $eqdkp_root_path, $user;
	include_once($eqdkp_root_path . 'pluskernel/include/GoogleGraph.class.php');
		$count_values = count($a_daten);
		
		$max = 0;
		$_a_daten = array();
		
		//Suche den Maxwert
		foreach ($a_daten as $value)
		{
			if($value > $max)
			{$max = $value;}
		}

		//Array umdrehen und Wert glätten auf max 97 da die
		//goddammedfuckshice google api im normalen modus nur 100 werte kann
		foreach ($a_daten as $value)
		{
			if ($max > 0)
			{
				$offset= $max / 99 ;
				if ($offset > 0)
				{
				$_a_daten[]= $value/ $offset ;
				}
			}
		}

		//Create Object
		$graph = new GoogleGraph();

		//Graph
		$graph->Graph->setType('line');
		$graph->Graph->setSubtype('chart');
		$graph->Graph->setSize(700, 300);

		$graph->Graph->setAxis(); //no arguments means all on
		
		$x_grid = (string)round((100/($count_values-1)),6);
		$x_grid = str_replace(",", ".", $x_grid);

		$graph->Graph->setGridLines($x_grid, round((100/$max),0), 1, 0);

		//Title
		#$graph->Graph->setTitle('DKP History', '#FF0000', 10);

		//Background
		$graph->Graph->addFill('chart', '#000000', 'solid');
		$graph->Graph->addFill('background', '#FFFFFF', 'gradient', '#000000', 90, 0.5, 0);

		//Axis Labels -> ToDO
		 # rechts nach links unten
		#$graph->Graph->addAxisLabel(array('','','')); # rechts nach links oben
		#$graph->Graph->addAxisLabel(array('', '')); # oben nach unten links
		#$graph->Graph->addAxisLabel(array('', '', '')); #oben nach unten rechts
		#$graph->Graph->addLabelPosition(array(1, 10, 37, 75));
		#$graph->Graph->addLabelPosition(array(2, 0, 1, 2, 4));
		$graph->Graph->setAxisRange('2,0,'.$max.'|1,1,'.$count_values.'|0,1,'.$count_values.'|3,0,'.$max);
		
		switch ($count_values){
			case "12" :
							$label_pos_array[] = 0;

							for ($i=1; $i<=12; $i++){
								$label_array[] = $user->lang['dl_month_short_'.$i];	
								$label_pos_array[] = $i;
							}

							$graph->Graph->addAxisLabel($label_array);
							$graph->Graph->addAxisLabel();
							$graph->Graph->addAxisLabel($label_array);
							$graph->Graph->addLabelPosition($label_pos_array);
							$label_pos_array2 = $label_pos_array;
							$label_pos_array2[0] = 1;
							$graph->Graph->addLabelPosition($label_pos_array2);
				break;
			
			case "7" :
						$label_pos_array[] = 0;

							for ($i=1; $i<=7; $i++){
								$label_array[] = $user->lang['dl_week_short_'.$i];	
								$label_pos_array[] = $i;
							}

							$graph->Graph->addAxisLabel($label_array);
							$graph->Graph->addAxisLabel();
							$graph->Graph->addAxisLabel($label_array);
							$graph->Graph->addLabelPosition($label_pos_array);
							$label_pos_array2 = $label_pos_array;
							$label_pos_array2[0] = 1;
							$graph->Graph->addLabelPosition($label_pos_array2);
				break;
		}
				
		
		#$graph->Graph->setAxisRange("0,0,3000|1,0,3000|2,0,3000|3,0,3000");
		#$graph->Graph->setAxisRange(array(1,0, 3000));
		#$graph->Graph->setAxisRange(array(2,0, 3000));
		#$graph->Graph->setAxisRange(array(3,0, 3000));
		#$graph->Graph->addAxisStyle(array(0, '#0000dd', 10));
		#$graph->Graph->addAxisStyle(array(3, '#0000dd', 12, 1));

		//Lines
		$graph->Graph->setLineColors(array('#FF0000', '#00FF00', '#0000FF'));
		for ($i=0; $i<$count_values; $i++){
			$graph->Graph->addShapeMarker(array('square', '000000', '0', $i, '3'));
		}

		//Data
		$graph->Data->addData($_a_daten);

		//Output Graph
		$img = $graph->printGraph();
		return $img;

	}








  } //END class
} //END if
?>
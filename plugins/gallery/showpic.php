<?php
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

define('EQDKP_INC', true);
$eqdkp_root_path = '../../';
include_once($eqdkp_root_path . 'common.php');

$permission_query = $db->query("SELECT * FROM __gallery_config WHERE config_name = 'extern'");
$permission = $db->fetch_record($permission_query);

$textstamp_query = $db->query("SELECT * FROM __gallery_config WHERE config_name = 'textstamp'");
$textstamp = $db->fetch_record($textstamp_query);

if ($permission['config_value'] == '0'){
  if(preg_match("|\Ahttp://(www\.)?".$_SERVER['SERVER_NAME']."|", $_SERVER['HTTP_REFERER'])){
    $image_file=$pcache->FilePath('upload/'.$_GET['filename'], 'gallery');
    $size = GetImageSize($image_file);
    if ($size[2] == 1){
      $image=ImageCreateFromGIF($image_file);
    } elseif ($size[2] == 2){
      $image=ImageCreateFromJPEG($image_file);
    } elseif ($size[2] == 3){
      $image=ImageCreateFromPNG($image_file);
      imageAlphaBlending($image, false);
	  imageSaveAlpha($image, true);
    }
    $textcolor = imagecolorallocate($img, 255, 25, 25);
  
    $text=$textstamp['config_value'];
    imagestring($image,5,20,16,$text,$textcolor);

    if ($size[2] == 1){
      header("Content-Type: image/gif");
      imagegif($image);
      imagedestroy($image); 
    } elseif ($size[2] == 2){
      header("Content-Type: image/jpeg");
      imagejpeg($image);
      imagedestroy($image);       
    } elseif ($size[2] == 3){
      header("Content-Type: image/png");
      imagepng($image);
      imagedestroy($image); 
    } 
  }else{
    message_die('Permission denied!');
  } 
} else {
    $image_file=$pcache->FilePath('upload/'.$_GET['filename'], 'gallery');
    $size = GetImageSize($image_file);
    if ($size[2] == 1){
      $image=ImageCreateFromGIF($image_file);
    } elseif ($size[2] == 2){
      $image=ImageCreateFromJPEG($image_file);
    } elseif ($size[2] == 3){
      $image=ImageCreateFromPNG($image_file);
      imageAlphaBlending($image, false);
	  imageSaveAlpha($image, true);
    }
    $textcolor=imagecolorallocate($image,255,25,25);
  
    $text=$textstamp['config_value'];
    imagestring($image,5,20,16,$text,$textcolor);

    header("Content-Type: image/png");
    imagepng($image);
    imagedestroy($image); 
}
?>
<?php
 /*
 * Project:     InfoPages
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-12-11 21:40:44 +0100 (Fri, 11 Dec 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: godmod $
 * @copyright   2007-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     info
 * @version     $Rev: 6689 $
 *
 * $Id: kompeditor.php 6689 2009-12-11 20:40:44Z godmod $
 */
if ( !defined('EQDKP_INC') ){
  header('HTTP/1.0 404 Not Found');exit;
} 

class kompEditor
{
	function generate($settings, $rootpath)
	{
		( !$settings['language'] ) ? $language = "en" : $language = $settings['language'];
		( !$settings['autoresize'] ) ? $autoresize = "" : $autoresize = ",autoresize";
		( !$settings['autoresize'] ) ? $resizing = 'theme_advanced_resizing : true,' : $resizing = "";
		
		
	$output = 	'
<script language="javascript" type="text/javascript" src="'.$rootpath.'plugins/info/include/javascript/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "specific_textareas",
		editor_selector : "mceEditor",
		theme : "advanced",
		plugins : "table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,wordcount",
		


		// Theme options
		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons3_add : "emotions,iespell,flash,advhr,separator,print",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>';
return $output;
	}
	
	function textbox($input, $settings){
		( !$settings['textbox_cols'] ) ? $text_cols = "85" : $text_cols = $settings['textbox_cols'];
		( !$settings['textbox_rows'] ) ? $text_rows = "15" : $text_rows = $settings['textbox_rows'];
		( !$settings['textbox_name'] ) ? $textbox = "content" : $textbox = $settings['textbox_name'];
		
			$textdata =	('<textarea name="'.$textbox.'" class="mceEditor" cols="'.$text_cols.'" rows="'.$text_rows.'">'.$input.'</textarea>');
		return $textdata;
	}
	
	function encode($input){
		$encode = addslashes(htmlentities($input));
		return $encode;
	}
	
	function decode($input){
		$decode = html_entity_decode(stripslashes($input));
		return $decode;
	}
}

?>

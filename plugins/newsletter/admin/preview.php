<?PHP
/*************************************************\
*             Newsletter 4 EQdkp plus             *
* ----------------------------------------------- *
* Project Start: 2007                             *
* Author: GodMod                                  *
* Copyright: GodMod 				              *
* Link: http://eqdkp-plus.com/forum               *
* Version: 1.3.0                                  *
* ----------------------------------------------- *
* Former Version by WalleniuM                     *
\*************************************************/
//License: Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
//License-Link: http://creativecommons.org/licenses/by-nc-sa/3.0/

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('IN_ADMIN', true);         // Must be set if admin page
define('PLUGIN', 'newsletter');   // Must be set!
$eqdkp_root_path = './../../../';
include_once('../include/common.php');

// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'newsletter')) { message_die('The Newsletter-plugin is not installed.'); }

// Check user permission
$user->check_auth('a_newsletter_');

if ($in->get('mode') == "ajax"){
	if ($in->get('type') == "html"){	
	
		$bbclass = new BBcode();
		$bbclass->ChangeLightBoxStatus(true); 
		$bbclass->SetSmiliePath("../../../libraries/jquery/images/editor/icons/");
		$body = $bbclass->MyEmoticons(sanitize($in->get('text')));
		$body = $nlclass->toHTML($body);
		$body = $nl_bbcode->parse($body);
		$body = $bbclass->toHTML($body);
		
	} else {
		$body = $nlclass->stripBBcode(sanitize(str_replace("[br]", "\n", $in->get('text'))));
	};
	echo $body;
	die();
};

if ($conf['style_bg_color'] != "" || $conf['style_font_color'] != ""){
			$bg_color = ($conf['style_bg_color'] != "") ? $conf['style_bg_color'] : "#ffffff";
			$font_color = ($conf['style_font_color'] != "") ? $conf['style_font_color'] : "#000000";
			
			$style = "<style> body { background-color:".sanitize($bg_color)."; color:".sanitize($font_color).";} </style>\n";

		};
		if ($conf['style_css'] != ""){
			$style = "<style> ".sanitize($conf['style_css'])." </style>\n".$style;
		};
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
  <head>
  <?php echo $style;?>
	<script language='javascript' src='./../../../libraries/jquery/jquery_all.js'></script>	

<script>
$(document).ready(function() { 

	text = window.parent.document.newsletter.body.value;
	if (window.parent.document.newsletter.format[0].checked == true){
		var type = "html";
	} else {
		var type = "text";
	}
	data2HTML(text, type);
	


});

function data2HTML(_text, _type){
$.post("preview.php?mode=ajax", { text: _text, type: _type },
  function(data){
	document.getElementById("preview").innerHTML = data;
  }, "html");

}

</script>
</head>
<body id="preview">


</body>
</html>
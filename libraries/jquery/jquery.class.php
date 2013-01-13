<?php
 /*
 * Project:     eqdkpPLUS Libraries: jQuery
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2011-01-26 01:20:56 +0100 (Wed, 26 Jan 2011) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     libraries:jquery
 * @version     $Rev: 9628 $
 *
 * $Id: jquery.class.php 9628 2011-01-26 00:20:56Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

if (!class_exists("jquery")) {
  class jquery {

    var $version  = '3.0.0';

    /**
    * Initialize the Class
    *
    * @param $path    Set the path to the libraries folder
    * @param $lang    The languagefiles to include
    * @return CHAR
    */
    function __construct(){
      global $eqdkp_root_path, $user;
      $this->GenerateHeader();
			define('JQUERY_LOADED', true);
    }

    /**
    * Generate the jQuery Header
    *
    * @param $template    template folder name
    * @return CHAR
    */
    public function GenerateHeader($template=''){
    	global $eqdkp_root_path, $user;
    	$this->path			= $eqdkp_root_path."libraries/jquery/";
      $this->headerr  = "<link rel='stylesheet' href='".$this->path."jquery_all.css' type='text/css'>";
      if(is_file($eqdkp_root_path.'templates/'.$template.'/jquery_tmpl.css')){
				$this->headerr  .= "<link rel='stylesheet' href='".$eqdkp_root_path."templates/".$template."/jquery_tmpl.css' type='text/css'>";
      }
      $this->headerr .= "<!--[if IE]><script language='javascript' src='".$this->path."excanvas.min.js'></script><![endif]-->";
			$this->headerr .= "<script language='javascript' src='".$this->path."jquery_all.js'></script>";
    }

    /**
    * Return the Header for file include
    *
    * @return CHAR
    */
    public function Header(){
      return $this->headerr;
    }

    /**
    * Alarm Dialog Construct
    *
    * @param $name    the name/id of the window
    * @param $width		the width of the alarm window
    * @param $height	the height of the alarm window
    * @return CHAR
    */
    private function Dialog_AlConstruct($name, $width, $height){
    	$out = "$(function() {
									$('#{$name}').dialog({
											bgiframe: true,
											modal: true,
											height: {$height},
											width: {$width},
											autoOpen: false,
											buttons: {
										Ok: function() {
											$(this).dialog('close');
										}
									}
								});
							});";
			return $out;
    }

    /**
    * Alert Window (direct)
    *
    * @param $name    Name/ID of the window (must be unique)
    * @param $msg     The Message to show in Alert
    * @param $title   The Title of the Modal Alert
    * @param $width   The width of the alert window
    * @param $height  The height of the alert window
    * @return CHAR
    */
    public function Dialog_Alert($name, $msg, $title, $width=300, $height='200'){
    	$out  = "<script>
								".$this->Dialog_AlConstruct('al_'.$name, $width, $height)."
								function show_{$name}(){
									$('#al_{$name}').dialog('open');
								}
							</script>
							<div id='al_{$name}' title='{$title}'><p>{$msg}</p></div>";
			return $out;
    }

    /**
    * Alert Window (indirect)
    *
    * @param $width   the width of the alert window
    * @param $height  the height of the alert window
    * @return CHAR
    */
    public function Dialog_Alert2($width=300, $height='200'){
    	$out = "<div id='alert_mssg' title=''></div>";
    	$out .= '<script>';
			$out .= $this->Dialog_AlConstruct('alert_mssg', $width, $height);
			$out .= "function DisplayErrorMessage(errmsg){
								$('#alert_mssg').dialog('open');
								document.getElementById('alert_mssg').innerHTML = errmsg;
							}
							</script>";
			return $out;
    }

    /**
    * Close Dialog by Name/ID
    *
    * @param $id        The Name/ID of the window to be closed
    * @param $tags      Add the <script> tags to the output?
    * @param $parent    Use the parent tag, use this if you want to close an window from main page
    * @param $function  Output as a JavaScript function
    * @return CHAR
    */
    public function Dialog_close($id, $tags=false, $parent=true, $function=false){
      // not implemented yet
    }

    /**
    * Window with iFrame & URL
    *
    * @param $name      Name/ID of the window (must be unique)
    * @param $title     The Title of the window, shown in Header
    * @param $url       The URL to show in the iFrame
    * @param $height    The width of the alert window
    * @param $height    The height of the alert window
    * @param $onclose   URL of page to redirect onClose, if empty, no redirect
    * @param $minimize  Window minimizable? (true/false)
    * @param $modal     Window modal, rest of page greyed out? (true/false)
    * @param $resize    Window resizable? (true/false)
    * @param $draggable Window dragable? (true/false)
    * @return CHAR
    */
    public function Dialog_URL($name, $title, $url, $width="600", $height="300", $onclose='', $minimize="true", $modal="false", $resize="true", $draggable="true"){
      $myclose = ($onclose) ? ",
      close: function(event, ui) { window.location.href = '".$onclose."'; }" : '';
      $jscode  = "jQuery.FrameDialog
                  .create({
                      url: '".$url."',
                      title: '".$title."',
                      height: ".$height.",
                      width: ".$width.",
                      modal: ".$modal.",
                      resizable: ".$resize.",
                      draggable: ".$draggable.$myclose."
                  })";
      return $jscode;
    }

    /**
    * Confirm Dialog
    *
    * @param $name    Name/ID of the window (must be unique)
    * @param $text    The Message to show in Confirm dialog
    * @param $jscode  The javaScript Code to perform on confirmation
    * @return CHAR
    */
    public function Dialog_Confirm($name, $text, $jscode, $withid=''){
      global $user;
      $myTrueButton = ($withid) ? $withid : 'true';
      $jscode = "function submit_".$name."(v,m){
                  if(v){
                    ".$jscode."
                  }
                  return true;
                }

                function ".$name."(".$withid."){
                  $.prompt('".$text."', {
                            buttons:{ ".$user->lang['cl_bttn_ok'].": ".$myTrueButton.", ".$user->lang['cl_bttn_cancel'].": false },
                            submit: submit_".$name.",
                            prefix:'cleanblue',
                            show:'slideDown'}
                          );
                }
                ";
          return $jscode;
    }

  	/**
    * SuckerFish horizontal Menu
    *
    * @param $name    Name of the ul class
    * @return CHAR
    */
  	public function SuckerFishMenu($array, $name, $mnuimagepth, $nodefimage=false){
  		global $eqdkp_root_path;
  		$out  = $this->MenuConstruct_js($name, true);
  		$out .= $this->MenuConstruct_html($array, $name, $mnuimagepth, $nodefimage);
  		return $out;
  	}

  	/**
    * Construct: HTML for the SuckerFish Menu
    *
    * @param $name    Name of the ul class
    * @param $tags		Show <script> Tags (true,false)
    * @return CHAR
    */

  	private function MenuConstruct_html($array, $name, $mnuimagepth, $nodefimage){
  		global $eqdkp_root_path;
			$hhm  = '<ul class="'.$name.'">';

			// Header row
			if(is_array($array)){
				foreach($array as $k => $v){
					// Restart next loop if the element isn't an array we can use
					if ( !is_array($v) ){continue;}
					$hhm .= '<li><a href="#"><img src="'.((!isset($v[99])) ? (($nodefimage) ? '' : $mnuimagepth.'plugin.png') : $mnuimagepth.$v[99]).'" alt="img" /> '.$v[0].'</a>
										<ul>';

					// Generate the Menues
					if(is_array($v)){
						foreach ( $v as $k2 => $row ){
			      	$admnsubmenu = (($row['link'] && $row['text']) ? false : true);
							// Ignore the first element (header)
							if ( ($k2 == 0 || $k2 ==99) &&  !$admnsubmenu){
								continue;
							}
							if($admnsubmenu){
								$hhm .= '<li><a href="#"><img src="'.((!isset($row[99])) ? (($nodefimage) ? '' : $mnuimagepth.'plugin.png') : $mnuimagepth.$row[99]).'" alt="img" /> '.$row[0].'</a>
													<ul>';
								// Submenu
								if(!$row['link'] && !$row['text']){
									if(is_array($row)){
										foreach($row as $k3 => $row2){
											if ($k3 == 0 || $k3 ==99){continue;}
											$hhm .= '<li><a href="'.$eqdkp_root_path . $row2['link'].'">'.$row2['text'].'</a></li>';
										}
									}
								}
								$hhm .= '</ul></li>';
							}else{
								$hhm .= '<li><a href="'.$eqdkp_root_path . $row['link'].'">'.$row['text'].'</a></li>';
							}
						}
					}
					$hhm .= '</ul></li>';
				}
  		}
			$hhm .= '</ul>
						</td>
					</tr>';
			return $hhm;
  	}

  	/**
    * Construct: Javascript for the SuckerFish Menu
    *
    * @param $name    Name of the ul class
    * @param $tags		Show <script> Tags (true,false)
    * @return CHAR
    */
  	private function MenuConstruct_js($name, $tags=true){
  		$js  = ($tags) ? "<script>" : '';
			$js .= "jQuery(function(){
									jQuery('ul.".$name."').supersubs({
						            minWidth:    12,
						            maxWidth:    27,
						            extraWidth:  1
						        }).superfish();
								});";
			$js .= ($tags) ? "</script>" : '';
			return $js;
  	}

    /**
    * Horizontal Accordion
    *
    * @param $name    Name/ID of the accordion (must be unique)
    * @param $list    Content array in the format: title => content
    * @return CHAR
    */
    public function Accordion($name, $list){
      $jscode   = "<script>
                    jQuery('#".$name."').accordion({
                        header: '.title',
                        autoHeight: false
                      });
                  </script>";
      $acccode   = '<div id="'.$name.'">';
      if(is_array($list)){
	      foreach($list as $title=>$content){
	        $acccode  .= '<div>
	                        <div class="title">'.$title.'</div>
	                        <div class="content">'.$content.'</div>
	                      </div>';
	      }
      }
      $acccode  .= '</div>';
      return $acccode.$jscode;
    }

    /**
    * Growl Messages
    *
    * @param $mssg      The Message to show
    * @param $options   Option List: life,sticky,speed, header,theme
    * @return CHAR
    */
    public function Growl($mssg, $options){
      $jsoptions = array();
      if(is_array($options)){
        if($options['life']){$jsoptions[] = "life: '".$options['life']."'";}
        if($options['sticky']){$jsoptions[] = "sticky: true";}
        if($options['speed']){$jsoptions[] = "speed: '".$options['speed']."'";}
        if($options['header']){$jsoptions[] = "header: '".$options['header']."'";}
        if($options['theme']){$jsoptions[] = "theme: '".$options['theme']."'";}
      }else{
        $jsoptions[] = 'sticky: true';
      }

      $jscode = 'jQuery(document).ready(function(){
                  $.jGrowl("'.$this->SanatizeJStext($mssg).'", { '.implode(", ", $jsoptions).' });
                });';
      return $jscode;
    }

    /**
    * Date Picker
    *
    * @param $name    Name/ID of the calendar (must be unique)
    * @param $value   Value for the input field
    * @param $options Array with Options for calendar
    * @return CHAR
    */
    public function Calendar($name, $value, $jscode='', $options=''){
      global $user;
      $html       = '<input type="text" id="cal_'.$name.'" name="'.$name.'" value="'.$value.'" size="15" '.$jscode.' />';
      $MySettings = ''; $dpSettings = array();
      $MyLanguage = ($user->lang['XML_LANG'] && count($user->lang['XML_LANG']) < 3) ? $user->lang['XML_LANG'] : '';

      // Load default settings if no custom ones are defined..
      $options['format'] = ($options['format']) ? $options['format'] : 'dd.mm.yy';
      $options['cal_icons'] = ($options['cal_icons']) ? $options['cal_icons'] : true;

      // Options
      if($options['format'] != ''){
        $dpSettings[] = "dateFormat: '".$options['format']."'";
      }
      if($options['change_fields']){
        $dpSettings[] = 'changeMonth: true, changeYear: true';
      }else{
        $dpSettings[] = 'changeMonth: false, changeYear: false';
      }
      if($options['cal_icons']){
        $dpSettings[] = "showOn: 'button', buttonImage: '".$this->path."images/calendar.png', buttonImageOnly: true";
      }
      if($options['show_buttons']){
        $dpSettings[] = "showButtonPanel: true";
      }
      if($options['number_months'] && $options['number_months'] > '1'){
        $dpSettings[] = "numberOfMonths: ".$options['number_months'];
      }
      if($options['year_range'] != ''){
        $dpSettings[] = "yearRange: '".$options['year_range']."'";
      }
      if($options['other_months']){
        $dpSettings[] = "showOtherMonths: true";
      }

      if(count($dpSettings)>0){
        $MySettings = implode(", ", $dpSettings);
      }

      // JS Code Output
      $jscode = "<script>
                  jQuery(function($){
                    $.datepicker.setDefaults($.datepicker.regional['".$MyLanguage."']);
  				          $('#cal_".$name."').datepicker({".$MySettings."});
                  });
                  </script>";
      return $html.$jscode;
    }

    /**
    * Tab
    *
    * @param $name    Name/ID of the tabulator (must be unique)
    * @param $array   Content array in the format: title => content
    * @return CHAR
    */
    public function Tab($name, $array, $taboptions=false){
      $taboptions = ($taboptions) ? $taboptions : '{ fxSlide: true, fxFade: true, fxSpeed: \'normal\' }';
      $numberk = $numberv = 1;
      $jscode = '<script>
                  $(function() {
                    $("#'.$name.'").tabs('.$taboptions.');
                  });
                </script>';
      $html   = '<div id="'.$name.'">
                  <ul>';
      if(is_array($array)){
	      foreach($array as $key=>$value){
	        $html .= ' <li><a href="#'.$name.'-'.$numberk.'"><span>'.$key.'</span></a></li>';
	        $numberk++;
	      }
    	}
      $html  .= '</ul>';
      if(is_array($array)){
	      foreach($array as $key=>$value){
	        $html .= ' <div id="'.$name.'-'.$numberk.'">'.$value.'</div>';
	        $numberv++;
	      }
      }
      $html  .= '</div>';
      return $jscode.$html;
    }

    /**
    * Tab Header
    *
    * @param $name    Name/ID of the tabulator (must be unique)
    * @return CHAR
    */
    public function Tab_header($name, $taboptions=false){
      $taboptions = ($taboptions) ? $taboptions : '{ fxSlide: true, fxFade: true, fxSpeed: \'normal\' }';
      $jscode = '<script>
                  $(function() {
                    $("#'.$name.'").tabs('.$taboptions.');
                  });
                </script>';
      return $jscode;
    }

    /**
    * Tab Select
    *
    * @param $name    Name/ID of the tabulator (must be unique)
    * @return CHAR
    */
    public function Tab_Select($name, $selection){
      $jscode = '<script>
      $(function() {
      							$("#'.$name.'").tabs(\'option\', \'selected\', '.$selection.');
      						});
                </script>';
      return $jscode;
    }

    /**
    * Right Click Menu
    *
    * @param $name    Name/ID of the tabulator (must be unique)
    * @return CHAR
    */
    public function RightClickMenu($id, $divid, $data, $width='170px'){
      $arrycount = count($data);
      if($arrycount > 0){
        $ii = 0;
        $html   = '<div class="contextMenu" id="myMenu'.$id.'">
                    <ul>';
        if(is_array($data)){
	        foreach($data as $liid=>$name){
	          $html  .= '<li id="'.$liid.'"><img src="'.$name['image'].'" /> '.$name['name'].'</li>';
	        }
      	}
        $html  .= '</ul>
                  </div>';
        $jscode = "<script>
                  $(document).ready(function() {
                    $('".$divid."').contextMenu('myMenu".$id."', {
                      menuStyle: {
                        width: '".$width."'
                      },
                      bindings: {";
        if(is_array($data)){
	        foreach($data as $liid=>$name){
	          $ii++;
	          $seperator  = ($arrycount > $ii) ? ',' : '';
	          $jscode .= "'".$liid."': function(t) {
	                            ".$name['jscode']."
	                          }".$seperator;
	        }
        }
        $jscode .= " }
                    });
                  });
                  </script>";
        return $html.$jscode;
      }
    }

    /**
    * WYSIWYG Editor
    *
    * @param $id      ID of the text area field
    * @return CHAR
    */
    public function wysiwyg($id, $type='bbcode'){
    	$myType = ($type=='html') ? 'mySettings_html':'mySettings_bb';
      if($type=='html'){
      $html 	= '.markItUp .markItUpButton1 a {
										background-image:url('.$this->path.'images/bold.png);
									}
									.markItUp .markItUpButton2 a {
										background-image:url('.$this->path.'images/italic.png);
									}
									.markItUp .markItUpButton3 a {
										background-image:url('.$this->path.'images/stroke.png);
									}
									.markItUp .markItUpButton4 a {
										background-image:url('.$this->path.'images/picture.png);
									}
									.markItUp .markItUpButton5 a {
										background-image:url('.$this->path.'images/link.png);
									}
									.markItUp .markItUpButton6 a {
										background-image:url('.$this->path.'images/clean.png);
									}
									.markItUp .preview a {
										background-image:url('.$this->path.'images/preview.png);
									}';
      }else{
      	$html = '.markItUp .markItUpButton1 a	{
										background-image:url('.$this->path.'images/bold.png);
									}
									.markItUp .markItUpButton2 a	{
										background-image:url('.$this->path.'images/italic.png);
									}
									.markItUp .markItUpButton3 a	{
										background-image:url('.$this->path.'images/underline.png);
									}
									.markItUp .markItUpButton4 a	{
										background-image:url('.$this->path.'images/left.png);
									}
									.markItUp .markItUpButton5 a	{
										background-image:url('.$this->path.'images/center.png);
									}
									.markItUp .markItUpButton6 a	{
										background-image:url('.$this->path.'images/right.png);
									}
									.markItUp .markItUpButton7 a	{
										background-image:url('.$this->path.'images/picture.png);
									}
									.markItUp .markItUpButton8 a	{
										background-image:url('.$this->path.'images/link.png);
									}
									.markItUp .markItUpButton9 a {
										background-image:url('.$this->path.'images/colors.png);
									}
										.markItUp .markItUpButton9 ul {
											width:81px;
											padding:1px;
										}
										.markItUp .markItUpButton9  li {
											border:1px solid white;
											width:25px;	height:25px;
											overflow:hidden;
											padding:0px; margin:0px;
											float:left;
										}
										.markItUp .markItUpButton9 ul a {
											width:25px;	height:25px;
										}
										.markItUp .markItUpButton9 ul a:hover {
											background-color:none;
										}
										.markItUp .markItUpButton9 .col1-1 a {
											background:yellow;
										}
										.markItUp .markItUpButton9 .col1-2 a {
											background:orange;
										}
										.markItUp .markItUpButton9 .col1-3 a {
											background:red;
										}
										.markItUp .markItUpButton9 .col2-1 a {
											background:blue;
										}
										.markItUp .markItUpButton9 .col2-2 a {
											background:purple;
										}
										.markItUp .markItUpButton9 .col2-3 a {
											background:green;
										}
										.markItUp .markItUpButton9 .col3-1 a {
											background:white;
										}
										.markItUp .markItUpButton9 .col3-2 a {
											background:gray;
										}
										.markItUp .markItUpButton9 .col3-3 a {
											background:black;
										}
									.markItUp .markItUpButton10 a	{
										background-image:url('.$this->path.'images/fonts.png);
									}
									.markItUp .markItUpButton11 a	{
										background-image:url('.$this->path.'images/list-bullet.png);
									}
									.markItUp .markItUpButton12 a	{
										background-image:url('.$this->path.'images/list-item.png);
									}
									.markItUp .markItUpButton13 a	{
										background-image:url('.$this->path.'images/code.png);
									}
									.markItUp .markItUpButton14 a	{
										background-image:url('.$this->path.'images/item.png);
									}
									.markItUp .markItUpButton15 a	{
										background-image:url('.$this->path.'images/itemlink.png);
									}
									.markItUp .clean a {
										background-image:url('.$this->path.'images/clean.png);
									}
									.markItUp .preview a {
										background-image:url('.$this->path.'images/preview.png);
									}';
      }
      $jscode = "<script type='text/javascript'>
                    $(document).ready(function()	{
                    	$('#".$id."').markItUp(".$myType.");

                    });
                </script>";
      return '<style>'.$html.'</style>'.$jscode;
    }

    /**
    * Color Picker
    *
    * @param $name    Name/ID of the colorpicker field (must be unique)
    * @param $value   Value for the input field
    * @param $jscode  Optional JavaScript Code tags
    * @return CHAR
    */
    public function colorpicker($id, $value, $size='7', $jscode=''){
      $html   = '<input type="text" id="'.$id.'" name="'.$id.'" value="'.$value.'" size="'.$size.'" '.$jscode.' />';
      $jscode = "<script type='text/javascript'>
                  jQuery(function($){
                    $('#".$id."').attachColorPicker();
                  });
                  </script>";
      return $html.$jscode;
    }

    /**
    * Progress Bar
    *
    * @param $id    	ID of the div (must be unique)
    * @param $value   Value between 0 and 100
    * @return CHAR
    */
    function ProgressBar($id, $value, $text='', $textalign='center'){
    	$value	= ($value >= 0 && $value <= 100) ? $value : '0';
    	if($text){
    		$html   = '<style>
						    	.ui-progressbar { position:relative; }
						    	.'.$id.'_label { position: absolute; width: 90%; text-align: '.$textalign.'; line-height: 1.9em; left:5%; right:5%;}
						    </style>';
    	}else{
    		$html = '';
			}
    	$html  .= '<script type="text/javascript">
									$(function() {
										$("#'.$id.'").progressbar({
											value: '.$value.'
										});
									});
									</script>';
			if($text){
				$html  .= '<div id="'.$id.'"><span class="'.$id.'_label">'.$text.'</span></div>';
			}else{
				$html  .= '<div id="'.$id.'"></div>';
			}
			return $html;
    }

    /**
    * Star Rating Widget
    *
    * @param $name    		name/id of the rating thing
    * @param $array    		array with rating infos
    * @param $post    		URL for $_POST
    * @param $value    		amount of stars to be selected by default
    * @param $disabled    disable the possibility to vote
    * @return CHAR
    */
    function StarRating($name, $array, $post, $value='', $disabled=false, $onevote=true, $halfstars=false){
    	global $user;
    	$lang_cancvote	= ($user->lang['lib_starrating_cancel']) ? $user->lang['lib_starrating_cancel'] : 'Cancel Rating';
    	$disable_js  = ($disabled) ? 'disabled: true,' : '';
    	$disable_js .= ($onevote) ? 'oneVoteOnly: true,' : '';
    	$disable_js .= ($halfstars) ? 'split: 2,' : '';
    	$html = '	<script type="text/javascript">
								$(function(){
									$("#'.$name.'_form").children().not(":radio").hide();
									$("#'.$name.'_form").stars({
										'.$disable_js.'
										cancelTitle: "Cancel Rating",
										cancelValue: 99,
										callback: function(ui, type, value)
										{
											$.post("'.$post.'", {'.$name.': value}, function(data)
											{
												$("#ajax_response").html(data);
											});
										}
									});
								});
							</script>
							<form id="'.$name.'_form" action="'.$post.'" method="post">';
    	foreach($array as $no=>$element){
    		$select_me	= ($no == $value) ? 'checked="checked"' : '';
    		$html  .= '<input type="radio" name="'.$name.'" value="'.$no.'" title="'.$element.'" id="'.$name.$no.'" '.$select_me.' /> <label for="'.$name.$no.'">'.$element.'</label><br />';
    	}
    	$html .= '<input type="submit" value="Rate it!" />
    						</form>';
    	$html .= '<p id="ajax_response"></p></div>';
    	return $html;
    }

    function StarRatingValue($name, $value, $echo=true){
    	$html  = ($echo) ? '<script>' : '';
    	$html .= '$("#'.$name.'_form").stars("select", parseInt('.$value.'));';
    	$html .= ($echo) ? '</script>' : '';
    	if($echo){
    		echo $html;
    	}else{
    		return $html;
    	}
    }

    /**
    * MultiSelect with checkboxes
    *
    * @param $name      Name/ID of the colorpicker field (must be unique)
    * @param $value     List as an array
    * @param $selected  selected items as string or array
    * @param $height    height of the popup
    * @param $width     width of the popup
    * @param $firstall  Should the first line be a "check all" checkbox?
    * @return CHAR
    */
    public function MultiSelect($name, $list, $selected, $height='200', $width='200', $firstall=false, $idname=false){
      $myID     = ($idname) ? $idname : "dw_".$name;
      $firstall = ($firstall) ? 'firstItemChecksAll: true, ' : '';
      $dropdown = '<script>
        $(document).ready(function() {
          $("#'.$myID.'").dropdownchecklist({ '.$firstall.'maxDropHeight: '.$height.', width: '.$width.' });
        });
    </script>';
  		$dropdown .= "<select name='".$name."[]' id='".$myID."' multiple='multiple'>";
  		$dropdown .= ($firstall) ? "<option value=''>(alle)</option>" : '';
      $selected = (is_array($selected))? $selected : explode("|", $selected);
  		if($list){
  			foreach ($list as $key => $value) {
  				$selected_choice = (in_array($key, $selected)) ? 'selected' : '';
  				$dropdown .= "<option value='".$key."' ".$selected_choice.">".$value."</option>";
  			}
  		}
  		$dropdown .= "</select>";
  		return $dropdown;
  	}

  	/**
    * DropDown Menu
    *
    * @param $name      ID of the css class (must be unique)
    * @param $menuitems Array with menu information
    * @param $imagepath Where are the images?
    * @param $button    Show a button with name?
    * @return CHAR
    */
  	public function DropDownMenu($id, $class,  $menuitems, $imagepath ,$button=''){
  	 global $eqdkp_root_path;
      $dmmenu  = '<ul id="'.$id.'" class="'.$class.'">
                    <li><a href="#">'.$button.'</a>
                      <ul>';
      foreach($menuitems as $key=>$value){
        if($value['perm']){
          $dmimg = ($value['img']) ? '<img src="'.$eqdkp_root_path.$imagepath.'/'.$value['img'].'" alt="" />' : '';
          $dmmenu .= '<li><a href="'.$value['link'].'">'.$dmimg.'&nbsp;&nbsp;'.$value['name'].'</a></li>';
        }
      }
      $dmmenu .= '</ul>
                  </li>
                  </ul>';
      $dmmenu .= "<script type='text/javascript'>
                    $(function() {
                      $('#".$id."').droppy();
                    });
                  </script>";
      return $dmmenu;
    }
		
		/**
    * RSS Feed
    *
    * @param $name      ID of the css class (must be unique)
    * @param $url				URL to the Feed
    * @param $items			Amount of Feed items to show
    * @param $length		Preview text length
    * @param $backgr		Bakcground (true/false)
    * @return TimePicker JS Code
    */
    function rssFeeder($name, $url, $items='4', $length='80', $backgr=false){
      global $user;
      $backgr = ($backgr) ? $backgr : $user->style['tr_color1'];
      $html = '$("#'.$name.'").rssReader({
                targeturl: "'.$url.'",
        				items: '.$items.',
        			 	Maxlength:'.$length.',
        			  loadingImg: "'.$this->path.'images/35-1.gif",
        			  background: "'.$backgr.'",
        			  lang_readmore: "'.$user->lang['lib_rss_readmore'].'",
          			lang_loadingalt: "'.$user->lang['lib_rss_loading'].'",
          			lang_errorpage: "'.$user->lang['lib_rss_error'].'"
        		  });';
      return $html;
    }
		
		/**
    * Set a Time Picker
    *
    * @param $name      ID of the css class (must be unique) 
    * @return TimePicker JS Code
    */
		function timePicker($name, $hourf=24){
		$html  = '<style>
								#'.$name.' {
						    position:relative;
						    float:left;
							}
							</style>';
		$html .= '<div id="'.$name.'"></div>';
		$html .= "<script>
							$(document).ready(function() {
				       $('#".$name."').jtimepicker({
				       		clockIcon:	'".$this->path."images/icon_clock_2.gif',
				       		hourSlider:	'".$name."hourSlider',
				       		minSlider:	'".$name."minSlider',
				       		hourCombo:	'".$name."hourCombo',
				       		minCombo:		'".$name."mincombo',
				       		hourMode:		".$hourf."
				       });
				    });
				    </script>";
			return $html;
		}
		
		/**
    * Load the RSS Feed
    *
    * @param $url      URL of the Feed File
    * @return --
    */
    function loadRssFeed($url){
    	global $urlreader;
      header('content-type: application/xml');
      print $urlreader->GetURL($url);
      die();
    }

		function PieChart($id, $data, $title='', $options='', $piemargin=6, $showlegend=true){
			if(is_array($data)){
				$last_item = max(array_keys($data));
				$js_array = '['; $color_array = array();
				foreach($data as $ids=>$values){
					$js_array .= "['".$values['name']."', ".$values['value']."]";
					if($values['color']){
						$color_array[] = $values['color'];
					}
					if($last_item != $ids){
						$js_array .= ',';
					}
				}
				$js_array .= ']';
			}
			
			$own_colors = (count($color_array) > 0) ? 'seriesColors: [ '.$this->implode_wrapped('"','"', ',', $color_array).' ],' : '';
			$show_legend = ($showlegend) ? 'true' : 'false';
			$mytitle = ($title) ? "title: '".$title."'," : '';
			$js = "<script>$(document).ready(function(){
				
				jqplotdata_".$id." = ".$js_array.";
				
				plot_".$id." = $.jqplot('".$id."', [jqplotdata_".$id."], {
								  ".$mytitle."
								  ".$own_colors."
								  grid: {background: '".(($options['background']) ? $options['background'] : '#f5f5f5')."',borderColor: '".(($options['bordercolor']) ? $options['bordercolor'] : '#999999')."', borderWidth: ".(($options['border']) ? $options['border'] : '2.0').", shadow: ".(($options['shadow']) ? 'true' : 'false')."},
								  seriesDefaults:{renderer:$.jqplot.PieRenderer, rendererOptions:{sliceMargin:".$piemargin."}}, 
								  legend:{show:".$show_legend.", escapeHtml:true}
								});
			});</script>";
			
			$html = '<div id="'.$id.'"></div>';
			return $js.$html;
		}
		
		function implode_wrapped($before, $after, $glue, $array){
	    $output = '';
	    foreach($array as $item){
	        $output .= $before . $item . $after . $glue;
	    }
	    return substr($output, 0, -strlen($glue));
		}

		
    /**
    * Clean up Text for usage in JS Outputs
    *
    * @param $mssg      The text to sanatize
    * @return sanatized text
    */
    public function SanatizeJStext($mssg){
      $mssg = html_entity_decode($mssg);
      $mssg = str_replace('&#039;', "'", $mssg);
      $mssg = str_replace('"', "'", $mssg);
      $mssg = str_replace(array("\n", "\r"), '', $mssg);
      $mssg = addslashes($mssg);
      return $mssg;
    }

    /**
    * OUTDATED FUNCTIONS
    */
    public function HumanMsg($content){
      System_Message($content, '', 'green');
    }
  }
}
?>
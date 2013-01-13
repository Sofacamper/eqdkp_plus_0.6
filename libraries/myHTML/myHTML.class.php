<?php
 /*
 * Project:     eqdkpPLUS Libraries: myHTML
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-08-03 20:16:41 +0200 (Mon, 03 Aug 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     libraries:myHTML
 * @version     $Rev: 5417 $
 * 
 * $Id: myHTML.class.php 5417 2009-08-03 18:16:41Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

if (!class_exists("myHTML")) {
  class myHTML {  	
  	
  	/**
    * Init Class
    * 
    * @param $pluginname	Plugin folder name
    */
  	function __construct($pluginname=''){
      global $eqdkp_root_path;
  		$this->pluginname = $pluginname;
  		$this->imagePath  = $eqdkp_root_path.'libraries/myHTML/images/';
  	}
  	
  	/**
    * Set Plugin name
    * 
    * @param $pluginname	Plugin folder name
    */
  	public function SetPluginName($pluginname){
  		$this->pluginname = $pluginname;
  	}
  	
  	/**
    * Button
    * 
    * @param $name    	fieldname
    * @param $value			Value
    * @param $type			Type, default "submit"
    * @return Button
    */
  	public function Button($name, $value, $type='submit'){
			return '<input name="'.$name.'" value="'.$value.'" class="mainoption" type="'.$type.'">';
		}
  	
  	/**
    * Form - START
    * 
    * @param $name    	formname
    * @param $value			url ro submit
    * @param $action		method to send, default is "Post"
    * @return StartForm
    */
		public function StartForm($name, $url, $action = 'post'){
			return '<form method="'.$action.'" action="'.$url.'" name="'.$name.'">';
		}

		/**
    * Form - END
    * @return End of form
    */
		public function EndForm(){
			return '</form>';
		}
  	
  	/**
    * Table - START
    * @return Start of Table
    */
		public function starttable($width='100%'){
			return "<table border='0' width='".$width."'>";
		}
		
		/**
    * Table - END
    * @return End of table
    */
		public function endtable(){
			return "</table>";
		}
		
		public function tableheader($text) {
			$tab = "<tr><td align='left' colspan=2></td> </tr>";
			$tab .= "<tr class=row2><th align='left' colspan=2>".$text."</td> </tr>";
			return $tab;
		}
  	
  	public function tablerow($text,$row='row1'){
			return "<tr class=".$row."><td align='left' colspan=2>".$text."</td> </tr>";
		}
  	
  	/**
    * DropDown
    * 
    * @param $name    	fieldname
    * @param $list			Array with dropdown items
    * @param $selected	selected, true or not
    * @param $text			Label of the Dropdown
    * @param $javascr		custom additions, such as onclick...
    * @param $class			css class
    * @param $customid	ID of field
    * @return Dropdown
    */
  	public function DropDown($name, $list, $selected, $text='', $javascr = '', $class = 'input', $customid=''){
  		( $text != '' ) ? $dropdown = $text.": " : '';
  		$dropdown  .= "<select size='1' ".$javascr." name='".$name."' id='".(($customid) ? $customid : $name)."' class='".$class."'>";
  		if($list){
  			foreach ($list as $key => $value) {
  				$selected_choice = ($key == $selected) ? 'selected' : '';
  				$dropdown .= "<option value='".$key."' ".$selected_choice.">".$value."</option>";
  			}
  		}
  		$dropdown .= "</select>";
  		return $dropdown;
  	}
  	
  	/**
    * Plus DropDown Helper
    * 
    * @param $name    	fieldname
    * @param $list			Array with dropdown items
    * @param $selected	selected, true or not
    * @param $text			Label of the Dropdown
    * @param $text			Text
    * @param $hekp			Help or not?
    * @param $notable		Table or not?
    * @return Dropdown
    */
  	public function Plus_DropDown($name, $list, $selected, $text='', $help = '', $notable=false){
			$dropdown = $this->DropDown($name, $list, $selected, $text='', $javascr = '', $class = 'input', $customid='');
			$dropdown .= " ".$text;
			if ($notable){
				$dropdown = $this->HelpTooltip($help).$dropdown;
			}else{
				$dropdown = $this->WrapIntoTableField($dropdown, $help);
			}
			return $dropdown;
		}

  	
  	/**
    * Multiselect
    * 
    * @param $name    	fieldname
    * @param $list			Array with dropdown items
    * @param $selected	selected, true or not
    * @param $text			Label of the Dropdown
    * @param $javascr		custom additions, such as onclick...
    * @param $class			css class
    * @param $size			Size of the Multiselect
    * @return Dropdown
    */
  	public function MultiSelect($name, $list, $selected, $text='', $javascr = '', $class = '', $size=4){
  		( $text != '' ) ? $dropdown = $text.": " : '';
  		$dropdown  .= "<select size='".$size."' ".$javascr." name='".$name."[]' class='".$class."' multiple>";
  		$selected = explode("|", $selected);
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
    * Radio Box
    * 
    * @param $name    	fieldname
    * @param $list			Array with dropdown items
    * @param $selected	selected, true or not
    * @param $class			css class
    * @return Dropdown
    */
  	public function RadioBox($name, $list, $selected, $class = 'input'){
  	  global $user;
  		$radiobox  = '';
  		if(!is_array($list)){
  		  $list = array (
          '0'   => $user->lang['cl_off'],
          '1'   => $user->lang['cl_on']
        );
  		}
  			foreach ($list as $key => $value) {
  				$selected_choice = ($key == $selected) ? 'checked' : '';
  				$radiobox .='<input type="radio" name="'.$name.'" value="'.$key.'" '.$selected_choice.'>'.$value;
  			}
  		return $radiobox;
  	}
  	
  	/**
    * CheckBox
    * 
    * @param $name    	fieldname
    * @param $langname	Field Label
    * @param $options		Checked or not
    * @param $help			Help Tooltip Text
    * @param $value			Value of checkbox
    * @param $notable		Table or not?
    * @return Dropdown
    */
  	public function CheckBox($name, $langname, $options, $help='', $value='1', $notable=true){
  			$is_checked = ( $options == 1 ) ? 'checked' : '';
  			if ($notable){
  				$check = ($help) ? "&nbsp;&nbsp;".$this->HelpTooltip($help) : '';
  				$check .= "<input type='checkbox' name='".$name."' value='".$value."' ".$is_checked." /> ".(($langname) ? $langname : '');
  			}else{
  				$check .= $this->WrapIntoTableField(" <input type='checkbox' name='".$name."' value='".$value."' ".$is_checked." /> ".(($langname) ? $langname : ''), $help);
  			}
  			return $check;
  	}
  	
  	/**
    * Text Area
    * 
    * @param $name    	fieldname
    * @param $rows    	Text Area Rows
    * @param $cols			Text Area Cols
    * @param $value			Value of checkbox
    * @param $test			Field Lable
    * @param $help			Help Tooltip Text
    * @param $type			Type of the Text
    * @param $notable		Table or not?
    * @return TextArea
    */
		public function TextArea($name, $rows, $cols, $value = '', $text='', $help='', $type = 'text', $notable=false){
			if ($notable){
				$textfield = "&nbsp;&nbsp;".$this->HelpTooltip($help);
				$textfield .= " <textarea name='".$name."' rows='".$size."' cols='".$cols."' class='input' />".$value."</textarea>";
				$textfield .= " ".$text;
			}else{
				$textfield .= $this->WrapIntoTableField(" <textarea name='".$name."' rows='".$size."' cols='".$cols."' class='input' />".$value."</textarea> ".$text, $help);
			}
			return $textfield;
		}
		
		/**
    * Text Field
    * 
    * @param $name    	fieldname
    * @param $size			Size
    * @param $value			Value of checkbox
    * @param $text			Field Lable
    * @param $help			Help Tooltip Text
    * @param $type			Type of the Text
    * @param $notable		Table or not?
    * @param $id				Field ID
    * @return TextField
    */
		public function TextField($name, $size, $value = '', $text='', $help='', $type = 'text', $notable=false, $id=''){
			$myID = ($id) ? ' id="'.$id.'"' : '';
			if ($notable){
				$textfield = "&nbsp;&nbsp;".$this->HelpTooltip($help);
				$textfield .= " <input type='".$type."' name='".$name."' size='".$size."' value='".$value."' class='input'".$myID." />";
				$textfield .= " ".$text;
			}else{
				$textfield .= $this->WrapIntoTableField(" <input type='".$type."' name='".$name."' size='".$size."' value='".$value."' class='input'".$myID." /> ".$text, $help);
			}
			return $textfield;
		}
  	
  	/**
    * Radio Boxes
    * 
    * @param $name    	fieldname
    * @param $size			Size
    * @param $value			Value
    * @param $text			Field Lable
    * @param $help			Help Tooltip Text
    * @param $notable		Table or not?
    * @param $cssid			CSS ID
    * @return TextField
    */
		public function AutoTextField($name, $size, $value = '', $text='', $help='', $type = 'text', $notable=false, $cssid='autocomplete'){
			return $this->WrapIntoTableField(" <input name='".$name."' size='".$size."' value='".$value."' id='".$cssid."' /> ".$text, $help);
		}
  	
  	/**
    * Radio Boxes
    * 
    * @param $name    	fieldname
    * @param $list			Array with radio box values
    * @param $selected	Selected or not?
    * @param $text			Field Lable
    * @param $help			Help Tooltip Text
    * @param $notable		Table or not?
    * @return TextField
    */
		public function RadioBoxes($name, $list, $selected, $text='', $help = '', $notable=false){
			$dropdown = $text.': ';
			if(is_array($list)){
				foreach ($list as $key => $value) {
					$selected_choice = ($key == $selected) ? ' checked="checked"' : '';
					$dropdown .= "<input type='radio' name='".$name."' value='".$key."' ".$selected_choice." /> ".$value;
				}
			}
			return ($notable) ? $this->HelpTooltip($help).$dropdown : $this->WrapIntoTableField($dropdown, $help);
		}
  	
  	/**
    * Help Tooltip
    * 
    * @param $help		Text to show
    * @return Tooltip
    */
  	public function HelpTooltip($help){
  		global $user, $eqdkp_root_path;
  		if ($help != ''){
  		  $helptt .= '<a '.$this->HTMLTooltip($help, 'rp_tt_help')."><img src='".$this->imagePath."help_small.png' border='0' alt='' align='absmiddle' /></a>";
  		}else{
  			$helptt = '';
  		}
  		return $helptt;
  	}
  
  	/**
    * Warn Tooltip
    * 
    * @param $help		Text to show
    * @return Tooltip
    */
  	public function WarnTooltip($help){
  		global $user, $eqdkp_root_path;
  		$helptt .= '<a '.$this->HTMLTooltip($help, 'rp_tt_warn')."><img src='".$this->imagePath."warn_small.png' border='0' alt='' align='absmiddle' /></a>";
  		return $helptt;
  	}
    
    /**
    * Overlip Helper Class
    * 
    * @param $help		Text to show
    * @return Tooltip
    */
    private function Overlib($tt){
      $tt = html_entity_decode($tt);
      $tt = str_replace('&#039;', "'", $tt);
      $tt = str_replace('"', "'", $tt);
      $tt = str_replace(array("\n", "\r"), '', $tt);
      $tt = addslashes($tt);
      $output = 'onmouseover="return overlib(' . "'" . $tt . "'" . ', MOUSEOFF, HAUTO, VAUTO,  FULLHTML, WRAP);" onmouseout="return nd();"';
      return $output;
    }
    
    /**
    * HTML Tooltip
    * 
    * @param $content		Content to show
    * @param $divstyle	Class name of Div
    * @param $icon			Icon path (optional)
    * @param $width			Width of the tooltip
    * @return Tooltip
    */
    public function HTMLTooltip($content, $divstyle, $icon='', $width='70px'){
      $output = $this->Overlib($this->TooltipStyle($content, $divstyle, $icon, $width));
      return $output;
    }
    
    /**
    * ToolTip Style Helper
    * 
    * @param $content		Content to show
    * @param $divstyle	Class name of Div
    * @param $icon			Icon path (optional)
    * @param $width			Width of the tooltip
    * @return Tooltip
    */
    private function TooltipStyle($content, $divstyle, $icon='', $width){
      global $eqdkp_root_path;
      $output = "<div class='".$divstyle."' style='display:block'>
                  <div class='rptooldiv'>
                  <table cellpadding='0' border='0' class='borderless'>
                  <tr>";
      if($icon){
        $output .= "<td valign='middle' width='".$width."' align='center'>
                      <img src='".$eqdkp_root_path."plugins/".$this->pluginname."/images/tooltip/".$icon."' alt=''/>
                    </td>";
      }
      $output .= "<td>
                    ".$content."
                    
                  </td>
                  </tr>
                  </table></div></div>";
      return $output;
    }
    
		public function ToolTip($tooltip_content, $normaltext, $title='', $icon='',$a_edge_text=null){
			if(strlen($tooltip_content)>0){
				//Outlines with title and icon
				$tt="<table class='wh_outer'>
					   	<tr><td valign='top'>";
				if($icon){
	        $tt.="<div class='iconsmall' style='background-image: url(".$icon.");'>";
				}else{
	        $tt.="<div class='iconsmall'>";
	      }
				$tt.="<div class='tile'>".$title."</div></div>
							</td>
							<td>";

				//Tooltip itself - css
				$tt.="<table class='eqdkpplus_tooltip'>
									<tr>
										<td class='top-left'>".$a_edge_text['tl']."</td>
										<td class='top-right'>".$a_edge_text['tr']."</td>
									</tr>
									<tr>
										<td colspan='2' class='wh_left'>
											<div class='wh_right'>
											<div class='eqdkpplus_tooltip'> ".htmlspecialchars ($tooltip_content)." </div></div>
										</td>
									</tr>
									<tr>
										<td class='bottom-left'>".$a_edge_text['bl']."</td>
										<td class='bottom-right'>".$a_edge_text['br']."</td>
									</tr>
								</table>";
				$tt.="</td>
						</tr>
					</table>";
				$tt = "<span " . $this->Overlib($tt) . ">" . $normaltext . "</span>";
				return $tt ;
			}else{
				return $normaltext;
		 	}
		}# end functions
    
    /**
    * Wrap Help into Table Field
    * 
    * @param $input		Description
    * @param $help		Tooltip Text
    * @return Tooltip
    */
    public function WrapIntoTableField($input, $help){
			global $eqdkp_root_path;
			return "<tr class='row1'><td width='25px'>".$this->HelpTooltip($help)."</td><td>". $input."</td></tr>";
		}
   
		/**
		* Eqdkp Plus Growl System Messages
		*
		* @param string $text
		* @param string $title
		* @param string $kind
		* @return string
		*/
  	public function growlMessage($text='', $title='', $kind='default'){
	    global $jquery;
	    $myOut = $jquery->Growl($text, array(
	                              'header' => $title,
	                              'sticky' => true,
	                              'theme'  => 'eqdkp-'.$kind,
	                            )
	                    );
	    return $myOut;
	  }
	  
		public function MsgBox($title, $value, $image, $width='100%', $center='false', $imgheight='48px', $imgwidth='48px', $reflect = false){
			if ($center == true){
				$startcenter 	= '<center>';
				$endcenter 		= '</center>';
			}else{
				$startcenter 	= '';
				$endcenter 		= '';
			}
			if($reflect == true){
				$showreflect = 'class="reflect"';
			}else{
				$showreflect = $divreflect = $enddivreflect = '';
			}
			$tdwidth= $imgwidth+6;
			$msgbox = $startcenter.'<table width="'.$width.'" border="0" cellspacing="1" cellpadding="2">
	  							<tr>
	    							<th align="center" colspan="2">'.$title.'</th>
	  							</tr>
	  							<tr>
	    							 <td class="row1" width ="'.$tdwidth.'" ><img '.$showreflect.' src="'.$image.'" width ="'.$imgwidth.'" height="'.$imgheight.'" /></td>
	    								<td class="row1">'.$value.'</td>
	  							</tr>
	  							<tr>
									</table>'.$endcenter;
			return $msgbox;
		}
	  
    // Prevent XSS and other stupid things..
    public function CleanInput($input){
      // remove HTML & PHP Tags
      $output = html_entity_decode($input);
      $output = strip_tags($output);
      
      // Secure against XSS
      $output = htmlentities($output, ENT_QUOTES);
      return $output;
    }
  }
} 
?>

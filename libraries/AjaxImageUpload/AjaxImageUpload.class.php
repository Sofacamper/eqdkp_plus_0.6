<?php
 /*
 * Project:     eqdkpPLUS Libraries: AjaxImageUpload
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2012-05-09 12:56:29 +0200 (Wed, 09 May 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: godmod $
 * @copyright   2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     libraries:AjaxImageUpload
 * @version     $Rev: 11779 $
 * 
 * $Id: AjaxImageUpload.class.php 11779 2012-05-09 10:56:29Z godmod $
 */
 
if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
if (!class_exists("AjaxImageUpload")) { 
  class AjaxImageUpload {
    
    var $version  = '1.0.0';
    var $options = array(
          'filesize'  => '1048576',  // 1 MB
          'maxheight' => '300',
          'maxwidth'  => '300'
        );
    
    public function Show($name, $pfile, $showimage='', $wform=true){
      global $user;
      $out = '<script>
            	 function ajaxFileUpload(){
            		$("#loading_'.$name.'")
            		.ajaxStart(function(){
            			$(this).show();
            		})
            		.ajaxComplete(function(){
            			$(this).hide();
            		});
            
            		$.ajaxFileUpload({
            				url:\''.$pfile.'&debug=0\',
            				secureuri:false,
            				fileElementId:\'real_'.$name.'\',
            				dataType: \'json\',
            				success: function (data, status)
            				{
            				  //console.log(data);
            					if(data.image != ""){
            					  // Do sth with the image name...
            					  $("#preview_'.$name.'").show();
            					  $("#preview_'.$name.'").attr("src", data.fullpath);
            					  $("#'.$name.'").attr("value", data.image);
            					  $("#real_'.$name.'").attr("disabled", "disabled");
            					  $("#error_'.$name.'").hide();
            					  $("#button_'.$name.'").hide();
            					}else{
            					  $("#error_'.$name.'").show();
            					  $("#button_'.$name.'").show();
                        $("#error_'.$name.'").html(data.error);
                      }
            				},
            				error: function (data, status, e)
            				{
            				  // Catch errors
            					alert(e);
            				}
            			}
            		)
            		return false;
            	}
            	
            	// Upload on change of the image field
            	jQuery(document).ready(function(){
              	jQuery("#real_'.$name.'").change(function (){
              	 return ajaxFileUpload();
              	});
              });
            	</script>	
                <div id="error_'.$name.'" style="display:none;color:red;width:100%;">Errormessage..</div>
                <img id="loading_'.$name.'" src="images/loading.gif" style="display:none;" />';
        if($showimage){
          $out .= '<img id="preview_'.$name.'" src="'.$showimage.'" />';
        }else{
          $out .= '<img id="preview_'.$name.'" src="images/blank.png" style="display:none;"/>';
        }
        $out .= '<br/>';
        $out .= ($wform) ? '<form name="form" action="" method="POST" enctype="multipart/form-data">' : '';
        $out .= '<input id="real_'.$name.'" type="file" size="45" name="real_'.$name.'" class="input">
            		  <input type="button" id="button_'.$name.'" value="'.$user->lang['aiupload_upload_again'].'" class="maininput" onclick="return ajaxFileUpload();" style="display:none;"/>';
        $out .=  ($wform) ? '</form>' : '';
        return $out;
    }
    
    public function PerformUpload($rname, $plugin, $folders, $options=''){
      global $pcache, $user;
      $error = $save_name = $fullsavepath = '';
      // Define the Options
      if(!is_array($options)){
        $options = $this->options;
      }
      $name = 'real_'.$rname;
	  $allowedExtensions = array('jpg', 'jpeg', 'gif', 'png');
	  $extension = strtolower(pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION));
	  
	  
      if(empty($_FILES[$name]['error'])){
        if(in_array($extension, $allowedExtensions) && ($_FILES[$name]['type'] == 'image/jpeg' || $_FILES[$name]['type'] == 'image/pjpeg' || 
        $_FILES[$name]['type'] == 'image/gif' || $_FILES[$name]['type'] == 'image/png' || 
        $_FILES[$name]['type'] == 'image/x-png') && $_FILES['upload']['size'] <= $options['filesize'])
        {
          $file_formats = getimagesize($_FILES[$name]['tmp_name']);
          if($file_formats[0] <= $options['maxwidth'] && $file_formats[1] <= $options['maxheight']){
            // Add a serialized key to the file.. and upload..
            $save_name    = substr(md5(uniqid(rand(),1)), 3, 10).$_FILES[$name]['name'];
            $fullsavepath = $pcache->FolderPath($folders, $plugin).$save_name;
            @move_uploaded_file($_FILES[$name]['tmp_name'], $fullsavepath);
            @chmod($fullsavepath, 0666);
          }else{
            $error = sprintf($user->lang['aiupload_wrong_format'], $options['maxwidth'], $options['maxheight']);
          }
        }else{
          $error = $user->lang['aiupload_wrong_type'];
        }
      }else{
        $error = $_FILES[$name]['error'];
      }
      
       // Generate the Output to be read by the JS..
      echo "{";
      echo				"image: '" . $save_name . "',\n";
      echo				"fullpath: '" . $fullsavepath . "',\n";
      echo				"error: '" . $error . "'\n";
      echo "}";
      die();
    }
  }
}
?>
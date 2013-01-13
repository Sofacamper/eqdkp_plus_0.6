<?php
 /*
 * Project:     eqdkpPLUS Libraries: MyMailer
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-10-27 16:11:52 +0100 (Tue, 27 Oct 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     libraries:MyMailer
 * @version     $Rev: 6270 $
 * 
 * $Id: MyMailer.class.php 6270 2009-10-27 15:11:52Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

// Include Needed files...
include_once('class.phpmailer.php');

/**************** OPTIONS HELP *****************
	$options = array(
		'sender_mail'		=> $conf['rp_sender_email'],
		'mail_type'			=> 'html',
		'template_type'	=> 'file',
		'sendmail_path'	=> $conf['sendmail_path'],
		'smtp_auth'			=> $conf['smtp_auth'],
		'smtp_host'			=> $conf['smtp_host'],
		'smtp_username'	=> $conf['smtp_username'],
		'smtp_password'	=> $conf['smtp_password'],
	);
************************************************/

class MyMailer extends PHPMailer {
		
	/**
  * Construct
  * 
  * @param $options				Array with options (see above)
  * @param $path					root path to tmplate/language files folder
  * @return traue/false
  */
	function __construct($options, $path='') {
		global $eqdkp, $user;
		$this->eqmpath		= $path;
		$this->myoptions	= $options;
		
		// Some usefull information
		$this->mydeflang	= $eqdkp->config['default_lang'];
		$this->adminmail	= $eqdkp->config['admin_email'];
		$this->dkpname		= ($eqdkp->config['main_title']) ? $eqdkp->config['main_title'] : $eqdkp->config['guildtag'].' '.$eqdkp->config['dkp_name'];
		
		// Language Vars
		$this->nohtmlmssg	= $user->lang['rp_nohtml_msg'];
	}
  
  /**
  * Set Options Array
  * 
  * @param $options		Array with options (see above)
  * @return --
  */
  public function SetOptions($options){
  	$this->myoptions	= $options;
  }
  
  /**
  * Set Path
  * 
  * @param $path			root path to tmplate/language files folder
  * @return --
  */
  public function SetPath($path){
  	$this->eqmpath		= $path;
  }
  
  /**
  * Send the Mail with admin sender adress
  * 
  * @param $adress				recipient email address
  * @param $subject				email subject
  * @param $templatename	Name of the Email template to use
  * @param $bodyvars			Body Vars
  * @param $method				Method to send the mails (smtp, sendmail, mail)
  * @return traue/false
  */
  public function SendMailFromAdmin($adress, $subject, $templatename, $bodyvars, $method){
    $this->AddAddress(stripslashes($adress));
    $this->GenerateMail($subject, $templatename, $bodyvars, $this->eqmpath);
    return $this->PerformSend($method);
  }
  
  /**
  * Build Character List with some details out of Guild List
  * 
  * @return URL
  */
  public function BuildLink(){
    global $eqdkp;
    $script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($eqdkp->config['server_path']));
    $script_name = ( $script_name != '' ) ? $script_name . '/' : '';
    $server_name = trim($eqdkp->config['server_name']);
    $server_port = ( intval($eqdkp->config['server_port']) != 80 ) ? ':' . trim($eqdkp->config['server_port']) . '/' : '/';
    return 'http://' . $server_name . $server_port . $script_name;
  }
  
  /****** PRIVATE FUNCTIONS *****/
  
  /**
  * Template
  * 
  * @param $templatename	Name of the Email template to use
  * @param $inputs				Array with input variables to change in mail body
  * @return traue/false
  */
  private function Template($templatename, $inputs){
		// Check if the template is from a file or not
		if($this->myoptions['template_type'] == 'input'){
			$body   = $templatename;
		}else{
			$body   = $this->getFile($this->eqmpath.'language/'.$this->mydeflang.'/email/'.$templatename);
		}
    $body   = eregi_replace("[\]",'',$body);
    if(is_array($inputs)){
      foreach($inputs as $name => $value){
        $body = eregi_replace("{".$name."}",$value,$body);
      }
    }
    return $body;
  }
  
  /**
  * Generate the Mail Body & rest
  * 
  * @param $subject				Subject of the Mail
  * @param $templatename	Name of the Email template to use
  * @param $bodyvars			Array with input variables to change in mail body
  * @return traue/false
  */
  private function GenerateMail($subject, $templatename, $bodyvars){
    $this->From     = ($this->myoptions['sender_mail']) ? $this->myoptions['sender_mail'] : $this->adminmail;
    $this->FromName = $this->dkpname;
    $this->Subject  = $subject;
    $tmp_body				= $this->Template($templatename, $bodyvars, $this->eqmpath);
    
    if($this->myoptions['mail_type'] == 'text'){
    	// Text Mail
    	$this->Body	= $tmp_body;
    }else{
    	// HTML Mail
	    $this->MsgHTML($tmp_body);
	    $this->AltBody = $this->nohtmlmssg;
    	
    }
  }
  
  /**
  * Perform the message delivery
  * 
  * @param $method Method to send the mails (smtp, sendmail, mail)
  * @return traue/false
  */
	private function PerformSend($method){
    if($method == 'smtp'){
      // set the smtp auth
      $this->Mailer   = 'smtp';
      $this->SMTPAuth = ($this->myoptions['smtp_auth'] == 1) ? true : false;
      $this->Host     = $this->myoptions['smtp_host'];
      $this->Username = $this->myoptions['smtp_username'];
      $this->Password = $this->myoptions['smtp_password'];
    }elseif($method == 'sendmail'){
      $this->Mailer   = 'sendmail';
      if(isset($this->myoptions['sendmail_path'])){
        $this->Sendmail = $this->myoptions['sendmail_path'];
      }
    }else{
      $this->Mailer   = 'mail';
    }
    $sendput = $this->Send();
    $this->ClearAddresses();
    return $sendput;
  }
  
  /**
  * Helper file for file Handling
  * 
  * @param $filename  the filename of the template file
  * @return file/false
  */
  function getFile($filename) {
  	if( false == ($return = file_get_contents($filename))){
      return false;
    }else{
      return $return;
    }
  }
}
?>

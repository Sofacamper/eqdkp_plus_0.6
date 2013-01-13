<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2009-07-15 00:30:25 +0200 (Wed, 15 Jul 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 5261 $
 * 
 * $Id: email.class.php 5261 2009-07-14 22:30:25Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

include_once('class.phpmailer.php');

class MyMailer extends PHPMailer {
    
  function Template($templatename, $inputs, $path='../'){
    global $eqdkp;
    $body   = $this->getFile($path.'language/'.$eqdkp->config['default_lang'].'/email/'.$templatename);
    $body   = eregi_replace("[\]",'',$body);
    if(is_array($inputs)){
      foreach($inputs as $name => $value){
        $body = eregi_replace("{".$name."}",$value,$body);
      }
    }
    return $body;
  }
  
  function GenerateMail($subject, $templatename, $bodyvars, $path){
    global $eqdkp, $user, $conf;
    $this->From     = ($conf['rp_sender_email']) ? $conf['rp_sender_email'] : $eqdkp->config['admin_email'];
    $this->FromName = $eqdkp->config['dkp_name'];
    $this->Subject  = $subject;
    $this->MsgHTML($this->Template($templatename, $bodyvars, $path));
    $this->AltBody = $user->lang['rp_nohtml_msg']; // optional, comment out and test
  }
  
  function SendMailFromAdmin($adress, $subject, $templatename, $bodyvars, $method, $path='../'){
    $this->AddAddress(stripslashes($adress));
    $this->GenerateMail($subject, $templatename, $bodyvars, $path);
    return $this->PerformSend($method);
  }
  
  function PerformSend($method){
    global $conf;
    if($method == 'smtp'){
      // set the smtp auth
      $this->Mailer   = 'smtp';
      $this->SMTPAuth = ($conf['smtp_auth'] == 1) ? true : false;
      $this->Host     = $conf['smtp_host'];
      $this->Username = $conf['smtp_username'];
      $this->Password = $conf['smtp_password'];
    }elseif($method == 'sendmail'){
      $this->Mailer   = 'sendmail';
      if(isset($conf['sendmail_path'])){
        $this->Sendmail = $conf['sendmail_path'];
      }
    }else{
      $this->Mailer   = 'mail';
    }
    $sendput = $this->Send();
    $this->ClearAddresses();
    return $sendput;
  }
  
  function BuildLink(){
    global $eqdkp;
    $script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($eqdkp->config['server_path']));
    $script_name = ( $script_name != '' ) ? $script_name . '/' : '';
    $server_name = trim($eqdkp->config['server_name']);
    $server_port = ( intval($eqdkp->config['server_port']) != 80 ) ? ':' . trim($eqdkp->config['server_port']) . '/' : '/';
    return 'http://' . $server_name . $server_port . $script_name;
  }
}
?>

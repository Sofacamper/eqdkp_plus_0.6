<?php
 /*
 * Project:     EQdkp Newsletter
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2008-09-05 10:06:11 +0200 (Fr, 05 Sep 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     newsletter
 * @version     $Rev: 2685 $
 * 
 * $Id: email.class.php 2685 2008-09-05 08:06:11Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

if (!class_exists("NewsletterClass")){
  class NewsletterClass
  {
    // Generate the page header
    function GeneratePageTitle($name){
      global $user, $eqdkp;
      return sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['newsletter'].' - '.$name;
    }
  	
  	// Build the CopyRight
  	function Copyright(){
      global $pm, $user;
      $nl_version = $pm->get_data('newsletter', 'version');
	  
      $nl_status  = (strtolower($pm->plugins['newsletter']->vstatus) == 'stable' ) ? ' ' : ' '.$pm->plugins['newsletter']->vstatus.' ';
	  $act_year = date("Y", time());
	  $nl_copyyear = ( $act_year == 2009) ? $act_year : '2009 - '.$act_year;
      return $user->lang['newsletter'].' '.$nl_version.$nl_status.' &copy; '.$nl_copyyear.' by '.$pm->plugins['newsletter']->copyright;
    }
    
  
  function update_config($config_value, $config_name){
	global $db;
	
	$updatequery = $db->query("UPDATE __newsletter_config SET config_value = '".$db->escape($config_value)."' WHERE config_name = '".$db->escape($config_name)."'");
}
 
 
	function is_checked($value){
	
		return ( $value == 1 ) ? ' checked="checked"' : '';
	} //END function is_checked
 
 
 
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
	} //END function wrapText
 	function removeBr($s) {
      return str_replace("\n", "", $s[0]);
    }
	
 	function toHTML($text){
	// BBCode to find...
		
		//Replace all Breaks in a table
		//$text = preg_replace_callback('/[table](.*?)[\/table]/msi', array($this,"removeBr"), $text);
		
    	$in = array(	 '/\[class\="?(.*?)"?\](.*?)\[\/class\]/msi',
      					 '/\[table](.*?)\[\/table\]/msi',
						 '/\[tr](.*?)\[\/tr\]/msi',
						 '/\[td](.*?)\[\/td\]/msi',
      					 
    	           );
		
		$out = array(        '<span class="\1">\2</span>',
          					 '<table>\1</table>',
							 '<tr>\1</tr>',
							 '<td>\1</td>',
          					 
      	             );
		//Not needed anymore because of bbcode-class
		//$text = preg_replace($in, $out, $text);
		return $text;
		
	}
 
 	 function stripBBcode($text){
		global $eqdkp;
		$text = trim($text);

		// BBCode to find...
    	$in = array( 	 '/\[b\](.*?)\[\/b\]/msi',	
    					 '/\[i\](.*?)\[\/i\]/msi',
    					 '/\[u\](.*?)\[\/u\]/msi',
						 '/\[img\](.*?)\[\/img\]/m',
						 '/\[code\](.*?)\[\/code\]/msi',
    					 '/\[email\](.*?)\[\/email\]/msi',
    					 '/\[url\="?(.*?)"?\](.*?)\[\/url\]/msi',
    					 '/\[size\="?(.*?)"?\](.*?)\[\/size\]/msi',
    					 '/\[color\="?(.*?)"?\](.*?)\[\/color\]/msi',
    					 '/\[quote](.*?)\[\/quote\]/msi',
    					 '/\[center](.*?)\[\/center\]/msi',
    					 '/\[left](.*?)\[\/left\]/msi',
    					 '/\[right](.*?)\[\/right\]/msi',
    					 '/\[list\=(.*?)\](.*?)\[\/list\]/msi',
    					 '/\[list\](.*?)\[\/list\]/msi',
    					 '/\[\*\]\s?(.*?)\n/msi',
    					 '/\[br\]/msi',
						 '/\[class\="?(.*?)"?\](.*?)\[\/class\]/msi',
      					 '/\[table](.*?)\[\/table\]/msi',
						 '/\[tr](.*?)\[\/tr\]/msi',
						 '/\[td](.*?)\[\/td\]/msi',
						 '/\[file](.*?)\[\/file\]/msi',
						 '/\[datei](.*?)\[\/datei\]/msi',
    	);
	      
      // And replace them by...
    	$out = array(	 '\1',
    					 '\1',
    					 '\1',
    					 '\1',
						 '\1',
						 '\1',
    					 '\2 (\1)',
    					 '\2',
    					 '\2',
    					 '\1',
    					 '\1',
    					 '\1',
    					 '\1',
    					 '\2',
    					 '\1',
    					 '\1',
    					 '',
						 '\2',
          				 '\1',
						 '\1',
						 '\1',
						 '\1',
						 '\1',
    	);
    	$text = preg_replace($in, $out, $text);
    	
   	
    	// paragraphs
    	$text = str_replace("\r", "", $text);
    	//$text = "<p>".ereg_replace("(\n){2,}", "</p><p>", $text)."</p>";
    	$text = nl2br($text);
	

    	$text = preg_replace_callback('/<pre>(.*?)<\/pre>/msi', array($this,"removeBr"), $text);
    	$text = preg_replace('/<p><pre>(.*?)<\/pre><\/p>/msi', "<pre>\\1</pre>", $text);
    	
    	$text = preg_replace_callback('/<ul>(.*?)<\/ul>/msi', array($this,"removeBr"), $text);
    	$text = preg_replace('/<p><ul>(.*?)<\/ul><\/p>/msi', "<ul>\\1</ul>", $text);
	
      return $text;
    } //END function stripBBcode
	

	
	function insert_into_queue($subject, $template, $bodyvars, $recipient_user, $recipient_mail, $template_path, $template_type = 'input',  $mail_method = 'php', $mail_type = "html"){
		global $db, $pdc;
		
		        $db->query("INSERT INTO __newsletter_queue :params", array(
                'subject' 			=> $subject,
                'template'  		=> $template,
                'bodyvars'  		=> serialize($bodyvars),
				'recipient_user' 	=> $recipient_user,
				'recipient_mail' 	=> $recipient_mail,
				'template_type'  	=> $template_type,
				'template_path'  	=> $template_path,
				'mail_method'  		=> $mail_method,
				'mail_type'			=> $mail_type,
        		));
		
		$pdc->del('plugin.newsletter.queue');
	} //END function insert_into_queue
	
	
	function send_from_queue(){
		global $db, $pdc, $eqdkp_root_path, $conf_plus;
		//Hack for 0.6
		include_once($eqdkp_root_path."libraries/MyMailer/MyMailer.class.php");
	
			if ($conf['queue_locked'] != 1){
				//Lock Queue
				$this->update_config(1, "queue_locked");
				$sql = $db->query("SELECT * FROM __newsletter_queue LIMIT 1");
				$sql_affected_rows = $db->affected_rows();
				
	
				//While there are mails to send
				while ($queue_data = $db->fetch_record($sql)){
					$options = array(
									 								 
						'sender_mail'		=> $conf_plus['lib_email_sender_email'],
						'mail_type'			=> $queue_data['mail_type'],
						'template_type'		=> $queue_data['template_type'],
						'sendmail_path'		=> $conf_plus['lib_email_sendmail_path'],
						'smtp_auth'			=> $conf_plus['lib_email_smtp_auth'],
						'smtp_host'			=> $conf_plus['lib_email_smtp_host'],
						'smtp_username'		=> $conf_plus['lib_email_smtp_user'],
						'smtp_password'		=> $conf_plus['lib_email_smtp_pw'],
					);
					
					$mailcl = new MyMailer($options);
		
					if ($queue_data['recipient_mail'] != "") {
			
						$recipient_mail = $queue_data['recipient_mail'];
		
					} else {
						if (is_numeric($queue_data['recipient_user'])) {
						$usermail_query = $db->query("SELECT * FROM __users WHERE user_id='".$db->escape($queue_data['recipient_user'])."'");
				
						$usermail = $db->fetch_record($usermail_query);
						$recipient_mail = $usermail['user_email'];

						};
					};
		
					//Send Mail

					if ($this->check_email($recipient_mail)){

						$status = $mailcl->SendMailFromAdmin($recipient_mail, $queue_data['subject'], $queue_data['template'], unserialize($queue_data['bodyvars']), $conf_plus['lib_email_method']);

						if ($status == true){

							$delete_query = "DELETE FROM __newsletter_queue WHERE id='".$db->escape($queue_data['id'])."'";
							$delete_query = $db->query($delete_query);
			
						};

					} else {
		
						$delete_query = "DELETE FROM __newsletter_queue WHERE id='".$db->escape($queue_data['id'])."'";
						$delete_query = $db->query($delete_query);
			
					};
		
				} //END While
					
				//Unlock queue
				$this->update_config(0, "queue_locked");
				return $sql_affected_rows;
			};
	
	
	
	} //END function send_from_queue
	
	
	function check_email($email) {
  		// RegEx begin
  		$nonascii      = "\x80-\xff"; # Non-ASCII-Chars are not allowed

  		$nqtext        = "[^\\\\$nonascii\015\012\"]";
 		$qchar         = "\\\\[^$nonascii]";

  		$protocol      = '(?:mailto:)';

  		$normuser      = '[a-zA-Z0-9][a-zA-Z0-9_.-]*';
  		$quotedstring  = "\"(?:$nqtext|$qchar)+\"";
  		$user_part     = "(?:$normuser|$quotedstring)";

  		$dom_mainpart  = '[a-zA-Z0-9][a-zA-Z0-9._-]*\\.';
  		$dom_subpart   = '(?:[a-zA-Z0-9][a-zA-Z0-9._-]*\\.)*';
  		$dom_tldpart   = '[a-zA-Z]{2,5}';
  		$domain_part   = "$dom_subpart$dom_mainpart$dom_tldpart";

  		$regex         = "$protocol?$user_part\@$domain_part";
  	// RegEx end

  		return preg_match("/^$regex$/",$email);
	} //END function check_email

	function generate_dkp_link(){
		global $eqdkp;
	$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($eqdkp->config['server_path']));
    	$script_name = ( $script_name != '' ) ? $script_name . '/' : '';
    	$server_name = trim($eqdkp->config['server_name']);
    	$server_port = ( intval($eqdkp->config['server_port']) != 80 ) ? ':' . trim($eqdkp->config['server_port']) . '/' : '/';
      	$dkp_link = 'http://' . $server_name . $server_port . $script_name;
		return $dkp_link;
	}
	
 } //END class
  

  
}
?>
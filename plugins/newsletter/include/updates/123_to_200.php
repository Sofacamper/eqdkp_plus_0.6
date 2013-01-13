<?PHP
  $new_version    = '2.0.0';
  $updateFunction = insert_sig();
  
  $updateSQL = array( 
"INSERT INTO __auth_options` (`auth_id`, `auth_value`, `auth_default`) VALUES ('572', 'u_newsletter_view', 'Y')",

"INSERT INTO __newsletter_config (config_name, config_value) VALUES ('enable_updatecheck', '1')",
"INSERT INTO __newsletter_config (config_name, config_value) VALUES ('public_subscribe', '1')",
"INSERT INTO __newsletter_config (config_name, config_value) VALUES ('public_archive', '1')",
"INSERT INTO __newsletter_config (config_name, config_value) VALUES ('signature', '')",
"INSERT INTO __newsletter_config (config_name, config_value) VALUES ('queue_locked', '0')",

"ALTER TABLE __newsletter_templates ADD `signature` TINYINT NOT NULL DEFAULT 0 AFTER `body` ;",

"CREATE TABLE IF NOT EXISTS __newsletter_queue (
						`id` int(10) NOT NULL auto_increment,
						`subject` TEXT,
						`template` TEXT,
						`template_type` varchar(30),
						`template_path` TEXT,
						`bodyvars` TEXT,
						`mail_method` varchar(25),
						`mail_type` varchar(25),
						`recipient_user` mediumint(5) NULL,
						`recipient_mail` varchar(255) NULL,
       					 PRIMARY KEY  (`id`)
						 )",

"CREATE TABLE IF NOT EXISTS __newsletter_archive (
					`id` INT PRIMARY KEY AUTO_INCREMENT,
					`letter_id` mediumint(5) NOT NULL default '0',
					`body` TEXT,
					`subject` TEXT,
					`recipients` TEXT,
					`date` DATETIME NOT NULL
		     )",

"CREATE TABLE IF NOT EXISTS __newsletter_userconfig (
      						`id` mediumint(8) unsigned NOT NULL auto_increment,
      						`config_name` varchar(255) NOT NULL default '',
      						`user_id` mediumint(5) NOT NULL default '0',
                  			`config_value` varchar(255) default NULL,
      						PRIMARY KEY  (id)
                  )",

"CREATE TABLE IF NOT EXISTS __newsletter_letters (
      						`id` mediumint(8) unsigned NOT NULL auto_increment,
      						`name` varchar(255) default NULL,
							`description` TEXT,
      						`permission` TINYINT NOT NULL DEFAULT 0,
							`archive` TINYINT NOT NULL DEFAULT 0,
							`preset_archive` TINYINT NOT NULL DEFAULT 0,
							`preset_type` VARCHAR(22),
							`preset_template` TINYINT NOT NULL DEFAULT 0,
      						PRIMARY KEY  (id)
              )",
 "CREATE TABLE IF NOT EXISTS __newsletter_subscribers (
      						`id` mediumint(8) unsigned NOT NULL auto_increment,
      						`user_id` mediumint(5) NOT NULL default '0',
							`letter_id` mediumint(5) NOT NULL default '0',
      						`status` TINYINT NOT NULL DEFAULT 0,
							`date` DATETIME NOT NULL,
      						PRIMARY KEY  (id)
                  )",
 
																																																																																																																																								);
  

$reloadSETT = 'settings.php';

function insert_sig()
  { 
    global $eqdkp_root_path, $pcache, $dbname, $user, $eqdkp, $db;
    
	//Create Signature:
		$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($eqdkp->config['server_path']));
    	$script_name = ( $script_name != '' ) ? $script_name . '/' : '';
    	$server_name = trim($eqdkp->config['server_name']);
    	$server_port = ( intval($eqdkp->config['server_port']) != 80 ) ? ':' . trim($eqdkp->config['server_port']) . '/' : '/';
      	$dkp_link = 'http://' . $server_name . $server_port . $script_name;
	
		$signature = "--\n";
		$signature .= $user->lang['nl_signature_value'];
		$signature .= $eqdkp->config['guildtag'];
		$signature .= "\nDKP: ";
		$signature .= $dkp_link;
		
		$db->query("INSERT INTO __newsletter_config (config_name, config_value) VALUES ('signature', '".$db->escape($dkp_link)."')");
		

   
  } // END function 

 
  

?>
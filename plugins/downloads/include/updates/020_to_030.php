<?PHP
  $new_version    = '0.3.0';
  $updateFunction = set_permissions(array('8005'));
  $updateSQL = array(
	"INSERT INTO __auth_options (`auth_id`, `auth_value`, `auth_default`) VALUES ('8005', 'a_downloads_stats', 'N')",

	"UPDATE __downloads_config SET config_name='enable_statistics_ov' WHERE config_name='enable_statistics'",	
	"INSERT INTO __downloads_config (config_name, config_value) VALUES ('enable_statistics', '1')",
	"INSERT INTO __downloads_config (config_name, config_value) VALUES ('single_vote', '1')",
	"INSERT INTO __downloads_config (config_name, config_value) VALUES ('prune_statistics', '')",
	"INSERT INTO __downloads_config (config_name, config_value) VALUES ('folder_limit', '')",
	"INSERT INTO __downloads_config (config_name, config_value) VALUES ('filesize_limit', '')",

	
	"DELETE FROM __downloads_config WHERE config_name='recaptcha_private_key'",
	"DELETE FROM __downloads_config WHERE config_name='recaptcha_public_key'",
	"DELETE FROM __downloads_config WHERE config_name='disable_mimecheck'",
	
	"ALTER TABLE __downloads_links ADD `reported` TEXT NULL AFTER `permission` ;",
	"ALTER TABLE __downloads_links ADD `voted_users` TEXT NULL AFTER `rating` ;",
	
	"CREATE TABLE __downloads_stats (
  				`ID` int(22) unsigned NOT NULL AUTO_INCREMENT,
  				`fileID` int(22) unsigned DEFAULT NULL,
  				`category` int(22) unsigned DEFAULT NULL,
  				`date` date DEFAULT NULL,
  				PRIMARY KEY (`ID`)
	)",
	);

 $reloadSETT = 'settings.php'; 
 
 function set_permissions($perm_array, $perm_setting='Y'){
		global $table_prefix, $db, $user;
		$userid = ( $user->data['user_id'] != ANONYMOUS ) ? $user->data['user_id'] : '';
		if($userid){
		  foreach ($perm_array as $value) {
		    $sql = $db->query("INSERT INTO `__auth_users` VALUES('".$db->escape($userid)."', '".$db->escape($value)."', '".$db->escape($perm_setting)."');");
    		$sql = $db->query("UPDATE `__auth_users` SET auth_setting='".$db->escape($perm_setting)."' WHERE user_id='".$db->escape($userid)."' AND auth_id='".$db->escape($value)."';");

  		}
		} 
	}
  

?>
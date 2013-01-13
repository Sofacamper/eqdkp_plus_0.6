<?PHP
  $new_version    = '0.0.2';
  $updateFunction = false;
  
  $updateSQL = array( 

"INSERT INTO __downloads_config (config_name, config_value) VALUES ('enable_related_links', '1')",
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('enable_comments', '1')",
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('traffic_limit', '')",
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('traffic_month', '0')",
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('traffic_total', '0')",
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('traffic_reset', '0')",
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('enable_recaptcha', '0')",
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('recaptcha_private_key', '')",
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('recaptcha_public_key', '')",
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('enable_statistics', '1')",
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('enable_updatecheck', '1')",
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('enable_debug', '0')",
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('items_per_page', '50')",
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('show_link_on_tab', '0')",


"DELETE FROM __downloads_config WHERE (`config_name`='enable_multiple_links') ;",
"ALTER TABLE __downloads_links CHANGE `description` `name` VARCHAR(255) DEFAULT NULL NULL;",
"ALTER TABLE __downloads_links CHANGE `comment` `description` TEXT NULL  ;",
"ALTER TABLE __downloads_links CHANGE `file_size` `file_size` INT(20) DEFAULT NULL NULL  ;",
"ALTER TABLE __downloads_links ADD `traffic` INT(20) DEFAULT '0' NOT NULL AFTER `file_size` ;",
"ALTER TABLE __downloads_links ADD `related_downloads` TEXT NULL AFTER `traffic` ;",
"ALTER TABLE __downloads_links ADD `mirrors` TEXT NULL AFTER `related_downloads` ;",
"ALTER TABLE __downloads_links ADD `votes` INT DEFAULT '0' NOT NULL AFTER `mirrors` ;",
"ALTER TABLE __downloads_links ADD `rating_points` INT DEFAULT '0' NOT NULL AFTER `votes` ;",
"ALTER TABLE __downloads_links ADD `rating` INT DEFAULT '0' NOT NULL AFTER `rating_points` ;",
"ALTER TABLE `eqdkp_downloads_links` DROP `parent_id` ;",
"DELETE FROM __downloads_links WHERE url=NULL",
"DELETE FROM __downloads_links WHERE file_type='mirror'",

																																																																																																																																								);
$reloadSETT = 'settings.php';


 
  

?>
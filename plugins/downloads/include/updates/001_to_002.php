<?PHP
  $new_version    = '0.0.2';
  $updateFunction = false;
  $updateSQL = array("INSERT INTO __downloads_config (config_name, config_value) VALUES ('disable_htacc_check', '0')", 
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('disable_categories', '0')", 
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('enable_mirrors', '1')", 
"INSERT INTO __downloads_config (config_name, config_value) VALUES ('enable_multiple_links', '1')",
"ALTER TABLE __downloads_categories CHANGE `category_sortid` `category_sortid` INT(11) NOT NULL",
"ALTER TABLE __downloads_categories CHANGE `category_comment` `category_comment` VARCHAR(255) DEFAULT NULL NULL  ;",
"ALTER TABLE __downloads_links CHANGE `url` `url` TEXT DEFAULT NULL",
"ALTER TABLE __downloads_links CHANGE `comment` `comment` TEXT;",
"ALTER TABLE __downloads_links CHANGE `category` `category` INT DEFAULT '0' NOT NULL",
"ALTER TABLE __downloads_links ADD `file_size` INT AFTER `file_type` ",
"ALTER TABLE __downloads_links ADD `parent_id` INT NOT NULL AFTER `file_size` ",
"ALTER TABLE __downloads_links ADD `user_id` INT NOT NULL AFTER `parent_id` ",
"ALTER TABLE __downloads_links CHANGE `permission` `permission` TINYINT DEFAULT '0' NOT NULL ",
																																																																																																																																								);
 $reloadSETT = 'settings.php'; 
  

?>
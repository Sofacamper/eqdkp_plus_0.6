<?PHP
  $new_version    = '2.0.3';
  $updateFunction = false;
  
  $updateSQL = array( 

"INSERT INTO __newsletter_config (config_name, config_value) VALUES ('bridge_items', '10')",
"INSERT INTO __newsletter_config (config_name, config_value) VALUES ('bridge_preselected', '0')",
"ALTER TABLE __newsletter_config CHANGE `config_value` `config_value` TEXT DEFAULT NULL  ;");
  

$reloadSETT = 'settings.php';



 
  

?>
<?PHP
  $new_version    = '2.0.2';
  $updateFunction = false;
  
  $updateSQL = array( 

"INSERT INTO __newsletter_config (config_name, config_value) VALUES ('style_css', '')",
"INSERT INTO __newsletter_config (config_name, config_value) VALUES ('style_bg_color', '')",
"INSERT INTO __newsletter_config (config_name, config_value) VALUES ('style_font_color', '')",

"DELETE FROM __newsletter_config WHERE config_name='mailmethod'",
"DELETE FROM __newsletter_config WHERE config_name='sender_email'",
"DELETE FROM __newsletter_config WHERE config_name='sender_name'",
"DELETE FROM __newsletter_config WHERE config_name='sendmail_path'",
"DELETE FROM __newsletter_config WHERE config_name='smtp_host'",
"DELETE FROM __newsletter_config WHERE config_name='smtp_username'",
"DELETE FROM __newsletter_config WHERE config_name='smtp_auth'",
"DELETE FROM __newsletter_config WHERE config_name='smtp_password'",
 
																																																																																																																																								);
  

$reloadSETT = 'settings.php';



 
  

?>
<?PHP
  $new_version    = '0.4.0';
  $updateFunction = set_permissions_032_040(array('8006'));
  $updateSQL = array(
	"INSERT INTO __auth_options (`auth_id`, `auth_value`, `auth_default`) VALUES ('8006', 'a_downloads_import', 'N')",

	);

 $reloadSETT = 'settings.php'; 
 
 function set_permissions_032_040($perm_array, $perm_setting='Y'){
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
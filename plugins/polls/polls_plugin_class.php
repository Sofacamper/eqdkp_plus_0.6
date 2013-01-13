<?PHP
/*********************************************************************************\
* Project:	EQdkp-Plus																														*
* License:	Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported *
* Link:			http://creativecommons.org/licenses/by-nc-sa/3.0/											*
* --------------------------------------------------------------------------------*
* Polls 4 EQdkp plus																															*
* --------------------------------------------------------------------------------*
* Project Start: 10/2009																													*
* Author: BadTwin																																	*
* Copyright: Andreas (BadTwin) Schrottenbaum																			*
* Link: http://badtwin.dyndns.org																									*
* Version: 0.0.1																																	*
\*********************************************************************************/

// prevent accessing this file directly
if (!defined('EQDKP_INC')){
	header('HTTP/1.0 404 Not Found');
	exit;
}

// define Class
class polls_plugin_class extends EQdkp_Plugin{
	var $version    = '1.0.4';
	var $copyright  = 'BadTwin';
	var $vstatus    = 'Stable';

	function polls_plugin_class($pm) {
		// use globals
		global $eqdkp_root_path, $user, $SID, $table_prefix;

		// call the parent's constructor
		$this->eqdkp_plugin($pm);

		// get language pack
		$this->pm->get_language_pack('polls');

		// data for this plugin
		$this->add_data(array(
			'name'					=> $user->lang['polls'],
			'code'					=> 'polls',
			'path'					=> 'polls',
			'contact'				=> 'badtwin@gmx.at',
			'template_path'	=> 'plugins/polls/templates/',
			'version'				=> $this->version,)
		);

		// Addition Information for eqdkpPLUS
		$this->additional_data = array(
			'author'						=> 'BadTwin',
			'description'				=> $user->lang['po_common_description'],
			'long_description'	=> $user->lang['po_common_long_description'],
			'homepage'					=> 'http://badtwin.dyndns.org',
			'manuallink'				=> false,
		);

		// Permissions
		$this->add_permission('8700', 'a_polls_manage',	'N', $user->lang['po_permissions_manage']);
		$this->add_permission('8701', 'u_polls_vote',		'Y', $user->lang['po_permissions_vote']);

		// Add Menus
		$this->add_menu('main_menu1', $this->gen_main_menu1());
		$this->add_menu('admin_menu', $this->gen_admin_menu());

		// Define installation.
		if (!($this->pm->check(PLUGIN_INSTALLED, 'polls'))){
			$perm_array = array('8700', '8701');
			$this->set_permissions($perm_array);
		}

		$sql = "CREATE TABLE IF NOT EXISTS __polls_settings (
			`config_name` VARCHAR(255) PRIMARY KEY NOT NULL,
			`config_value` VARCHAR(255) NOT NULL
		)";
		$this->add_sql(SQL_INSTALL, $sql);
		$this->InsertIntoTable('po_multiple', '0');
		$this->InsertIntoTable('po_comments', '1');
		$this->InsertIntoTable('po_editable', '0');
		$this->InsertIntoTable('po_intermed', '1');
		$this->InsertIntoTable('po_modstyle', '1');
		$this->InsertIntoTable('po_updcheck', '1');

		$sql = "CREATE TABLE IF NOT EXISTS __polls (
			`id` INT PRIMARY KEY AUTO_INCREMENT,
		  `title` varchar(255) NOT NULL,
			`description` TEXT default '',
			`multiple` ENUM('1', '0') default '0',
			`comments` ENUM('1', '0') default '0',
			`editable` ENUM('1', '0') default '0',
			`intermed` ENUM('1', '0') default '0',
			`modstyle` ENUM('1', '2') default '1',
			`enddate` VARCHAR(255) NULL,
		  `closed` ENUM('1', '0') default '0'
		)";
		$this->add_sql(SQL_INSTALL, $sql);

		$sql = "CREATE TABLE IF NOT EXISTS __polls_options(
			`id` INT PRIMARY KEY AUTO_INCREMENT,
			`poll_id` INT NOT NULL,
			`option` VARCHAR(255) NOT NULL
		)";
		$this->add_sql(SQL_INSTALL, $sql);

		$sql = "CREATE TABLE IF NOT EXISTS __polls_votes(
			`id` INT PRIMARY KEY AUTO_INCREMENT,
			`poll_id` INT NOT NULL,
			`opt_id` INT NOT NULL,
			`user_id` INT NOT NULL
		)";
		$this->add_sql(SQL_INSTALL, $sql);

		// Define uninstallation
		$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __polls_settings");
		$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __polls");
		$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __polls_options");
		$this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS __polls_votes");
  }

	// generate the Main Menu
	function gen_main_menu1(){
		global $user, $SID;
		// check if its enabled
		if ($this->pm->check(PLUGIN_INSTALLED, 'polls') && $user->data['user_id'] != ANONYMOUS){
			$main_menu1 = array(array(
				'link' => 'plugins/polls/polls.php' . $SID,
				'text' => $user->lang['polls'],
				'check' => 'u_polls_vote'
			));
			return $main_menu1;
		}
		return;
	}

	/**
	* Generate admin menu
	*
	* @return array
	*/
	function gen_admin_menu(){
		global $user, $SID, $eqdkp, $eqdkp_root_path;
			$url_prefix = ( EQDKP_VERSION < '1.3.2' ) ? $eqdkp_root_path : '';
			if ($this->pm->check(PLUGIN_INSTALLED, 'polls')){
				global $db, $user, $eqdkp_root_path;
				$admin_menu = array(
					'polls' => array(
						0 => $user->lang['polls'],
						1 => array(
							'link' => $url_prefix . 'plugins/polls/admin/settings.php' . $SID,
							'text' => $user->lang['po_ad_men_settings'],
							'check' => 'a_polls_manage'),
						2 => array(
							'link' => $url_prefix . 'plugins/polls/admin/editpolls.php' . $SID,
							'text' => $user->lang['po_ad_men_edit'],
							'check' => 'a_polls_manage'),
						3 => array(
							'link' => $url_prefix . 'plugins/polls/admin/createpoll.php' . $SID,
							'text' => $user->lang['po_ad_men_create'],
							'check' => 'a_polls_manage'),
						99 => './../../plugins/polls/images/admin_icon.png',
					)
				);
				return $admin_menu;
			}
		return;
	}

	/***************************************
	* Set the perm. for installing user    *
	* @return --                           *
	****************************************/
	function set_permissions($perm_array, $perm_setting='Y'){
		global $table_prefix, $db, $user;
		$userid = ( $user->data['user_id'] != ANONYMOUS ) ? $user->data['user_id'] : '';
		if($userid){
			foreach ($perm_array as $value) {
				$sql = "INSERT INTO `__auth_users` VALUES('".$db->escape($userid)."', '".$db->escape($value)."', '".$db->escape($perm_setting)."');";
				$this->add_sql(SQL_INSTALL, $sql);
				$sql = "UPDATE `__auth_users` SET auth_setting='".$db->escape($perm_setting)."' WHERE user_id='".$db->escape($userid)."' AND auth_id='".$db->escape($value)."';";
				$this->add_sql(SQL_INSTALL, $sql);
			}
		}
	}

	/**********************************************
	* Write the Standard Settings to the Database *
	***********************************************/
	function InsertIntoTable($fieldname, $insertvalue){
		global $db;
    $sql = "INSERT INTO __polls_settings VALUES ('".$db->escape($fieldname)."', '".$db->escape($insertvalue)."');";
	  $this->add_sql(SQL_INSTALL, $sql);
  }
}
?>

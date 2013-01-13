<?PHP
/*
* Project:     EQdkp-Plus
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
* ----------------------------------------------------------------------------------
* Gallery 4 EQdkp plus
* ----------------------------------------------------------------------------------
* Project Start: 09/2008
* Author: BadTwin
* Copyright: Andreas (BadTwin) Schrottenbaum
* Link: http:// bloody-midnight.eu
* Version: 2.0.11
* ----------------------------------------------------------------------------------
* Based on the HelloWorld Plugin by Wallenium
*
* $Id: $
*/


// prevent accessing this file directly

if (!defined('EQDKP_INC')){
	header('HTTP/1.0 404 Not Found');
	exit;
}

// Set table names
if (!defined('GL_CATEGORIES_TABLE')) { define('GL_CATEGORIES_TABLE', $table_prefix . 'gallery_categories'); }
if (!defined('GL_PICTURES_TABLE')) { define('GL_PICTURES_TABLE', $table_prefix . 'gallery_pictures'); }
if (!defined('GL_COMMENTS_TABLE')) { define('GL_COMMENTS_TABLE', $table_prefix . 'gallery_comments'); }


// define Class
class gallery_plugin_class extends EQdkp_Plugin{
  var $version    = '2.1.4';
  var $copyright  = 'BadTwin';
  var $vstatus    = 'Stable';
  var $build      = '5912';

    function gallery_plugin_class($pm)
    {
    // use globals
      global $eqdkp_root_path, $user, $SID, $table_prefix;

    // call the parent's constructor
    $this->eqdkp_plugin($pm);

    // get language pack
    $this->pm->get_language_pack('gallery');

    // data for this plugin
    $this->add_data(array(
            'name'					=> 'EQdkp Plus Gallery',
            'code'					=> 'gallery',
            'path'					=> 'gallery',
            'contact'				=> 'eqdkp-gallery@guensel.net',
            'template_path'			=> 'plugins/gallery/templates/',
            'version'				=> $this->version,)
    );

    // Addition Information for eqdkpPLUS
    $this->additional_data = array(
            'author'            => 'BadTwin & Lunary',
	          'description'     => $user->lang['gl_shortdesc'],
            'long_description'  => $user->lang['gl_description'],
            'homepage'          => 'http://www.eqdkp-plus.com',
            'manuallink'        => false,
      );

	// Register permissions... this is for the permissions manager. the permission ID
	// should not be used by ANY other plugin!
	// permissions of admins starts with "a_", for users with "u_"

      // (ID, Name, Enables Y/N, Language cariable)
      $this->add_permission('8847', 'a_gallery_pics',  'N', $user->lang['gl_ad_manage_pictures_ov']);
      $this->add_permission('8848', 'a_gallery_cat',  'N', $user->lang['gl_ad_manage_categories_ov']);
	    $this->add_permission('8851', 'a_gallery_cfg', 'N', $user->lang['gl_ad_manage_config_ov']);
      $this->add_permission('8849', 'u_gallery_view', 'Y', $user->lang['gl_view']);
	    $this->add_permission('8850', 'u_gallery_upload', 'N', $user->lang['gl_upload']);

      // Add Menus
      // Name of menu/function (see above)
			$this->add_menu('main_menu1', $this->gen_main_menu1());    // This is the main Menu
			$this->add_menu('admin_menu', $this->gen_admin_menu());    // This is the admin Menu

      // Define installation.
      // -----------------------------------------------------
      if (!($this->pm->check(PLUGIN_INSTALLED, 'gallery'))){
        $perm_array = array('8847', '8848', '8849', '8850', '8851');
    		$this->set_permissions($perm_array);
  		}

		$sql = "CREATE TABLE IF NOT EXISTS " . $table_prefix . "gallery_categories (
			`category_id` INT PRIMARY KEY AUTO_INCREMENT,
			`category_name` VARCHAR(255) NOT NULL,
			`category_comment` VARCHAR(255)
		       )";
		  $this->add_sql(SQL_INSTALL, $sql);
		  $this->InsertNewCategory('Standard', 'Standard');

		$sql = "CREATE TABLE IF NOT EXISTS " . $table_prefix . "gallery_pictures (
			`id` INT PRIMARY KEY AUTO_INCREMENT,
			`user_id` VARCHAR(255) NOT NULL,
			`filename` VARCHAR(255) NOT NULL,
			`description` VARCHAR(255),
			`views` VARCHAR(255) NOT NULL DEFAULT '0',
			`category` INT NOT NULL,
			`date` DATETIME NOT NULL,
			`comment` TEXT,
			`votes` TEXT NULL,
			`rating_points` TEXT NULL,
			`rating` TEXT NULL,
			`voted_users` TEXT NULL
		       )";
		  $this->add_sql(SQL_INSTALL, $sql);

		$sql = "CREATE TABLE IF NOT EXISTS " .$table_prefix ."gallery_config (
        `config_name` VARCHAR(255) NOT NULL,
        `config_value` VARCHAR(255) NULL,
        PRIMARY KEY ( `config_name` )
            )";
      $this->add_sql(SQL_INSTALL, $sql);
      $this->InsertIntoTable('mthumbs', '15');
      $this->InsertIntoTable('categories', '4');
      $this->InsertIntoTable('textstamp', 'powered by EQdkp-Plus');
      $this->InsertIntoTable('extern', '0');
      $this->InsertIntoTable('inst_version', $this->version);
      $this->InsertIntoTable('limit', '0');

      // Define uninstallation
      // -----------------------------------------------------
	    $this->add_sql(SQL_UNINSTALL, "DROP TABLE IF EXISTS " . $table_prefix . "gallery_config");
  }

  // generate the Main Menu
  function gen_main_menu1(){
    global $user, $SID, $db, $eqdkp;

    // check if its enabled
    if ($this->pm->check(PLUGIN_INSTALLED, 'gallery')){
      // Start the Menu array
      $main_menu1 = array(array(
    						'link' => 'plugins/gallery/gallery.php' . $SID,
    						'text' => $user->lang['gl_view'],
    						'check' => 'u_gallery_view'
    					  )
    					 );
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
        if ($this->pm->check(PLUGIN_INSTALLED, 'gallery')){
          global $db, $user, $eqdkp_root_path;

    			$admin_menu = array(
    				'gallery' => array(
    					0 => $user->lang['gallery'],
    					1 => array(
    						'link' => $url_prefix . 'plugins/gallery/admin/categories.php' . $SID,
    						'text' => $user->lang['gl_ad_manage_categories'],
    						'check' => 'a_gallery_cat'),
    					2 => array(
    						'link' => $url_prefix . 'plugins/gallery/admin/pictures.php' . $SID,
    						'text' => $user->lang['gl_ad_manage_pictures'],
    						'check' => 'a_gallery_pics'),
    					3 => array(
                'link' => $url_prefix . 'plugins/gallery/admin/settings.php' . $SID,
                'text' => $user->lang['gl_ad_manage_config'],
                'check' => 'a_gallery_cfg'),
    					99 => './../../plugins/gallery/images/admin_logo.png',
   				  )
    			 );

          return $admin_menu;
        }
      return;
    }

   function InsertIntoTable($fieldname,$insertvalue){
   		global $eqdkp_root_path, $user, $SID, $table_prefix;
		  $sql = "INSERT INTO " . $table_prefix . "gallery_config VALUES ('".$fieldname."', '".$insertvalue."');";
		  $this->add_sql(SQL_INSTALL, $sql);
   }

   function InsertNewCategory($fieldname,$insertvalue){
   		global $eqdkp_root_path, $user, $SID, $table_prefix;
		  $sql = "INSERT INTO " . $table_prefix . "gallery_categories (category_name, category_comment) VALUES ('".$fieldname."', '".$insertvalue."');";
		  $this->add_sql(SQL_INSTALL, $sql);
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
		    $sql = "INSERT INTO `".$table_prefix."auth_users` VALUES('".$userid."', '".$value."', '".$perm_setting."');";
    		$this->add_sql(SQL_INSTALL, $sql);
    		$sql = "UPDATE `".$table_prefix."auth_users` SET auth_setting='".$perm_setting."' WHERE user_id='".$userid."' AND auth_id='".$value."';";
    		$this->add_sql(SQL_INSTALL, $sql);
  		}
		}
	}
}
?>

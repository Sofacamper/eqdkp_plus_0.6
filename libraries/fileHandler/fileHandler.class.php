<?php
 /*
 * Project:     eqdkpPLUS Libraries: cacheHandler
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2009
 * Date:        $Date: 2010-12-02 21:58:52 +0100 (Thu, 02 Dec 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2009 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     libraries:cacheHandler
 * @version     $Rev: 9332 $
 * 
 * $Id: fileHandler.class.php 9332 2010-12-02 20:58:52Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

if (!class_exists("fileHandler")) {
  class fileHandler
  {
    var $errors   = array();
    var $version  = '1.1.0';
    
    /**
    * Initiate the cacheHandler
    */
    public function __construct(){
      global $dbname, $eqdkp_root_path;
      $this->safe_mode = false;
      $tmpchdir = $eqdkp_root_path.'data/';
      if(is_writable($tmpchdir)){
        $this->CacheFolder = $tmpchdir.md5($dbname).'/';
        $this->CacheFolder2 = 'data/'.md5($dbname).'/';
        $this->CheckCreateFolder($this->CacheFolder);
      }else{
        $this->errors[] = 'lib_cache_notwriteable';
      }
      if(ini_get('safe_mode') == '1'){
        $this->safe_mode = true;
      }
    }
    
    /**
    * Test if a file could be written
    */
    public function CheckWrite(){
      $write = false;
      if($this->safe_mode){
        $fp = @fopen($this->CacheFolder.'test_file', 'wb');
        if ($fp !== false){
          $write = true;
        }
        @fclose($fp);
        @unlink($this->CacheFolder.'test_file');
      }
      return $write;
    }
    
    /**
    * Build an eqdkp Link for rss syndication and others
    */
    public function BuildLink(){
      global $eqdkp;
      if(!$this->dkplink){
	      $script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($eqdkp->config['server_path']));
	      $script_name = ( $script_name != '' ) ? $script_name . '/' : '';
	      $server_name = trim($eqdkp->config['server_name']);
	      $server_port = ( intval($eqdkp->config['server_port']) != 80 ) ? ':' . trim($eqdkp->config['server_port']) . '/' : '/';
	      $this->dkplink = 'http://' . $server_name . $server_port . $script_name;
      }
      return $this->dkplink;
    }
    
    /**
    * Check if a Folder is available or must be created
    */
    // eigentlich private... aber hoofy nutzt es...
    public function CheckCreateFolder($path){
      if(!is_dir($path)){
        umask(0000);
        mkdir($path, 0777, true);
      }
    }
    
    /**
    * Check if a File is available or must be created
    */
    public function CheckCreateFile($path, $createFile){
      if(!is_file($path) && $createFile){
        $myhandl = fopen($path, "a");
        fclose($myhandl);
      }
      if(is_file($path)){
        @chmod($path, 0777);
      }
    }
    
    /**
    * Delete a File/Folder
    */
    public function Delete($dir, $deletefolder=false){
			if(!$dh = @opendir($dir)){
				return;
			}
			while (false !== ($obj = readdir($dh))) {
				if($obj=='.' || $obj=='..'){
					continue;
				}
				if (!@unlink($dir.'/'.$obj)){
					$this->Delete($dir.'/'.$obj, true);
				}
			}
			closedir($dh);
			if ($deletefolder){
				@rmdir($dir);
			}
    }
    
    
    /**
    * Check if the cache is writable
    */
    public function CacheWritable(){
      return (is_array($this->errors) && count($this->errors) > 0) ? false : true;
    }
    
    /**
    * If you want to move a file..
    */
    public function FileMove($filename, $tofile) {
      copy ($filename, $tofile);
      unlink ($filename);
      @chmod($tofile, 0777);
    }
    
    /**
    * Return a path to the file
    * 
    * @param $filename    The name of the file
    * @param $plugin      Plugin name, p.e. 'raidplan'
    * @param $createFile  Should the file be created on check if not available?    
    * @return Link to the file
    */
    public function FilePath($filename, $plugin='', $createFile=true){
      if(!$filename){ return ''; }
      if($plugin != ""){
        $tmpfolder = $this->CacheFolder.$plugin;
        $this->CheckCreateFolder($tmpfolder);
        $tmpfilelink=$tmpfolder.'/'.$filename;
        $this->CheckCreateFile($tmpfilelink, $createFile);
        return $tmpfilelink;
      }else{
        return $this->CacheFolder.$filename;
      }
    }
    
    /**
    * Checks if a file is available or not
    * 
    * @param $filename    The name of the file
    * @param $plugin      Plugin name, p.e. 'raidplan'
    * @return 1/0
    */
    public function FileExists($filename, $plugin=''){
      if(is_file($this->FilePath($filename, $plugin, false))){
        return 1;
      }else{
        return 0;
      }
    }
    
    /**
    * Return a file link to the file
    * 
    * @param $filename    The name of the file
    * @param $plugin      Plugin name, p.e. 'raidplan'
    * @return Link to the file
    */
    public function FileLink($filename, $plugin='', $folder=''){
      $realfolder = ($folder) ? $folder.'/' : '';
      if($plugin != ""){
        return $this->CacheFolder2.$plugin.'/'.$realfolder.$filename;
      }else{
        return $this->CacheFolder2.$realfolder.$filename;
      }
    }
  	
  	/**
    * Return a path to a folder
    * 
    * @param $filename    The name of the file
    * @param $plugin      Plugin name, p.e. 'raidplan'
    * @return Link to the file
    */
  	public function FolderPath($foldername, $plugin=''){
  		$tmpfolder = ($plugin) ? $this->CacheFolder.$plugin : $this->CacheFolder;
  		$this->CheckCreateFolder($tmpfolder);
  		
      if(is_array($foldername)){
      	$mytmpfoldr = $tmpfolder;
      	foreach($foldername as $fname){
      		$mytmpfoldr = $mytmpfoldr.'/'.$fname;
      		$this->CheckCreateFolder($mytmpfoldr);
      	}
      	$myFolders = implode("/",$foldername);
      }else{
      	$myFolders = $foldername;
      }
      
      $tmpfilelink=$tmpfolder.'/'.$myFolders.'/';
      $this->CheckCreateFolder($tmpfilelink);
      return $tmpfilelink;
    }
  }
}
?>

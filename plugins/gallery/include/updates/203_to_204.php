<?PHP
  $new_version    = '2.0.4';
  $updateFunction = move_pics_204();
  $updateSQL = array("INSERT INTO __gallery_config (config_name, config_value) VALUES ('textstamp', 'powered by EQdkp-Plus');");
  
  function move_pics_204()
  { // BEGIN function move_pics
    global $eqdkp_root_path, $pcache, $dbname;
    

    if (is_dir($eqdkp_root_path.'plugins/gallery/upload/'.md5($dbname))) {
      $folder = $eqdkp_root_path.'plugins/gallery/upload/'.md5($dbname);
    } else if (is_dir($eqdkp_root_path.'plugins/gallery/upload/')) {
      $folder = $eqdkp_root_path.'plugins/gallery/upload/';
    }

    $handle = opendir($folder);
    while($file = readdir($handle)){
      if($file != '.' && $file != '..'){
        if(!is_dir($folder.$file)){
          // copy and delete
          $pcache->FileMove($folder.$file, $pcache->FolderPath('upload', 'gallery').$file);
          $pcache->FileMove($folder.'thumb/'.$file, $pcache->FolderPath('upload/thumb', 'gallery').$file);
          $pcache->FileMove($folder.'mthumb/'.$file, $pcache->FolderPath('upload/mthumb', 'gallery').$file);          
        }
      }        
    }    	
    closedir($handle);
    rmdir($folder.'thumb/');
    rmdir($folder.'mthumb/');
    rmdir($folder);  	
  } // END function move_pics
?>
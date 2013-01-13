<?PHP
  $new_version    = '0.1.3';
  $updateFunction =  move_files_013();
  
  $updateSQL = array( 

"ALTER TABLE __downloads_links ADD `preview_image` VARCHAR(255) NULL AFTER `file_size` ;"

																																																																																																																																								);
    function  move_files_013()
  { // BEGIN function move_files
    global $eqdkp_root_path, $pcache, $dbname;
    

    if (is_dir($pcache->FolderPath('', 'downloads'))) {
      $folder = $pcache->FolderPath('', 'downloads');
    };

    $handle = opendir($folder);
    while($file = readdir($handle)){
      if($file != '.' && $file != '..'){
        if(!is_dir($folder.$file)){
          // copy and delete
          $pcache->FileMove($folder.$file, $pcache->FolderPath('files', 'downloads').$file);
      
        }
      }        
    }    	
    closedir($handle);
    rmdir($folder);  	
  } // END function move_files
  
$reloadSETT = 'settings.php';


 
  

?>
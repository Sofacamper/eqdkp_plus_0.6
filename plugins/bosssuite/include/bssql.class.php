<?php
 /*
 * Project:     BossSuite v4 MGS
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2010-06-17 23:52:24 +0200 (Thu, 17 Jun 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: sz3 $
 * @copyright   2006-2008 sz3
 * @link        http://eqdkp-plus.com
 * @package     bosssuite
 * @version     $Rev: 8110 $
 *
 * $Id: bssql.class.php 8110 2010-06-17 21:52:24Z sz3 $
 */

if ( !defined('EQDKP_INC') )
{
     die('Do not access this file directly.');
}

if ( !class_exists( "BSSQL" ) ) {
  /**
  * BSSQL class
  * BossSuite Base SQL class
  */
  class BSSQL{
      
      //get default values
      function reset_to_defaults($plugin){
      global $eqdkp, $user, $pm;
        $game_arr = explode('_', $eqdkp->config['default_game']);

        if (file_exists(dirname(__FILE__).'/../games/'.$game_arr[0].'/defaults.php')){
          require(dirname(__FILE__).'/../games/'.$game_arr[0].'/defaults.php');
        }
        
        $defaults = $defaults[$plugin];//array_merge($gi_defaults, $defaults[$plugin]); 
        foreach ($defaults as $key => $value){
          $this->update_config($plugin, array(), $key, $value);
        }
        $retvals = array();
        foreach ($defaults as $key => $value){
          $retvals[substr($key,3)] = $value;
        }
        return $retvals;
      }
        
      // Save or add values to the database.
      function update_config($plugin, $confarr, $fieldname, $insertvalue) {
      global $db;         
        if(array_key_exists( substr($fieldname,3), $confarr )){
          $sql = "UPDATE __bs_config SET config_value='".strip_tags(htmlspecialchars($insertvalue))."' WHERE config_name='".$fieldname."';";
        	$db->query($sql);
        }else{
        	$sql = "INSERT INTO __bs_config VALUES('".$fieldname."', '".strip_tags(htmlspecialchars($insertvalue))."');";	
        	$db->query($sql);
       	}
      }
      
      function get_config($plugin) {
      global $db;
        $def_count = 0;
        switch ($plugin) {
          case "bossbase":
              $prefix = 'bb_';
              $def_count = 2;
              break;
          case "bossprogress":
              $prefix = 'bp_';
              break;
          case "bossloot":
              $prefix = 'bl_';
              break;
          case "bosscounter":
              $prefix = 'bc_';
              break;
          default:
              $prefix = 'bb_';
        }
        
      	$sql = "SELECT * FROM __bs_config WHERE config_name LIKE '".$prefix."%';";
      	if (!($settings_result = $db->query($sql))) {
      		message_die('Could not obtain '.$plugin.' configuration data', '', __FILE__, __LINE__, $sql);
      	}
      
      	while($roww = $db->fetch_record($settings_result)) { 
      	   		$conf[substr($roww['config_name'],3)] = $roww['config_value'];
      	}	
      	
      	if (count($conf) > $def_count)
      	   return $conf;
      	else{
      	   $sql = "SELECT * FROM __bs_config";
      	   if (!($count_result = $db->query($sql))) {
      		    message_die('Could not obtain '.$plugin.' configuration data', '', __FILE__, __LINE__, $sql);
      	   }
      	   $count = $db->fetch_record($count_result);
      	   if(empty($count) || count(array_keys($count)) == 2)
      	     return $this->reset_to_defaults($plugin);
      	}
      }
      
      function get_parse_zone(){
        global $eqdkp, $db;

      	$sql = 'SELECT zone_id, zone_string FROM __bs_data_zone';
      	if (!($settings_result = $db->query($sql))) {
      		message_die('Could not obtain zone string data', '', __FILE__, __LINE__, $sql);
      	}
      
      	while($roww = $db->fetch_record($settings_result)) { 
      	   		$conf['pz_'.$roww['zone_id']] = $roww['zone_string'];
      	}	
      	
      	if (!empty($conf))
      	   return $conf;
      	else{
          $this->reset_zones();
          return $this->get_parse_zone();
        }
      }
      
      function update_parse_zone($confarr, $fieldname, $value){
        global $eqdkp, $user, $db;
      
	      if(array_key_exists( 'pz_'.$fieldname, $confarr )){
	        $sql = "UPDATE __bs_data_zone SET zone_string='".strip_tags(htmlspecialchars($value))."' WHERE zone_id='".$fieldname."';";
	        $db->query($sql);
	      }else{
	      	$sql = "INSERT INTO __bs_data_zone VALUES('".$fieldname."', '".strip_tags(htmlspecialchars($value))."','0','".BS_MAX_DATE."','".BS_MIN_DATE."','1','1');";
	      	$db->query($sql);
	      }
      
      }     
      
      function get_parse_boss(){
        global $eqdkp, $db;
      
      	$sql = 'SELECT boss_id, boss_string FROM __bs_data_boss';
      	if (!($settings_result = $db->query($sql))) {
      		message_die('Could not obtain boss string data', '', __FILE__, __LINE__, $sql);
      	}
      
      	while($roww = $db->fetch_record($settings_result)) { 
      	   		$conf['pb_'.$roww['boss_id']] = $roww['boss_string'];
      	}	
      	
      	if (!empty($conf))
      	   return $conf;
      	else{
          $this->reset_bosses();
          return $this->get_parse_boss();
        }
      }
      
      function update_parse_boss($confarr, $fieldname, $value){
        global $eqdkp, $user, $db;
      
      if(array_key_exists( 'pb_'.$fieldname, $confarr )){
        $sql = "UPDATE __bs_data_boss SET boss_string='".strip_tags(htmlspecialchars($value))."' WHERE boss_id='".$fieldname."';";
      	$db->query($sql);
      }else{
      	$sql = "INSERT INTO __bs_data_boss VALUES('".$fieldname."', '".strip_tags(htmlspecialchars($value))."','0','".BS_MAX_DATE."','".BS_MIN_DATE."');";	
      	$db->query($sql);
      	}
      }
      
      function get_boss_offsets(){
        global $eqdkp, $db;
      
      	$sql = 'SELECT boss_id, boss_co_offs, boss_fd_offs, boss_ld_offs FROM __bs_data_boss';
      	if (!($settings_result = $db->query($sql))) {
      		message_die('Could not obtain boss offset data', '', __FILE__, __LINE__, $sql);
      	}
      
      	while($roww = $db->fetch_record($settings_result)) { 
              $conf[$roww['boss_id']]['counter'] = $roww['boss_co_offs'];
      	   		$conf[$roww['boss_id']]['fd'] = $roww['boss_fd_offs'];
      	   		$conf[$roww['boss_id']]['ld'] = $roww['boss_ld_offs'];
      	}	
      	
      	if (!empty($conf)){
      	   return $conf;
      	}else{
          $this->reset_bosses();
          return $this->get_boss_offsets();
        }
      }
      
      function get_zone_offsets(){
        global $eqdkp, $db;
      
      	$sql = 'SELECT zone_id, zone_co_offs, zone_fd_offs, zone_ld_offs FROM __bs_data_zone';
      	if (!($settings_result = $db->query($sql))) {
      		message_die('Could not obtain zone offset data', '', __FILE__, __LINE__, $sql);
      	}
      
      	while($roww = $db->fetch_record($settings_result)) { 
      	   		$conf[$roww['zone_id']]['counter'] = 0 + $roww['zone_co_offs'];
      	   		$conf[$roww['zone_id']]['fd'] = $roww['zone_fd_offs'];
      	   		$conf[$roww['zone_id']]['ld'] = $roww['zone_ld_offs'];
      	}	
      	
      	if (!empty($conf)){
      	   return $conf;
      	}else{
          $this->reset_zones();
          return $this->get_zone_offsets();
        }
      }

      function reset_bosses(){
      global $user, $db;
        $bzone = $this->get_bzone();
	      foreach ($bzone as $zone => $bosses){
	        foreach ($bosses as $boss){
            if (strcmp($user->lang[$boss]['long'], $user->lang[$boss]['short'])){
				      $bossstring = "''". str_replace("'", "''", $user->lang[$boss]['long']) . "'', ''" .  str_replace("'", "''", $user->lang[$boss]['short']) . "''";
			      }else{
				      $bossstring = "''". str_replace("'", "''", $user->lang[$boss]['long']) . "''";
			      }   
      	    $sql = "INSERT INTO __bs_data_boss VALUES('".$boss."', '".strip_tags(htmlspecialchars($bossstring))."','0','".BS_MAX_DATE."','".BS_MIN_DATE."');";	
    	      if($db->query($sql) === false)
              message_die("Couldn't reset/initialise bosses. See sql debug statement.");
          }
        }
      }
      
      function reset_zones(){
      global $user, $db;
        $bzone = $this->get_bzone();
	      foreach ($bzone as $zone => $bosses){
          if (strcmp($user->lang[$zone]['long'], $user->lang[$zone]['short'])){
			      $zonestring = "''". str_replace("'", "''", $user->lang[$zone]['long']) . "'', ''" .  str_replace("'", "''", $user->lang[$zone]['short']) . "''";
		      }else{
			      $zonestring = "''". str_replace("'", "''", $user->lang[$zone]['long']) . "''";
		      }   
    	    $sql = "INSERT INTO __bs_data_zone VALUES('".$zone."', '".strip_tags(htmlspecialchars($zonestring))."','0','".BS_MAX_DATE."','".BS_MIN_DATE."','1','1');";	
    	    if($db->query($sql) === false)
            message_die("Couldn't reset/initialise zones. See sql debug statement.");
        }
      }
            
      function update_boss_offsets($offsets, $id, $fdate, $ldate, $counter){
      global $eqdkp, $user, $db;
      
      if(array_key_exists( $id, $offsets )){
        $sql = "UPDATE __bs_data_boss SET boss_co_offs='".$counter."', boss_ld_offs='".$ldate."', boss_fd_offs='".$fdate."' WHERE boss_id='".$id."';";
      	$db->query($sql);
      }else{
      	$sql = "INSERT INTO __bs_data_boss VALUES('".$id."', '', '".$counter."', '".$fdate."', '".$ldate."');";	
      	$db->query($sql);
      	}
      }
      
      function update_zone_offsets($offsets, $id, $fdate, $ldate, $counter){
      global $eqdkp, $user, $db;
      
      if(array_key_exists( $id, $offsets )){
        $sql = "UPDATE __bs_data_zone SET zone_co_offs='".$counter."', zone_ld_offs='".$ldate."', zone_fd_offs='".$fdate."' WHERE zone_id='".$id."';";
      	$db->query($sql);
      }else{
      	$sql = "INSERT INTO __bs_data_zone VALUES('".$id."', '', '".$counter."', '".$fdate."', '".$ldate."');";	
      	$db->query($sql);
      	}
      }

      function get_bzone($plugin='all'){
       global $db, $eqdkp, $user;
       
       $game_arr = explode('_', $eqdkp->config['default_game']);
       $game = $game_arr[0];
       
    	 require(dirname(__FILE__).'/../games/'.$game.'/bzone.php');
    	 if ($plugin == 'all'){
	       return $bzone;
	     } else if ($plugin == 'bossprogress'){   
         $sql = 'SELECT zone_id, zone_sz_bp FROM __bs_data_zone';
         $settings_result = $db->query($sql);
         while($roww = $db->fetch_record($settings_result)) { 
           $szarr[$roww['zone_id']] = $roww['zone_sz_bp'];
         }
         $sbzone = array();
         foreach($bzone as $zone => $bosses){
		       if ($szarr[$zone] == true){
			       $sbzone[$zone] = $bosses;
		       }
	       }	
      	 return $sbzone;
       } else if ($plugin == 'bosscounter'){
         $sql = 'SELECT zone_id, zone_sz_bc FROM __bs_data_zone';
         $settings_result = $db->query($sql);
         while($roww = $db->fetch_record($settings_result)) { 
           $szarr[$roww['zone_id']] = $roww['zone_sz_bc'];
         }
         $sbzone = array();
         foreach($bzone as $zone => $bosses){
		       if ($szarr[$zone] == true){
			       $sbzone[$zone] = $bosses;
		       }
	       }	
      	 return $sbzone;
       }      
       return $bzone;  
    }
    
    function update_zone_visibility($plugin, $zoneid, $value){
      global $eqdkp, $user, $db;
      
        if ( $plugin == 'bossprogress'){
          $sql = "UPDATE __bs_data_zone SET zone_sz_bp='".$value."' WHERE zone_id='".$zoneid."';";
      	  $db->query($sql);
      	}else if ( $plugin == 'bosscounter'){
          $sql = "UPDATE __bs_data_zone SET zone_sz_bc='".$value."' WHERE zone_id='".$zoneid."';";
      	  $db->query($sql);
        }
    }
    
    function update_cache(){
      global $eqdkp, $user, $db;
      $bzone = $this->get_bzone();
      $config = $this->get_config('bossbase');
      $config['source'] = 'both';
      $data = $this->get_data($config, $bzone);
      $sql = 'TRUNCATE TABLE __bs_cache_zone';
      $db->query($sql);
      $sql = 'TRUNCATE TABLE __bs_cache_boss';
      $db->query($sql);

      foreach ($bzone as $zone => $bosses){
	      $sql = "INSERT INTO __bs_cache_zone VALUES('".$zone."', '".$data[$zone]['vc']."', '".$data[$zone]['zk']."', '".$data[$zone]['fvd']."', '".$data[$zone]['lvd']."');";	
     	  $db->query($sql);
	      foreach ($bosses as $boss){
		      $sql = "INSERT INTO __bs_cache_boss VALUES('".$boss."', '".$data[$zone]['bosses'][$boss]['kc']."', '".$data[$zone]['bosses'][$boss]['fkd']."', '".$data[$zone]['bosses'][$boss]['lkd']."');";	
      	  $db->query($sql);
	      }
		  }
    }
    
    function get_cache($bzone = null){
        global $eqdkp, $db, $table_prefix;
      
        $data = array();
      	$sql = 'SELECT * FROM __bs_cache_zone';
      	if (!($result = $db->query($sql))) {
      		message_die('Could not obtain zone cache', '', __FILE__, __LINE__, $sql);
      	}
      
      	while($roww = $db->fetch_record($result)) { 
      	   		$data2[$roww['zone_id']]['vc'] = $roww['zone_co_cache'];
      	   		$data2[$roww['zone_id']]['zk'] = $roww['zone_zk_cache'];
      	   		$data2[$roww['zone_id']]['fvd'] = $roww['zone_fd_cache'];
      	   		$data2[$roww['zone_id']]['lvd'] = $roww['zone_ld_cache'];
      	}	
      	
      	$sql = 'SELECT * FROM __bs_cache_boss';
      	if (!($result2 = $db->query($sql))) {
      		message_die('Could not obtain boss cache', '', __FILE__, __LINE__, $sql);
      	}
      	
        unset($roww);
        
      	while($roww = $db->fetch_record($result2)) { 
              $data2[$roww['boss_id']]['kc'] = $roww['boss_co_cache'];
      	   		$data2[$roww['boss_id']]['fkd'] = $roww['boss_fd_cache'];
      	   		$data2[$roww['boss_id']]['lkd'] = $roww['boss_ld_cache'];
      	}	
      	if($bzone == null){
          $bzone = $this->get_bzone();
        }
      	foreach ( $bzone as $zone => $bosses){
      	  $data[$zone] = $data2[$zone];
          foreach ($bosses as $boss){
            $data[$zone]['bosses'][$boss] = $data2[$boss];
          }
        }
        unset($data2);
      	return $data;
    }

    //function from http://de2.php.net/manual/en/function.in-array.php
    //contact at simplerezo dot com   
    function in_array_nocase($search, &$array) {
      $search = strtolower($search);
      foreach ($array as $item)
        if (strtolower($item) == $search)
          return TRUE;
      return FALSE;
    }


    function get_data($bb_conf, $bzone){
      if ($bb_conf['source'] == 'cache'){
        return $this->get_cache($bzone);
      }
      if ($bb_conf['source'] == 'database'){
        $data = $this->init_data_array($bzone);
        return $this->get_db_data($bzone, $data, $bb_conf);
      }
      if ($bb_conf['source'] == 'both'){
        $data = $this->init_data_array($bzone);
        $data = $this->get_offsets($bzone, $data);
        return $this->get_db_data($bzone, $data, $bb_conf);
      }
      if ($bb_conf['source'] == 'offsets'){
        $data = $this->init_data_array($bzone);
        return $this->get_offsets($bzone, $data);
      }  
    }   
          
      function init_data_array($bzone){
          foreach ($bzone as $zone => $bosses) {
          	$data[$zone]['vc'] = 0;
          	$data[$zone]['fvd']=BS_MAX_DATE;
          	$data[$zone]['lvd']=BS_MIN_DATE;
          	$data[$zone]['zk'] = 0;		 
          	foreach ($bosses as $boss){
              	$data[$zone]['bosses'][$boss]['kc'] = 0;
              	$data[$zone]['bosses'][$boss]['fkd']=BS_MAX_DATE;
              	$data[$zone]['bosses'][$boss]['lkd']=BS_MIN_DATE;
          	}
          }          
          return $data;
      }
                  
      function get_offsets($bzone, $data){
        $bb_boffs = $this->get_boss_offsets();
      	$bb_zoffs = $this->get_zone_offsets();   
      	foreach($bzone as $zone => $bosses){  	
        	if ($data[$zone]['fvd'] > $bb_zoffs[$zone]['fd']) {
      			$data[$zone]['fvd'] = $bb_zoffs[$zone]['fd'];
      		}
          if ($data[$zone]['lvd'] < $bb_zoffs[$zone]['ld']) {
            $data[$zone]['lvd'] = $bb_zoffs[$zone]['ld'];
          }
      		$data[$zone]['vc'] += $bb_zoffs[$zone]['counter'];	
          $data[$zone]['zk'] = 0;		
      		foreach($bosses as $boss){
        		if ($data[$zone]['bosses'][$boss]['fkd'] > $bb_boffs[$boss]['fd']) {
        		  $data[$zone]['bosses'][$boss]['fkd'] = $bb_boffs[$boss]['fd'];
        		}
        		if ($data[$zone]['bosses'][$boss]['lkd'] < $bb_boffs[$boss]['ld']) {
        		  $data[$zone]['bosses'][$boss]['lkd'] = $bb_boffs[$boss]['ld'];
        		}           
      			$data[$zone]['bosses'][$boss]['kc'] += $bb_boffs[$boss]['counter'];
            if ($data[$zone]['bosses'][$boss]['kc'] > 0)
              $data[$zone]['zk']++;	
      		}
        }
        return $data;
      }
   
      function get_sql_data_string($tablestring){
        $tables = array();
        if($tablestring != '')
        $tables = explode(", ", $tablestring);
         
        $sql = "";
        if (count($tables) == 0) {
          $sql = "SELECT  raid_name AS rname, raid_date AS rdate, raid_note AS rnote FROM __raids";
        } else {
        $bpinc = 0;
        foreach ($tables as $raidtable) {
        	if ($bpinc == 0) {
        		$sql .= "SELECT raid_name AS rname, raid_date AS rdate, raid_note AS rnote FROM " . $raidtable . "raids";
        		$bpinc++;
        	} else {
        		$sql .= " UNION ALL SELECT raid_name, raid_date, raid_note FROM " . $raidtable . "raids";
        	}
        }
        $sql .= ";";
        }
        return $sql;
      }
   
      function get_db_data($bzone, $data, $bb_conf) {
        	global $db;
          $delim = array (
            'rnote' => '/'.$bb_conf['noteDelim'].'/',
        		'rname' => '/'.$bb_conf['nameDelim'].'/'
        	);
        
        	$bossInfo = $bb_conf['bossInfo'];
        	$zoneInfo = $bb_conf['zoneInfo'];
          $bb_pboss = $this->get_parse_boss();
          $bb_pzone = $this->get_parse_zone();
        	#Get data from the raids tables
        	##################################################
        	$sql = $this->get_sql_data_string($bb_conf['tables']);	
        
          $result = $db->query($sql);
          $dbdata = $db->fetch_record_set();
          if (!is_array($dbdata)){
            return $data;
          }
        	foreach($dbdata as $row) {
          	foreach ($bzone as $zone => $bosses){
          	  $zone_hit = false;
        			# Get zoneinfo from current row
        			################################
        			if ($delim[$zoneInfo] != "//"){
        				$zone_element = preg_split($delim[$zoneInfo], $row[$zoneInfo], -1, PREG_SPLIT_NO_EMPTY);
        			} else {
        				$zone_element = array($row[$zoneInfo]);
        			}
        			foreach ($zone_element as $raid){
        				$zparseList = preg_split("/\',[ ]*\'/", stripslashes(trim($bb_pzone['pz_'.$zone], "\' ")));
        				if ($this->in_array_nocase(stripslashes(trim($raid)), $zparseList)) {
        					$data[$zone]['vc']++;
        					if ($data[$zone]['fvd'] > $row["rdate"]) {
        						$data[$zone]['fvd'] = $row["rdate"];
        					}
        					if ($data[$zone]['lvd'] < $row["rdate"]) {
        						$data[$zone]['lvd'] = $row["rdate"];
        					}
        					$zone_hit = true;
        				}	
        			}
              if($zone_hit || !$bb_conf['depmatch']){  
          			# Get bossinfo from current row
          			################################
          			if ($delim[$bossInfo] != "//"){
          				$boss_element = preg_split($delim[$bossInfo], $row[$bossInfo], -1, PREG_SPLIT_NO_EMPTY);
          			} else {
          				$boss_element = array($row[$bossInfo]);
          			}
          			foreach ($boss_element as $raid){
          				foreach ($bosses as $boss){
                  			$bparseList = preg_split("/\',[ ]*\'/", stripslashes(trim($bb_pboss['pb_'.$boss], "\' ")));
          					if ($this->in_array_nocase(stripslashes(trim($raid)), $bparseList)) {
          						$data[$zone]['bosses'][$boss]['kc']++;
          						if ($data[$zone]['bosses'][$boss]['fkd'] > $row["rdate"]) {
          							$data[$zone]['bosses'][$boss]['fkd'] = $row["rdate"];
          						}
          						if ($data[$zone]['bosses'][$boss]['lkd'] < $row["rdate"]) {
          							$data[$zone]['bosses'][$boss]['lkd'] = $row["rdate"];
          						}
          					}		
          				}
          			}//end for bosses
        			}
        		}	
        	}
        	foreach ($bzone as $zone => $bosses){
        	  $data[$zone]['zk'] = 0;
            foreach ($data[$zone]['bosses'] as $boss){
              if ($boss['kc'] > 0)
                $data[$zone]['zk']++;
            }
          }
        	return $data;
      }
 
  }//end class
}//end if
?>

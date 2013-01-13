<?php
/******************************
 * EQDKP PLUS
 * (c) 2008 by EQDKP Plus Dev Team
 * http://www.eqdkp-plus.com
 * ------------------
 * $Id: module.php 2392 2008-07-22 15:29:24Z ralv $
   	
 * @Date: 2009-07-15 07:44:00
 * @Author: Damien
 * @Change: 
 * - module renamed from counter to advancedcounter
 *  
 * @Date: 2009-07-13 12:10:00
 * @Author: Damien
 * @Change: 
 * - misstyping in german.php
 * - Number of queries reduced from 8 to 2
 * - stored procedures can't return ouput vars at the moment -> only with PDO  	
 * 
 * @Date: 2009-07-09 00:14:39
 * @Author: Damien
 * @Change: 
 * - Index on location and remote_ip for faster Querys implemented
 * - Codecleanup 
 * @ToDo: 
 * - should implement some Statistics and graphics maybe
 * - should implement some Bugtracking features, only if needed - save sessions, post / get data, querystring data, cookie data 
 * 
 * ******************************/

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$portal_module['advancedcounter'] = array(
			'name'			    => 'Counter-Module',
			'path'			    => 'advancedcounter',
			'version'		    => '1.2',
			'author'   	        => 'Damien',
			'contact'		    => 'http://www.eqdkp-plus.com',
			'description'   => 'Shows a counter',
			'positions'     => array('left1', 'left2', 'right'),
      'signedin'      => '0',
      'install'       => array(
                            'autoenable'        => '0',
                            'defaultposition'   => 'left',
                            'defaultnumber'     => '20',
                            'customsql'         => array(
							"DROP TABLE IF EXISTS __advanced_counter_ip;",
							"CREATE TABLE IF NOT EXISTS __advanced_counter_ip (
  								`time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  								remote_ip varchar(15) NOT NULL,
  								location varchar(500) NOT NULL,
  								KEY location (location),
  								KEY remote_ip (remote_ip)
							) ENGINE=InnoDB;"
                            ),
                        ),
    );

$portal_settings['advancedcounter'] = array(
		'ga-code'  => array(
        'name'      => 'ga-code',
        'language'  => 'ga-code',
        'property'  => 'textarea',
        'size'      => '80',
        'cols'      => '80',
        'help'      => '',
        'codeinput' => true,
  ),

);

if(!function_exists(advancedcounter_module)){
  function advancedcounter_module(){
  	global $db, $eqdkp, $dkpplus, $html, $conf_plus,$tpl, $plang;
		// set the variables
		$var_counter["ip"] = $_SERVER["REMOTE_ADDR"];
		$var_counter["page"] = $_SERVER['PHP_SELF'];
		$var_counter["now"] = time();
		$var_counter["today"] = mktime(0,0,0,date("m",$var_counter["now"]),date("d",$var_counter["now"]),date("Y",$var_counter["now"]));
		$var_counter["yesterday"] = mktime(0,0,0,date("m",$var_counter["now"]),date("d",$var_counter["now"])-1,date("Y",$var_counter["now"]));
		$var_counter["interval_online"] = 300; // Zeitspanne für Benutzer online / Timespan for online Users
		
		
		// output the template
		$counter  = '<table width="100%" border="0" cellspacing="1" cellpadding="2" >';
		$counter .='';

		// IP in database for this page within the timespan of $var_counter["interval_online"]?
		/*
		 * DE: Ist die IP bereits in der Datenbank für die aufgerufene Seite innerhalb der Zeitspanne $var_counter["interval_online"]?
		 * Um den Traffic und die Datenbank zu schonen wird nur alle 5 minuten je seite ein eintrag gemacht (verhindert refreshs...)
		 * dies bringt eine ungenauigkeit bei der Statistik da nicht jeder klick / reload aufgezeichnet wird
		 * 
		 * ENG: IP in database for this page within the timespan of $var_counter["interval_online"]?
		 * We need to lower the traffic,this part reduces insert,
		 * but the statistics wan't fit (not every click is tracked)
		 */
		$sql = "SELECT remote_ip FROM __advanced_counter_ip WHERE remote_ip = '".$var_counter["ip"]."' AND location='". $var_counter["page"] ."' AND time >=FROM_UNIXTIME(".($var_counter["now"]-$var_counter["interval_online"]).")";
		$result = $db->query($sql);

			if($db->num_rows($result) <1) { 
				// ok, theres no ip from the user in the DB so we update the information
				$db->query("insert into __advanced_counter_ip (remote_ip, location) values ('".$var_counter["ip"]."','".$var_counter["page"]."')");
			}		
		
		// Höchste Userzahl / max user on a day
		$sql_vm = "SELECT count(distinct remote_ip) as visitors FROM __advanced_counter_ip GROUP BY DATE(time) order by count(distinct remote_ip) desc";
		$result = $db->query($sql_vm);
		$row = $db->sql_fetchrow($result);
		$visitors["per_day"] = $row["visitors"]; 
		$db->free_result($result);		
	
		$sql_counter1  = "SELECT count(distinct remote_ip) as visitors_total, NULL as visitors_today, NULL as visitors_yesterday, NULL as visitors_online, NULL as pagehits_total, NULL as pagehits_this_page FROM __advanced_counter_ip ";
		$sql_counter1 .= " UNION ";
		$sql_counter1 .= "SELECT NULL as visitors_total, count(distinct remote_ip) as visitors_today, NULL as visitors_yesterday, NULL as visitors_online, NULL as pagehits_total, NULL as pagehits_this_page FROM __advanced_counter_ip WHERE `time`>=FROM_UNIXTIME(".$var_counter["today"].")"; 
		$sql_counter1 .= " UNION "; 
		$sql_counter1 .= "SELECT NULL as visitors_total, NULL as visitors_today, count(distinct remote_ip) as visitors_yesterday, NULL as visitors_online, NULL as pagehits_total, NULL as pagehits_this_page FROM __advanced_counter_ip WHERE `time`>=FROM_UNIXTIME(".$var_counter["yesterday"].") AND `time`<FROM_UNIXTIME(".$var_counter["today"].")";  
		$sql_counter1 .= " UNION ";
		$sql_counter1 .= "SELECT NULL as visitors_total, NULL as visitors_today, NULL as visitors_yesterday, count(distinct remote_ip) as visitors_online, NULL as pagehits_total, NULL as pagehits_this_page FROM __advanced_counter_ip WHERE `time`>=FROM_UNIXTIME(".($var_counter["now"]-$var_counter["interval_online"]).")"; 
		$sql_counter1 .= " UNION ";
		$sql_counter1 .= "SELECT NULL as visitors_total, NULL as visitors_today, NULL as visitors_yesterday, NULL as visitors_online, count(*) as pagehits_total, NULL as pagehits_this_page FROM __advanced_counter_ip ";
		$sql_counter1 .= " UNION ";
		$sql_counter1 .= "SELECT NULL as visitors_total, NULL as visitors_today, NULL as visitors_yesterday, NULL as visitors_online, NULL as pagehits_total, count(distinct remote_ip) as pagehits_this_page FROM __advanced_counter_ip WHERE location='".$var_counter["page"]."'";
		$result = $db->query($sql_counter1);
		while ( $row = $db->fetch_record($result) )
		{
			if(!is_null($row["visitors_total"])){
				$hits["max"]=$row["visitors_total"];
			}
			if(!is_null($row["visitors_today"])){
				$visitors["today"]=$row["visitors_today"];
			}
			if(!is_null($row["visitors_yesterday"])){
				$visitors["yesterday"]=$row["visitors_yesterday"];
			}
			if(!is_null($row["visitors_online"])){
				$visitors["online"]=$row["visitors_online"];
			}
			if(!is_null($row["pagehits_total"])){
				$hits["total"]=$row["pagehits_total"];
			}
			if(!is_null($row["pagehits_this_page"])){
				$hits["this_page"]=$row["pagehits_this_page"];
			}
		}
		$db->free_result($result);

		$counter .= ' <tr class="'.$eqdkp->switch_row_class().'">';
		$counter .= '<td>'.$plang['portal_counter_visitors_total'].'</td>
					 <td>'.$hits["max"].'</td></tr>';
		$counter .= ' <tr class="'.$eqdkp->switch_row_class().'">';
		$counter .= '<td>'.$plang['portal_counter_visitors_today'].'</td>
					 <td>'.$visitors["today"].'</td></tr>';
		$counter .= ' <tr class="'.$eqdkp->switch_row_class().'">';		
		$counter .= '<td>'.$plang['portal_counter_visitors_yesterday'].'</td>
					 <td>'.$visitors["yesterday"].'</td></tr>';
		$counter .= ' <tr class="'.$eqdkp->switch_row_class().'">';		
		$counter .= '<td>'.$plang['portal_counter_visitors_per_day'].'</td>
					 <td>'.$visitors["per_day"].'</td></tr>';	
		$counter .= ' <tr class="'.$eqdkp->switch_row_class().'">';		
		$counter .= '<td>'.$plang['portal_counter_visitors_online'].'</td>
					 <td>'.$visitors["online"].'</td></tr>';
		$counter .= ' <tr class="'.$eqdkp->switch_row_class().'">';
		$counter .= '<td>'.$plang['portal_counter_pagecalls_total'].'</td>
					 <td>'.$hits["total"].'</td></tr>';					
		$counter .= ' <tr class="'.$eqdkp->switch_row_class().'">';
		$counter .= '<td>'.$plang['portal_counter_pagecalls_this'].'</td>
					 <td>'.$hits["this_page"].'</td></tr>';			
	
		$counter  .='</table>';
		if($conf_plus['ga-code']) {
		$counter .= html_entity_decode(htmlspecialchars_decode($conf_plus['ga-code']));
		}

	
		return $counter;
	}
}

?>
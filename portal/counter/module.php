<?php
/******************************
 * EQDKP PLUS
 * (c) 2008 by EQDKP Plus Dev Team
 * http://www.eqdkp-plus.com
 * ------------------
 * $Id: module.php 6309 2009-10-29 23:50:46Z ghoschdi $
 ******************************/

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$portal_module['counter'] = array(
			'name'			    => 'Counter-Module',
			'path'			    => 'counter',
			'version'		    => '0.1',
			'author'   	        => 'badknight',
			'contact'		    => 'http://www.eqdkp-plus.com',
			'description'   => 'Shows a counter',
			'positions'     => array('left1', 'left2', 'right'),
      'signedin'      => '0',
      'custom_tables'	=> array('counter_hits', 'counter_ip', 'counter_maxhits'),
      'install'       => array(
                            'autoenable'        => '0',
                            'defaultposition'   => 'left',
                            'defaultnumber'     => '20',
                            'customsql'         => array(
                              "CREATE TABLE IF NOT EXISTS __counter_hits (
                                `hits` int(10) default NULL
                              ) TYPE=InnoDB;",
                              "INSERT INTO __counter_hits (`hits`) VALUES (0);",
                              "CREATE TABLE IF NOT EXISTS __counter_ip (
                                `time` int(10) NOT NULL default '0',
                                `remote_ip` varchar(15) NOT NULL default '0'
                              ) TYPE=InnoDB;",
                              "CREATE TABLE IF NOT EXISTS __counter_maxhits (
                                `max_hits` int(10) default '0'
                              ) TYPE=InnoDB;",
                              "INSERT INTO __counter_maxhits (`max_hits`) VALUES (0);"
                            ),
                        ),
    );

$portal_settings['counter'] = array(
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

if(!function_exists(counter_module)){
  function counter_module(){
  	global $db, $eqdkp, $dkpplus, $html, $conf_plus,$tpl, $plang;
		// set the variable
		$user_ip = $_SERVER["REMOTE_ADDR"];
		$timestamp = time();
			// 86400 sekcounds  are 24 hours
		$delete = $timestamp - 86400;

		// output the template
		$counter  = '<table width="100%" border="0" cellspacing="1" cellpadding="2" >';
		$counter .='';

		// is the user_ip allready in the databse?
	//	$sql = "select count(remote_ip) from eqdkp_counter_ip where remote_ip = '".$user_ip."'";
		$sql = "select remote_ip from __counter_ip where remote_ip = '".$user_ip."'";
		$result = $db->query($sql);

			if($db->num_rows($result) <1) {
				// ok, theres no ip from the user in the DB so we update the information
				$db->query("update __counter_maxhits set max_hits=max_hits+1");
				$db->query("insert into __counter_ip (time, remote_ip) values ('".$timestamp."', '".$user_ip."')");
			}

		$db->query("update __counter_hits set hits=hits+1");

		// now, we delete the user_ip from the DB where the last hit ist 24 hours ago
		$db->query("Delete from __counter_ip where time < ".$delete."");

		// so now we load the counter information
		$sql = "select __counter_hits.hits, __counter_maxhits.max_hits from __counter_hits, __counter_maxhits";
		$result = $db->query($sql);
		$row = $db->sql_fetchrow($result);
		$max_hits = $row["max_hits"];
		$hits = $row["hits"];

		$today = mktime(24, 0, 0, date('m'), date('d'), date('Y'));
        $yd = $today - (60*60*24);

        $sql1 = "Select time from __counter_ip where time >= '".$yd."' AND time < '".$today."'";
        $result1 = $db->query($sql1);
        $visitors = $db->num_rows($result1);
		
		$counter .= ' <tr class="'.$eqdkp->switch_row_class().'">';
		$counter .= '<td>'.$plang['counter_visitors_summ'].'</td>
					 <td>'.$max_hits.'</td></tr>';
		$counter .= ' <tr class="'.$eqdkp->switch_row_class().'">';
		$counter .= '<td>'.$plang['counter_visitors_tody'].'</td>
					 <td>'.$visitors.'</td></tr>';
		$counter .= ' <tr class="'.$eqdkp->switch_row_class().'">';
		$counter .= '<td>'.$plang['counter_pimpr_today'].'</td>
					 <td>'.$hits.'</td></tr>';

		$counter  .='</table>';
		if($conf_plus['ga-code']) {
			$counter .= html_entity_decode(htmlspecialchars_decode($conf_plus['ga-code']));
		}


		return $counter;
	}
}

?>
<?php
 /*
 * Project:     EQdkp-Plus Raidlogimport
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008-2010
 * Date:        $Date: 2009-05-07 17:52:03 +0200 (Do, 07 Mai 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: hoofy_leon $
 * @copyright   2008-2009 hoofy_leon
 * @link        http://eqdkp-plus.com
 * @package     raidlogimport
 * @version     $Rev: 4786 $
 *
 * $Id: 0514-0516.php 4786 2009-05-07 15:52:03Z hoofy_leon $
 */

if(!defined('EQDKP_INC'))
{
	header('HTTP/1.0 404 Not Found');
	exit;
}

$new_version    = '0.5.7.5';
$updateFunction = 'add_cata_tier13';

$updateDESC = array(
	'',
);
$reloadSETT = false;

$updateSQL = array(
);

function add_cata_tier13() {
	global $eqdkp_root_path, $db, $user;
	$file = $eqdkp_root_path.'plugins/raidlogimport/games/WoW/language/'.$user->lang_name.'/bz_sql.php';
	if(!is_file($file)) $file = $eqdkp_root_path.'plugins/raidlogimport/games/WoW/language/english/bz_sql.php';
	include($file);
	if(count($bz_data) < 193) include($eqdkp_root_path.'plugins/raidlogimport/games/WoW/language/english/bz_sql.php');
	$data2import = array();
	$zones = array();
	foreach($bz_data as $key => $data) {
		if($key < 193) continue;
		if($data[0] == 'zone') {
			$db->query("INSERT INTO __raidlogimport_bz (bz_type, bz_string, bz_note, bz_bonus, bz_bonusph, bz_diff, bz_sort) VALUES ('zone', '".$db->escape($data[1])."', '".$db->escape($data[2])."', '".$data[3]."', '".$data[4]."', '".$data[5]."', '".$data[6]."');");
			$zones[($key+1)] = $db->insert_id();
		} else {
			$data2import[] = array(
				'bz_type' 	=> $data[0],
				'bz_string' => $db->escape($data[1]),
				'bz_note'	=> $db->escape($data[2]),
				'bz_bonus'	=> $data[3],
				'bz_bonusph'=> $data[4],
				'bz_diff'	=> $data[5],
				'bz_sort'	=> $data[6]
			);
		}
	}
	$sql = "INSERT INTO __raidlogimport_bz (bz_type, bz_string, bz_note, bz_bonus, bz_bonusph, bz_diff, bz_sort) VALUES ('";
	$sqls = array();
	foreach($data2import as $data) {
		$data['bz_sort'] = str_replace(array_keys($zones), $zones, $data['bz_sort']);
		$sqls[] = implode("', '", $data);
	}
	$db->query($sql.implode("'), ('", $sqls)."');");
}
?>
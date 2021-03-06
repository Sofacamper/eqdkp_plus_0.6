<?php
/******************************
* EQdkp
* Copyright 2002-2007
* Licensed under the GNU GPL.  See COPYING for full terms.
* ------------------
*
* some code from Charmanager by Wallenium
*
* Began: Juni 1 2007 Corgan
*
* $Id: updateitemstats_include.php 62 2007-05-15 18:42:34Z osr-corgan $
 ******************************/

// EQdkp required files/vars
define('EQDKP_INC', true);
define('IN_ADMIN', true);
$eqdkp_root_path = './../';
include_once($eqdkp_root_path . 'common.php');
include_once($eqdkp_root_path . 'itemstats/config.php');
include_once($eqdkp_root_path . 'itemstats/eqdkp_itemstats.php');
$user->check_auth('a_item_upd');

// start output-buffer
My_ob_start();

echo '<link REL=StyleSheet HREF="'.$eqdkp_root_path.'itemstats/templates/itemstats.css" TYPE="text/css" MEDIA=screen />
<script type="text/javascript" src="'.$eqdkp_root_path.'itemstats/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>';

	$step = $_GET['step']	;
	$items = array();
  $ii = 0;

	if($step=='items')
	{
		$sql = "SELECT item_name, game_itemid AS item_id FROM " . ITEMS_TABLE .";" ;
	}

  if($step=='trade')
	{
			if ($pm->check(PLUGIN_INSTALLED, 'ts'))
			{
		  	if (!defined('RP_RECIPES_TABLE')) { define('RP_RECIPES_TABLE', $table_prefix . 'tradeskill_recipes'); }
				$sql = ' SELECT DISTINCT recipe_name as item_name FROM ' . RP_RECIPES_TABLE . ' GROUP BY recipe_name ';
			}
	}

 	if($step=='bank')
	{
		if ($pm->check(PLUGIN_INSTALLED, 'raidbanker'))
		{
			if (!defined('RB_BANKS_TABLE')) { define('RB_BANKS_TABLE', $table_prefix . 'raidbanker_bank'); }
			$sql = ' SELECT  DISTINCT rb_item_name as item_name FROM ' . RB_BANKS_TABLE . ' GROUP BY rb_item_name ';
		}
	}

 	if($step=='bad')
	{
	 		$sql = 'SELECT item_name, item_id, item_lang FROM ' . item_cache_table . ' WHERE item_icon LIKE "%INV_Misc_QuestionMark%"'  ;
	}

 	if($step=='all')
	{
	 		$sql = 'SELECT item_name, item_id, item_lang FROM ' . item_cache_table . ' ' . ITEMS_TABLE   ;
	}

	 	if($step=='clear')
	{
	 		$sql = 'TRUNCATE TABLE '. item_cache_table ;
	 		//clean up file-cache
	 		$cache_path = $eqdkp_root_path.'itemstats/'.path_cache;
	 		$files = scandir($cache_path);
	 		$ignore_files = array('.', '..', '.svn');
	 		foreach($files as $file) {
	 			if(!in_array($file, $ignore_files)) {
	 				unlink($cache_path.$file);
	 			}
	 		}
	 		$pdc->del($item_stats->webDB);
	}

	if(isset($sql))
	{
	  $result = $db->query($sql) ;
	  while($row = $db->fetch_record($result))
      {
	      $items[$ii]['name'] = $row['item_name'];
	      $items[$ii]['id'] = $row['item_id'];
	      $items[$ii]['lang'] = ($row['item_lang']) ? $row['item_lang'] : items_lang_default;
	      $ii++;
      }
    $db->free_result($result);
	}

	if($_GET['actual'] < $_GET['count'])
	{
		$nextcount = $_GET['actual']+1;
		$item_stats->item_cache->deleteItemFromCache($items[$_GET['actual']]['name'], $items[$_GET['actual']]['id'], $items[$_GET['actual']]['lang']);

	 	if($step=='bad')
		{
			$_output .= $user->lang['updi_baditemscount'].  $ii ."<br>";
			$_output .= $user->lang['done'] . ': '.$_GET['actual']."<br>";
		}else
		{
			$_output .= 'Item ' . $nextcount . ' / ' . $ii ."<br>";
		}


        $ihtml = itemstats_get_html($items[$_GET['actual']]['name'],$items[$_GET['actual']]['id'],true) ;
		$_output .= itemstats_decorate_name($items[$_GET['actual']]['name'],$items[$_GET['actual']]['id']).$ihtml ;
		echo $_output;

		header('Refresh: 0.1; url=updateitemstats_include.php?count='.$_GET['count'].'&actual='.$nextcount.'&step='.$step);
		ob_get_contents();
	}

	# Fertig
	if($_GET['actual'] == $_GET['count'])
	{

  		$_output = '<table><tr><td width="48px"><img src="../images/ok.png" alt="update" \></td><td>'. $user->lang['updi_update_ready'].'</td></tr></table>';

		if($step=='bad')
		{
	 		$sql = 'SELECT item_name FROM ' . item_cache_table . ' WHERE item_icon LIKE "%INV_Misc_QuestionMark%"'  ;
	 		if(isset($sql))
			{
			  $result = $db->query($sql) ;
			   $_output .= $user->lang['updi_baditemscount'] . "<br>";
			  while($row = $db->fetch_record($result))
		      {
			      $_output .= addslashes($row['item_name'])."<br>";

		      }
		    $db->free_result($result);
			}
		}

		echo "<script>
		parent.document.getElementById('loadingtext').innerHTML = '".$_output." ';
		</script>
		</body>
		</html>";


	}# end if



?>
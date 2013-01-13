<?PHP
  /**********************************************************************************\
  * Project:   Downloads for EQdkp-Plus                                              *
  * Licence:   Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported *
  * Link:      http://creativecommons.org/licenses/by-nc-sa/3.0/                     *
  *----------------------------------------------------------------------------------*
  * Project-Start:	 05/2009                                                         *
  * Author:    GodMod                                                                *
  * Copyright: 2009 GodMod                                                           *
  * Link:      http://eqdkp-plus.com/forum                                           *
  * Package:   Downloads                                                             *
  \**********************************************************************************/

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('IN_ADMIN', true);         // Must be set if admin page
define('PLUGIN', 'downloads');   // Must be set!
$eqdkp_root_path = './../../../';
include_once('../include/common.php');


// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'downloads')) { message_die('The Downloads-plugin is not installed.'); }

// Check user permission
$user->check_auth('a_downloads_stats');

//Incluce common download-functions
include_once($eqdkp_root_path . 'plugins/downloads/include/common.php');



//If Statistics are enabled
if ($conf['enable_statistics'] == 1){
	
	//Switch the mode
	switch($in->get('mode', 'chart')){
		
		//Mode filelist: get the files in a category and send it with AJAX
		case "filelist": 
				
				if (is_numeric($in->get('id'))){
						
					//Cache: plugin.downloads.links_in_cat.{CATID}
					$filelist_results = $pdc->get('plugin.downloads.links_in_cat.'.$in->get('id'),false,true);
					if (!$filelist_results){
						$filelist_query = $db->query("SELECT * FROM __downloads_links WHERE category='".$db->escape($in->get('id'))."'");
						$filelist_results = $db->fetch_record_set($filelist_query);
						$pdc->put('plugin.downloads.links_in_cat.'.$in->get('id'),$filelist_results,86400,false,true);
					};
		
					$filelist[""] = "";
					foreach ($filelist_results as $elem){
						$filelist[$elem['id']] = sanitize($elem['name']);
					};
					echo utf8_encode($user->lang['dl_file_headline'].": ".$khrml->DropDown('file', $filelist, $in->get('file')));
					die();
				};
		break;
		
		//Mode chart: show the Graph
		case "chart":
			
			//Switch Actions
			switch ($in->get('do')){
				case "del_cache" : 
									//Delete Cache
									$pdc->del_suffix('plugin.downloads');
				
				break;
			};
			
			if ($in->get('file')){
				
				$filter = "file";
				$sql_filter = " AND fileID = '".$db->escape($in->get('file'))."'";
				
				//Cache: plugin.downloads.links.{FILEID}
				$file_data = $pdc->get('plugin.downloads.links.'.$in->get('file'),false,true);
				if (!$file_data){
					$file_data = $db->query("SELECT * FROM __downloads_links WHERE id='".$db->escape($in->get('file'))."'");
					$file_data = $db->fetch_record($file_data);
					$pdc->put('plugin.downloads.links.'.$in->get('file'),$file_data,86400,false,true);
				};
			
				$headline = $user->lang['dl_file_headline'].": ".sanitize($file_data['name']);
				
			} else {
			
				if ($in->get('cat')){
				
					$filter = "cat";
					$sql_filter = " AND category = '".$db->escape($in->get('cat'))."'";
				
					//Cache: plugin.downloads.categories.{CAT_ID}
					$cat_data = $pdc->get('plugin.downloads.categories.'.$in->get('cat'),false,true);
					if (!$cat_data){
						$cat_data = $db->query("SELECT * FROM __downloads_categories WHERE category_id='".$db->escape($in->get('cat'))."'");
						$cat_data = $db->fetch_record($cat_data);
						$pdc->put('plugin.downloads.categories.'.$in->get('cat'),$cat_data,86400,false,true);

					};
				
					$headline = $user->lang['dl_cat_headline'].": ".sanitize($cat_data['category_name']);
			
				} else {
					//no filter
					$sql_filter = "";
					$filter = "";
					$headline = $user->lang['dl_total'];
				}
			}



		if ($in->get('w', 0)){
			$start_date = $dlclass->getDayOfWeek($in->get('y', date("Y")), $in->get('w', 1), 0);
			$end_date = $dlclass->getDayOfWeek($in->get('y', date("Y")), $in->get('w', 1), 6);
			$view = "week";

		} else {
			if($in->get('m', 0)){
				$month = ($in->get('m', 0) < 10) ? "0".$in->get('m', 0) : $in->get('m', 0);
				$start_date = $in->get('y')."-".$month."-01";
				$end_date = $in->get('y')."-".$month."-".$dlclass->getDaysOfMonth($in->get('m'), $in->get('y'));
				$view = "month";

			} else {
				$start_date = $in->get('y', date("Y"))."-01-01";
				$end_date = $in->get('y', date("Y"))."-12-31";
				$view = "year";

			}
		}
		
		
		
		//Cache: plugin.downloads.stats.{STARTDATE}.{ENDDATE}.{SQL_FILTER}
		$result = $pdc->get('plugin.downloads.stats.'.$start_date.'.'.$end_date.'.'.$sql_filter,false,true);
		if (!$result){
			$sql = $db->query("SELECT COUNT(*) AS anzahl, date FROM __downloads_stats WHERE date >= '".$db->escape($start_date)."' AND date <= '".$db->escape($end_date)."'".$sql_filter." GROUP BY date ORDER BY date ASC");
			$result = $db->fetch_record_set($sql);		
			$pdc->put('plugin.downloads.stats.'.$start_date.'.'.$end_date.'.'.$sql_filter,$result,86400,false,true);
			
		};

		foreach ($result as $row) {
    		$results[$row['date']] = $row['anzahl'];
		};


		switch ($view){
	
			case "month" : 	//Cache: plugin.downloads.stats.cals.y{YEAR}.m{MONTH}
							$value_array = $pdc->get('plugin.downloads.stats.calc.y'.$in->get('y').'.m'.$in->get('m').'.'.$sql_filter,false,true);
							if (!$value_array){
								$days_in_month = $dlclass->getDaysOfMonth($in->get('m'),$in->get('y'));
								for ($i=1; $i<=$days_in_month; $i++){
									$ii = ($i < 10) ? "0".$i : $i;
									$value_array[$i] =  ($results[$in->get('y')."-".$month."-".$ii]) ? $results[$in->get('y')."-".$month."-".$ii] : 0;
								};
								$pdc->put('plugin.downloads.stats.calc.y'.$in->get('y').'.m'.$in->get('m').'.'.$sql_filter,$value_array,86400,false,true);
							};
										
					
							$headline_year = $user->lang['dl_year']." ".$in->get('y', date("Y"));
							$headline_month = ", ".$user->lang['dl_month_'.$in->get('m')];

			break;
			
			case "week" :	//Cache: plugin.downloads.stats.cals.y{YEAR}.w{WEEK}
							$value_array = $pdc->get('plugin.downloads.stats.calc.y'.$in->get('y').'.w'.$in->get('w').'.'.$sql_filter,false,true);
							if (!$value_array){
								for ($i=1; $i<=7; $i++){
									$value_array[$i] =  ($results[$dlclass->getDayOfWeek($in->get('y', date("Y")), $in->get('w', 1), $i-1)]) ? $results[$dlclass->getDayOfWeek($in->get('y', date("Y")), $in->get('w', 1), $i-1)] : 0;
								};
								$pdc->put('plugin.downloads.stats.calc.y'.$in->get('y').'.w'.$in->get('w').'.'.$sql_filter,$value_array,86400,false,true);
							};

							$headline_year = $user->lang['dl_year']." ".$in->get('y', date("Y"));
							$headline_week = ", ".$user->lang['dl_week']." ".$in->get('w', 1);
			
			break;
			
			case "year" : 	//Cache: plugin.downloads.stats.cals.y{YEAR}
							$value_array = $pdc->get('plugin.downloads.stats.calc.y'.$in->get('y',date("Y")).'.'.$sql_filter,false,true);
							if (!$value_array){
								for ($i=1; $i<=12; $i++){
					
									$days_in_month = $dlclass->getDaysOfMonth($i,$in->get('y',date("Y")));
									$month_array = array();	
							
									for ($j=1; $j<=$days_in_month; $j++){
										$ii = ($j < 10) ? "0".$j : $j;
										$h = ($i < 10) ? "0".$i : $i;

										$month_array[$j] =  ($results[$in->get('y', date("Y"))."-".$h."-".$ii]) ? $results[$in->get('y', date("Y"))."-".$h."-".$ii] : 0;

									};
								
									$month_count = 0;

									foreach ($month_array as $elem){
										$month_count = $month_count + $elem;
									}
					
									$value_array[$i] = $month_count;
								}
								$pdc->put('plugin.downloads.stats.calc.y'.$in->get('y',date("Y")).'.'.$sql_filter,$value_array,86400,false,true);
							};
				
							$headline_year = $user->lang['dl_year']." ".$in->get('y', date("Y"));
			break;
	
		} //close switch (view)

		$headline = $headline_year.$headline_month.$headline_week." - ".$headline;
		if ($in->get('ajax') == "true"){
			echo "<b>".utf8_encode($headline).": </b><br><br>".$dlclass->createGraph($value_array);
			die();
		} else {
			$start_graph = "<b>".$headline.": </b><br><br>".$dlclass->createGraph($value_array);
		}
	
		break;
	
	} //Close switch ($in->get('mode'))

} //Close when Stats enabled

//Prune Data
if ($conf['prune_statistics'] != ""){
	$db->query("DELETE FROM __downloads_stats WHERE date < ".date('Y-m-d', strtotime("-".$conf['prune_statistics']."day", time())));
	//Delete Cache
	$pdc->del_suffix('plugin.downloads');
}

$cats_disabled = ($conf['disable_categories'] == 1) ? true : false;

// Check if the Update Check should ne enabled or disabled...
$updchk_enbled = ( $conf['enable_updatecheck'] == 1 ) ? true : false;

// Include the Database Updater
$gupdater = new PluginUpdater('downloads', '', 'downloads_config', 'include');

// The Data for the Cache Table
$cachedb        = array(
      'table' 		=> 'downloads_config',
      'data' 		=> $conf['vc_data'],
      'f_data' 		=> 'vc_data',
      'lastcheck' 	=> $conf['vc_lastcheck'],
      'f_lastcheck' => 'vc_lastcheck'
      );

// The Version Information
$versionthing   = array(
      'name' 	=> 'downloads',
      'version' => $pm->get_data('downloads', 'version'),
      'build' 	=> $pm->plugins['downloads']->build,
      'vstatus' => $pm->plugins['downloads']->vstatus,
      'enabled' => $updchk_enbled
      );

// Start Output à DO NOT CHANGE....
$rbvcheck = new PluginUpdCheck($versionthing, $cachedb);
$rbvcheck->PerformUpdateCheck();

//Admin-Menu	
$admin_optionsarray = array(
 
              0 => array(
                  'name'    => $user->lang['dl_ad_manage_categories_ov'],
                  'link'    => "categories.php".$SID,
                  'img'     => 'cat_edit.png',
                  'perm'    => $user->check_auth('a_downloads_cat', false),
                  ),
              1 => array(
                  'name'    => $user->lang['dl_ad_manage_links_ov'],
                  'link'    => 'downloads.php'.$SID,
                  'img'     => 'edit.png',
                  'perm'    => $user->check_auth('a_downloads_links', false),
                  ),
			  2 => array(
                  'name'    => $user->lang['dl_ad_statistics'],
                  'link'    => 'statistics.php'.$SID,
                  'img'     => 'statistics.png',
                  'perm'    => $user->check_auth('a_downloads_stats', false),
                  ),
              3 => array(
                  'name'    => $user->lang['dl_ad_manage_config'],
                  'link'    => 'settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_downloads_cfg', false),
                  ),
			  4 => array(
                  'name'    => $user->lang['dl_import'],
                  'link'    => 'import.php'.$SID,
                  'img'     => 'import.png',
                  'perm'    => $user->check_auth('a_downloads_import', false),
                  ),
              
             
);

if ($conf['enable_statistics'] == 1) {

	//Create year-dropdown
	for ($i=2009;$i<=date("Y");$i++)	{	

		$year_dropdown[$i] = $i; 		
	};
	


	//Create month-dropdown
	for ($i=0;$i<=12;$i++)	{	

		$month_dropdown[$i] = ($i == 0) ? "" : $user->lang['dl_month_'.$i];	
	};

	//Create weak-dropdown
	//Create month-dropdown
	for ($i=0;$i<=52;$i++)	{	

		$week_dropdown[$i] = ($i == 0) ? "" : $i; 		
	};
	
	//Cache: plugin.downloads.categories
	$catlist = $pdc->get('plugin.downloads.categories',false,true);
	if (!$catlist){
		$cat_data = $db->query("SELECT * FROM __downloads_categories");
		$catlist = $db->fetch_record_set($cat_data);
		$pdc->put('plugin.downloads.categories',$catlist,86400,false,true);
	};

	$cat_dropdown[""] = $user->lang['dl_total'];
	$cat_dropdown[-1] = "================";
	foreach ($catlist as $elem){

        $cat_dropdown[$elem['category_id']] = sanitize($elem['category_name']); 
 	}



	if ($in->get('file')){
		
		//Get all information about the download
			//Cache: plugin.downloads.links.{ID}.{USERID}
			$download = $pdc->get('plugin.downloads.links.'.$in->get('file'),false,true);
			if (!$download) {
				$download_query = "SELECT * from __downloads_links WHERE id = '".$db->escape($in->get('file'))."'";
				$download_query = $db->query($download_query);
				$download = $db->fetch_record($download_query);
				$pdc->put('plugin.downloads.links.'.$in->get('file'),$download,86400,false,true);
			};
			
			//Cache: plugin.downloads.links_in_cat.{CATID}
			$filelist_results = $pdc->get('plugin.downloads.links_in_cat.'.$download['category'],false,true);
			if (!$filelist_results){
				$filelist_query = $db->query("SELECT * FROM __downloads_links WHERE category='".$db->escape($download['category'])."'");
				$filelist_results = $db->fetch_record_set($filelist_query);
				$pdc->put('plugin.downloads.links_in_cat.'.$download['category'],$filelist_results,86400,false,true);
			};
			
			$filelist[""] = "";
			foreach ($filelist_results as $elem){
				$filelist[$elem['id']] = sanitize($elem['name']);
			};
	
			$start_filelist = $user->lang['dl_file_headline'].$khrml->DropDown('file', $filelist, $in->get('file'));
	} 


	if ($in->get('cat')){
			
			//Cache: plugin.downloads.links_in_cat.{CATID}
			$filelist_results = $pdc->get('plugin.downloads.links_in_cat.'.$in->get('cat'),false,true);
			if (!$filelist_results){
				$filelist_query = $db->query("SELECT * FROM __downloads_links WHERE category='".$db->escape($in->get('cat'))."'");
				$filelist_results = $db->fetch_record_set($filelist_query);
				$pdc->put('plugin.downloads.links_in_cat.'.$in->get('cat'),$filelist_results,86400,false,true);
			};
			
			$filelist[""] = "";
			foreach ($filelist_results as $elem){
				$filelist[$elem['id']] = sanitize($elem['name']);
			};
	
			$start_filelist = $user->lang['dl_file_headline'].$khrml->DropDown('file', $filelist, $in->get('file'));
			$download['category'] = 0;
	} 

	if ($cats_disabled == true){
		
		//Cache: plugin.downloads.links
		$filelist_results = $pdc->get('plugin.downloads.links',false,true);
		if (!$filelist_results){
			$filelist_query = $db->query("SELECT * FROM __downloads_links");
			$filelist_results = $db->fetch_record_set($filelist_query);
			$pdc->put('plugin.downloads.links',$filelist_results,86400,false,true);
		};
		
		$filelist[""] = "";
		foreach ($filelist_results as $elem){
			$filelist[$elem['id']] = sanitize($elem['name']);
		};

		$start_filelist = $user->lang['dl_file_headline'].$khrml->DropDown('file', $filelist, $in->get('file'));

	}
	
	
	
} else { //IF stats are disabled
	$error_msg = $user->lang['dl_stats_deactivated'];
}


// Send the Output to the template Files.
$tpl->assign_vars(array(
	'YEAR_DROPDOWN'						=> $khrml->DropDown('y', $year_dropdown, $in->get('y')),
	'MONTH_DROPDOWN'					=> $khrml->DropDown('m', $month_dropdown, $in->get('m')),
	'WEEK_DROPDOWN'						=> $khrml->DropDown('w', $week_dropdown, $in->get('w')),	
	'CATEGORY_DROPDOWN'					=> $khrml->DropDown('cat', $cat_dropdown, $in->get('cat', $download['category']), '', 'onChange="dl_file_list(this.value)"'),
	'START_GRAPH'						=> $start_graph,
	'START_FILELIST'					=> $start_filelist,


	'L_STATISTICS'						=> $user->lang['dl_ad_statistics'],
	'L_WEEK'							=> $user->lang['dl_week'],
	'L_MONTH'							=> $user->lang['dl_month'],
	'L_YEAR'							=> $user->lang['dl_year'],
	'L_SELECT_TIME'						=> $user->lang['dl_select_time'],
	'L_LOAD'							=> $user->lang['dl_load'],
	'L_FILTER'							=> $user->lang['dl_filter'],
	'L_LOAD'							=> $user->lang['dl_load'],
	'L_CATEGORY'						=> $user->lang['dl_cat_headline'],
	'L_CACHING_INFO'					=> $user->lang['dl_stats_caching_info'],
	
	'S_STATS_ENABLED'					=> ($conf['enable_statistics'] == 1) ? true : false,
	
	'DL_ERROR'							=> $error_msg,
	
	'DL_S_CATS_DISABLED'      			=> $cats_disabled,
	'UPDATE_BOX'              			=> $gupdater->OutputHTML(),
	'UPDCHECK_BOX'		  				=> $rbvcheck->OutputHTML(),
	'DL_JS_ABOUT'						=> $jquery->Dialog_URL('About', $user->lang['dl_about_header'], '../about.php', '500', '350'),
	'ADMIN_MENU'       					=> $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/downloads/images/",$user->lang['dl_admin_action']),


	

));

// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title' => sprintf($user->lang['admin_title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['dl_index_headline']." - ".$user->lang['dl_ad_statistics'],
	'template_path'			=> $pm->get_data('downloads', 'template_path'),
	'template_file' 		=> 'admin/statistics.html',
	'display'       		=> true)
  );

?>

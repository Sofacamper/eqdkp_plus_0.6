<?PHP
 /*
 * Project:     MediaCenter for EQDKPlus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2009
 * Date:        $Date$
 * ----------------------------------------------------------------------- 
 * @author      $Author$
 * @copyright   2009 GodMod
 * @link        http://eqdkp-plus.com
 * @package     MediaCenter
 * @version     $Rev$
 * 
 * $Id$
 */

// EQdkp required files/vars
define('EQDKP_INC', true);        // is it in the eqdkp? must be set!!
define('IN_ADMIN', true);         // Must be set if admin page
define('PLUGIN', 'mediacenter');   // Must be set!
$eqdkp_root_path = './../../../';
include_once('../include/common.php');


// Check if plugin is installed
if (!$pm->check(PLUGIN_INSTALLED, 'mediacenter')) { message_die('The MediaCenter-plugin is not installed.'); }

// Check user permission
$user->check_auth('a_mediacenter_stats');

//Incluce common download-functions
include_once($eqdkp_root_path . 'plugins/mediacenter/include/common.php');

//If Statistics are enabled
if ($conf['enable_statistics'] == 1){
	
	//Switch the mode
	switch($in->get('mode', 'chart')){
		
		//Mode filelist: get the files in a category and send it with AJAX
		case "filelist": 
				
				if (is_numeric($in->get('id'))){
						
					//Cache: plugin.mediacenter.links_in_cat.{CATID}
					$filelist_results = $pdc->get('plugin.mediacenter.media_in_cat.'.$in->get('id'),false,true);
					if (!$filelist_results){
						$filelist_query = $db->query("SELECT * FROM __mediacenter_media WHERE category='".$db->escape($in->get('id'))."'");
						$filelist_results = $db->fetch_record_set($filelist_query);
						$pdc->put('plugin.mediacenter.media_in_cat.'.$in->get('id'),$filelist_results,86400,false,true);
					};
		
					$filelist[""] = "";
					foreach ($filelist_results as $elem){
						$filelist[$elem['id']] = sanitize($elem['name']);
					};
					echo utf8_encode($user->lang['mc_videos'].": ".$khrml->DropDown('file', $filelist, $in->get('file')));
					die();
				};
		break;
		
		//Mode chart: show the Graph
		case "chart":
			
			//Switch Actions
			switch ($in->get('do')){
				case "del_cache" : 
									//Delete Cache
									$pdc->del_prefix('plugin.mediacenter');
				
				break;
			};
			
			if ($in->get('file')){
				
				$filter = "file";
				$sql_filter = " AND fileID = '".$db->escape($in->get('file'))."'";
				
				//Cache: plugin.mediacenter.links.{FILEID}
				$file_data = $pdc->get('plugin.mediacenter.media.'.$in->get('file'),false,true);
				if (!$file_data){
					$file_data = $db->query("SELECT * FROM __mediacenter_media WHERE id='".$db->escape($in->get('file'))."'");
					$file_data = $db->fetch_record($file_data);
					$pdc->put('plugin.mediacenter.media.'.$in->get('file'),$file_data,86400,false,true);
				};
			
				$headline = $user->lang['mc_videos'].": ".sanitize($file_data['name']);
				
			} else {
			
				if ($in->get('cat')){
				
					$filter = "cat";
					$sql_filter = " AND category = '".$db->escape($in->get('cat'))."'";
				
					//Cache: plugin.mediacenter.categories.{CAT_ID}
					$cat_data = $pdc->get('plugin.mediacenter.categories.'.$in->get('cat'),false,true);
					if (!$cat_data){
						$cat_data = $db->query("SELECT * FROM __mediacenter_categories WHERE category_id='".$db->escape($in->get('cat'))."'");
						$cat_data = $db->fetch_record($cat_data);
						$pdc->put('plugin.mediacenter.categories.'.$in->get('cat'),$cat_data,86400,false,true);

					};
				
					$headline = $user->lang['mc_category'].": ".sanitize($cat_data['category_name']);
			
				} else {
					//no filter
					$sql_filter = "";
					$filter = "";
					$headline = $user->lang['mc_total'];
				}
			}




		if($in->get('m', 0)){
			$month = ($in->get('m', 0) < 10) ? "0".$in->get('m', 0) : $in->get('m', 0);
			$start_date = $in->get('y')."-".$month."-01";
			$end_date = $in->get('y')."-".$month."-".$mcclass->getDaysOfMonth($in->get('m'), $in->get('y'));
			$view = "month";

		} else {
			$start_date = $in->get('y', date("Y"))."-01-01";
			$end_date = $in->get('y', date("Y"))."-12-31";
			$view = "year";

		}

		
		
		
		//Cache: plugin.mediacenter.stats.{STARTDATE}.{ENDDATE}.{SQL_FILTER}
		$result = $pdc->get('plugin.mediacenter.stats.'.$start_date.'.'.$end_date.'.'.$sql_filter,false,true);
		if (!$result){
			$sql = $db->query("SELECT date, count FROM __mediacenter_stats WHERE date >= '".$db->escape($start_date)."' AND date <= '".$db->escape($end_date)."'".$sql_filter." ORDER BY date ASC");
			$result = $db->fetch_record_set($sql);		
			$pdc->put('plugin.mediacenter.stats.'.$start_date.'.'.$end_date.'.'.$sql_filter,$result,86400,false,true);
			
		};
		
		foreach ($result as $row) {
			$date = date("Y-m", strtotime($row['date']));
			foreach (unserialize($row['count']) as $key => $elem){
				$results[$date."-".$key] = $results[$date."-".$key] + $elem;
			}
			
		};

		
		switch ($view){
	
			case "month" : 	//Cache: plugin.mediacenter.stats.cals.y{YEAR}.m{MONTH}
							$value_array = $pdc->get('plugin.mediacenter.stats.calc.y'.$in->get('y').'.m'.$in->get('m').'.'.$sql_filter,false,true);
							if (!$value_array){
								$days_in_month = $mcclass->getDaysOfMonth($in->get('m'),$in->get('y'));
								for ($i=1; $i<=$days_in_month; $i++){
									$ii = ($i < 10) ? "0".$i : $i;
									$value_array[$i] =  ($results[$in->get('y')."-".$month."-".$ii]) ? $results[$in->get('y')."-".$month."-".$ii] : 0;
								};
								$pdc->put('plugin.mediacenter.stats.calc.y'.$in->get('y').'.m'.$in->get('m').'.'.$sql_filter,$value_array,86400,false,true);
							};
										
					
							$headline_year = $user->lang['mc_year']." ".$in->get('y', date("Y"));
							$headline_month = ", ".$user->lang['mc_month_'.$in->get('m')];

			break;
					
			case "year" : 	//Cache: plugin.mediacenter.stats.cals.y{YEAR}
							$value_array = $pdc->get('plugin.mediacenter.stats.calc.y'.$in->get('y',date("Y")).'.'.$sql_filter,false,true);
							if (!$value_array){
								for ($i=1; $i<=12; $i++){
					
									$days_in_month = $mcclass->getDaysOfMonth($i,$in->get('y',date("Y")));
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
								$pdc->put('plugin.mediacenter.stats.calc.y'.$in->get('y',date("Y")).'.'.$sql_filter,$value_array,86400,false,true);
							};
				
							$headline_year = $user->lang['mc_year']." ".$in->get('y', date("Y"));
			break;
	
		} //close switch (view)

		$headline = $headline_year.$headline_month.$headline_week." - ".$headline;
		if ($in->get('ajax') == "true"){
			echo "<b>".utf8_encode($headline).": </b><br><br>".$mcclass->createGraph($value_array);
			die();
		} else {
			$start_graph = "<b>".$headline.": </b><br><br>".$mcclass->createGraph($value_array);
		}
	
		break;
	
	} //Close switch ($in->get('mode'))

} //Close when Stats enabled



// Check if the Update Check should ne enabled or disabled...
$updchk_enbled = ( $conf['enable_updatecheck'] == 1 ) ? true : false;

// Include the Database Updater
$gupdater = new PluginUpdater('mediacenter', '', 'mediacenter_config', 'include');

// The Data for the Cache Table
$cachedb        = array(
      'table' 		=> 'mediacenter_config',
      'data' 		=> $conf['vc_data'],
      'f_data' 		=> 'vc_data',
      'lastcheck' 	=> $conf['vc_lastcheck'],
      'f_lastcheck' => 'vc_lastcheck'
      );

// The Version Information
$versionthing   = array(
      'name' 	=> 'mediacenter',
      'version' => $pm->get_data('mediacenter', 'version'),
      'build' 	=> $pm->plugins['mediacenter']->build,
      'vstatus' => $pm->plugins['mediacenter']->vstatus,
      'enabled' => $updchk_enbled
      );

// Start Output à DO NOT CHANGE....
$rbvcheck = new PluginUpdCheck($versionthing, $cachedb);
$rbvcheck->PerformUpdateCheck();


//Prune Data
if ($conf['prune_statistics'] != ""){
	$db->query("DELETE FROM __mediacenter_stats WHERE date < ".date('Y-m-d', strtotime("-".$conf['prune_statistics']."day", time())));
	//Delete Cache
	$pdc->del_prefix('plugin.mediacenter');
}

if ($conf['enable_statistics'] == 1) {

	//Create year-dropdown
	for ($i=2009;$i<=date("Y");$i++)	{	

		$year_dropdown[$i] = $i; 		
	};
	


	//Create month-dropdown
	for ($i=0;$i<=12;$i++)	{	

		$month_dropdown[$i] = ($i == 0) ? "" : $user->lang['mc_month_'.$i];	
	};

	//Create weak-dropdown
	//Create month-dropdown
	for ($i=0;$i<=52;$i++)	{	

		$week_dropdown[$i] = ($i == 0) ? "" : $i; 		
	};
	
	//Cache: plugin.mediacenter.categories
	$catlist = $pdc->get('plugin.mediacenter.categories',false,true);
	if (!$catlist){
		$cat_data = $db->query("SELECT * FROM __mediacenter_categories");
		$catlist = $db->fetch_record_set($cat_data);
		$pdc->put('plugin.mediacenter.categories',$catlist,86400,false,true);
	};

	$cat_dropdown[""] = $user->lang['mc_total'];
	$cat_dropdown[-1] = "================";
	foreach ($catlist as $elem){

        $cat_dropdown[$elem['category_id']] = sanitize($elem['category_name']); 
 	}



	if ($in->get('file')){
		
		//Get all information about the download
			//Cache: plugin.mediacenter.links.{ID}.{USERID}
			$download = $pdc->get('plugin.mediacenter.media.'.$in->get('file'),false,true);
			if (!$download) {
				$download_query = "SELECT * from __mediacenter_media WHERE id = '".$db->escape($in->get('file'))."'";
				$download_query = $db->query($download_query);
				$download = $db->fetch_record($download_query);
				$pdc->put('plugin.mediacenter.media.'.$in->get('file'),$download,86400,false,true);
			};
			
			//Cache: plugin.mediacenter.links_in_cat.{CATID}
			$filelist_results = $pdc->get('plugin.mediacenter.media_in_cat.'.$download['category'],false,true);
			if (!$filelist_results){
				$filelist_query = $db->query("SELECT * FROM __mediacenter_media WHERE category='".$db->escape($download['category'])."'");
				$filelist_results = $db->fetch_record_set($filelist_query);
				$pdc->put('plugin.mediacenter.media_in_cat.'.$download['category'],$filelist_results,86400,false,true);
			};
			
			$filelist[""] = "";
			foreach ($filelist_results as $elem){
				$filelist[$elem['id']] = sanitize($elem['name']);
			};
	
			$start_filelist = $user->lang['mc_videos'].$khrml->DropDown('file', $filelist, $in->get('file'));
	} 


	if ($in->get('cat')){
			
			//Cache: plugin.mediacenter.links_in_cat.{CATID}
			$filelist_results = $pdc->get('plugin.mediacenter.media_in_cat.'.$in->get('cat'),false,true);
			if (!$filelist_results){
				$filelist_query = $db->query("SELECT * FROM __mediacenter_media WHERE category='".$db->escape($in->get('cat'))."'");
				$filelist_results = $db->fetch_record_set($filelist_query);
				$pdc->put('plugin.mediacenter.media_in_cat.'.$in->get('cat'),$filelist_results,86400,false,true);
			};
			
			$filelist[""] = "";
			foreach ($filelist_results as $elem){
				$filelist[$elem['id']] = sanitize($elem['name']);
			};
	
			$start_filelist = $user->lang['mc_videos'].$khrml->DropDown('file', $filelist, $in->get('file'));
			$download['category'] = 0;
	} 

	
	
} else { //IF stats are disabled
	$error_msg = $user->lang['mc_stats_deactivated'];
}
		 
		 
	$admin_optionsarray = array(
              
			  1 => array(
                  'name'    => $user->lang['mc_manage_videos'],
                  'link'    => 'media.php'.$SID,
                  'img'     => 'edit.png',
                  'perm'    => $user->check_auth('a_mediacenter_media', false),
                  ),
			  
			  2 => array(
                  'name'    => $user->lang['mc_manage_categories'],
                  'link'    => "categories.php".$SID,
                  'img'     => 'cat_edit.png',
                  'perm'    => $user->check_auth('a_mediacenter_media', false),
                  ),
			  
			  3 => array(
                  'name'    => $user->lang['mc_stats'],
                  'link'    => 'statistics.php'.$SID,
                  'img'     => 'statistics.png',
                  'perm'    => $user->check_auth('a_mediacenter_stats', false),
                  ),                            
			              
			  4 => array(
                  'name'    => $user->lang['mc_config'],
                  'link'    => 'settings.php'.$SID,
                  'img'     => 'wrench.png',
                  'perm'    => $user->check_auth('a_mediacenter_cfg', false),
                  ),
			  5 => array(
                  'name'    => $user->lang['mc_import'],
                  'link'    => 'import.php'.$SID,
                  'img'     => 'import.png',
                  'perm'    => $user->check_auth('a_mediacenter_import', false),
                  ),

              
             
      );

// Send the Output to the template Files.
$tpl->assign_vars(array(
	'YEAR_DROPDOWN'						=> $khrml->DropDown('y', $year_dropdown, $in->get('y')),
	'MONTH_DROPDOWN'					=> $khrml->DropDown('m', $month_dropdown, $in->get('m')),
	'WEEK_DROPDOWN'						=> $khrml->DropDown('w', $week_dropdown, $in->get('w')),	
	'CATEGORY_DROPDOWN'					=> $khrml->DropDown('cat', $cat_dropdown, $in->get('cat', $download['category']), '', 'onChange="mc_file_list(this.value)"'),
	'START_GRAPH'						=> $start_graph,
	'START_FILELIST'					=> $start_filelist,


	'L_STATISTICS'						=> $user->lang['mc_stats'],
	'L_MONTH'							=> $user->lang['mc_month'],
	'L_YEAR'							=> $user->lang['mc_year'],
	'L_SELECT_TIME'						=> $user->lang['mc_select_time'],
	'L_LOAD'							=> $user->lang['mc_load'],
	'L_FILTER'							=> $user->lang['mc_filter'],
	'L_LOAD'							=> $user->lang['mc_load'],
	'L_CATEGORY'						=> $user->lang['mc_category'],
	'L_CACHING_INFO'					=> $user->lang['mc_stats_caching_info'],
	
	'S_STATS_ENABLED'					=> ($conf['enable_statistics'] == 1) ? true : false,
	
	'MC_ERROR'							=> $error_msg,

	'UPDATE_BOX'              			=> $gupdater->OutputHTML(),
	'UPDCHECK_BOX'		  				=> $rbvcheck->OutputHTML(),
	'MC_ADMIN_MENU'       	=> $jquery->DropDownMenu("admin_menu", "dl_colortab",  $admin_optionsarray, "plugins/mediacenter/images/",$user->lang['mc_admin_menu']),
	


	

));

// Init the Template Shit
$eqdkp->set_vars(array(
	'page_title'	 		=> $user->lang['mediacenter']." - ".$user->lang['mc_stats'],
	'template_path'			=> $pm->get_data('mediacenter', 'template_path'),
	'template_file' 		=> 'admin/statistics.html',
	'display'       		=> true)
  );

?>

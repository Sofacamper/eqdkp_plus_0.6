<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-09-24 17:05:08 +0200 (Do, 24 Sep 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 5938 $
 * 
 * $Id: lang_main.php 5938 2009-09-24 15:05:08Z wallenium $
 */

//Main 
$lang['charmanager']          = 'Character Manager';
$lang['uc_manage_chars']			= 'Manage Characters';
$lang['uc_credit_name']				= 'EQDKP CharManager';
$lang['uc_enu_profiles']			= 'Profiles';
$lang['cm_short_desc']        = 'User can manage members';
$lang['cm_long_desc']         = 'With the Charmanager plugin, users can add, manage and assign members by themselve. There are additional profil fields, too.';

// Error Messages
$lang['uc_faild_memberadd']   = "Failed to add %1\$s member exists as ID %2\$s";
$lang['uc_saved_not']         = 'ERROR: The data could not be saved. Please try again or inform an administrator';
$lang['uc_error_memberinfos']	= 'Could not obtain member information of the CharManager Plugin';
$lang['uc_error_raidinfos']		= 'Could not obtain raid information of the CharManager Plugin';
$lang['uc_error_iteminfos']		= 'Could not obtain item information of the CharManager Plugin';
$lang['uc_error_itemraidi']		= 'Could not obtain item/raid information of the CharManager Plugin';
$lang['uc_not_loggedin']			= 'You are not logged in';
$lang['uc_not_installed']			= 'The Character Manager plugin is not installed';
$lang['uc_no_prmissions']			= 'You have no permission for entering this page. Please talk to an administrator.';
$lang['uc_php_version']				= "CharManager requires PHP %1\$s or higher. Your server runs PHP %2\$s";
$lang['uc_plus_version']			= "CharManager requires EQDKP-PLUS %1\$s or higher. Your installed Version is %2\$s";

// Info Boxes
$lang['uc_saved_succ']        = 'The changes was saved';
$lang['us_char added']        = 'The Character was added';
$lang['us_char_updated']      = 'The Character was updated';
$lang['uc_info_box']          = 'Information';
$lang['uc_pic_changed']				= 'The picture was successfully changed';
$lang['uc_pic_added']					= 'The picture was successfully added';

// Date functionality
$lang['uc_changedate']				= 'm-d-Y';

// Armory Menu
$lang['uc_armorylink1']				= 'armory';
$lang['uc_armorylink2']				= 'talents & glyphs';
$lang['uc_armorylink3']				= 'reputation';
$lang['uc_armorylink4']				= 'achievements';
$lang['uc_armorylink5']				= 'statistics';
$lang['uc_armorylink6']				= 'guild';

//User Settings
$lang['uc_charmanager']       = 'Character Management';
$lang['uc_change_pic']				= 'Change Picture';
$lang['uc_add_pic']						= 'Add Picture';
$lang['uc_add_char']          = 'Add Character';
$lang['uc_add_char_plain']		= 'Create new';
$lang['uc_add_char_armory']		= 'Import';
$lang['uc_save_char']					= 'Save Character';
$lang['overtake_char']        = 'Take this char as yours';
$lang['uc_edit_char']         = 'Edit selected Character';
$lang['uc_conn_members']			= 'Assignt Characters';
$lang['uc_connections']				= 'Char Assignments';
$lang['uc_button_cancel']     = 'Cancel';
$lang['uc_button_edit']				= 'Edit';
$lang['uc_tt_n1']							= 'Choose the character you want<br/> to edit';
$lang['uc_tt_n2']							= 'Assign your useraccount to<br/>characters which already exists in<br/>the DKP-System';
$lang['uc_tt_n3']							= 'Create a new character that<br/>doesnt exist in the DKP-System';
$lang['uc_prifler_expl']			= 'The Profilers will be shown as WebLinks, ther\'ll not be any import!';
$lang['uc_ext_import_sh']			= 'Import Data';
$lang['uc_connectme']         = 'Save';
$lang['uc_updat_armory']			= 'Update from Armory';
$lang['uc_add_massupdate']		= 'Update all';
$lang['uc_need_confirmation']	= '[to be confirmed]';

// Member Tasks
$lang['uc_del_warning']				= 'Should the member be removed? Alle Points and Items will be removed and cannot be restored.';
$lang['uc_del_msg_all']				= 'Should all chars be removed?';
$lang['uc_confirm_msg_all']		= 'Should all chars be confirmed?';
$lang['cm_todo_txt']					= "There are %1\$s admin jobs left.";
$lang['cm_todo_head']					= 'Charmanager Admin jobs';
$lang['uc_delete_manager']		= 'Manage Admin jobs';
$lang['uc_rewoke_char']				= 'Restore character';
$lang['uc_delete_char']				= 'Delete character';
$lang['uc_delete_allchar']		= 'Delete all';
$lang['uc_confirm_list']			= 'Character to confirm';
$lang['uc_delete_list']				= 'Character to delete';

// Import
$lang['uc_prof_import']				= 'import';
$lang['uc_import_forw']				= 'continue';
$lang['uc_imp_succ']					= 'The data was successfully imported';
$lang['uc_upd_succ']					= 'The Data was successfully updated';
$lang['uc_imp_failed']				= 'An error contained during the import process. Please try again.';

// Armory Import
$lang['uc_armory_loc']				= 'Server Location';
$lang['uc_charname']					= 'character name';
$lang['uc_servername']				= 'Name of the Gameserver (p.e. Mal\'Ganis)';
$lang['uc_charfound']					= "The Charakter  <b>%1\$s</b> is available on Armory.";
$lang['uc_charfound2']				= "This Charakter Profile was last updated on <b>%1\$s</b>.";
$lang['uc_charfound3']				= 'WARNING: During the import process all existing data will be overwritten!';
$lang['uc_armory_confail']		= 'No connection to Armory. Data could not be submitted.';
$lang['uc_armory_imported']		= 'imported';
$lang['uc_armory_impfailed']	= 'failed';
$lang['uc_armory_impduplex']	= 'duplicated';
$lang['uc_class_filter']			= 'Members of the class';
$lang['uc_class_nofilter']		= 'do not filter';
$lang['uc_guild_name']				= 'Guildname';
$lang['uc_level_filter']			= 'All members with levels higher or equal than';
$lang['uc_rank_filter1a']			= 'higher or equal';
$lang['uc_rank_filter1b']			= 'equal';
$lang['uc_rank_filter2a']			= 'Import all members with ranks';
$lang['uc_rank_filter2b']			= 'than';
$lang['uc_imp_novariables']		= 'You have to setup a server and a server location in the settings first.';
$lang['uc_imp_noguildname']		= 'No guildname provided';
$lang['uc_gimp_header_load']	= 'Guild will be imported, please wait...';
$lang['uc_gimp_header_fnsh']	= 'Guild import finished';
$lang['uc_gimp_finish_note']	= 'Note: Not all Fields are imported. Only Membername, Race, Class and Level have been imported. To import the rest, run the Armory update manually.';
$lang['uc_gimp_infotxt']			= 'The script execution time should be higher than 60s and the memory should be higher than 32M. Please scroll down to see if the download is finished.';
$lang['uc_startdkp']					= 'Add start DKP';
$lang['uc_noprofile_found']		= 'No profile available';
$lang['uc_profiles_complete']	= 'Profile successfully updated';
$lang['uc_notyetupdated']			= 'No new data (outdated character)';
$lang['uc_error_with_id']			= 'Error with the member ID, skipped member';

// Edit Profile tabs
$lang['uc_tab_profilers']			= 'Profile';
$lang['uc_tab_Character']			= 'Character';
$lang['uc_tab_skills']				= 'Skills';
$lang['uc_tab_raidinfo']			= 'Raid Info';
$lang['uc_tab_raids']					= 'Raids';
$lang['uc_tab_items']					= 'Items';
$lang['uc_tab_profession']		= 'Professions';
$lang['uc_tab_notes']         = 'Notes';

// Professions
$lang['uc_first_prof']				= 'First Profession';
$lang['uc_second_prof']				= 'Second Profession';
$lang['uc_prof_skill']				= 'Skill';
$lang['professionsarray']			= array(
																'alchemy'					=> 'Alchemy',
																'mining'					=> 'Mining',
																'engineering'			=> 'Engineering',
																'skinning'				=> 'Skinning',
																'herbalism'				=> 'Herbalism',
																'leatherworking'	=> 'Leatherworking',
																'blacksmithing'		=> 'Blacksmithing',
																'tailoring'				=> 'Tailoring',
																'enchanting' 			=> 'Enchanting',
																'jewelcrafting'		=> 'Jewelcrafting',
																'inscription'     => 'Inscription'
															);
$lang['uc_gender']						= 'Gender';
$lang['genderarray']					= array(
																'Male'						=> 'Male',
																'Female'					=> 'Female',
															);
$lang['uc_faction']						= 'Faction';
$lang['factionarray']					= array(
																'Horde'						=> 'Horde',
																'Alliance'				=> 'Alliance',
															);

// resistences
$lang['uc_resitence']				  = 'Resitence';
$lang['uc_res_fire']					= 'Fire';
$lang['uc_res_frost']					= 'Frost';
$lang['uc_res_arcane']				= 'Arcane';
$lang['uc_res_nature']				= 'Nature';
$lang['uc_res_shadow']				= 'Shadow';

// Bars
$lang['uc_bar_health']				= "Health";
$lang['uc_bar_energy']				= "Energy";
$lang['uc_bar_mana']					= "Mana";
$lang['uc_bar_rage']					= "Rage";

// Add Picture
$lang['uc_save_pic']					= 'Save';
$lang['uc_load_pic']					= 'Load Picture';
$lang['uc_allowed_types']			= 'Allowed picture types';
$lang['uc_max_resolution']		= 'max. Resolution';
$lang['uc_pixel']							= 'pixels';
$lang['uc_not_writable']			= 'The folder \'data/\' is not writable. Please inform an administrator.';

//Admin
$lang['is_adminmenu_uc']			= 'Character Manager';
$lang['uc_manage']            = 'Manage';
$lang['uc_add']            		= 'Add';
$lang['uc_connect']						= 'Assign characters';
$lang['uc_view']							= 'View Profiles';
$lang['uc_edit_all']					= 'Edit all';
$lang['uc_config']						= 'Settings';
$lang['uc_delete']						= 'Delete own characters';
$lang['uc_delmanager']				= 'Manage ToDo';

// About Dialog
$lang['about_header']					= 'Credits';

// Profile
$lang['uc_char_info']					= 'Character Information';
$lang['uc_last_5_raids']			= 'Last 5 Raids';
$lang['uc_last_5_items']			= 'Last 5 Items';
$lang['uc_ext_profile']				= 'External Profile';
$lang['uc_buffed']						= 'Buffed.de';
$lang['uc_allakhazam']				= 'Allakhazam';
$lang['uc_curse_profiler']		= 'Curse Profiler';
$lang['uc_ctprofiles']				= 'CT Profiles';
$lang['uc_receives']					= 'Professions';
$lang['uc_guild']							= 'Guild';
$lang['uc_raid_infos'] 				= 'Raid Information';
$lang['uc_talentplaner']			= 'Talentplaner';
$lang['uc_unknown']						= 'Unknown';
$lang['uc_lastupdate']				= 'Last Profile Update';
$lang['uc_level_out']					= 'Level';
$lang['uc_notes']             = 'Notes';

// About dialog
$lang['uc_copyright'] 				= 'Copyright';
$lang['uc_created_devteam']		= 'by WalleniuM';
$lang['uc_url_web']           = 'Web';
$lang['uc_dialog_header']			= 'About CharManager';
$lang['uc_additions']         = 'Submissions';

// config
$lang['uc_setting_saved_h']   = 'Settings saved';
$lang['uc_setting_saved']			= 'Charmanager Settings successfully saved';
$lang['uc_setting_failed']		= 'Settings not saved. Please try again or contact an administrator';
$lang['uc_header_global']			= 'Charmanager Settings';
$lang['uc_enabl_updatecheck']	= 'Enable Updatecheck';
$lang['uc_servername']				= 'Servername';
$lang['uc_lock_server']				= 'Freeze Servername for Users (they cannot change it)';
$lang['uc_update_all']				= 'Update all Profile data from Web (p.e. Armory)';
$lang['uc_bttn_update']				= 'Update';
$lang['uc_cache_update']			= 'Update member profiles';
$lang['uc_profile_updater']		= 'Loading profile information. please wait...';
$lang['uc_server_loc']				= 'Server Location';
$lang['uc_profile_ready']			= 'The Profiles was successfully imported. You can <a href="#" onclick="javascript:closeWindow()" >close</a> this window.';
$lang['uc_last_updated']			= 'Last Updated';
$lang['uc_never_updated']			= 'Never Updated';
$lang['uc_armory_link']				= 'Profile list: show menu with links to armory';
$lang['uc_no_resi_save']			= 'Do not import resistences';
$lang['uc_lp_hideresis']      = 'Hide User Resistences in Profile List';
$lang['uc_defaultrank']				= 'Rank for new created characters';
$lang['uc_defaultrank_none']	= 'None';
$lang['uc_reqconfirm']				= 'Administrators must confirm chars created by users';
$lang['uc_confirm_char']			= 'Confirm character';
$lang['uc_confirm_allchar']		= 'Confirm all';
$lang['uc_limport']						= 'Importsettings';
$lang['uc_import_guild']			= 'Import all guild members';
$lang['uc_import_guildb']			= 'Import guild';
$lang['uc_import_srvlang']    = 'Language of Armory';
?>
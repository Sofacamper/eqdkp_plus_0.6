<?PHP
/*********************************************************************************\
* Project:	EQdkp-Plus																														*
* License:	Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported *
* Link:			http://creativecommons.org/licenses/by-nc-sa/3.0/											*
* --------------------------------------------------------------------------------*
* Polls 4 EQdkp plus																															*
* --------------------------------------------------------------------------------*
* Project Start: 10/2009																													*
* Author: BadTwin																																	*
* Copyright: Andreas (BadTwin) Schrottenbaum																			*
* Link: http://badtwin.dyndns.org																									*
* Version: 0.0.1																																	*
\*********************************************************************************/

	// Common - Plugin-Manager
	$lang['polls']											= 'Umfragen';
	$lang['po_common_description']			= 'Umfragen f&uuml;r EQdkp-Plus';
	$lang['po_common_long_description'] = 'Dieses Plugin erm&ouml;glicht Umfragen in EQdkp-Plus';

	// About Dialogue
	$lang['po_about_header']						= '&Uuml;ber das Umfragen-Plugin';
	$lang['po_additionals']				 			= 'Beitr&auml;ge zum Umfragen-Plugin';
	$lang['po_licence'] 								= 'Lizenz';

	//Permissions
	$lang['po_permissions_manage']			= 'Umfragen erstellen und verwalten';
	$lang['po_permissions_vote']				= 'Abstimmen';

	// Admin: Menu
	$lang['po_ad_men_settings']					= 'Einstellungen';
	$lang['po_ad_men_edit']							= 'Umfragen bearbeiten';
	$lang['po_ad_men_create']						= 'Neue Umfrage erstellen';

	// Admin: Settings & Create
	$lang['po_ad_sett_manage']					= 'Umfragen - Einstellungen bearbeiten';
	$lang['po_ad_sett_legend']					= 'Hier kannst du die Vorgabewerte f&uuml;r neue Umfragen festlegen';
	$lang['po_ad_sett_multiple']				= 'Mehrfachauswahl m&ouml;glich';
	$lang['po_ad_sett_comments']				= 'Kommentare erlauben';
	$lang['po_ad_sett_editable']				= 'Meinung &auml;nderbar';
	$lang['po_ad_sett_intermed']				= 'Zwischenstand anzeigen';
	$lang['po_ad_sett_modstyle']				= 'Style des Portalmoduls';
	$lang['po_ad_sett_cake']						= 'Kuchengrafik';
	$lang['po_ad_sett_bars']						= 'Balken';
	$lang['po_ad_sett_common']					= 'Plugin-Einstellungen';
	$lang['po_ad_sett_updchk']					= 'Update-Check';
	$lang['po_ad_sett_update']					= 'Aktualisieren';
	$lang['po_ad_sett_saved']						= 'Die Einstellungen wurden gespeichert.';
	$lang['po_ad_sett_hlsaved']					= 'Gespeichert!';
	$lang['po_ad_sett_title']						= 'Titel der Umfrage';
	$lang['po_ad_sett_description']			= 'Detailbeschreibung';
	$lang['po_ad_sett_enddate']					= 'End-Datum';
	$lang['po_ad_sett_closed']					= 'Umfrage geschlossen';
	$lang['po_ad_sett_steptwo']					= 'Weiter (Schritt 2)';
	$lang['po_ad_sett_options']					= 'Optionen';
	$lang['po_ad_sett_nooptions']				= 'Noch keine Optionen vorhanden';
	$lang['po_ad_sett_newoption']				= 'Neue Option erstellen';
	$lang['po_ad_sett_createpoll']			= 'Umfrage erstellen';

	// Admin: Edit
	$lang['po_ad_edit_headline']				= 'Umfrage bearbeiten';
	$lang['po_ad_edit_headlines']				= 'Umfragen bearbeiten';
	$lang['po_ad_edit_title']						= 'Titel';
	$lang['po_ad_edit_multiple']				= 'Mehrfachauswahl';
	$lang['po_ad_edit_comments']				= 'Kommentare';
	$lang['po_ad_edit_editable']				= 'Editierbar';
	$lang['po_ad_edit_intermed']				= 'Zwischenergebnis';
	$lang['po_ad_edit_modstyle']				= 'Style Portalmodul';
	$lang['po_ad_edit_enddate']					= 'End-Datum';
	$lang['po_ad_edit_closed']					= 'Status';
	$lang['po_ad_edit_reset']						= 'Bisherige Stimmen l&ouml;schen';
	$lang['po_ad_edit_delete']					= 'Umfrage l&ouml;schen';
	$lang['po_ad_edit_common']					= 'Allgemein';
	$lang['po_ad_edit_options']					= 'Optionen';

	// User: Overview
	$lang['po_usr_ov_title']						= 'Titel der Umfrage';
	$lang['po_usr_ov_voted']						= 'Bereits abgestimmt?';
	$lang['po_usr_ov_status']						= 'Status';
	$lang['po_usr_ov_yes']							= 'Ja';
	$lang['po_usr_ov_no']								= 'Nein';
	$lang['po_usr_ov_enddate']					= 'Umfrage endet';

	// User: Detailview
	$lang['po_usr_pv_opthl']						= 'Deine Stimme ist gefragt!';
	$lang['po_usr_pv_vote']							= 'Abstimmen';
	$lang['po_usr_pv_votesum']					= 'Stimmen gesamt: ';

	// Portal Module
	$lang['po_pm_pollshown']						= 'Angezeigte Umfrage:';
	$lang['po_pm_last']									= 'Neueste Umfrage';
	$lang['po_pm_random']								= 'Zufällige Umfrage';
	$lang['po_pm_allpolls']							= 'Alle Umfragen';
	$lang['po_pm_nopermission']					= 'Du hast keine Berechtigung um an Umfragen teilzunehmen.';
	$lang['po_pm_nopolls']							= 'Es sind keine Umfragen vorhanden.';
	$lang['po_pm_votecounted']					= '<p><center>Deine Stimme wurde gez&auml;hlt</center></p>';
	$lang['po_pm_changeopinion']				= 'Meinung &auml;ndern';
	$lang['po_pm_enddate1']							= 'Das Ergebnis der Umfrage wird am ';
	$lang['po_pm_enddate2']							= ' ver&ouml;ffentlicht.';
	$lang['po_pm_noenddate']						= 'Das Ergebnis der Umfrage wird ver&ouml;ffentlicht, sobald sie geschlossen ist.';


	$lang['pollsmodule']								= 'Umfragen';
	$lang['po_pm_guestinfo']						= 'Du musst eingeloggt sein, um Umfragen sehen zu k&ouml;nnen';
?>

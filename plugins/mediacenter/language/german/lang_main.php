<?php
// Global Strings
$lang['mediacenter'] 				= 'EQdkp-Plus MediaCenter';
$lang['mc_mediacenter'] 			= 'EQdkp-Plus MediaCenter';
$lang['mc_about_header'] 			= 'Über das MediaCenter-Plugin';
$lang['mc_created_devteam'] 		= ' von GodMod';
$lang['mc_additionals'] 			= 'Beiträge zum MediaCenter-Plugin';
$lang['mc_licence'] 				= 'Lizenz';
$lang['mc_shortdesc']				= 'Videoportal für EQDKPlus';
$lang['mc_description']				= 'Ein Videoportal für EQDKPlus';
$lang['mc_alpha_status'] 			= '<b>Dies ist eine unfertige ALPHA-Version und NICHT für den produktiven Einsatz gedacht!</b>';
$lang['mc_beta_status'] 			= '<b>Dies ist eine BETA-Version und NICHT für den produktiven Einsatz gedacht!</b><br>Melde bitte Fehler in unser <a href="http://eqdkp-plus.com/forum">Forum</a>.';

//Import
$lang['mc_import_headline'] 				= 'Videos importieren';
$lang['mc_import'] 							= 'Importieren';
$lang['mc_import_no_files'] 				= 'Es sind keine Videos zum Importieren vorhanden.';
$lang['mc_import_info'] 					= 'Videos zum Importieren müssen das Format flv, wma oder wmv, avi oder divx haben und in folgenden Ordner hochgeladen werden:';
$lang['mc_import_success'] 					= 'Die ausgewählten Videos wurden erfolgreich importiert';
$lang['mc_import_footcount'] 				= '... %d Video(s) zum Importieren gefunden';

// Install and Menu entrys
$lang['mc_mediacenter_short'] 		= 'Mediathek';
$lang['mc_settings']				= "Einstellungen";
$lang['mc_stats']					= "Statistiken";
$lang['mc_view']					= "Ansehen";
$lang['mc_upload']					= "Hochladen";
$lang['mc_categories']				= "Kategorien";
$lang['mc_category']				= "Kategorie";
$lang['mc_manage_videos']			= "Videos verwalten";
$lang['mc_manage_categories']		= "Kategorien verwalten";
$lang['mc_config']					= "Einstellungen";
$lang['mc_admin_todo']				= "Es sind noch unerledigte Aufgaben vorhanden. <a href='".$eqdkp_root_path."plugins/mediacenter/admin/media.php'>Hier</a> klicken, um zur Videoverwaltung zu gelangen.";
$lang['mc_more_infos']				= 'Weitere Informationen';
$lang['mc_less_infos']				= 'Weniger Informationen';
$lang['mc_show_story']				= 'Editor für Text zum Video anzeigen';
$lang['mc_story']					= 'Text zum Video';
$lang['mc_hide_story']				= 'Editor für Text zum Video ausblenden';

$lang['mc_video_not_supported']		= 'Dieses Video kann leider nicht abgespielt werden.';
$lang['mc_contain_videos']			= 'Enthält %d Videos';
$lang['mc_type']					= 'Typ';
$lang['mc_type_video']				= 'Video';
$lang['mc_type_image']				= 'Bild';
$lang['mc_videos']					= 'Videos';
$lang['mc_duration']				= 'Dauer';
$lang['mc_tags']					= 'Schlagworte';
$lang['mc_votes']					= 'Bewertungen';
$lang['mc_search'] 					= "Suche";
$lang['mc_search_inputvalue'] 		= "Suche...";
$lang['mc_newest']					= "Neueste";
$lang['mc_most_viewed']				= "Meist Gesehene";
$lang['mc_best_rated']				= "Best Bewertetste";
$lang['mc_preview_image']			= 'Vorschaubild';
$lang['mc_url']						= 'URL';
$lang['mc_select_file']				= 'Datei auswählen';
$lang['mc_information']				= 'Informationen';
$lang['mc_reportmail_subject']		= 'Gemeldetes Video';

//Settings
$lang['mc_config_saved_success']	= "Die Einstellungen wurden erfolgreich gespeichert.";
$lang['mc_updatecheck'] 			= 'Benachrichtigung bei Plugin-Updates:';
$lang['mc_save'] 					= 'Speichern';
$lang['mc_force_db_update']			= 'Datenbank-Update erzwingen';
$lang['mc_force_db_update_warn']    = 'Soll das Datenbankupdate erzwungen werden? Danach wird der Updater erscheinen, ein Datenbankupdate wird notwendig!';
$lang['mc_extended_settings']    	= 'Erweiterte Einstellungen';
$lang['mc_view_settings']			= 'Anzeigeoptionen';
$lang['mc_show_link_tab'] 			= 'Zeige Link zur Mediathek im Reiter oben rechts:';
$lang['mc_items_per_page'] 			= 'Videos pro Seite:';
$lang['mc_single_vote'] 			= 'User dürfen Video nur einmal bewerten:';
$lang['mc_enable_comments'] 		= 'Kommentieren von Videos erlauben:';
$lang['mc_admin_activation'] 		= 'Neue Videos müssen vom Admin freigeschalten werden:';
$lang['mc_prune_statistics'] 		= 'Statistik-Daten älter als x Tage autom. löschen:';
$lang['mc_enable_statistics'] 		= 'Statistiken aktivieren:';
$lang['mc_reset_statistics'] 		= 'Statistiken löschen';
$lang['mc_default_view'] 			= 'Standardansicht';
$lang['mc_disable_reportmail'] 		= 'Admin keine E-Mail bei gemeldetem Video senden:';

//Help
$lang['mc_help_dbupdate']			= 'Beim Upgraden von unfertigen Versionen muss das Update von Hand erzwungen werden. Klicke hierzu auf den Button hinter der Version, um ein Update der Datenbankstruktur zu erzwingen.<br>Wenn Du von einer nicht fertigen Version updatest, kann es sein, dass einige Updateschritte Fehlschlagen, da diese Änderungen schon existieren.';
$lang['mc_help_comments']			= 'User können Videos kommentieren. Die Bewertungsfunktion bleibt davon unberührt.';
$lang['mc_help_statistics']			= 'Aktiviert ausführliche Statistiken über die Anzahl der Downloads im zeitlichen Zusammenhang';
$lang['mc_help_prune_statistics']	= 'Löscht automatisch alle Statistik-Daten, die älter als x Tage sind. Feld leer lassen, um Funktion zu deaktivieren.';
$lang['mc_help_admin_activation']	= 'Ein Administrator muss von Usern neu hinzugefügte Videos erst freischalten, bevor sie erscheinen.';


$lang['mc_delete_success'] 		= '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/ok.png">Die ausgewählten Videos wurden erfolgreich gelöscht.';
$lang['mc_move_success'] 		= '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/ok.png">Die ausgewählten Videos wurden erfolgreich verschoben.';
$lang['mc_no_categories'] 		= '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/credits/info.png">Es sind keine Kategorien vorhanden. Bitte erstelle in der <a href="'.$eqdkp_root_path.'plugins/mediacenter/admin/categories.php">Kategorie-Verwaltung</a> eine neue Kategorie.';
$lang['mc_videos_in_cat_footcount']   	= "... insgesamt %1\$d Video(s) in dieser Kategorie";
$lang['mc_catfootcount']   			= "... insgesamt %1\$d Kategorie(n)";
$lang['mc_cat_novideos']   		= "Es sind keine Videos in dieser Kategorie vorhanden.";
$lang['mc_delete'] 					= 'Löschen';
$lang['mc_cancel'] 					= 'Abbrechen';
$lang['mc_reset'] 					= 'Zurücksetzen';
$lang['mc_update'] 					= 'Aktualisieren';
$lang['mc_edit'] 					= 'Bearbeiten';
$lang['mc_move'] 					= 'Verschieben';
$lang['mc_to'] 						= 'nach';
$lang['mc_go'] 						= 'Los';
$lang['mc_order'] 					= 'Reihenfolge';
$lang['mc_select_all'] 				= 'Alle auswählen';
$lang['mc_deselect_all'] 			= 'Auswahl entfernen';
$lang['mc_all_marked'] 				= 'Alle markierte';
$lang['mc_upload'] 					= 'Video hinzufügen';
$lang['mc_uploader'] 				= 'Hinzugefügt von';
$lang['mc_uploaded'] 				= 'Hinzugefügt am';
$lang['mc_action'] 					= 'Vorgang';
$lang['mc_name'] 					= 'Name';
$lang['mc_description'] 			= 'Beschreibung';
$lang['mc_date'] 					= 'Datum';
$lang['mc_views'] 					= 'Aufrufe';
$lang['mc_category_created'] 		= 'Kategorie "<i>%s</i>" wurde erfolgreich erstellt.';
$lang['mc_category_deleted'] 		= 'Kategorie "<i>%s</i>" wurde erfolgreich gelöscht.';
$lang['mc_category_updated'] 		= 'Kategorie "<i>%s</i>" wurde erfolgreich aktualisiert.';
$lang['mc_all_categories_deleted'] 	= 'Alle Kategorien wurden gelöscht.';
$lang['mc_categoy_delete_warn']		= 'Diese Kategorie ist nicht leer. Alle enthaltenen Videos gehen beim Löschen verloren.';
$lang['mc_create_category']			= 'Neue Kategorie erstellen';
$lang['mc_create_video']			= 'Video eintragen';
$lang['mc_edit_video']				= 'Video bearbeiten';
$lang['mc_delete_video']			= 'Video löschen';
$lang['mc_create_images']			= 'Bilder hochladen';
$lang['mc_fields_empty'] 			= '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/error.png">Es wurden nicht alle notwendigen Felder ausgefüllt.';
$lang['mc_fields_empty_title'] 		= 'Eingaben erforderlich';

$lang['mc_more_from'] 				= 'Mehr von';
$lang['mc_more_from_cat'] 			= 'Mehr aus der Kategorie';
$lang['mc_related_videos'] 			= 'Ähnliche Videos';
$lang['mc_rating'] 					= 'Bewertung';
$lang['mc_ratings'] 				= 'Bewertungen';
$lang['mc_reset_ratings'] 			= 'Bewertungen zurücksetzen';
$lang['mc_embed'] 					= 'Einbetten';
$lang['mc_embed_info'] 				= 'Wenn du dieses Video auf deiner Seite einbinden willst, dann verwende dafür einfach folgenden HTML-Code:';
$lang['mc_share'] 					= 'Weiterempfehlen';
$lang['mc_report'] 					= 'Video melden';
$lang['mc_bookmark'] 				= 'Zu Favoriten';
$lang['mc_report_info'] 			= 'Wenn dieses Video defekt ist oder gegen Richtlinien verstößt, melde es mit einem Grund an den Administrator.';
$lang['mc_reported'] 				= 'Gemeldete Videos';
$lang['mc_reported_info'] 			= '<b>Dieses Video wurde gemeldet.</b> (<a href="%s">Hier klicken um diese Meldung zu entfernen</a>)';
$lang['mc_reporter'] 				= 'Gemeldet von:';
$lang['mc_reason'] 					= 'Grund:';
$lang['mc_unconfirmed'] 			= 'Noch nicht geprüfte Videos';
$lang['mc_confirm'] 				= 'Freigeben';

$lang['mc_share_info'] 				= 'Um dieses Video z.B. in Forum zu verlinken benutze folgenden BB-Code:';

//Statistics
$lang['mc_month_1'] = "Januar";
$lang['mc_month_2'] = "Februar";
$lang['mc_month_3'] = "März";
$lang['mc_month_4'] = "April";
$lang['mc_month_5'] = "Mai";
$lang['mc_month_6'] = "Juni";
$lang['mc_month_7'] = "Juli";
$lang['mc_month_8'] = "August";
$lang['mc_month_9'] = "September";
$lang['mc_month_10'] = "Oktober";
$lang['mc_month_11'] = "November";
$lang['mc_month_12'] = "Dezember";

$lang['mc_month_short_1'] = "Jan";
$lang['mc_month_short_2'] = "Feb";
$lang['mc_month_short_3'] = "Maer";
$lang['mc_month_short_4'] = "Apr";
$lang['mc_month_short_5'] = "Mai";
$lang['mc_month_short_6'] = "Jun";
$lang['mc_month_short_7'] = "Jul";
$lang['mc_month_short_8'] = "Aug";
$lang['mc_month_short_9'] = "Sept";
$lang['mc_month_short_10'] = "Okt";
$lang['mc_month_short_11'] = "Nov";
$lang['mc_month_short_12'] = "Dez";

$lang['mc_month'] = "Monat";
$lang['mc_year'] = "Jahr";
$lang['mc_total'] = "Gesamt";
$lang['mc_select_time'] = "Zeitraum auswählen";
$lang['mc_filter'] = "Filtern nach";
$lang['mc_load'] = "Laden";


$lang['mc_stats_caching_info'] = "Aufgrund Caching können diese Daten bis zu 24 Stunden alt sein. (<a href=\"statistics.php?do=del_cache\">Cache löschen</a>)";
$lang['mc_stats_deactivated'] = '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/credits/info.png"><b>Die Statistiken sind nicht aktiviert. Du kannst sie in den <a href="'.$eqdkp_root_path.'plugins/mediacenter/admin/settings.php">Einstellungen</a> aktivieren.</b>';

$lang['mc_admin_menu'] 			= 'Admin-Menü  <img src="'.$eqdkp_root_path.'plugins/mediacenter/images/down.png">';
$lang['mc_view_menu'] 			= 'Ansicht  <img src="'.$eqdkp_root_path.'plugins/mediacenter/images/down.png">';
$lang['mc_view_details'] 			= 'Liste';
$lang['mc_view_thumbs'] 			= 'Bilder';

$lang['mc_search_no_matches'] 		= 'Die Suche nach <b>"%s"</b> brachte keine Ergebnisse.';
$lang['mc_search_matches'] 			= "Die Suche nach \"%1\$s\" brachte %2\$s Ergebnisse:";
$lang['mc_search_footcount'] 		= "... %1\$d Video(s) gefunden / %2\$d pro Seite";
$lang['mc_search_no_value'] 		= '<img src="'.$eqdkp_root_path.'plugins/mediacenter/images/error.png">Es wurde kein Suchbegriff eingegeben. Bitte gebe einen Suchbegriff ein.';


//Usersettings
$lang['mc_usersettings'] 		= 'Mediathek-Einstellungen';
$lang['mc_youtube_nick'] 		= 'Youtube-Username';
$lang['mc_youtube_pw'] 			= 'Youtube-Passwort';
$lang['mc_youtube_upload'] 		= 'Youtube-Upload';
$lang['mc_next_step'] 			= 'Weiter';
$lang['mc_youtube_upload_success'] 	= 'Das Video wurde erfolgreich auf Youtube-Hochgeladen. Nachdem das Video von Youtube freigeschalten wurde, werden die letzten Daten in die Mediathek übernommen (dies kann etwas dauern). <br><br>Das Video ist auf Youtube unter folgendem Link zu erreichen:';
$lang['mc_youtube_auth_failed'] 	= 'Youtube-Login fehlgeschlagen. Bitte überprüfe deinen Usernamen und dein Passwort.';

//Portal Module
$lang['votm'] 		= 'Zufallsvideo';
$lang['pm_votm_tooltip'] = "Tooltip anzeigen";
$lang['pm_votm_novideos'] = "Es sind keine Videos vorhanden";
?>
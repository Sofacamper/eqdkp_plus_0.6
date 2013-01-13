<?php
// Global Strings
$lang['downloads'] 					= 'EQdkp-Plus Downloads';
$lang['dl_about_header'] 			= '�ber das Download-Plugin';
$lang['dl_created_devteam'] 		= ' von GodMod';
$lang['dl_additionals'] 			= 'Beitr�ge zum Download-Plugin';
$lang['dl_licence'] 				= 'Lizenz';
$lang['dl_alpha_status'] 			= '<b>Dies ist eine unfertige ALPHA-Version und NICHT f�r den produktiven Einsatz gedacht!</b>';
$lang['dl_beta_status'] 			= '<b>Dies ist eine BETA-Version und NICHT f�r den produktiven Einsatz gedacht!</b><br>Melde bitte Fehler in unser <a href="http://eqdkp-plus.com/forum">Forum</a>.';

//NEW

$lang['dl_import_headline'] 				= 'Dateien importieren';
$lang['dl_import'] 							= 'Importieren';
$lang['dl_import_no_files'] 				= 'Es sind keine Dateien zum Importieren vorhanden.';
$lang['dl_import_info'] 					= 'Dateien zum Importieren m�ssen in folgenden Ordner hochgeladen werden:';
$lang['dl_import_success'] 					= 'Die ausgew�hlten Dateien wurden erfolgreich importiert';
$lang['dl_import_footcount'] 				= '... %d Datei(en) zum Importieren gefunden';
//Statistics
$lang['dl_month_1'] = "Januar";
$lang['dl_month_2'] = "Februar";
$lang['dl_month_3'] = "M�rz";
$lang['dl_month_4'] = "April";
$lang['dl_month_5'] = "Mai";
$lang['dl_month_6'] = "Juni";
$lang['dl_month_7'] = "Juli";
$lang['dl_month_8'] = "August";
$lang['dl_month_9'] = "September";
$lang['dl_month_10'] = "Oktober";
$lang['dl_month_11'] = "November";
$lang['dl_month_12'] = "Dezember";

$lang['dl_month_short_1'] = "Jan";
$lang['dl_month_short_2'] = "Feb";
$lang['dl_month_short_3'] = "Maer";
$lang['dl_month_short_4'] = "Apr";
$lang['dl_month_short_5'] = "Mai";
$lang['dl_month_short_6'] = "Jun";
$lang['dl_month_short_7'] = "Jul";
$lang['dl_month_short_8'] = "Aug";
$lang['dl_month_short_9'] = "Sept";
$lang['dl_month_short_10'] = "Okt";
$lang['dl_month_short_11'] = "Nov";
$lang['dl_month_short_12'] = "Dez";

$lang['dl_week_short_1'] = "Mo";
$lang['dl_week_short_2'] = "Di";
$lang['dl_week_short_3'] = "Mi";
$lang['dl_week_short_4'] = "Do";
$lang['dl_week_short_5'] = "Fr";
$lang['dl_week_short_6'] = "Sa";
$lang['dl_week_short_7'] = "So";


$lang['dl_week'] = "Woche";
$lang['dl_month'] = "Monat";
$lang['dl_year'] = "Jahr";
$lang['dl_total'] = "Gesamt";
$lang['dl_select_time'] = "Zeitraum ausw�hlen";
$lang['dl_filter'] = "Filtern nach";
$lang['dl_load'] = "Laden";

$lang['dl_attachment'] = "Angeh�ngte Datei";
$lang['dl_times_downloaded'] = "%dx heruntergeladen";

$lang['dl_stats_caching_info'] = "Aufgrund Caching k�nnen diese Daten bis zu 24 Stunden alt sein. (<a href=\"statistics.php?do=del_cache\">Cache l�schen</a>)";
$lang['dl_stats_deactivated'] = '<img src="'.$eqdkp_root_path.'plugins/downloads/images/credits/info.png"><b>Die Statistiken sind nicht aktiviert. Du kannst sie in den <a href="'.$eqdkp_root_path.'plugins/downloads/admin/settings.php">Einstellungen</a> aktivieren.</b>';

$lang['dl_ad_cancel'] 				= 'Abbrechen';

// Install and Menu entrys
$lang['dl_view'] 					= 'Downloads';
$lang['dl_ad_delete'] 				= 'L�schen';
$lang['dl_ad_reset'] 				= 'Zur�cksetzen';
$lang['dl_ad_send'] 				= 'Speichern';
$lang['dl_ad_go'] 					= 'Los';
$lang['dl_shortdesc']				= 'Eine Download-Verwaltung f�r EQdkp';
$lang['dl_description']				= 'Einfache Download-Verwaltung f�r EQdkp';
$lang['dl_ad_manage_categories'] 	= 'Kategorien';
$lang['dl_ad_manage_links'] 		= 'Downloads';
$lang['dl_ad_manage_categories_ov']	= 'Kategorien verwalten';
$lang['dl_ad_manage_links_ov']		= 'Downloads verwalten';
$lang['dl_ad_manage_config'] 		= 'Einstellungen';
$lang['dl_ad_manage_config_ov'] 	= 'Konfigurationseinstellungen';
$lang['dl_ad_manage_upload_ov'] 	= 'Upload';
$lang['dl_ad_cat_footcount'] 		= "... insgesamt %d Kategorie(n)";
$lang['dl_mirror_footcount'] 		= "... insgesamt %d Mirror(s)";
$lang['dl_view_downloads_ov'] 		= 'Ansehen';
$lang['dl_ad_statistics_ov']		= 'Statistiken ansehen';
$lang['dl_ad_statistics']			= 'Statistiken';

$lang['dl_admin_action'] 			= 'Admin-Men�  <img src="'.$eqdkp_root_path.'plugins/downloads/images/down.png">';

//Error/Succes-messages
$lang['dl_ad_delete_success'] 		= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/ok.png">Die ausgew�hlten Downloads wurden erfolgreich gel�scht.';
$lang['dl_ad_move_success'] 		= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/ok.png">Die ausgew�hlten Downloads wurden erfolgreich verschoben.';
$lang['dl_ad_update_success'] 		= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/ok.png">Der Download wurde erfolgreich bearbeitet.';
$lang['dl_ad_fields_empty'] 		= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/error.png">Es wurden nicht alle notwendigen Felder ausgef�llt.';
$lang['dl_ad_upload_file_exists'] 	= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/error.png">Diese Datei wurde bereits hochgeladen.';
$lang['dl_ad_upload_fail_file'] 	= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/error.png">Dieser Datei-Typ darf nicht hochgeladen werden.';
$lang['dl_ad_upload_success'] 		= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/ok.png">Die Datei wurde erfolgreich hochgeladen.';
$lang['dl_ov_download_file_not_found'] 		= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/error.png">Die Datei wurde auf dem Webspace nicht mehr gefunden. Bitte kontaktiere den Administrator.';
$lang['dl_ov_category_created'] 		= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/ok.png">Die Kategorie <b>"%s"</b> wurde erfolgreich erstellt.';

$lang['dl_ad_fields_empty_title'] = "Feldeingaben erforderlich";

// Permission
$lang['dl_perm_public'] 			= '�ffentlich';
$lang['dl_perm_registered'] 		= 'Registriert';


//Settings
$lang['dl_ad_conf_gen'] 						= 'Allgemeine Einstellungen: ';
$lang['dl_ad_conf_view'] 						= 'Anzeigeoptionen: ';

$lang['dl_ad_conf_extended'] 					= 'Erweiterte Einstellungen: ';
$lang['dl_ad_conf_accepted_file_types'] 		= 'Erlaubte Dateiendungen: ';
$lang['dl_ad_conf_htcheck_disabled_warning'] 	= 'Mit dem Deaktivieren dieser Funktion wird nicht mehr gepr�ft, ob sich in dem Download-Verzeichnis eine .htaccess-Datei befindet.<br>Diese Datei verhindert, dass Downloads unberechtigterweise heruntergeladen werden k�nnen. <br><br><b><span style="color:#ff0000">Dass Deaktivieren dieser Funktion wird nicht empfohlen!</span></b>';
$lang['dl_ad_conf_htcheck_disabled'] 			= 'Deaktivieren der .htaccess-Pr�fung:';
$lang['dl_ad_conf_file'] 						= 'Datei-Einstellungen: ';
$lang['dl_ad_conf_filesize_limit'] 				= 'Maximale Dateigr��e:';
$lang['dl_ad_conf_folder_limit'] 				= 'Maximale Gr��e des Upload-Ordners:';
$lang['dl_ad_conf_cat_disabled'] 				= 'Kategorien deaktivieren:';
$lang['dl_ad_conf_related_links'] 				= 'Zugeh�rige Downloads aktivieren:';
$lang['dl_ad_conf_mirrors'] 					= 'Alternative Downloadserver d�rfen eingegeben werden:';
$lang['dl_ad_conf_traffic_limit'] 				= 'Maximaler monatlicher Traffic:';
$lang['dl_ad_conf_comments'] 					= 'User d�rfen Downloads kommentieren:';
$lang['dl_ad_conf_save_sucess'] 				= 'Die Einstellungen wurden erfolgreich gespeichert.';
$lang['dl_ad_conf_captcha'] 					= 'Unregistierte User m�ssen zum Downloaden CAPTCHA ausf�llen:';
$lang['dl_ad_conf_single_vote'] 				= 'User d�rfen Download nur einmal bewerten:';
$lang['dl_ad_conf_statistics'] 					= 'Statistiken aktivieren:';
$lang['dl_ad_conf_prune_statistics'] 			= 'Statistik-Daten �lter als xx Tage automatisch l�schen:';
$lang['dl_ad_conf_statistics_reset'] 			= 'Statistiken zur�cksetzen:';
$lang['dl_ad_conf_statistics_reset_warn'] 		= 'M�chtest du wirklich alle Statistiken l�schen?';
$lang['dl_ad_conf_statistics_ov'] 				= 'Statistiken auf der �bersichts-Seite anzeigen:';
$lang['dl_ad_conf_updatecheck'] 				= 'Benachrichtigung bei Plugin-Updates:';
$lang['dl_ad_conf_debug'] 						= 'Debug-Modus anschalten:';
$lang['dl_ad_conf_items_per_page'] 				= 'Downloads pro Seite:';
$lang['dl_ad_conf_show_downloads_tab'] 			= 'Zeige Link zum Download-Bereich im Reiter oben rechts:';
$lang['dl_ad_conf_force_db_update']				= 'Datenbank-Update erzwingen';
$lang['dl_ad_conf_force_db_warn']               = 'Soll das Datenbankupdate erzwungen werden? Danach wird der Updater erscheinen, ein Datenbankupdate wird notwendig!';
$lang['dl_ad_conf_disable_mimecheck'] 			= 'Mime-Type-Check deaktivieren:';
$lang['dl_ad_conf_disable_mimecheck_warning'] 	= 'Per Mime-Type Check werden hochgeladene Dateien �berpr�ft, ob ihre Dateiendung und ihr Mime-Type zueinander passen. Dies geschieht aus Sicherheitsgr�nden.<br>Deaktiviere diese Funktion nur, wenn Dateien aus absolut vertrauensw�rdigen Quellen hochgeladen werden. <br> <br> <b>Das Deaktivieren dieser Funktion wird nicht empfohlen!</b>';




//Help-messages
$lang['dl_help_file_types']			= 'F�ge hier (mit Komma getrennt) alle zul�ssigen Dateiendungen ein. Dies bezieht sich nur auf Dateien, die hochgeladen werden, nicht auf Verlinkungen. Als Dateiendung z�hlen nur die Buchstaben nach dem letzten Punkt, d.h. "tar.gz" ist nicht richtig, "gz" schon.';
$lang['dl_help_cat_disabled']		= 'Die Kategorieverwaltung kann hier abgeschaltet werden. Dadurch erscheinen alle Downloads in einer gro�en Liste.';
$lang['dl_help_htcheck']			= 'Eine Funktion pr�ft st�ndig, ob das Download-Verzeichnis mit einer .htaccess-Datei gegen unerlaubten Zugriff gesch�tzt ist. Diese Funktion kann hier abgeschaltet werden. <b><span style="color:#ff0000">Es wird nicht empfohlen, diese Funktion zu deaktivieren!</span> Dies ist nur etwas f�r erfahrene Administratoren!</b>';
$lang['dl_help_related_links']		= 'Zu einem Download k�nnen IDs von weiteren Downloads eingegeben werden, die dann unter dem jeweiligen Download als "Zugeh�rige Dateien" erscheinen.';
$lang['dl_help_mirrors']			= 'Um den Usern eine st�ndige Verf�gbarkeit von Downloads zu bieten, k�nnen alternative Downloadserver (sog. "Mirrors") eingetragen werden, also Links zu Servern, die die gleiche Datei vorhalten.';
$lang['dl_help_mirrors_upload']		= 'Trage hier einen oder mehrere Links zu anderen Servern ein, die die gleiche Datei zum Download bereithalten.';
$lang['dl_help_comments']			= 'User k�nnen Downloads ihre Meinung �ber Downloads in Form von Kommentaren abgeben. Die Bewertungsfunktion bleibt davon unber�hrt.';
$lang['dl_help_traffic_limit']		= 'Gib hier das monatliche Traffic-Limit f�r den Download von lokalen Dateien ein. Lasse dieses Feld leer, wenn kein Limit erw�nscht ist. Trage "0" ein, um den Download von allen lokalen Dateien zu verhindern.';
$lang['dl_help_filesize']			= 'Die Dateigr��e ist nur f�r externe Links. Bei hochgeladenen Dateien wird sie automatisch berechnet.';
$lang['dl_help_related_links']		= 'Trage hier IDs (durch Komma getrennt) von anderen Downloads ein, die bei diesem Downloads als "zugeh�rige Downloads" erscheinen. Die ID eines Downloads bekommst du z.B. aus der URL: "downloads.php?file=3". Hier w�re die ID "3".';
$lang['dl_help_recaptcha']			= 'Nicht registrierte User m�ssen vor dem Download einer lokalen Datei ein CAPTCHA l�sen. Die Aktivierung dieser Funktion spart Traffic, da nur Menschen eine Datei herunterladen k�nnen. Ein kostenloser Account bei reCAPTCHA.net ist daf�r erforderlich. Trage die Keys in den PLUS-Einstellungen->Libraries ein';
$lang['dl_help_statistics']			= 'Aktiviert ausf�hrliche Statistiken �ber die Anzahl der Downloads im zeitlichen Zusammenhang';
$lang['dl_help_prune_statistics']	= 'L�scht automatisch alle Statistik-Daten, die �lter sind als x Tage. Leer lassen, um Funktion nicht zu aktivieren.';
$lang['dl_help_statistics_ov']			= 'Blendet Statistiken wie z.B. monatlicher und totaler Traffic, neuester Downloads, TOP5-Downloads usw. auf der �bersichts-Seite ein.';
$lang['dl_help_debug']				= 'Mit dem Debug-Modus werden Fehlermeldungen beim Dateiupload angezeigt. <b>Nur zum Testen aktivieren!</b>';
$lang['dl_help_dbreset']			= 'Beim Upgraden von unfertigen Versionen muss das Update von Hand erzwungen werden. Klicke hierzu auf den Button hinter der Version, um ein Update der Datenbankstruktur zu erzwingen.<br>Wenn Du von einer nicht fertigen Version updatest, kann es sein, dass einige Updateschritte Fehlschlagen, da diese �nderungen schon existieren.';
$lang['dl_help_filesize_limit']					= 'Trage hier die maximale Gr��e einer hochgeladenen Datei ein. Lasse dieses Feld leer, wenn kein Limit erw�nscht ist (es wird dann die Servereinstellung verwendet).';
$lang['dl_help_folder_limit']					= 'Trage hier die maximale Gr��e des Upload-Ordners ein. Lasse dieses Feld leer, wenn kein Limit gew�nscht ist.';

//Categories
$lang['dl_cat_footcount']   			= "... insgesamt %1\$d Download(s) in dieser Kategorie / %2\$d pro Seite";
$lang['dl_cat_footcount_without_pagination']   	= "... insgesamt %1\$d Download(s) in dieser Kategorie";
$lang['dl_cat_footcount_catsdisabled'] 	= "... insgesamt %1\$d Download(s) / %2\$d pro Seite";
$lang['dl_related_links_footcount'] 	= "... insgesamt %d zugeh�rige Download(s)";
$lang['dl_ov_footcount']   				= "... insgesamt %1\$s Download(s) in %2\$s Kategorie(n)";
$lang['dl_cat_nolinks'] 				= '<i>Es sind keine Downloads in dieser Kategorie vorhanden.</i>';
$lang['dl_cat_delete_warn']				= 'Die gew�hlte Kategorie enth�lt noch Dateien, die beim L�schen verloren gehen w�rden.';
$lang['dl_cat_headline'] 				= 'Kategorie';
$lang['dl_edit_headline'] 				= 'Download bearbeiten';
$lang['dl_select_file_headline'] 		= 'Datei ausw�hlen';
$lang['dl_select_type_headline'] 		= 'Typ ausw�hlen';
$lang['dl_upload_headline'] 			= 'Neuen Download erstellen';
$lang['dl_url_headline'] 				= 'Externer Link';
$lang['dl_type_headline'] 				= 'Typ';
$lang['dl_filesize_headline'] 			= 'Gr��e';
$lang['dl_desc_headline'] 				= 'Beschreibung';
$lang['dl_name_headline'] 				= 'Name';
$lang['dl_perm_headline'] 				= 'Berechtigung';
$lang['dl_views_headline'] 				= 'Anz. Downloads';
$lang['dl_date_headline'] 				= 'Datum';
$lang['dl_action_headline'] 			= 'Vorgang';
$lang['dl_ad_all_marked'] 				= 'Alle markierte';
$lang['dl_ad_select_all'] 				= 'Alle Ausw�hlen';
$lang['dl_ad_deselect_all'] 			= 'Auswahl entfernen';
$lang['dl_no_cats'] 					= 'Es sind keine Kategorien vorhanden.';
$lang['dl_rating_headline'] 			= 'Bewertung';
$lang['dl_stat_headline'] 				= 'Statistiken';
$lang['dl_top5_headline'] 				= 'TOP5-Downloads';
$lang['dl_file_headline'] 				= 'Datei';
$lang['dl_file_info_headline'] 			= 'Datei-Informationen';
$lang['dl_files_headline'] 				= 'Dateien';
$lang['dl_related_links_headline'] 		= 'Zugeh�rige Downloads';

$lang['dl_l_cats'] 						= 'Kategorien';
$lang['dl_l_files'] 					= 'Dateien';
$lang['dl_l_traffic'] 					= 'Traffic';
$lang['dl_l_traffic_month'] 			= 'Traffic diesen Monat';
$lang['dl_l_traffic_total'] 			= 'Traffic gesamt';
$lang['dl_l_traffic_of'] 				= 'von';
$lang['dl_l_last_uploaded'] 			= 'Letzte hochgeladene Datei';
$lang['dl_l_filename'] 					= 'Dateiname';
$lang['dl_l_md5'] 						= 'MD5-Hash';
$lang['dl_l_filesize'] 					= 'Dateigr��e';
$lang['dl_l_uploader'] 					= 'Hochgeladen von';
$lang['dl_l_upload_date'] 				= 'Hochgelanden am';
$lang['dl_l_views'] 					= 'Anzahl der Downloads';
$lang['dl_l_report_dead_link'] 			= 'Def. Download melden';
$lang['dl_l_report_dead_link_info'] 	= 'Um den defekten Download zu melden, klicke  einfach unten auf den Button. Wenn Du einen anderen oder richtigen Link zu der Datei kennst, schreibe ihn bitte in das unten stehende Feld und hinterlasse eine kurze Beschreibung dazu.';
$lang['dl_l_reported'] 					= '<b>Dieser Download und/oder alternative Downloadserver wurden gemeldet.</b> (<a href="%s">Hier klicken, wenn der Download repariert wurde</a>)';
$lang['dl_l_reported_info'] 			= 'Dieser Download wurde gemeldet.';
$lang['dl_l_rating'] 					= 'Bewerten';
$lang['dl_l_thanks_for_rating'] 		= 'Vielen Dank f�r Deine Bewertung!';
$lang['dl_l_good'] 						= 'sehr gut';
$lang['dl_l_bad'] 						= 'sehr schlecht';
$lang['dl_l_select_rating'] 			= 'Bewertung ausw�hlen';
$lang['dl_l_votes'] 					= 'Bewertungen';
$lang['dl_l_your_vote'] 				= 'Deine Bewertung';
$lang['dl_l_delete_votes'] 				= 'Bewertungen zur�cksetzen';
$lang['dl_l_mirror'] 					= 'Alternativer Download-Server';
$lang['dl_l_mirrors'] 					= 'Alternative Download-Server';
$lang['dl_l_mirrors_download'] 		    = 'Datei vom alternativen Download-Server #%d herunterladen';
$lang['dl_l_cat_not_existing'] 		    = 'Diese Kategorie existiert nicht.';
$lang['dl_l_download_it'] 		    	= '%s jetzt herunterladen!';
$lang['dl_l_download'] 		    		= 'Herunterladen';

$lang['dl_l_preview_image'] 			= "Vorschau-Bild";
$lang['dl_l_preview'] 					= "Vorschau";

$lang['dl_l_to_category'] 				= "nach Kategorie: ";
$lang['dl_l_cache_not_writable'] 		= 'Der Cache-Ordner "data" ist nicht beschreibar. �ndere die CHMOD-Rechte in 0777!';


// Admin Categories
$lang['dl_ad_created'] 			= ' wurde erstellt.';
$lang['dl_ad_deleted'] 			= ' wurde gel�scht.';
$lang['dl_ad_categories'] 		= 'Vorhandene Kategorien';
$lang['dl_up_category'] 		= 'Kategorie: ';
$lang['dl_ad_input_comment'] 	= 'Beschreibung: ';
$lang['dl_ad_cats_disabled'] 	= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/credits/info.png"><b>Kategorien wurden deaktiviert. Sie k�nnen die Kategorieverwaltung <a href="'.$eqdkp_root_path.'plugins/downloads/admin/settings.php">hier</a> wieder aktivieren.</b>';
$lang['dl_ad_will_linked'] 		= 'Hinweis: URLs werden nur verlinkt!';
$lang['dl_ad_nocats'] 			= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/credits/info.png">Es sind keine Kategorien vorhanden. Bitte erstelle in der <a href="'.$eqdkp_root_path.'plugins/downloads/admin/categories.php">Kategorie-Verwaltung</a> eine neue Kategorie.<br><br>';
$lang['dl_ad_name_uncategorized_cat'] = 'Unkategorisiert';
$lang['dl_ad_comment_uncategorized_cat'] = 'F�r alle unkategorisierten Downloads';
$lang['dl_ad_manage_cat_order'] 	= 'Reihenfolge';
$lang['dl_ad_manage_cat_update'] 	= 'Aktualisieren';
$lang['dl_ad_new_cat'] 				= 'Neue Kategorie erstellen';
$lang['dl_ad_create'] 				= 'Erstellen';
$lang['dl_ad_reset'] 				= 'Zur�cksetzen';
$lang['dl_ad_no_cats'] 				= '<i>Es sind keine Kategorien vorhanden. Bitte erstelle eine Kategorie.</i>';
$lang['dl_ad_cat_delall']			= 'Alle Kategorien l�schen';
$lang['dl_ad_cat_all_deleted']		= 'Alle Kategorien wurden gel�scht!';
$lang['dl_ad_move'] 				= "Verschieben";


//Report download
$lang['dl_report_email_subject'] 		= 'Defekter Download';

//CAPTCHA
$lang['dl_captcha']		= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/credits/info.png">Bitte gib\' den Best�tigungscode ein, um die Datei <b>"%s"</b> herunterzuladen.';
$lang['dl_captcha_wrong']	= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/error.png">Der eingegebene Best�tigungscode war nicht korrekt. <br><br>Bitte gebe den unten stehenden Best�tigungscode ein, um die Datei <b>%s</b> herunterzuladen.';


//Search
$lang['dl_search_no_matches'] 		= 'Die Suche nach <b>"%s"</b> brachte keine Ergebnisse.';
$lang['dl_search_matches'] 			= "Die Suche nach \"%1\$s\" brachte %2\$s Ergebnisse:";
$lang['dl_search_footcount'] 		= "... %1\$d Download(s) gefunden / %2\$d pro Seite";
$lang['dl_search_headline'] 		= "Suche";
$lang['dl_search_inputvalue'] 		= "Suche...";
$lang['dl_search_no_value'] 		= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/error.png">Es wurde kein Suchbegriff eingegeben. Bitte gebe einen Suchbegriff ein.';


//Overview
$lang['dl_index_headline']		= 'Downloads';
$lang['dl_download_headline']	= 'Download';
$lang['dl_title_cat_edit']		= 'Kategorie bearbeiten';
$lang['dl_title_link_delete']	= 'Download l�schen';
$lang['dl_title_link_edit']		= 'Download bearbeiten';
$lang['dl_index_error_perm'] 	= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/error.png">Diese Datei existiert nicht oder Du hast keine Berechtigung.';
$lang['dl_index_error_perm'] 	= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/error.png">Diese Datei existiert nicht oder Du hast keine Berechtigung.';
$lang['dl_l_cat_not_existing'] 	= '<img src="'.$eqdkp_root_path.'plugins/downloads/images/error.png">Diese Kategorie existiert nicht oder Du hast keine Berechtigung.';
$lang['dl_index_error_traffic'] = '<img src="'.$eqdkp_root_path.'plugins/downloads/images/credits/info.png">Das monatliche Traffic-Limit ist erreicht. Bitte Lade die Datei von einem alternativen Downloadserver (falls angegeben) oder n�chsten Monat herunter.';


// Portal Module

$lang['lastuploads']		= 'Aktuelle Downloads:';
$lang['pm_lu_maxlinks']		= 'Maximal angezeigte Downloads:';
$lang['pm_lu_tooltip'] 		= 'Tooltip anzeigen:';
$lang['pm_lu_nolinks'] 		= 'Es sind keine Downloads vorhanden.';

?>
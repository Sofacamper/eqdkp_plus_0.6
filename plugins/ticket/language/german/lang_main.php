<?php
 /*
 * Project:     EQdkp Ticket
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-02-28 16:47:21 +0100 (Sa, 28 Feb 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2007 Achaz, Maintained by PLUS Dev Team
 * @link        http://eqdkp-plus.com
 * @package     ticket
 * @version     $Rev: 4028 $
 * 
 * $Id: common.php 4028 2009-02-28 15:47:21Z wallenium $
 */

#Main 
$lang['ticket'] = "Ticket";
$lang['ticket_open'] = "offene Tickets";
$lang['ticket_usersettings'] = "Einstellungen";
$lang['ticket_adminsettings'] = "Einstellungen";
$lang['ticket_admin_converse'] = "Tickets beantworten";
$lang['ticket_accdenied']= "Zugriff verweigert";

#permissions
$lang['ticket_admin'] = "Administration";
$lang['ticket_submit'] = "Tickets abschicken";

#index.php und mehr als eine Datei
$lang['tk_message_body'] = "Nachrichtentext";
$lang['tk_submit_ticket'] = "Ticket absenden";
$lang['tk_reset'] = "Zurücksetzen";
$lang['tk_update_ticket'] = "Ticket verändern";
$lang['tk_delete_ticket'] = "Ticket löschen";
$lang['tk_replyticket'] = "Mit weiterem Ticket antworten";
$lang['ticket_settings_header'] = "Einstellungen";
$lang['tk_delete'] = "Löschen";
$lang['tk_read'] = "gelesen";
$lang['tk_date'] = "Datum";
$lang['tk_submit_ticket'] = "Ticket aufgeben";
$lang['tk_submit_replyticket'] = "Antwort-Ticket aufgeben";

#usersettings.php
$lang['ticket_email'] = "Email Benachrichtigungen";
$lang['ticket_email_note'] = "Email Benachrichtigungen werden nur versand, wenn die Servereinstellungen dies zulassen. Bitte kontrolliere deine Email-Adresse in den allg. Einstellungen.";
$lang['ticket_color'] = "Farbe neuer Antworten";

#adminconverse.php
$lang['helptextdel'] = "Diese Tickets wurden von Administratoren gelöscht. Löscht ein Nutzer ebenfalls eines dieser Tickets, so wird es aus der Datenbank entfernt. Antwortet ein Nutzer mit einem neuen Ticket auf gelöschte Tickets, so wird die Löschung aufgehoben.";
$lang['helptext'] = "Kursiv dargestellte Tickets wurden von dem Benutzer gelöscht. Wenn du diese löscht, werden sie aus der Datenbank entfernt. Bei Antwort auf ein von Benutzer gelöschtes Ticket (kursiv), wird die Löschung aufgehoben, so dass er es wieder sieht.";
$lang['showdeleted'] = "Gelöschte Tickets anzeigen";
$lang['hidedeleted'] = "Tickets anzeigen";
$lang['tk_fv_required_message'] = "Fehler - überprüfe den Ticket Text";
$lang['tk_replytoticket'] = "Auf Ticket antworten";
$lang['tk_from_user'] = "Von Nutzer";
$lang['tk_from_admin'] = "Von Admin";
$lang['tk_submit_st_reply'] = "Mit Nutzer Kontakt aufnehmen";
$lang['tk_submit_st_reply_button'] = "Absenden";
$lang['tk_to_user'] = "An Nutzer";
$lang['admin-sends-message'] = "Dies ist ein automatisch erstelltes Ticket, da ein Admin ein Anliegen hat, welches in der Antwort unten zu finden ist.";
$lang['tk_usernameerror'] = "Benutzername nicht gefunden";
$lang['tk_submit'] = "Absenden";
$lang['tk_replyheader'] = "Tickets beantworten oder Kontakt mit Nutzer herstellen";
$lang['tk_submit_reply'] = "Antwort erstellen";
$lang['tk_undelete'] = "Löschen rückgängig machen";

#adminSettings
$lang['edit_admin_emails'] = "Bearbeite die Emailadressen der Admins";
$lang['submit_edited_emails'] = "Abschicken";
//$lang['reset'] = "Zurücksetzen";
$lang['ticket_email_general'] = "Emailbenachrichtigungen benutzen";
$lang['ticket_email_general_note'] = "Diese Einstellung bezieht sich auf jegliche Emailbenachrichtigung.";
$lang['ticket_email_admin']= "Emailbenachrichtigung für Admins benutzen";
$lang['ticket_email_admincolor'] = "Farbeeinstellung für neue Tickets (Admin)";
$lang['ticket_default_user_color'] = "Standard Farbeinstellung für Antworten auf Tickets";

#HTML

// Help lines
$lang['b_help'] = 'fetter Text: [b]text[/b] (alt+b)';
$lang['i_help'] = 'cursiver Text: [i]text[/i] (alt+i)';
$lang['u_help'] = 'unterstrichener Text: [u]text[/u] (alt+u)';
$lang['q_help'] = 'Zitat: [quote]text[/quote] (alt+q)';
$lang['c_help'] = 'zentrierter Text: [center]text[/center] (alt+c)';
$lang['p_help'] = 'Bild einfügen: [img]http://image_url[/img] (alt+p)';
$lang['w_help'] = 'URL einfügen: [url]http://url[/url] oder [url=http://url]URL text[/url]  (alt+w)';

// PLUS Infos
$lang['ticket_desc_short']  = "Hilfe & Ticketsystem";
$lang['ticket_desc_long'] = "Lasse deine Benutzer Tickets schreiben, und online verwalten.";
?>

 /*
 * Project:     EQdkp Newsletter
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-03-04 00:28:05 +0100 (Mi, 04 Mrz 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     newsletter
 * @version     $Rev: 4087 $
 * 
 * $Id: newsletter_plugin_class.php 4087 2009-03-03 23:28:05Z wallenium $
 */

INSTALLATION

* Den Ordner "ticket" in den Plugin Ordner Kopieren
* Im Administrationsbereich unter Plugins verwalten auf Installieren klicken
* Rechte für die Nutzer und Administratorenvergeben
* Einstellungen für das Plugin bearbeiten

\\::\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//:://////////////////////////////////////////////

BENUTZUNG / FEATURES

* Benutzer können Tickets verfassen auf die alle berechtigten Admins antworten können
* Es können mehrere Antworten zu einem Ticket erstellt werden
* Die Benutzer können Antworttickets auf die Antworten der Admins erstellen.
* Email Benachrichtigungen für Admins und Nutzer sind möglich (für Nutzer default on)
* Visuelle Anzeige im Menu gibt an, ob neue Tickets/Antworten existieren
* Löschen der Tickets ist möglich

\\::\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//:://////////////////////////////////////////////

CHANGELOG:

to 0.02
* Admin: Bei Antwort ohne Ticket wird nun bei nicht findens den Nutzernamens ein Fehler ausgegeben doch der Text bleibt erhalten
* Kleiner Bug bei Variablenzuweisung

to 0.03
* Adminbereich: Es werden nun alle Tickets angezeigt, auch wenn der Admin selbst noch keins verfasst hat (Bug Report von titan_flippi)
* Der "mit weiterem Ticket antworten" Link ist nun zentriert ausgerichtet und hat ein icon

to 0.04
* Adminbereich: Antworten werden korrekt angezeit (Bug Report von titan_flippi)
* Sprache etwas überarbeitet

to 0.05
* Adminbereich: bei vom Admin gelöschten Tickets kann nun das Löschen rückgängig gemacht werden
* Adminbereich: Das "Antworten auf Ticket" hat nun auch ein Icon
* Userbereich: Löscht ein Nutzer ein Ticket ohne die Antwort als gelesen markiert zu haben werden diese automatisch markiert
* Beim permanenten Löschen von Tickets werden nun tatsächlich die gesamten Sessions gelöscht 
* "Ticket"-Link nun in MainMenu2, da es eher eine Benutzersache ist, als eine Membersache

to 0.06
* Installation: Fehler bei erstellen des Feldes für firstreplydates versucht zu beheben durch löschen des Default Wertes
* Neuer Link für Admins in MainMenu2

to 0.07
* Email Benachrichtigungen haben nun einen Link zur entsprechenden Seite
* Die Zahl der offenen Tickets für die Admins steht nun auch rechts beim Link

to 0.08
* Bug behoben, wobei User die noch nie für sich settings erstellt hatten, nach erstmaligen Abschicken der Einstellungen immer noch die Standards sahen.
* Wenn man nicht eingeloggt ist, sieht man nicht den "Ticket" Link auch wenn die Standardberechtigungen dies erlauben würden. Hintergrund ist der, dass die Standardberechtigungen sowohl für neue Nutzer wie auch für Gäste gelten - einige Admins möchten, dass neue Nutzer standardmäßig das Plugin sehen können (ohne erst noch extra die Rechte zu vergeben) aber Gäste nicht. Demnach ist nun auch die Standardberechtigung für das Versenden von tickets gesetzt. Dazu gehört nun auch eine weitere Abfrage, dass nicht eingeloggte Nutzer auch nicht die Plugin-Seite anzeigen, wenn sie die genaue adresse kennen.
* Auf der Admin-Seite wird bei angezeigten gelöschten Tickets das Löschen Symbol nicht mehr angezeigt

to 0.1
* Updated Readme files

to 0.101
* Wenn ein Ticket gelöscht wird, wird es nun auch als beantwortet markiert, damit es nicht mehr als neues Ticket angezeigt wird

to 0.102
* Läuft nun unter 1.3.1 und auch unter 1.3.2

to 0.103
* Einige Anzeige Bugs gefixed u.a. auch das nicht auftauchen von
* Folgetickets (screentech bugreport)

to 0.104
* Mini-Darstellungsveränderung und Typo

to 0.105
* Der Admin der das Plugin installiert bekommt nun automatisch alle Rechte für das Plugin

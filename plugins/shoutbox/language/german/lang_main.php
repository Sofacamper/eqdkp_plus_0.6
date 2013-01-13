<?php
/*
 * Project:     EQdkp Shoutbox
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-08-05 11:41:15 +0200 (Wed, 05 Aug 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: Dallandros $
 * @copyright   2008 Aderyn
 * @link        http://eqdkp-plus.com
 * @package     shoutbox
 * @version     $Rev: 5478 $
 *
 * $Id: lang_main.php 5478 2009-08-05 09:41:15Z Dallandros $
 */

if (!defined('EQDKP_INC'))
{
    header('HTTP/1.0 404 Not Found');exit;
}

$lang = array(
  'shoutbox'                        => 'Shoutbox',
  'sb_shoutbox'                     => 'Shoutbox',

  // Description
  'sb_short_desc'                   => 'Shoutbox',
  'sb_long_desc'                    => 'Shoutbox ist ein Plugin mit dem User kleine Mitteilungen austauschen k�nnen.',

  // General
  'sb_plugin_not_installed'         => 'Das Shoutbox Plugin ist nicht installiert',
  'sb_php_version'                  => "Shoutbox ben�tigt PHP %1\$s oder h�her. Dein Server l�uft mit PHP %2\$s",
  'sb_plus_version'                 => "Shoutbox ben�tigt EQDKP-PLUS %1\$s oder h�her. Die installierte Version ist %2\$s",

  // Menu
  'sb_manage'                       => 'Verwalten',

  // Admin -> Settings
  'sb_date_format'                  => 'd.m.Y H:i',  // DD.MM.YYYY HH:mm
  'sb_time_format'                  => 'H:i',        // HH:mm
  'sb_adm_date'                     => 'Datum',
  'sb_adm_name'                     => 'Name',
  'sb_adm_text'                     => 'Text',
  'sb_adm_select_all'               => 'Alle ausw&auml;hlen',
  'sb_adm_select_none'              => 'Auswahl entfernen',

  // Configuration
  'sb_config_saved'                 => 'Einstellungen wurden gespeichert',
  'sb_header_general'               => 'Allgemeine Shoutbox Einstellungen',
  'sb_updatecheck'                  => 'Benachrichtigung bei Plugin-Updates',
  'sb_timezone'                     => 'Zeitzone des Servers',
  'sb_dstcorrect'                   => 'Automatisch auf Sommerzeit umstellen?',

  // Portal Modules
  'sb_output_count_limit'           => 'Maximale Anzahl an Shoutbox Eintr�gen.',
  'sb_show_date'                    => 'Zus�tzlich das Datum anzeigen?',
  'sb_show_archive'                 => 'Archiv anzeigen?',
  'sb_input_box_below'              => 'Eingabefeld unterhalb der Eintr�ge?',
  'sb_autoreload'                   => 'Zeit in Sekunden nach der die Shoutbox automatisch neu geladen werden soll (Standard 0 = Aus)',
  'sb_autoreload_help'              => 'Wird 0 eingetragen so wird das automatische Neu Laden abgeschalten',
  'sb_invisible_to_guests'          => 'F�r G�ste <u>nicht</u> sichtbar?',
  'sb_no_character_assigned'        => 'Es wurde kein Charakter verkn�pft. Es muss ein Charakter verkn�pft sein bevor Eintr�ge gemacht werden k�nnen.',
  'sb_submit_text'                  => 'Absenden',
  'sb_save_wait'                    => 'Speichern, bitte warten...',
  'sb_reload'                       => 'Neu laden',
  'sb_no_entries'                   => 'Keine Eintr�ge',
  'sb_archive'                      => 'Archiv',
  'sb_shoutbox_archive'             => 'Shoutbox Archiv',
  'sb_footer'                       => "... %1\$d gefunden / %2\$d pro Seite",

  // About/Credits
  'sb_about_header'                 => '�ber Shoutbox',
  'sb_credits_part1'                => 'Shoutbox v',
  'sb_credits_part2'                => ' von Aderyn',
  'sb_copyright'                    => 'Copyright',
);

?>

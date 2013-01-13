<?php
 /*
 * Project:     eqdkpPLUS Libraries: pluginUpdates
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2009
 * Date:        $Date: 2009-08-17 08:52:58 +0200 (Mon, 17 Aug 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2009 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     libraries:pluginUpdates
 * @version     $Rev: 5689 $
 * 
 * $Id: pluginlist.php 5689 2009-08-17 06:52:58Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

$plugin_names	= array(
										'raidplan'	=> array(
              											'table'        => 'raidplan_config',
              											'fieldprefix'  => 'rp_',
											),
										'charmanager'	=> array(
              											'table'        => 'charmanager_config',
              											'fieldprefix'  => 'uc_',
											),
										'itemspecials'	=> array(
              											'table'        => 'itemspecials_config',
              											'fieldprefix'  => 'is_',
											),
										'newsletter'	=> array(
              											'table'        => 'newsletter_config',
              											'fieldprefix'  => 'nl_',
											),
										'raidbanker'	=> array(
              											'table'        => 'raidbanker_config',
              											'fieldprefix'  => 'rb_',
											),
										'shoutbox'	=> array(
              											'table'        => 'shoutbox_config',
              											'fieldprefix'  => 'sb_',
											),
										'bosssuite'	=> array(
              											'table'        => 'bs_config',
              											'fieldprefix'  => 'bb_',
											),
										'info'	=> array(
              											'table'        => 'info_config',
              											'fieldprefix'  => 'info_',
											),
										'raidlogimport'	=> array(
              											'table'        => 'raidlogimport_config',
              											'fieldprefix'  => 'rli_',
											),
										'gallery'	=> array(
              											'table'        => 'gallery_config',
              											'fieldprefix'  => '',
											),
										'guildrequest'	=> array(
              											'table'        => 'guildrequest_config',
              											'fieldprefix'  => 'gr_',
											),
										'downloads'	=> array(
              											'table'        => 'downloads_config',
              											'fieldprefix'  => '',
											),
							);

?>

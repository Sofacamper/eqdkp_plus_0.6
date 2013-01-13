<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2008-11-03 13:24:11 +0100 (Mo, 03 Nov 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 2963 $
 * 
 * $Id: config.php 2963 2008-11-03 12:24:11Z wallenium $
 */

  if ( !defined('EQDKP_INC') ){
      header('HTTP/1.0 404 Not Found');exit;
  }

  // Config Values
  $dstvalue   = (date('I', time()) == 1) ? 1 : 0;
  $tzvalue    = date('H.i', time()) - (gmdate('H.i', time())+$dstvalue);
  $tzvalue    = str_replace(',', '.', $tzvalue);
  $config_vars = array(
        'rp_inst_version'     => $this->version,      # Save the version for Autoupdate?
        'rp_show_ranks'       => '1',                 # Show ranks in raid planner?
        'rp_short_rank'       => '1',                 # Show only short ranks?
        'rp_last_days'        => '7',                 # show recent raids: last x days
        'rp_updatecheck'      => '1',                 # Enable UpdateCheck?
        'rp_rank'             => '1',                 # Select Rank of Officers
        'rp_hours_offset'     => '0',                 # Hours Offset
        'rp_min_offset'       => '15',                # Minutes Offset
        'rp_end_hour_offset'  => '0',                 # End Hours Offset
        'rp_end_min_offset'   => '30',                # End Minutes Offset
        'rp_cal_ab'           => '0',                 # Select Rank of Officers
        'rp_cal_startday'     => 'monday',            # Startday; Monday/Sunday
        'rp_rep_enable'       => '1',                 # Enable Repeatable Raid addition
        'rp_rep_value'        => '40',                # Repeatable: Days in Future
        'rp_breakvalue'       => '8',                 # Linebreak Value
        'rp_dstcheck'         => '1',                 # Enable DST Check
        'rp_ghidenotes'       => '1',                 # Hide game notes for guests
        'rp_activeonly_sn'    => '1',                 # E-Mails to active Users only
        'rp_mode'             => '2',                 # Calendar + classic mode enabled
        'rp_colored_memb'     => '1',                 # Colored Member Names
        'rp_comments'         => '1',                 # Comment System enabled
        'color_status0'       => 'lime',              # Color Status 0
      	'color_status1'       => 'yellow',            # Color Status 1
      	'color_status2'       => 'red',               # Color Status 2
      	'color_status3'       => 'purple',            # Color Status 3
      	'rp_us_layout'        => '1',                 # Usersettings Layout
	      'rp_us_time'          => '1',                 # Usersettings Time
        'rp_timezone'         => $tzvalue             # Timezone Value
  );

?>

<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2008-12-12 13:42:28 +0100 (Fri, 12 Dec 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 3354 $
 * 
 * $Id: time.class.php 3354 2008-12-12 12:42:28Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

if (!class_exists("DateFormats")){
  class DateFormats {
  	function DateFormats($language, $dstchk='', $timezone=''){
      global $conf;
  		$this->SelectLanguage($language);
  		setlocale (LC_ALL, $this->datestrings['local']); //set local
  		$this->dstcheck = ($dstchk) ? $dstchk : $conf['rp_dstcheck'];
  		$this->timezone = ($timezone) ? $timezone : $conf['rp_timezone'];
  		$this->RealTimeZone = $this->RealTimeZone();
  	}
  	
  	/**
  	* Generate the DateValue
  	*
  	* @param $raid_name
  	* @return string Raid value
  	*/
  	function GenRaidDate($tabledate, $tstamp, $tval, $tmp_days_offset){
      global $user, $conf;
      if(isset($tabledate) || isset($tstamp)){
        $tmpinput = (isset($tabledate) && !$tstamp) ? $tabledate : $tstamp;
        $outp = $this->DoDate($conf['timeformats']['jstime'], $tmpinput);
      }else{
        $outp = $this->DoDate($conf['timeformats']['jstime'], date(mktime(date("H"), $tval, 0, date("m"), date("d")+$tmp_days_offset, date("Y"))));
      }
      return $outp;
    }
  	
  	/**
  	* Genereate time() in GMT
  	*      	
  	* @return timestamp
  	*/
    function DoTime($withTimezone=false){
      $DoMyTime = time()+Date('I')*3600;
      return ($withTimezone) ? $DoMyTime-$this->RealTimeZone : $DoMyTime;
    }

    /**
    * Output proper Time
    *
    * @param $format     Format of the Output
    * @param $tstamp     Timestamp
    * @param $timetones  Use Correction or not
    * @return Formatted time string
    */
    function DoDate($format, $tstamp, $timetones = true){
      if($timetones == true){
        $tztempvalue   = $this->RealTimeZone;
        return gmstrftime($format, ($tstamp+$tztempvalue));
      }else{
        return gmstrftime($format, $tstamp);
      }
    }
    
    /**
    * Return Date in RFC 2822 Compatible Output
    *
    * @param $tstamp     Timestamp
    * @return RFC2822 Time String
    */
    function DoRFC2822($tstamp){
      // maybe we have to add the DST.. working in DST=0 without it!
      return date('r', $tstamp);
    }
    
    /**
    * Mktime in GMT by using DST & Timezone
    *
    * @param $hour   Hour      int
    * @param $min    Minutes   int
    * @param $sec    Seconds   int
    * @param $month  Month     int
    * @param $day    Day       int
    * @param $year   Year      int
    * @return Timestamp
    */
    function rp_mktime($hour=0, $min=0, $sec=0, $month=0, $day=0, $year=0){
      $time   = mktime( $hour, $min, $sec, $month, $day, $year );
      return $time+date('I',$time)*3600;
    }
    
    
    /**
  	* Return Time Format Array
  	*
  	* @return Dates format array
  	*/
  	function timeFormats(){
      return $this->datestrings;
    }
  	
  	/**
  	* OUTDATED
  	*/
  	function stripGetDate($getdate){
      // format must be: 01012006 ddmmyyyy
      if (strlen($getdate) == 8){
        // Datum aufbereiten
            $getdat['day']    = substr($getdate,0,2); //|17|052006  -> 0,2
            $getdat['month']  = substr($getdate,2,2);  //17|05|2006 -> 2,2
            $getdat['year']   = substr($getdate,4,4); //1705|2006| -> 4,4
      } //end of strlen
        return $getdat;
    }
  	
  	/**
  	* Select the Time variables by language
  	*
  	* @param $language  Language     string
  	* @return --
  	*/
  	function SelectLanguage($language){
  		global $conf;
  		$datestrings = '';
  		$dtimevalue = ($conf['rp_time_12h'] != 1) ? "%H:%M" : "%I:%M %p";
  		  
      if($conf['rp_date_format'] == '1'){
        // MM.DD.YYYY
  			$datestrings1 = array(
  						'medium'		=> "%m-%d-%Y, ".$dtimevalue,
  						'long'			=> "%A, %m-%d-%Y, ".$dtimevalue,
  						'short'			=> "%m-%d-%Y",
  			);
      }else{
  		  $datestrings1 = array(
  						'medium'		=> "%d.%m.%Y, ".$dtimevalue,
  						'long'			=> "%A, %d.%m.%Y, ".$dtimevalue,
  						'short'			=> "%d.%m.%Y",
  			);
  		}
  		
  		$datestrings2 = array(
  		        'time'			=> $dtimevalue,
  		        'day'				=> "%A",
  		        'jstime'		=> "%d.%m.%Y" // do not change. will cause bugs!
      );
      
      if($language == 'de'){
  		  $datestrings3 = array(
              'local'           => array('de_DE@euro', 'de_DE', 'de', 'ge', 'de-de', 'de_DE.ISO8859-1', 'german', 'de_DE.ISO_8859-15'),
  						'specialsettings' => false
        );
  		}else{
  		  $datestrings3 = array(
        			'local'           => "en_US",
  						'specialsettings' => true,
  			);
      }
  		$this->datestrings = array_merge($datestrings1, $datestrings2, $datestrings3);
  	}
  	
  	/**
  	* Calculate the time difference between two timestamps
  	*
  	* @param $month  Month     int
  	* @param $year   Year      int
  	* @return month names      array
  	*/
  	function MonthName($month, $year){
  	 global $user;
  	 $month = sprintf ("%02d",$month);
      switch ($month){
          case '01': 	$dayInMonth = 31; $MonthName=$user->lang['rp_january']; break;
          case '02': 	( $year%4 == 0 ) ? $dayInMonth = 29 : $dayInMonth = 28; $MonthName=$user->lang['rp_february']; break;
          case '03':  $dayInMonth = 31; $MonthName=$user->lang['rp_march']; break;
          case '04':  $dayInMonth = 30; $MonthName=$user->lang['rp_april']; break;
          case '05':  $dayInMonth = 31; $MonthName=$user->lang['rp_may']; break;
          case '06':  $dayInMonth = 30; $MonthName=$user->lang['rp_june']; break;
          case '07':  $dayInMonth = 31; $MonthName=$user->lang['rp_july']; break;
          case '08':  $dayInMonth = 31; $MonthName=$user->lang['rp_august']; break;
          case '09':  $dayInMonth = 30; $MonthName=$user->lang['rp_september']; break;
          case '10':  $dayInMonth = 31; $MonthName=$user->lang['rp_october']; break;
          case '11':  $dayInMonth = 30; $MonthName=$user->lang['rp_november']; break;
          case '12':  $dayInMonth = 31; $MonthName=$user->lang['rp_december']; break;
      }
      $month_array = array(
                        'days'    => $dayInMonth,
                        'name'    => $MonthName
                      );
      return $month_array;
    }
  	
  	/**
  	* Generate the Weekday Array for Calendar Mode
  	*
  	* @param $startday The First day in the week
  	* @return array week
  	*/
  	function weekdays($startday){
      global $user;
      $weekdays0 = array($user->lang['rp_sunday']);
      $weekdays1 = array(
                    $user->lang['rp_monday'],
                    $user->lang['rp_tuesday'],
                    $user->lang['rp_wednesday'],
                    $user->lang['rp_thursday'],
                    $user->lang['rp_friday'],
                    $user->lang['rp_saturday']
                  );
      
      return ($startday == 'monday') ? array_merge($weekdays1, $weekdays0) : array_merge($weekdays0, $weekdays1);
    }
  	
  	/**
  	* Calculate the time difference between two timestamps
  	*
  	* @param $time_a   Timestamp A     int
  	* @param $time_b   Timestamp B     int
  	* @return time diff                string
  	*/
    function datediff($time_a, $time_b='') {
      $time_b     = ($time_b) ? $time_b : time();
      if($time_a>$time_b){
        $tmp      = $time_b;
        $time_b   = $time_a;
        $time_a   = $tmp;
      }	  
      $timevalue            = array();
      $difference           = $time_b - $time_a;
      $timevalue['days']    = floor($difference / (60 * 60 * 24));
      $difference           %= (60 * 60 * 24);
      $timevalue['hours']   = floor($difference / (60 * 60));
      $difference           %= (60 * 60);
      $timevalue['minutes'] = floor($difference / 60);
      $timevalue['seconds'] = $difference % 60;
      return $timevalue;
    }
  	
  	/**
  	* Checks if there's a DST change between two dates, if yes
  	* output the correction value L = {-1,0,1}
  	*
  	* @param $past   Timestamp n the past/now
  	* @param $future Timestamp in the future
  	* @return string Raid value
  	*/
  	function DST_Correction($future, $past='', $cloned=''){
      $myout = 0;
      if($this->dstcheck == '1'){    
    	  $past = ($past) ? $past : time();
    	  
    	  // if past is newer than future..
        if($past > $future){
          $tmp      = $future;
          $future   = $past;
          $past     = $tmp;
        }	
        
        // Get the DST on the dates
        $dst_past   = date('I', $past);
        $dst_future = date('I', $future);
        
        // Summer -> Winter
        if($dst_past == '1' && $dst_future == '0'){
          $myout = 0;
          // Winter -> Summer, which means gmt+1 to gmt+2 = +1 hour
        }elseif($dst_past == '0' && $dst_future == '1'){
          $myout = 0;
          //Now we take summer to summer, which means gmt+2 to gmt+2 = 0 hour
        }elseif($dst_past == '1' && $dst_future == '1' && !$cloned){
          $myout = +3600;
          //The rest is just winter to winter = +-0 hour
        }else{
          $myout = 0;
        }
      }
      return $myout;
    }
    
    /**
        * Get the "real timezone", by calculating:
        * User Timezone - Server Timezone = (Real Timezone + User Timezone)
        *
        * @param $timestamp  Timestamp
        * @return string Raid value
        */
    function RealTimeZone(){
          // get the Server Timezone:
      $server_tz  = date('Z')- date('I')*3600;   //
      $user_tz    = ($this->timezone) ? ($this->timezone*3600) : 0;
      $tz_diff    = $server_tz - $user_tz;
      $real_tz    = -$tz_diff+$server_tz;
      //echo '('.$server_tz.'|'.$user_tz.'#'.$real_tz.')';

      return $real_tz;
        }
    
  }
}
?>

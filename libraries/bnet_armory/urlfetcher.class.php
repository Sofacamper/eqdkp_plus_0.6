<?php
 /*
 * Project:		EQdkp-Plus
 * License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:		2011
 * Date:		$Date: 2011-09-20 22:29:06 +0200 (Tue, 20 Sep 2011) $
 * -----------------------------------------------------------------------
 * @author		$Author: wallenium $
 * @copyright	2006-2011 EQdkp-Plus Developer Team
 * @link		http://eqdkp-plus.com
 * @package		eqdkp-plus
 * @version		$Rev: 11302 $
 * 
 * $Id: urlfetcher.class.php 11302 2011-09-20 20:29:06Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}

class urlfetcher {

	private $useragent			= 'EQDKP-PLUS (http://www.eqdkp-plus.com)';		// User Agent
	private $timeout			= array('curl'=> 10);							// Timeout for curl
	private $methods			= array('curl','file_gets','fopen');			// available function methods
	private $method				= '';											// the selected method

	public function get_method(){
		return $this->method;
	}

	public function __construct($method=false){
		if($method){
			$this->method = $method;
		}else{
			foreach($this->methods as $methods){
				if(!$this->check_function($methods)){
					continue;
				}
				$this->method = $methods;
				break;
			}
		}
	}

	/**
	 * Return the Data
	 * Checks all given methods to get the date from the url
	 *
	 * @param String $geturl
	 * @return string
	 */
	public function fetch($geturl, $header=''){
		$this->method = ($this->method) ? $this->method : 'fopen';
		return $this->{'get_'.$this->method}($geturl, $header);
	}

	/**
	 * Try to get the data from the URL via the curl function
	 *
	 * @param string $geturl
	 * @return string
	 */
	private function get_curl($geturl, $header){
		$curlOptions = array(
			CURLOPT_URL				=> $geturl,
			CURLOPT_USERAGENT		=> $this->useragent,
			CURLOPT_TIMEOUT			=> $this->timeout['curl'],
			CURLOPT_ENCODING		=> "gzip",
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_SSL_VERIFYHOST	=> false,
			CURLOPT_SSL_VERIFYPEER	=> false,
			CURLOPT_VERBOSE			=> false,
			CURLOPT_HTTPAUTH		=> CURLAUTH_ANY,
			CURLOPT_HTTPHEADER		=> ((is_array($header) && count($header) > 0) ? $header : array())
		);
		if (!(@ini_get("safe_mode") || @ini_get("open_basedir"))) {
			$curlOptions[CURLOPT_FOLLOWLOCATION] = true;
		}
		$curl = curl_init();
		curl_setopt_array($curl, $curlOptions);
		$getdata = curl_exec($curl);
		curl_close($curl);
		return $getdata;
	}

	/**
	 * Try to get the data from the URL via the file_get_contents function
	 *
	 * @param string $geturl
	 * @return string
	 */
	private function get_file_gets($geturl, $header){	
		// set the useragent first. if not, you'll get the source....
		if(function_exists('ini_set')){
			@ini_set('user_agent', $this->useragent);
		}else{
			$_SERVER["HTTP_USER_AGENT"] = $this->useragent;
		}

		$opts = array (
			'http'	=>array (
				'method'	=> 'GET',
				'header'	=> ((is_array($header) && count($header) > 0) ? implode("\r\n", $header): '')
			)
		);
		$context	= @stream_context_create($opts);
		$getdata	= @file_get_contents($geturl, false, $context);
		return $getdata;
	}

	/**
	 * Try to get the data from the URL via the fsockopen function
	 *
	 * @param string $geturl
	 * @return string
	 */
	private function get_fopen($geturl, $header){
		$url_array	= parse_url($geturl);
		$getdata = '';
		if (isset($url_array['host']) AND $fp = @fsockopen($url_array['host'], 80, $errno, $errstr, 5)){
			$out	 = "GET " .$url_array['path']."?".((isset($url_array['query'])) ? $url_array['query'] : '')." HTTP/1.0\r\n";
			$out	.= "Host: ".$url_array['host']." \r\n";
			$out	.= "User-Agent: ".$this->useragent;
			$out	.= "Connection: Close\r\n";
			$out	.= ((is_array($header) && count($header) > 0) ? implode("\r\n", $header): '');
			$out	.= "\r\n";
			fwrite($fp, $out);

			// Get rid of the HTTP headers
			while ($fp && !feof($fp)){
				$headerbuffer = fgets($fp, 1024);
				if (urlencode($headerbuffer) == "%0D%0A"){
					// We've reached the end of the headers
					break;
				}
			}
			// Read the raw data from the socket in 1kb chunks
			while (!feof($fp)){
				$getdata .= fgets($fp, 1024);
			}
			fclose($fp);
		}
		return $getdata;
	}

	private function check_function($method){
		switch($method){
			case 'curl':			$func_ex = 'curl_init';			break;
			case 'file_gets':		$func_ex = 'file_get_contents';	break;
			case 'fopen':			$func_ex = 'fgets';				break;
			default: $func_ex =$method;
		}
		return (function_exists($func_ex)) ? true : false;
	}
}
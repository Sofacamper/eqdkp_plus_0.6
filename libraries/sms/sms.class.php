<?php
 /*
 * Project:     eqdkpPLUS Library Manager
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-03-05 13:55:22 +0100 (Do, 05 Mrz 2009) $
 * ----------------------------------------------------------------------- 
 * @author      $Author: wallenium $
 * @copyright   2006-2009 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     libraries
 * @version     $Rev: 4117 $
 * 
 * $Id: sms.class.php 5403 2009-08-02 15:40:22Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

/**
 * SMS Send  Class written by Stefan "Corgan" Knaak
 * works only with the api on www.sms-news.de
 */
class sms
{
	
	var $username = "";
	var $passwort = "";
	
	public function sendSMS($send_to,$message)
	{		
	    $postdata = 'xml_daten=%3C%3Fxml+version%3D%221.0%22%3F%3E%0D%0A%3Cident+user%3D%22'
		            .$this->username.'%22+pass%3D%22'
	                .$this->passwort.'%22%3E%0D%0A%3Ccontent+id%3D%22100%22%3E%0D%0A%3Cmessage%3E'
	                .$message.'%3C%2Fmessage%3E%0D%0A%3Ctarget+structur%3D%22adresse%22%3E'
	                .$send_to.'%3C%2Ftarget%3E%0D%0A%3C%2Fcontent%3E%0D%0A%3C%2Fident%3E'
	                .'&senden=senden';
	                         
	    $sslsock = fsockopen("ssl://www.sms-news-media.de", 443, $errno, $errstr, 30);
	    
	    if(is_resource($sslsock)) 
	    {
	        fputs($sslsock, "POST /evo2/schnittstelle/xml_versand_0905.cfm HTTP/1.0\r\n");
	        fputs($sslsock, "Host: www.sms-news-media.de\r\n");
	        fputs($sslsock, "Content-type: application/x-www-form-urlencoded\r\n");
	        fputs($sslsock, "Content-Length: ".strlen($postdata)."\r\n");
	        fputs($sslsock, "Connection: close\r\n\r\n");
	        fputs($sslsock, $postdata);
	        $res = "";
	        while(!feof($sslsock))
	        {
	            $res .= @fgets($sslsock, 2048);
	        }
	        fclose($sslsock);                    
	
	        $xml_return = substr($res,strpos($res,'xml')-2);
	        if (version_compare(phpversion(), "5.0.0", ">="))
			{        	                
	        	$xml = $this->XML2Array($xml_return);
	        	if(is_array($xml)) 
		        {    		        	
		        	$return['status'] = $xml['@attributes']['status'];		        		        	 
		        	$return['msg'] = $xml[0]; 
		        }
					        
		        $return = $return;		        
			}else
			{
				$return['status'] = '-1';		        		        	 
		       	$return['msg'] = str_replace('<','&lt;',$xml_return); 		        	
			}		        	
		}
	    else
	    {
	    	$return['status'] = '-2';		        		        	 
		    $return['msg'] = $errno. " ". $errstr;		    	        
	    }	
		
	    return $return;
	}
	
	private function XML2Array ( $xml , $recursive = false )
	{
	    if ( ! $recursive )
	    {
	        $array = simplexml_load_string ( $xml ) ;
	    }
	    else
	    {
	        $array = $xml ;
	    }
	   
	    $newArray = array () ;
	    $array = ( array ) $array ;
	    foreach ( $array as $key => $value )
	    {
	        $value = ( array ) $value ;
	        if ( isset ( $value [ 0 ] ) )
	        {
	            $newArray [ $key ] = trim ( $value [ 0 ] ) ;
	        }
	        else
	        {
	            $newArray [ $key ] = $this->XML2Array ( $value , true ) ;
	        }
	    }
	    return $newArray ;
	}

}// end of class
?>
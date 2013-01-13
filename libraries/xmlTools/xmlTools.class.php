<?php
 /*
 * Project:     eqdkpPLUS Libraries: xmlTools
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-09-07 14:52:36 +0200 (Mon, 07 Sep 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: hoofy_leon $
 * @copyright   2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     libraries:xmlTools
 * @version     $Rev: 5808 $
 *
 * $Id: xmlTools.class.php 5808 2009-09-07 12:52:36Z hoofy_leon $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
if (!class_exists("xmlTools")) {
  class xmlTools
  {
    // Prepare an xml string to save
    public function prepareSave($xml){
      $xml = addslashes($xml);
			$xml = @base64_encode(gzcompress(serialize($xml)));
			return $xml;
    }

    // Prepare an xml string to load
    public function prepareLoad($xml){
      $xml = @base64_decode($xml);
      $xml = @gzuncompress($xml);
			$xml = @unserialize($xml);
			$xml = stripslashes($xml);
			return $xml;
    }

    // Converts an Array to an serialized XML object to be saved
    // in a MySQL database
    public function Array2Database($array, $name="config" ,&$xml=null ){
      $mysxml = $this->array2simplexml($array, $name);
      return serialize($mysxml->asXML());
    }

    // Convert a serialized XML object back to an array
    function Database2Array($fieldname){
      $unser_fieldname  = ($this->isSerialized($fieldname)) ? @unserialize($fieldname) : $fieldname;
      $xml_obj          = simplexml_load_string($unser_fieldname);
      return $this->simplexml2array($xml_obj);
    }

    // Array to SimpleXML converter
    function array2simplexml($array, $name="config" ,&$xml=null ){
      if(is_null($xml)){
          $xml = new SimpleXMLElement("<{$name}/>");
      }

      foreach($array as $key => $value){
        if($key === '@attributes'){
          if(is_array($value)){
          	  foreach($value as $name => $val){
          	  	$xml->addAttribute($name, $val);
          	  }
          }
        } else {
          if(is_array($value)){
              $xml->addChild($key);
              $this->array2simplexml($value, $name, $xml->$key);
          }else{
              $xml->addChild($key, $value);
          }
        }
      }
      return $xml;
    }

    // SimpleXML to Array Converter
    function simplexml2array($knoten){
      $xmlArray = array();
      if(is_object($knoten)){
          settype($knoten,'array') ;
      }
      if(is_array($knoten)){
        foreach($knoten as $key=>$value){
            if(is_array($value)||is_object($value)){
                $xmlArray[$key] = $this->simplexml2array($value);
            }else{
                $xmlArray[$key] = $value;
            }
        }
      }
      return $xmlArray;
    }

    // Check if the file is serialized
    function isSerialized($str) {
      return ($str == serialize(false) || @unserialize($str) !== false);
    }
  }
}
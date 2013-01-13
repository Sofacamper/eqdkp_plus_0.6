<?php
 /*
 * Project:     EQdkp Newsletter
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-06-22 00:32:23 +0200 (Mo, 22. Jun 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     newsletter
 * @version     $Rev: 5093 $
 * 
 * $Id: common.php 5093 2009-06-21 22:32:23Z wallenium $
 */

  if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
  } 
  
  if ( !isset($eqdkp_root_path) ){
    $eqdkp_root_path = './';
  }
  include_once($eqdkp_root_path . 'common.php');

  /**
  * Load the global Configuration
  */

  //Cache: plugin.newsletter.config
  $config_cache = $pdc->get('plugin.newsletter.config',false,true);
  if (!$config_cache){
		$config_query = $db->query("SELECT * FROM __newsletter_config");
		$config_data = $db->fetch_record_set($config_query);
		$db->free_result($config_query);
		$pdc->put('plugin.newsletter.config',$config_data,86400,false,true);
  } else{
		$config_data = $config_cache;
  };
	
	if (is_array($config_data)){
		foreach ($config_data as $elem){
			$conf[$elem['config_name']] = $elem['config_value'];
		};
	};	

  /**
  * Framework include
  **/
  require_once($eqdkp_root_path . 'plugins/newsletter/include/libloader.inc.php');
  $wpfcdb = new AdditionalDB('newsletter_config');
  $htmlclass = new myHTML();

  /**
  * Load rest of Libraries
  */
  include_once($eqdkp_root_path . '/plugins/newsletter/include/newsletter.class.php');
  $nlclass = new NewsletterClass();
  
  //BB-Code Parser
  include_once($eqdkp_root_path . '/plugins/newsletter/include/stringparser_bbcode/stringparser.class.php');
  include_once($eqdkp_root_path . '/plugins/newsletter/include/stringparser_bbcode/stringparser_bbcode.class.php');
  $nl_bbcode = new StringParser_BBCode ();
  $nl_bbcode->addCode ('table', 'callback_replace', doTables, array (),
                  'table', array ('block', 'table', 'td', 'tr', 'class'), array ());
  $nl_bbcode->addCode ('tr', 'simple_replace', null, array ('start_tag' => '<tr>', 'end_tag' => '</tr>'),
                  'tr', array ('table'), array ());
  $nl_bbcode->addCode ('td', 'simple_replace', null, array ('start_tag' => '<td>', 'end_tag' => '</td>'),
                  'td', array ('tr'), array ());
  $nl_bbcode->addCode ('class', 'usecontent?', "doClass", array ('usecontent_param' => 'default'),
                  'class', array ('block', 'td', 'class'), array ());
  
  function doClass ($action, $attributes, $content, $params, $node_object) {
    if (!isset ($attributes['default'])) {
        $out = $content;

    } else {
        $class = $attributes['default'];
        $out = "<span class=\"".$class."\">".$content."</span>";
    }
    if ($action == 'validate') {
        return true;
    }
    return $out;
}

  function doTables ($action, $attributes, $content, $params, $node_object) {
		$content = str_replace("\n", "", $content);
		$content = str_replace("<br>", "", $content);
        $out = "<table>".$content."</table>";
	if ($action == 'validate') {
        return true;
    }
    return $out;
}
  
  
  /**
  * JQUERY Header
  */
  $tpl->assign_vars(array('JQUERY_INCLUDES'   => $jquery->Header()));
  
?>
<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2011-02-09 14:49:26 +0100 (Wed, 09 Feb 2011) $
 * -----------------------------------------------------------------------
 * @author      $Author: Godmod $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 9691 $
 * 
 * $Id: logs.class.php 9691 2011-02-09 13:49:26Z Godmod $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}
if (!class_exists("LogManager")) {
  class LogManager
  {
    var $version  = '0.0.0';
    var $build    = '17072008a';
  
    function LogManager($plugin){
      global $table_prefix, $db;
      $this->pluginname = $plugin;
      $this->db_prefix  = $table_prefix;
      $this->db_table   = $table_prefix.$plugin.'_logs';
      
      $result = $db->query("SHOW TABLE STATUS LIKE '".$this->db_table."'");
      if (!$db->num_rows($result)){
        $this->AddTable();
        //echo 'LogManager Table added';
      }
    }
    
    function Clean($timestamp){
      global $db, $user, $stime;
      $log_date = $stime->DoTime()-($timestamp*24*60*60);
      $sql = 'DELETE FROM '.$this->db_table.' WHERE log_date < '.$log_date;
      $db->query($sql);
    }

	function logs_total(){
      global $db;
      return $db->query_first('SELECT count(*) FROM '.$this->db_table);
    }

    function AddEntry($attachto, $tag, $value, $result='{L_SUCCESS}'){
      global $db, $user, $stime;
      $query = $db->build_query('INSERT', array(
                'log_attachto'    => $attachto,
								'log_value'       => $this->make_log_action($value),
								'log_result'      => $result,
								'log_tag'         => $tag,
								'log_date'        => $stime->DoTime(),
								'log_ipaddress'   => $user->ip_address,
								'log_sid'         => $user->sid,
								'user_id'         => $user->data['user_id'],
			));
			$db->query('INSERT INTO ' . $this->db_table . $query);
    }
    
    function GetList($log_id=''){
      global $db, $current_order;
      $start = ( isset($_GET['start']) ) ? $_GET['start'] : 0;
      $sql = 'SELECT l.*, u.username FROM (' . $this->db_table . ' l
              LEFT JOIN ' . USERS_TABLE . ' u
              ON u.user_id=l.user_id )';
      if($log_id){
        $sql .= " WHERE log_id='".$log_id."'";
      }
      $current_order['sql'] = ($current_order['sql']) ? $current_order['sql'] : 'log_date desc';
      $readysql = $sql.' ORDER BY '.$current_order['sql'].' LIMIT '.$start.',100';
      $result = $db->query($readysql);
      
      while ( $log = $db->fetch_record($result) ){
        $data_out[$log['log_id']] = array(
                'id'        => $log['log_id'],
                'date'      => $log['log_date'],
                'sid'       => $log['log_sid'],
                'tag'       => $this->lang_replace($log['log_tag']), 
                'value'     => $this->lang_replace($log['log_value']),
                'ip'        => $log['log_ipaddress'],
                'result'    => $this->lang_replace($log['log_result']),
                'user'      => $log['username'],
                'attached'  => $log['log_attachto'],
        );
      }
      return $data_out;
    }
    
    // Add the Plugin Log Table
    function AddTable(){
      global $db;
      $sql = "CREATE TABLE ".$this->db_table." (
                  `log_id` int(11) unsigned NOT NULL auto_increment,
                  `log_attachto` int(11) NOT NULL default '0',
                  `log_date` int(11) NOT NULL default '0',
                  `log_value` text NOT NULL default '',
                  `log_ipaddress` varchar(15) NOT NULL default '',
                  `log_sid` varchar(32) NOT NULL default '',
                  `log_result` varchar(255) NOT NULL default '',
                  `log_tag` varchar(255) NOT NULL default '',
                  `user_id` smallint(5) NOT NULL default '0',
                PRIMARY KEY  (`log_id`),
                KEY `user_id` (`user_id`),
                KEY `log_tag` (`log_tag`),
                KEY `log_ipaddress` (`log_ipaddress`)
              )";
      $db->query($sql);
    }
  
    // Language Replacement
    function lang_replace($variable){
      global $user;

      preg_match("/\{L_(.+)\}/", $variable, $to_replace);
      if ( (isset($to_replace[1])) && (isset($user->lang[strtolower($to_replace[1])]))){
        $variable = str_replace('{L_'.$to_replace[1].'}', $user->lang[strtolower($to_replace[1])], $variable);
      }
      return $variable;
    }
    
    function make_log_action($action = array()){
        $str_action = "\$log_action = array(";
        foreach ( $action as $k => $v ){
            $str_action .= "'" . $k . "' => '" . addslashes($v) . "',";
        }
        $action = substr($str_action, 0, strlen($str_action)- 1) . ");";

        // Take the newlines and tabs (or spaces > 1) out of the action
        $action = preg_replace("/[[:space:]]{2,}/", '', $action);
        $action = str_replace("\t", '', $action);
        $action = str_replace("\n", '', $action);
        $action = preg_replace("#(\\\){1,}#", "\\", $action);

        return $action;
    }
  }
}
?>

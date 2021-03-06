<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2010-07-11 16:25:04 +0200 (Sun, 11 Jul 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2010 EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 8345 $
 * 
 * $Id: phpbb2.php 8345 2010-07-11 14:25:04Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

// Define the tables & configuration options
$table_topics   = trim($conf_plus['pk_latestposts_dbprefix']). "topics";
$table_forums   = trim($conf_plus['pk_latestposts_dbprefix']). "forums";
$table_posts    = trim($conf_plus['pk_latestposts_dbprefix']). "posts";
$table_users    = trim($conf_plus['pk_latestposts_dbprefix']). "users";

// Build the db query
$myBBquery  = "SELECT t.topic_title as bb_topic_title, 
              t.topic_last_post_id as bb_last_post, t.topic_replies as bb_replies,
              p.post_id as bb_post_id, p.poster_id as bb_poster_id, p.post_time as bb_posttime, 
              u.user_id as bb_user_id, u.username as bb_username
              FROM ".$table_topics." t, ".$table_forums." f, ".$table_posts." p, ".$table_users." u
              WHERE t.topic_id = p.topic_id AND
              f.forum_id = t.forum_id AND
              t.topic_status <> 2 AND ";
if(is_array($privateforums)){
  $myBBquery .= "t.forum_id ".$black_or_white."(". implode(', ', $privateforums).") AND ";
}
$myBBquery .= "p.post_id = t.topic_last_post_id AND
              p.poster_id = u.user_id
              ORDER BY p.post_id DESC LIMIT ".trim($topicnumber);
            
// Link
function bbLinkGeneration($mode, $row){
  global $conf_plus;
  if($mode=='member'){
    return '/profile.php?mode=viewprofile&u='.$row['bb_user_id'];
  }else{
    return '/viewtopic.php?p='.$row['bb_post_id'].'#'.$row['bb_post_id'];
  }
}
?>
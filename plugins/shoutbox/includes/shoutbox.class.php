<?php
/*
 * Project:     EQdkp Shoutbox
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2011-06-29 14:40:50 +0200 (Wed, 29 Jun 2011) $
 * -----------------------------------------------------------------------
 * @author      $Author: corgan $
 * @copyright   2008 Aderyn
 * @link        http://eqdkp-plus.com
 * @package     shoutbox
 * @version     $Rev: 10505 $
 *
 * $Id: shoutbox.class.php 10505 2011-06-29 12:40:50Z corgan $
 */

if (!defined('EQDKP_INC'))
{
  header('HTTP/1.0 404 Not Found');exit;
}


/*+----------------------------------------------------------------------------
  | Shoutbox
  +--------------------------------------------------------------------------*/
if (!class_exists("Shoutbox"))
{
  class Shoutbox
  {
    var $smiley_path;  /* The smiley path */
    var $rss;          /* RSS object      */
    var $reqVersions = array( /* Required versions */
        'php'   => '5.0.0',
        'eqdkp' => '0.6.3.5'
    );

    /**
     * Constructor
     */
    public function __construct()
    {
      global $eqdkp, $pcache;

      // set smiley path
      $this->smiley_path = 'libraries/jquery/images/editor/icons';

      $this->rss = new UniversalFeedCreator();
      $this->rss->title          = 'Shoutbox';
      $this->rss->description    = $eqdkp->config['main_title'].' - Shoutbox';
      $this->rss->link           = $pcache->BuildLink();
      $this->rss->syndicationURL = $pcache->BuildLink().$_SERVER['PHP_SELF'];
    }

    /**
     * checkRequirements
     * Check the shoutbox requirements
     *
     * @returns true if success, otherwise error string
     */
    public function checkRequirements()
    {
      global $user;

      // set defult to OK
      $result = true;

      // compare
      if (version_compare(phpversion(), $this->reqVersions['php'], "<"))
      {
        $result = sprintf($user->lang['sb_php_version'], $this->reqVersions['php'], phpversion());
      }
      else if (version_compare(EQDKPPLUS_VERSION, $this->reqVersions['eqdkp'], "<"))
      {
        $result = sprintf($user->lang['sb_plus_version'], $this->reqVersions['eqdkp'],
                          ((EQDKPPLUS_VERSION > 0) ? EQDKPPLUS_VERSION : '[non-PLUS]'));
      }

      return $result;
    }

    /**
     * getUserIdFromMemberId
     * Get the user id from a member id
     *
     * @param  int  $member_id  member id
     *
     * @return integer
     */
    public function getUserIdFromMemberId($member_id)
    {
      global $db;

      $user_id = ANONYMOUS;

      if ($member_id > 0)
      {
        $sql = 'SELECT user_id FROM `__member_user` WHERE member_id='.$member_id;
        $user_id = $db->sql_query_first($sql);
      }

      return $user_id;
    }

    /**
     * getNumShoutboxEntries
     * Get the number of shoutbox entries in database
     *
     * @returns integer
     */
    public function getNumShoutboxEntries()
    {
      global $db;

      $sql = 'SELECT COUNT(*) FROM `__shoutbox`';
      $count = $db->query_first($sql);

      return $count;
    }

    /**
     * getShoutboxEntries
     * Get all shoutbox entries as array
     *
     * @return array(
     *           'name',
     *           'class_id',
     *           'member_id',
     *           'date',
     *           'text',
     *           'id'
     *         )
     *
     */
    public function getShoutboxEntries($start = 0, $limit = false, $decode=false)
    {
      global $conf_plus, $db, $sb_conf;

      // init array
      $shoutbox = array();

      // (re)set limit
      if ($limit === false)
      {
        $limit = ($conf_plus['sb_output_count_limit'] > 0 ? $conf_plus['sb_output_count_limit'] : SHOUTBOX_DEFAULT_LIMIT);
      }

      // get last $(limit) entries
      $sql = 'SELECT members.member_name, members.member_class_id, members.member_id,
                     shoutbox.shoutbox_date, shoutbox.shoutbox_text, shoutbox.shoutbox_id
              FROM `__shoutbox` AS shoutbox
              LEFT JOIN `__members` AS members ON members.member_id = shoutbox.member_id
              ORDER BY shoutbox.shoutbox_date DESC
              LIMIT '.$start.' , '.$limit;
      $result = $db->query($sql);
      if ($result)
      {
        while ($row = $db->fetch_record($result))
        {
          if ($sb_conf['sb_dstcorrect'] == 1)
          {
            $row['shoutbox_date'] = $row['shoutbox_date']+date('I')*3600;
          }

          $shoutbox[] = array(
            'name'      => htmlspecialchars(($decode == true) ? utf8_encode($row['member_name']) : $row['member_name']),
            'class_id'  => $row['member_class_id'],
            'member_id' => $row['member_id'],
            'date'      => $row['shoutbox_date'],
            'text'      => ($decode == true) ? utf8_encode(stripslashes($row['shoutbox_text'])) : stripslashes($row['shoutbox_text']),
            'id'        => $row['shoutbox_id'],
          );
        }
        $db->sql_freeresult($result);
      }

      return $shoutbox;
    }

    /**
     * insertShoutboxEntry
     * Insert a shoutbox entry for current member
     *
     * @param    int    $member_id   member id
     * @param    string $text        text to insert
     * @param    int    $tz          timezone offset
     */
    public function insertShoutboxEntry($member_id, $text, $tz=0)
    {
      global $user, $db;

      // get timezone
      $timezone = ($tz != '' && is_numeric($tz)) ? intval($tz) : 0;

      // is user allowed to add a shoutbox entry?
      if ($user->data['user_id'] != ANONYMOUS && $user->check_auth('u_shoutbox_add', false))
      {
        // clean input
        $text_insert = strip_tags($text); // No html or javascript in comments
        $text_insert = $this->shoutbox_wordwrap($text_insert, SHOUTBOX_WORDWRAP, "\n", true);
        $text_insert = $this->utf8_htmlentities($text_insert);
        $text_insert = $this->toHTML($text_insert);

        // get current timestamp
        $cur_time = time() + ($timezone*3600);
        $cur_timestamp = mktime(gmdate('H', $cur_time), gmdate('i', $cur_time), gmdate('s', $cur_time),
                                gmdate('n', $cur_time), gmdate('j', $cur_time), gmdate('Y', $cur_time));
        // insert
        $sql = 'INSERT INTO `__shoutbox` (`member_id`, `shoutbox_text`, `shoutbox_date`)
                VALUES ('.$member_id.', \''.$db->sql_escape($text_insert).'\', '.$cur_timestamp.')';
        $result = $db->query($sql);
        return ($result ? true : false);
      }

      return false;
    }

    /**
     * deleteShoutboxEntry
     * delete a shoutbox entry
     *
     * @param  int  $shoutbox_id  shoutbox entry id
     */
    public function deleteShoutboxEntry($shoutbox_id)
    {
      global $user, $db;

      // is user owner of the shoutbox entry or is admin?
      if (($user->data['user_id'] != ANONYMOUS && $user->data['user_id'] == $this->getUserIdFromShoutboxId($shoutbox_id)) ||
          ($user->check_auth('a_shoutbox_delete', false)))
      {
        $sql = 'DELETE FROM `__shoutbox` WHERE shoutbox_id='.$shoutbox_id;
        $result = $db->query($sql);
        return ($result ? true : false);
      }

      return false;
    }

    /**
     * showShoutbox
     * show the complete shoutbox
     *
     * @return  string
     */
    public function showShoutbox()
    {
      global $user, $conf_plus;

      $html = '';

      // only output if visible to guest
      if ($user->data['user_id'] != ANONYMOUS || $conf_plus['sb_invisible_to_guests'] != 1)
      {
        // javascript code
        $html .= $this->getShoutboxJCode();

        // is input above (and user logged in?) append form
        if ($conf_plus['sb_input_box_below'] != 1 &&
            $user->data['user_id'] != ANONYMOUS && $user->check_auth('u_shoutbox_add', false))
        {
          $html .= $this->getForm();
        }

        // content table
        $html .= '<div id="htmlShoutboxTable">';
        $html .= $this->getContent();
        $html .= '</div>';

        // archive link (and user logged in?)
        if ($conf_plus['sb_show_archive'] &&
            $user->data['user_id'] != ANONYMOUS && $user->check_auth('u_shoutbox_add', false))
        {
          $html .= $this->getArchiveLink();
        }

        // is input below (and user logged in?) append form
        if ($conf_plus['sb_input_box_below'] == 1 &&
            $user->data['user_id'] != ANONYMOUS && $user->check_auth('u_shoutbox_add', false))
        {
          $html .= $this->getForm();
        }
      }

      return $html;
    }

    /**
     * getContent
     * get the content of the shoutbox
     *
     * @param  string   $rpath   root path
     * @param  boolean  $decode  UTF8 decode?
     *
     * @return  string
     */
    public function getContent($rpath='', $decode=false)
    {
      global $user, $eqdkp, $SID, $eqdkp_root_path, $conf_plus, $pcache;

      // root path
      $root_path = ($rpath != '') ? $rpath : $eqdkp_root_path;

      $html = '';

      // the delete form
      if ($user->data['user_id'] != ANONYMOUS)
      {
        $html .= '<form id="del_shoutbox" name="del_shoutbox" action="'.$eqdkp_root_path.'plugins/shoutbox/shoutbox.php" method="post">
                  </form>';
      }

      // get shoutbox entries
      $shoutbox_entries = $this->getShoutboxEntries(0, false, $decode);
      $count = count($shoutbox_entries);
      if ($count > 0 && is_dir($root_path))
      {
        // output table header
        $html .= '<table width="100%" border="0" cellspacing="1" cellpadding="2">';
        // input above?
        if ($conf_plus['sb_input_box_below'] != 1 &&
            $user->data['user_id'] != ANONYMOUS && $user->check_auth('u_shoutbox_add', false))
        {
          $html .= '<tr><th>&nbsp;</th></tr>';
        }

        foreach ($shoutbox_entries as $entry)
        {
          // cleanup text
          $entry['text'] = $this->getCleanOutput($entry['text'], $root_path, $decode);

          // get class for row
          $class = $eqdkp->switch_row_class();


          $html .= '<tr class="'.$class.'" onmouseout="this.className=\''.$class.'\';" onmouseover="this.className=\'rowHover\';">
                      <td>';

          // if logged in and (admin or own entry), ouput delete link
          if (($user->data['user_id'] != ANONYMOUS) &&
              ($user->data['user_id'] == $this->getUserIdFromMemberId($entry['member_id']) ||
               $user->check_auth('a_shoutbox_delete', false)))
          {
            $img = $root_path.'images/global/delete.png';
            $delete_text = ($decode == true) ? utf8_encode($user->lang['delete']) : $user->lang['delete'];

            // Java Script for delete
            $html .= '<span class="small bold floatRight hand" onclick="$(\'#del_shoutbox\').ajaxSubmit(
                        {
                          target: \'#htmlShoutboxTable\',
                          url:\''.$root_path.'plugins/shoutbox/shoutbox.php'.$SID.'&shoutbox_delete='.$entry['id'].'&sb_root='.$root_path.'\',
                          beforeSubmit: function(formData, jqForm, options) {
                            deleteShoutboxRequest(\''.$root_path.'\', '.$entry['id'].', \''.$delete_text.'\');
                          }
                        }); ">
                        <span id="shoutbox_delete_button_'.$entry['id'].'">
                          <img src="'.$img.'" alt="'.$delete_text.'" title="'.$delete_text.'"/>
                        </span>
                      </span>';
          }

          // output Date,
          if ($conf_plus['sb_show_date'])
          {
            $html .= date($user->lang['sb_date_format'], $entry['date']).': ';
          }
          else
          {
            $html .= date($user->lang['sb_time_format'], $entry['date']).': ';
          }

          // as well as User and text
          $html .= $this->getColoredClassName($entry['name']).
                   '<br/>'.
                   $entry['text'];

          $html .= '  </td>
                    </tr>';

          // create RSS feed item
          $rssitem = $this->getRSSItem($entry);
          if ($rssitem)
          {
            $this->rss->addItem($rssitem);
          }
        }

        // output table footer
        $html .= '</table>';

        // save RSS
        $this->rss->saveFeed('RSS2.0', $pcache->FilePath('shoutbox.xml', 'shoutbox'), false);
        // add link to RSS
        $html .= '<link rel="alternate" type="application/rss+xml" title="EQDkp-Plus Shoutbox"
                   href="'.$pcache->BuildLink().$pcache->FileLink('shoutbox.xml', 'shoutbox').'" />';

      }
      else
      {
        $no_entries = ($decode == true) ? utf8_encode($user->lang['sb_no_entries']) : $user->lang['sb_no_entries'];

        $html .= '<table width="100%" border="0" cellspacing="1" cellpadding="2">
                    <tr class="'.$eqdkp->switch_row_class().'"><td><div align="center">'.$no_entries.'</div></td></tr>
                  </table>';
      }

      return $html;
    }

    /**
     * getCleanOutput
     * get a clean output
     *
     * @param   string    $text   Text to replace with
     * @param   string    $rpath  root path
     * @param   boolean   $decode  UTF8 decode?
     *
     * @return  string
     */
    public function getCleanOutput($text, $rpath='', $decode=false)
    {
      global $eqdkp_root_path;

      // root path
      $root_path = ($rpath != '') ? $rpath : $eqdkp_root_path;

      // search array
      $search = array(
        '{SMILEY_PATH}',
      );
      // replace array
      $replace = array(
        $root_path.$this->smiley_path,
      );

      // cleanup
      $clean_output = ($decode == true ? utf8_decode($text) : $text);
      $clean_output = str_replace($search, $replace, $clean_output);

      return $clean_output;
    }

    /**
     * getMembersForUser
     * Get all members of current user as array with array() = array('name', 'id', 'class_id')
     *
     * @return  array(
     *            'name',
     *            'id',
     *            'class_id'
     *          )
     */
    private function getMembersForUser()
    {
      global $user, $db;

      $members = array();

      if ($user->data['user_id'] != ANONYMOUS)
      {
        // get number of members for user
        $sql = 'SELECT members.member_name, members.member_id, members.member_class_id
                FROM `__member_user` AS member_user
                LEFT JOIN `__members` AS members ON members.member_id = member_user.member_id
                WHERE member_user.user_id='.$user->data['user_id'];
        $result = $db->query($sql);
        if ($result)
        {
          while ($row = $db->fetch_record($result))
          {
            $members[] = array(
              'name'     => $row['member_name'],
              'id'       => $row['member_id'],
              'class_id' => $row['member_class_id'],
            );
          }
          $db->sql_freeresult($result);
        }
      }

      return $members;
    }

    /**
     * getMemberCountForUser
     * Get the number of member current user
     *
     * @return  integer
     */
    private function getMemberCountForUser()
    {
      global $user, $db;

      $count = 0;

      if ($user->data['user_id'] != ANONYMOUS)
      {
        // get number of members for user
        $sql = 'SELECT COUNT(members.member_id)
                FROM `__member_user` AS member_user
                LEFT JOIN `__members` AS members ON members.member_id = member_user.member_id
                WHERE member_user.user_id='.$user->data['user_id'];
        $count = $db->sql_query_first($sql);
      }

      return $count;
    }

    /**
     * getUserIdFromShoutboxId
     * Get the user id from a shoutbox id
     *
     * @param  int  $shoutbox_id  shoutbox id
     *
     * @return integer
     */
    private function getUserIdFromShoutboxId($shoutbox_id)
    {
      global $db;

      $user_id = ANONYMOUS;

      $sql = 'SELECT m.user_id
              FROM `__member_user` m
              LEFT JOIN `__shoutbox` s
              ON m.member_id = s.member_id
              WHERE s.shoutbox_id='.$shoutbox_id;
      $user_id = $db->sql_query_first($sql);

      return $user_id;
    }

    /**
     * shoutbox_wordwrap
     * Wrap words ignoring bb code
     *
     * @param   string   $text   Text to wrap
     * @param   integer  $width  Max length of one line
     * @param   string   $break  String to insert for line break, default '\n'
     * @param   boolean  $cut    cut inside of words?
     *
     * @return  string
     */
    private function shoutbox_wordwrap($text, $width, $break="\n", $cut=false)
    {
      // explode by spaces
      $element_array = explode(' ', $text);
      $count = count($element_array);

      // loop through all the elements
      $wraped_text = '';
      foreach($element_array as $org_text)
      {
        // explode by \n
        $inner_element_array = explode("\n", $org_text);
        foreach($inner_element_array as $inner_org_text)
        {
          // strip bbcode from text
          $striped_text = preg_replace('#\[[\w=]+\](.*?)\[/[\w]+\]#si', '\1', $inner_org_text);
          // get sriped size
          $striped_size = strlen($striped_text);

          // do not wrap image/urls/emails
          $inner_cut = $cut;
          if (preg_match('#\[img\](.*?)\[/img\]#si', $inner_org_text) ||
              preg_match('#\[url=?"?(.*?)"?\](.*?)\[/url\]#si', $inner_org_text) ||
              preg_match('#\[email\](.*?)\[/email\]#si', $inner_org_text))
          {
            $inner_cut = false;
          }

          // fits?
          if ($striped_size > $width)
          {
            $new_text = wordwrap($striped_text, $width, $break, $inner_cut);
            // replace in original text
            $new_text = str_replace($striped_text, $new_text, $inner_org_text);
          }
          else
          {
            // fit, so just take original text
            $new_text = $inner_org_text;
          }

          // append to output
          $wraped_text .= $new_text."\n";
        }
        // replace last char by space
        $wraped_text[strlen($wraped_text)-1]= ' ';
      }

      return $wraped_text;
    }

    /**
     * escape
     * Escape string
     *
     * @param  array  $s  string to escape
     *
     * @return  string
     */
    private function escape($s)
    {
      global $text;

      $text = strip_tags($text);
      return '<pre><code>'.htmlspecialchars($s[1]).'</code></pre>';
    }

    /**
     * removeBr
     * Clean some tags to remain strict
     * not very elegant, but it works. No time to do better ;)
     *
     * @param  array  $s  string to remove br
     *
     * @return  string
     */
    private function removeBr($s)
    {
      return preg_replace('/\<br[\s\/]*\>/ms', '', $s[0]);
    }

    /**
     * toHTML
     * Convert input to HTML by cleaning up and decode BBCode
     *
     * @param  string  $text  Text to convert
     *
     * @return  string
     */
    private function toHTML($text)
    {
      $text = trim($text);
      $text = '<p>'.$text.'</p>';
      $text = preg_replace_callback('/\[code\](.*?)\[\/code\]/msi', array($this,"escape"), $text);

      // Smileys to find...
      $in = array(
              ':)', ':-)',
              ':D', ':-D',
              ':o', ':-o',
              ':p', ':-p',
              ':P', ':-P',
              ':(', ':-(',
              ';)', ';-)'
      );
      $out = array(
              '<img alt=":)" src="{SMILEY_PATH}/happy.png" />',     '<img alt=":-)" src="{SMILEY_PATH}/happy.png" />',
              '<img alt=":D" src="{SMILEY_PATH}/smile.png" />',     '<img alt=":-D" src="{SMILEY_PATH}/smile.png" />',
              '<img alt=":o" src="{SMILEY_PATH}/surprised.png" />', '<img alt=":-o" src="{SMILEY_PATH}/surprised.png" />',
              '<img alt=":p" src="{SMILEY_PATH}/tongue.png" />',    '<img alt=":-p" src="{SMILEY_PATH}/tongue.png" />',
              '<img alt=":P" src="{SMILEY_PATH}/tongue.png" />',    '<img alt=":-P" src="{SMILEY_PATH}/tongue.png" />',
              '<img alt=":(" src="{SMILEY_PATH}/unhappy.png" />',   '<img alt=":-(" src="{SMILEY_PATH}/unhappy.png" />',
              '<img alt=";)" src="{SMILEY_PATH}/wink.png" />',      '<img alt=";-)" src="{SMILEY_PATH}/wink.png" />'
      );
      $text = str_replace($in, $out, $text);

      // BBCode to find...
      $in = array(
               '/\[b\](.*?)\[\/b\]/msi',
               '/\[i\](.*?)\[\/i\]/msi',
               '/\[u\](.*?)\[\/u\]/msi',
               '/\[img\](.*?)\[\/img\]/msi',
               '/\[email\](.*?)\[\/email\]/msi',
               '/\[url\](.*?)\[\/url\]/msi',
               '/\[url\="?(.+)"?\](.*?)\[\/url\]/msi',
               '/\[size\="?(.*?)"?\](.*?)\[\/size\]/msi',
               '/\[color\="?(.*?)"?\](.*?)\[\/color\]/msi',
               '/\[quote](.*?)\[\/quote\]/msi',
               '/\[list\=(.*?)\](.*?)\[\/list\]/msi',
               '/\[list\](.*?)\[\/list\]/msi',
               '/\[\*\]\s?(.*?)\n/msi'
      );

      // And replace them by...
      $out = array(
               '<strong>\1</strong>',                     // [b]
               '<em>\1</em>',                             // [i]
               '<u>\1</u>',                               // [u]
               '',                                        // [img]
               '<a href="mailto:\1">\1</a>',              // [email]
               '<a href="\1">\1</a>',                     // [url]
               '<a href="\1">\2</a>',                     // [url=]
               '<span style="font-size:\1%">\2</span>',   // [size]
               '<span style="color:\1">\2</span>',        // [color]
               '\1',                                      // [quote]
               '',                                        // [list=]
               '',                                        // [list]
               '\1'                                       // [*]
      );
      $text = preg_replace($in, $out, $text);

      // paragraphs
      $text = str_replace("\r", "", $text);
      $text = nl2br($text);

      $text = preg_replace_callback('/<pre>(.*?)<\/pre>/msi', array($this,"removeBr"), $text);
      $text = preg_replace('/<p><pre>(.*?)<\/pre><\/p>/msi', "<pre>\\1</pre>", $text);

      $text = preg_replace_callback('/<ul>(.*?)<\/ul>/msi', array($this,"removeBr"), $text);
      $text = preg_replace('/<p><ul>(.*?)<\/ul><\/p>/msi', "<ul>\\1</ul>", $text);

      return $text;
    }

    /**
     * getRSSItem
     * Create a RSS item out of shoutbox entry
     *
     * @param  array  $shoutbox_entry   Shoutbox entry
     *
     * @return FeedItem object
     */
    private function getRSSItem($shoutbox_entry)
    {
      // init
      $rssitem = NULL;

      if (is_array($shoutbox_entry))
      {
        $rssitem = new FeedItem();
        $rssitem->title       = $shoutbox_entry['name'];
        $rssitem->link        = $this->rss->link;
        $rssitem->description = $shoutbox_entry['text'];
        $rssitem->date        = $shoutbox_entry['date'];
        $rssitem->source      = $this->rss->link;
        $rssitem->author      = $shoutbox_entry['name'];
      }

      return $rssitem;
    }

    /**
     * getShoutboxJCode
     * get the Java Code for the Shoutbox
     *
     * @return  string
     */
    private function getShoutboxJCode()
    {
      global $user, $eqdkp_root_path, $conf_plus, $SID, $_HMODE;

      // set autoreload
      $autoreload = ($conf_plus['sb_autoreload'] != '') ? intval($conf_plus['sb_autoreload']) : SHOUTBOX_AUTORELOAD;
      $autoreload = ($autoreload < 600 ? $autoreload : 0);
      $autoreload = ($_HMODE) ? 0 : $autoreload ; 
      
      $jscode  = "<script type=\"text/javascript\" src=\"".$eqdkp_root_path."plugins/shoutbox/includes/javascripts/shoutbox.js\"></script>
                  <script type='text/javascript'>
                    // wait for the DOM to be loaded
                    $(document).ready(function() {
                      $('#Shoutbox').ajaxForm({
                        target: '#htmlShoutboxTable',
                        beforeSubmit:  function(formData, jqForm, options) {
                          showShoutboxRequest('".$eqdkp_root_path."', '".$user->lang['sb_save_wait']."');
                        },
                        success: function() {
                          showShoutboxFinished('".$eqdkp_root_path."', '".$user->lang['sb_submit_text']."', '".$user->lang['sb_reload']."');
                        }
                      });
                 ";
      if ($autoreload > 0)
      {
        $jscode .= "     setInterval(function() {
                           shoutboxAutoReload('".$eqdkp_root_path."', '".$SID."', '".$user->lang['sb_reload']."');
                         }, ".($autoreload * 1000).");
                   ";
      }
      $jscode .= "  });
                  </script>";

      return $jscode;
    }

    /**
     * getForm
     * get the Shoutbox <form>
     *
     * @param  string  $rpath  root path
     *
     * @return  string
     */
    private function getForm($rpath='')
    {
      global $user, $eqdkp, $eqdkp_root_path, $conf_plus, $SID;

      // root path
      $root_path = ($rpath != '') ? $rpath : $eqdkp_root_path;

      // get class for row
      $class = $eqdkp->switch_row_class();

      // only display form if user has members assigned to
      if ($this->getMemberCountForUser() > 0)
      {
        // html
        $html = '<form id="reload_shoutbox" name="reload_shoutbox" action="'.$root_path.'plugins/shoutbox/shoutbox.php" method="post">
                 </form>
                 <form id="Shoutbox" name="Shoutbox" action="'.$root_path.'plugins/shoutbox/shoutbox.php" method="post">
                   <table width="100%" border="0" cellspacing="1" cellpadding="2">';
        // input below?
        if ($conf_plus['sb_input_box_below'] == 1 &&
            $user->data['user_id'] != ANONYMOUS && $user->check_auth('u_shoutbox_add', false))
        {
          $html .= '<tr><th>&nbsp;</th></tr>';
        }

        $html .= '   <tr class="'.$class.'">
                       <td>
                         <div align="center">'
                         .$this->getFormMember().
                        '</div>
                       </td>
                     </tr>
                     <tr class="'.$class.'">
                       <td><div align="center"><textarea class="input" name="sb_text" cols="20" rows="3"></textarea></div></td>
                     </tr>
                     <tr class="'.$class.'">
                       <td>
                         <div align="center">
                           <input type="hidden" name="sb_root" value="'.$root_path.'"/>
                           <span id="shoutbox_button"><input type="submit" class="input" name="sb_submit" value="'.$user->lang['sb_submit_text'].'"/></span>
                           <span class="small bold hand" onclick="$(\'#reload_shoutbox\').ajaxSubmit(
                             {
                               target: \'#htmlShoutboxTable\',
                               url:\''.$root_path.'plugins/shoutbox/shoutbox.php'.$SID.'&sb_root='.$root_path.'\',
                               beforeSubmit: function(formData, jqForm, options) {
                                 reloadShoutboxRequest(\''.$root_path.'\');
                               },
                               success: function() {
                                 reloadShoutboxFinished(\''.$root_path.'\', \''.$user->lang['sb_reload'].'\');
                               }
                             });">
                             <span id="shoutbox_reload_button">
                               <img src="'.$root_path.'plugins/shoutbox/images/reload.png" alt="'.$user->lang['sb_reload'].'" title="'.$user->lang['sb_reload'].'"/>
                             </span>
                           </span>
                         </div>
                       </td>
                     </tr>
                   </table>
                 </form>';
      }
      else
      {
        $html .= '<div align="center">'.$user->lang['sb_no_character_assigned'].'</div>';
      }

      return $html;
    }

    /**
     * getFormMember
     * get the Shoutbox <form> Members
     *
     * @return  string
     */
    private function getFormMember()
    {
      // get members
      $members = $this->getMembersForUser();
      $membercount = count($members);

      $html = '';

      if ($membercount > 1)
      {
        // show dropdown box
        $html .= '<select name="sb_member_id" size="1">';
        foreach($members as $member)
        {
          $html .= '<option value="'.$member['id'].'">'.$member['name'].'</option>';
        }
        $html .= '</select>';
      }
      else if ($membercount == 1)
      {
        // show name as text and member id as hidden value
        $html .= '<input type="hidden" name="sb_member_id" value="'.$members[0]['id'].'"/>'.
                 $members[0]['name'];
      }

      return $html;
    }

    /**
     * getArchiveLink
     * get the archive link text
     *
     * @return  string
     */
    private function getArchiveLink()
    {
      global $user, $eqdkp, $SID, $eqdkp_root_path;

      $html = '<table width="100%" border="0" cellspacing="1" cellpadding="2">
                 <tr class="'.$eqdkp->switch_row_class().'">
                   <td>
                     <div align="center"><a href="'.$eqdkp_root_path.'plugins/shoutbox/archive.php'.$SID.'">'.$user->lang['sb_archive'].'</a></div>
                   </td>
                 </tr>
               </table>';

      return $html;
    }

    /**
     * getColoredClassName
     * get the class name colored
     *
     * @param   string    $name   Member name
     *
     * @return  string
     */
    private function getColoredClassName($name)
    {
      global $eqdkp;

      // decode if UTF-8
      $name_for_class = ($this->checkUTF8($name) == 1) ? utf8_decode($name) : $name;

      // get class by name
      $class = get_classNamebyMemberName($name_for_class);

      if(strtolower($eqdkp->config['default_game']) == 'wow')
      {
        return '<span class="'.get_classColorChecked($class).'">'.$name.'</span>';
      }
      else
      {
        return '<span>'.$name.'</span>';
      }
    }

    /**
     * checkUTF8
     * Check if UTF-8
     *
     * @param  string  $string  text
     *
     * @return boolean
     */
    private function checkUTF8($string)
    {
      if (is_array($string))
      {
        $enc = implode('', $string);
        return @!((ord($enc[0]) != 239) && (ord($enc[1]) != 187) && (ord($enc[2]) != 191));
      }
      else
      {
        return (utf8_encode(utf8_decode($string)) == $string);
      }
    }

    /**
     * utf8_htmlentities
     * get html entities of UTF-8 string
     *
     * @param  string  $content  text to replace entities
     *
     * @return  string
     */
    private function utf8_htmlentities($content)
    {
      // convert to array, and convert each array element to entity if neccessary
      $contents = $this->unicode_string_to_array($content);
      $count = count($contents);

      $swap = '';
      for ($i = 0; $i < $count; $i++)
      {
        $contents[$i] = $this->unicode_entity_replace($contents[$i]);
        $swap .= $contents[$i];
      }

      return mb_convert_encoding($swap, 'UTF-8');
    }

    /**
     * unicode_string_to_array
     * convert unicode string to array of unicode chars
     *
     * @param  string  $string  unicode string to make array of
     *
     * @return  array
     */
    private function unicode_string_to_array($string)
    {
      $strlen = mb_strlen($string);
      while ($strlen)
      {
        $array[] = mb_substr($string, 0, 1, 'UTF-8');
        $string = mb_substr($string, 1, $strlen, 'UTF-8');
        $strlen = mb_strlen($string);
      }

      return $array;
    }

    /**
     * unicode_entity_replace
     * replace unicode char by html entity
     *
     * @param  char  $c  unicode character
     *
     * @return  array
     */
    private function unicode_entity_replace($c)
    {
      // get ornial of character, if less than 127, just return, else check for UTF8 and decode
      $h = ord($c{0});
      if ($h <= 0x7F)
      {
        return $c;
      }
      else if ($h < 0xC2)
      {
        return $c;
      }

      if ($h <= 0xDF)
      {
        $h = ($h & 0x1F) << 6 | (ord($c{1}) & 0x3F);
        $h = '&#'.$h.';';
        return $h;
      }
      else if ($h <= 0xEF)
      {
        $h = ($h & 0x0F) << 12 | (ord($c{1}) & 0x3F) << 6 | (ord($c{2}) & 0x3F);
        $h = '&#'.$h.';';
        return $h;
      }
      else if ($h <= 0xF4)
      {
        $h = ($h & 0x0F) << 18 | (ord($c{1}) & 0x3F) << 12 | (ord($c{2}) & 0x3F) << 6 | (ord($c{3}) & 0x3F);
        $h = '&#'.$h.';';
        return $h;
      }
    }
  }
}

?>

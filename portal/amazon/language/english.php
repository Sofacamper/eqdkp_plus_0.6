<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2008-07-30 13:56:27 +0200 (Mi, 30 Jul 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 2465 $
 * 
 * $Id: english.php 2465 2008-07-30 11:56:27Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}


$default = '
<script type="text/javascript"><!--
amazon_ad_tag="eqdkp-21"; 
amazon_ad_width="160"; 
amazon_ad_height="600"; 
amazon_color_background="14293B"; 
amazon_color_border="295F8E"; 
amazon_color_logo="FFFFFF"; 
amazon_color_text="FDF3F3"; 
amazon_color_link="CDCDCD"; 
amazon_ad_logo="hide"; 
amazon_ad_link_target="new"; 
amazon_ad_border="hide"; 
amazon_ad_title="EQdkp-Plus presents"; //--></script>
<script type="text/javascript" src="http://www.assoc-amazon.de/s/asw.js"></script>
';

$plang = array_merge($plang, array(
  'amazon'                 => 'Amazon',
  'portal_amazon_text'     => $default,
  'pk_amazon_useroutput'   => 'HTML Codes',
  'pk_amazon_headtext'     => 'Custom Title',
));
?>

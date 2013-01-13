<?php 
define('EQDKP_INC', true);
define('IN_ADMIN', true);
$eqdkp_root_path = './../../';
include_once($eqdkp_root_path . 'common.php');

if($in->get('plugin')){
  $changelog = $db->query_first("SELECT changelog FROM __updates WHERE plugin='".$db->escape($in->get('plugin'))."'");
}
echo ((trim($changelog)) ? nl2br($changelog) : $user->lang['lib_pupd_nochangelog']);
?>
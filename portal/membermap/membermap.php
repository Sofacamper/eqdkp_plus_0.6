<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-07-30 22:24:40 +0200 (Thu, 30 Jul 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 5387 $
 * 
 * $Id: membermap.php 5387 2009-07-30 20:24:40Z wallenium $
 */

define('EQDKP_INC', true);
$eqdkp_root_path = './../../';
include_once($eqdkp_root_path . 'common.php');

if($conf_plus['pk_mmp_googlekey']){
  include_once($eqdkp_root_path.'libraries/EasyGoogleMap/EasyGoogleMap.class.php');
  $gm = new EasyGoogleMap($conf_plus['pk_mmp_googlekey']);
  
  if($_GET['large'] == 'true'){
    if($user->data['username']){
      $allLoc_sql     = "SELECT * FROM __users ORDER BY username";
      $allLoc_result  = $db->query($allLoc_sql);
      $map_users = array();
      while ( $row = $db->fetch_record($allLoc_result)){
        if($row['country'] && (($row['town'] && $conf_plus['pk_mmp_useziptown'] != '1') || 
          ($row['ZIP_code'] && $conf_plus['pk_mmp_useziptown'] == '1'))){
          $map_users[$row['user_id']] = array(
              'country'   => $row['country'],
              'town_zip'  => (($conf_plus['pk_mmp_useziptown'] == '1')? $row['ZIP_code'] : $row['town']),
              'username'  => $row['username'],
              'town_name' => $row['town'],
              'address'   => $row['address'],
              'name'      => (($conf_plus['pk_mmp_shwusername']) ? $row['username'] : $row['first_name'].' '.$row['last_name']),
          );
        }
      }
      
      // Style me!
      $myCSS = '<style>
                  .gmmodule_members {
                    width:105px;
                    height:440px;
                    padding-left:4px;
                    overflow:auto;
                  }
                  .gmmodule_members a,
                  .gmmodule_members a:visited{
                    text-decoration: none;
                    color: black;
                  }
                  .gmmodule_members a:hover,
                  .gmmodule_members a:active{
                    text-decoration: none;
                    font-weight: bold;
                    color: black;
                  }
                </style>';
      
      // Config
      $gm->SetMarkerIconStyle('FLAG');
      $gm->SetMapZoom(10);
      $gm->SetMapWidth(400);
      $gm->SetMapHeight(440);
    
      // Add Member to Map
      if(count($map_users) > 0){
        foreach($map_users as $maprow){
          if($maprow['address']){
            $gm->SetAddress($maprow['address'].", ".$maprow['town_zip']);
          }else{
            $gm->SetAddress($maprow['town_zip']);
          }
          $gm->SetInfoWindowText($maprow['name'].'<br>'.$maprow['town_name']);
          $gm->SetSideClick($maprow['username']);
        }
      }
      
      
      $myOut  =  '<html xmlns:v="urn:schemas-microsoft-com:vml">
                  <head>';
      $myOut .= $gm->GmapsKey();
      $myOut .= $myCSS;
      $myOut .= '</head>
                  <body>
                  <table>
                  <tr><td width="401px">';
      $myOut .= $gm->MapHolder();
      $myOut .= $gm->InitJs();
      $myOut .= '</td><td valign="top">
                <div class="gmmodule_members">';
      $myOut .= $gm->GetSideClick();
      $myOut .= '</div></td></tr></table>';
      $myOut .= $gm->UnloadMap();
      $myOut .= '</body>
                  </html>';
    }else{
      $myOut = $plang['pk_membermap_noaccess'];
    }
  }else{
    
    // This is the minimap of the portal mode...
    $myLoc_sql      = "SELECT country, ZIP_code, town, address FROM __users 
                      WHERE user_id='".$db->escape($user->data['user_id'])."'";
    $myLoc_result   = $db->query($myLoc_sql);
    $user_loc       = $db->fetch_record($myLoc_result);
  
    if($user_loc['town']){
      if($user_loc['address']){
        $gm->SetAddress($user_loc['address'].", ".(($conf_plus['pk_mmp_useziptown'] == '1')? $user_loc['ZIP_code'] : $user_loc['town']));
      }else{
        $gm->SetAddress((($conf_plus['pk_mmp_useziptown'] == '1')? $user_loc['ZIP_code'] : $user_loc['town']));
      }
      $gm->SetMarkerIconStyle('FLAG');
      $gm->mContinuousZoom = FALSE;
      $gm->mDoubleClickZoom = FALSE;
      $gm->SetMapZoom(10);
      $gm->mScale = FALSE;
      $gm->mInset = FALSE;
      $gm->SetMapControl('NONE');
      $gm->SetMapWidth(200);
      $gm->SetMapHeight(200);
          
      // OUT
      $myOut  =  '<html xmlns:v="urn:schemas-microsoft-com:vml">
                  <head>';
      $myOut .= $gm->GmapsKey();
      $myOut .= $myCSS;
      $myOut .= '</head>
                  <body>';
      $myOut .= $gm->MapHolder('parent.OpenMap()');
      $myOut .= $gm->InitJs();
      $myOut .= $gm->UnloadMap();
      $myOut .= '</body>
                  </html>';
    }else{
      $myOut = $plang['pk_membermap_no_data'];
    }
  }
}else{
  $myOut = $plang['pk_membermap_no_gmapi'];
}
echo $myOut;
?>
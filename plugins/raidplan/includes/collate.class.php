<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2008-11-03 13:24:11 +0100 (Mon, 03 Nov 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 2963 $
 * 
 * $Id: collate.class.php 2963 2008-11-03 12:24:11Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

class CollateCheck {
  
  /**
  * Get the Collate and Character Set from Database Table
  * 
  * @set collation and charset variables
  */
  function CollateCheck(){
    global $db;
    // Get the Server variables
    $sql1   = 'SHOW VARIABLES LIKE "collation_database"';
    $result = $db->query($sql1);
    $row1   = $db->fetch_record($result);
    $db->free_result($result);
    // Set the collocation / server variables
    $this->db_collation 	= $row1['Value'];
    
    // dropdown:
    $sql2   = 'SHOW COLLATION';
    $result = $db->query($sql2);
    while($row2 = $db->fetch_record($result)){
      $this->coll_dropdown[$row2['Collation']]  = $row2['Collation'];
      $this->CollationTable[$row2['Collation']] = $row2['Charset'];
    }
    $db->free_result($result);
  }
  
  /**
  * Perform the Alter table event for all containing table fields
  * 
  * @param    array     $repeair[tablename][i][field]/$repeair[tablename][i][type]

  * @return --
  */
  function AlterTable($repairarray, $mycollation){
    global $db, $dbname;
    $mycharset = $this->CollationTable[$mycollation];
    if($mycollation && $mycharset){
      $sql = 'ALTER DATABASE '.$dbname.' DEFAULT CHARACTER SET '.$mycharset.' COLLATE '.$mycollation.";\r\n";
      $db->query($sql);
      foreach($repairarray as $table=>$input){
        foreach($input as $field){
          $sql ='ALTER TABLE `'.$table.'` CHANGE `'.$field['field'].'` `'.$field['field'].'` '.$field['type'].' CHARACTER SET '.$mycharset.' COLLATE '.$mycollation.' NOT NULL';
          $db->query($sql);
        }
      }
    }
  }
  
  /**
  * Check the collate of the table fields
  * 
  * @param    array     $repeair[tablename][i][field]/$repeair[tablename][i][type]

  * @return html output
  */
  function CheckCollate($tablearray, $output=false, $gettrigger=false){
    global $db, $khrml;
    $repair = array();
    $outp = '<style type="text/css">
  					a, a:visited {
  						font-size: 12px;
  						color: red;
  						text-decoration: none;
  					}
  					a:hover, a:active {
  						text-decoration: underline;
  					}
  					img {
  						border:0px;
  					}
  					</style>';
   $outp .= '<form method="POST" action="collate_check.php">';
   $outp .= 'Database Collation: '.$khrml->DropDown('db_collation', $this->coll_dropdown, $this->db_collation).'<br/><br/>';
	 
    foreach($tablearray as $tables){
      $sql = 'SHOW FULL COLUMNS FROM ' . $tables .'';
      $result = $db->query($sql);
      $outp .= '<b>'.$tables.'</b><br/>';
      $outp .= '<table>';
      $i = 0;
      while ( $row = $db->fetch_record($result) ){
        $tmpcoll = ($row['Collation']) ? $row['Collation'] : 'NULL';
        $tmpcoll = ($tmpcoll != 'NULL' && $tmpcoll != $this->db_collation) ? '<b><font color="red">'.$tmpcoll.'</font></b>' : '<font color="black">'.$tmpcoll.'</font';
        $outp .= '<tr>
                <td width="240px">'.$row['Field'].'</td>
                <td width="200px">'.$row['Type'].'</td>
                <td width="250px">'.$tmpcoll.'</td>';
      	$tmp_repair = true;
      	$repair[$tables][$i]['field']  = $row['Field'];
        $repair[$tables][$i]['type']   = $row['Type'];
      	$i++;
        $outp .=  '</tr>';
		  }
		  $db->free_result($result);
      $outp .= '</table><br/>';
    }
    if($tmp_repair){
      $outp .= '<input type="submit" name="issavebu" value="Fix Collation" class="mainoption" />';
    }
    $outp .= '</form>';
    if($output){
      if($gettrigger){
        $this->AlterTable($repair, $gettrigger);
        redirect('plugins/raidplan/admin/collate_check.php');
      }else{
  	   return $outp;
  	  }
  	}else{
      return true;
    }
  }
}
?>

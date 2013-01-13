<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-03-16 12:02:35 +0100 (Mo, 16 Mrz 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: osr-corgan $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 4235 $
 *
 * $Id: module.php 4235 2009-03-16 11:02:35Z osr-corgan $
 */

if ( !defined('EQDKP_INC') ){
  header('HTTP/1.0 404 Not Found');exit;
}

$portal_module['gametimecard'] = array(
			'name'           => 'Gametimecards',
			'path'           => 'gametimecard',
			'version'        => '1.0.1',
			'author'         => 'Corgan',
			'contact'        => 'http://www.eqdkp-plus.com',
			'description'    => 'the cheapest Gametimecards',
			'positions'      => array('left1', 'left2', 'right'),
      		'signedin'       => '0',
      		'install'        => array(
			                            'autoenable'        => '1',
			                            'defaultposition'   => 'left2',
			                            'defaultnumber'     => '9',
			                            ),
    );

if(!function_exists(gametimecard_module))
{
  function gametimecard_module()
  {
  	global $eqdkp , $plang, $pcache, $pm, $jqueryp, $conf_plus, $eqdkp_root_path;

  	$url = "http://www.eqdkp-plus.com/gameladen.xml";
    if($url)
    {    	    	
    	$output  = "<style>
                  #gametimecard_module{
                  	margin:0;
                  	padding:5px;
                  	height:200px;
                  	overflow: auto;
                  }
                  #gametimecard_module a{
                  	color:#FF9900;
                  	font-size:11px;
                  }
                  #gametimecard_module .rss_readmore{
                  	color:#f5f5f5;
                  	font-size:10px;
                  	margin-bottom: 5px;
                  }
                  #gametimecard_module .date{
                  	margin:0;
                  	color:#999999;
                  	font-size:9px;
                  }
                  #gametimecard_module .description{
                   	margin:0;
                   	padding:0;
                  }
                  #gametimecard_module .description p{
                  	font-size:10px;
                  }
                  .loading{
                  	margin:20% 0% 0% 40%;
                  	float:left;
                  }
                </style>";
  	$output .= '<div id="gametimecard_module"></div>';

  	// JS Part
  	$output .= '<script type="text/javascript">
	               $(document).ready(function(){';
  	$output .= $jqueryp->rssFeeder('gametimecard_module', $eqdkp_root_path."portal/gametimecard/load.php?loadrss=true", 4, 80, true);
  	$output .= '});
	             </script>';
    }
    
	// Set the header	
	$output .= "<script>document.getElementById('txtgametimecard').innerHTML = '".addslashes('Gametimecards')."'</script>";
	
    
    return $output;
  }
}
?>

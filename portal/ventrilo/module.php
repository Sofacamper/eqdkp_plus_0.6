<?php 

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
	} 
	
$portal_module['ventrilo'] = array(                        
'name'          => 'Ventrilo Status',                       
'path'          => 'ventrilo',                        
'version'       => '0.1.1',                        
'author'        => 'Chex',                        
'contact'       => 'http://www.eqdkp-plus.com',                       
'description'   => 'A ventrilo status panel',                       
'positions'     => array('left1', 'left2', 'right', 'middle'),          
'signedin'      => '0',         
'install'       => array(                                                    
'autoenable'        => '0',                                                   
'defaultposition'   => 'right',                                                    
'defaultnumber'     => '7',     
	                                               
),    
); 

$portal_settings['ventrilo'] = array(  
'pk_ventrilo_server'     => array(        
'name'      => 'pk_ventrilo_server',        
'language'  => 'pk_ventrilo_server',        
'property'  => 'text',        
'size'      => '50',        
'help'      => '',      
),  
'pk_ventrilo_port'     => array(       
'name'      => 'pk_ventrilo_port',        
'language'  => 'pk_ventrilo_port',        
'property'  => 'text',        
'size'      => '30',        
'help'      => '',  
),  
'pk_ventrilo_backgroundc'     => array(       
'name'      => 'pk_ventrilo_backgroundc',        
'language'  => 'pk_ventrilo_backgroundc',        
'property'  => 'text',        
'size'      => '6',        
'help'      => '',  
),  
'pk_ventrilo_channelc'     => array(       
'name'      => 'pk_ventrilo_channelc',        
'language'  => 'pk_ventrilo_channelc',        
'property'  => 'text',        
'size'      => '6',        
'help'      => '',  
),  
'pk_ventrilo_servercolor'     => array(       
'name'      => 'pk_ventrilo_servercolor',        
'language'  => 'pk_ventrilo_servercolor',        
'property'  => 'text',        
'size'      => '6',        
'help'      => '',  
),  
'pk_ventrilo_usercolor'     => array(       
'name'      => 'pk_ventrilo_usercolor',        
'language'  => 'pk_ventrilo_usercolor',        
'property'  => 'text',        
'size'      => '6',        
'help'      => '',    
   )
   );
   
if(!function_exists(ventrilo_module)){  
	function ventrilo_module(){ 
	     
		 global $eqdkp , $user , $tpl, $db, $plang, $conf_plus;
		 return '<iframe src="http://vspy.guildlaunch.net/srv/minispy.php?Address=' . $conf_plus['pk_ventrilo_server'] . '&Port=' . $conf_plus['pk_ventrilo_port'] . '&J=&Scroll=&T=8&E=&Main=&Color=' . $conf_plus['pk_ventrilo_backgroundc'] . '&S=' . $conf_plus['pk_ventrilo_servercolor'] . '&C=' . $conf_plus['pk_ventrilo_channelc'] . '&U=' . $conf_plus['pk_ventrilo_usercolor'] . '&Names=&Compact=" width="200" height="300" frameborder="0"></iframe>';  }
		 
}
?>
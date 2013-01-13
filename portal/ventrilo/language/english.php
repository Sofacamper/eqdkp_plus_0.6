<?php
if ( !defined('EQDKP_INC') ){    
header('HTTP/1.0 404 Not Found');exit;
} 
$plang = array_merge($plang, array(  
'ventrilo'                 => 'Ventrilo',  
'pk_ventrilo_server'   => 'Enter the ventrilo server address',  
'pk_ventrilo_port'     => 'Enter the ventrilo server port',
'pk_ventrilo_backgroundc'     => 'Enter the 6 digit hex color for the BACKGROUND (TTTTTT = transparent (http://www.computerhope.com/htmcolor.htm))',
'pk_ventrilo_channelc'     => 'Enter the 6 digit hex color for the CHANNEL color',
'pk_ventrilo_servercolor'     => 'Enter the 6 digit hex color for the SERVER color',
'pk_ventrilo_usercolor'     => 'Enter the 6 digit hex color for the USER NAME color',
));
?>
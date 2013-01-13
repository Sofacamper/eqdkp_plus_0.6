<?php
if ( !defined('EQDKP_INC') ){    
header('HTTP/1.0 404 Not Found');exit;
} 
$plang = array_merge($plang, array(  
'ventrilo'                 	=> 'Ventrilo',  
'pk_ventrilo_server'   		=> 'IP Adresse des Ventrilo Servers',  
'pk_ventrilo_port'     		=> 'Port des Ventrilo Servers',
'pk_ventrilo_backgroundc'   => 'Hex Code der Hintrgrundfarbe (6 Stellen) (TTTTTT = für Transparent (http://www.computerhope.com/htmcolor.htm))',
'pk_ventrilo_channelc'     	=> 'Hex Code der Channelfarbe',
'pk_ventrilo_servercolor'   => 'Hex Code der Serverfarbe',
'pk_ventrilo_usercolor'     => 'Hex Code der Usernamefarbe',
));
?>
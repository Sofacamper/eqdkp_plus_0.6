<?php

if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

//Language: Spanish	
//Created by EQdkp Plus Translation Tool on  2010-07-09 15:10
//File: module_rssfeed
//Source-Language: english

$alang = array( 
"rssfeed" => "Canal RSS",
"pk_rssfeed_limit" => "N�mero de entradas mostradas",
"pk_rssfeed_url" => "URL del canal RSS",
"pk_rssfeed_nourl" => "Por favor, configura un canal primero",
"pk_rssfeed_length" => "N�mero de caracteres del feed a mostrar",
"pk_rssfeed_length_h" => "Si el m�dulo de feed es demasiado ancho, el problema podr�a ser una etiqueta HTML destruida debido a los caracteres limitados. Si hay muchos caracteres sin espacios en blanco en la etiqueta, no habr� nueva l�nea y por tanto la columna izquierda ser� muy ancha.",
 );
$plang = array_merge($plang, $alang);
?>
<?php

if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

//Language: Spanish	
//Created by EQdkp Plus Translation Tool on  2010-07-09 15:10
//File: module_teamspeak
//Source-Language: english

$alang = array( 
"teamspeak" => "Teamspeak",
"pk_portal_tsvoice" => "Servidor de voz",
"pk_set_ts_title" => "T�tulo del Servidor-TS",
"pk_set_ts_serverAddress" => "IP del servidor TS",
"pk_set_ts_serverQueryPort" => "Puerto de consulta (Query)",
"pk_set_ts_serverUDPPort" => "Puerto UDP",
"pk_set_ts_serverPasswort" => "Contrase�a del servidor",
"pk_set_ts_channelflags" => "Mostrar banderas de canal (R,M,S,P etc.)",
"pk_set_ts_userstatus" => "Mostrar estado de usuario (U,R,SA etc.)",
"pk_set_ts_showchannel" => "�Mostrar canales? (0 = s�lo jugador)",
"pk_set_ts_showEmptychannel" => "�Mostrar canales vac�os?",
"pk_set_ts_overlib_mouseover" => "�Mostrar informaci�n mouseover? (s�lo en alem�n por ahora)",
"pk_set_ts_joinable" => "�Mostrar enlace para unirse al servidor?",
"pk_set_ts_joinableMember" => "�Mostrar enlace para unirse al servidor s�lo a usuarios registrados?",
"pk_ts_shortmemnames" => "Acortar nombres de usuario largos",
"pk_ts_memnamelength" => "Longitud m�xima de los nombres de usuario, acortados si tienen m�s de xx caracteres.",
"pk_ts_channel_name" => "Nombre del canal padre que quieres mostrar",
"pk_ts_channel_name_help" => "S�lo el canal introducido y sus subcanales ser�n mostrados. D�jalo en blanco para mostrarlos todos. �Separa m�ltiples canales por comas! Por ej: Canal A, Canal B. �No funciona para nombres de subcanales!",
 );
$plang = array_merge($plang, $alang);
?>
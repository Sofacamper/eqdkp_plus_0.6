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
"pk_set_ts_title" => "Título del Servidor-TS",
"pk_set_ts_serverAddress" => "IP del servidor TS",
"pk_set_ts_serverQueryPort" => "Puerto de consulta (Query)",
"pk_set_ts_serverUDPPort" => "Puerto UDP",
"pk_set_ts_serverPasswort" => "Contraseña del servidor",
"pk_set_ts_channelflags" => "Mostrar banderas de canal (R,M,S,P etc.)",
"pk_set_ts_userstatus" => "Mostrar estado de usuario (U,R,SA etc.)",
"pk_set_ts_showchannel" => "¿Mostrar canales? (0 = sólo jugador)",
"pk_set_ts_showEmptychannel" => "¿Mostrar canales vacíos?",
"pk_set_ts_overlib_mouseover" => "¿Mostrar información mouseover? (sólo en alemán por ahora)",
"pk_set_ts_joinable" => "¿Mostrar enlace para unirse al servidor?",
"pk_set_ts_joinableMember" => "¿Mostrar enlace para unirse al servidor sólo a usuarios registrados?",
"pk_ts_shortmemnames" => "Acortar nombres de usuario largos",
"pk_ts_memnamelength" => "Longitud máxima de los nombres de usuario, acortados si tienen más de xx caracteres.",
"pk_ts_channel_name" => "Nombre del canal padre que quieres mostrar",
"pk_ts_channel_name_help" => "Sólo el canal introducido y sus subcanales serán mostrados. Déjalo en blanco para mostrarlos todos. ¡Separa múltiples canales por comas! Por ej: Canal A, Canal B. ¡No funciona para nombres de subcanales!",
 );
$plang = array_merge($plang, $alang);
?>
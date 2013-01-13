<?php

if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

//Language: Spanish	
//Created by EQdkp Plus Translation Tool on  2010-07-09 15:10
//File: module_teamspeak3
//Source-Language: english

$alang = array( 
"teamspeak3" => "Teamspeak 3",
"lang_pk_ts3_ip" => "IP de tu servidor ( sin el puerto )",
"lang_pk_ts3_port" => "El puerto - Defecto: 9987",
"lang_pk_ts3_telnetport" => "Puerto telnet de tu servidor - Defecto: 10011",
"lang_pk_ts3_id" => "ID de tu Servidor Virtual - Defecto: 1",
"lang_help_pk_ts3_id" => "Introduce -1 para usar los puertos del servidor par ala conexión en vez de la ID.",
"lang_pk_ts3_cache" => "Tiempo mínimo entre consultas-TS3 (segundos)",
"lang_help_pk_ts3_cache" => "¿Cuánto tiempo en segundos deberían ser almacenados los datos de TS3 antes de lanzar una nueva consulta? 0 para desactivar caché. Debes tener instalado APC-Cache en tu instalación de PHP para que esto funcione.",
"lang_pk_ts3_banner" => "¿Mostrar banner si la URL está disponible en TS?",
"lang_pk_ts3_join" => "¿Mostrar enlace para unirse al servidor?",
"lang_pk_ts3_jointext" => "Texto del enlace para unirse al servidor",
"lang_pk_ts3_legend" => "¿Mostrar información de grupo abajo?",
"lang_pk_ts3_cut_names" => "Acortar nombres de usuario",
"lang_help_pk_ts3_cut_names" => "Si quieres acortar los nombres de usuario, indica el tamaño deseado - Sin cortes = 0",
"lang_pk_ts3_cut_channel" => "Acortar nombres de canales",
"lang_help_pk_ts3_cut_channel" => "Si quieres acortar los nombres de canales, indica el tamaño deseado - Sin cortes = 0",
"lang_pk_only_populated_channel" => "¿Mostrar solo canales con usuarios?",
"lang_pk_ts3_useron" => "¿Mostrar usuarios conectados / posibles usuarios ?",
"lang_pk_ts3_stats" => "¿Mostrar estadísticas bajo el visor de TS?",
"lang_pk_ts3_stats_showos" => "¿Mostrar en que sistema operativo funciona el servidor TS3?",
"lang_pk_ts3_stats_version" => "¿Mostrar la versión del servidor TS3?",
"lang_pk_ts3_stats_numchan" => "¿Mostrar el número de canales?",
"lang_pk_ts3_stats_uptime" => "¿Mostrar el tiempo en funcionamiento desde el ultimo reinicio?",
"lang_pk_ts3_stats_install" => "¿Mostrar la fecha en que fue instalado el servidor?",
"lang_pk_ts3_timeout" => "Timeout ( NO CAMBIAR )",
"lang_help_pk_ts3_timeout" => "¡Deja este campo en blanco a menos que sepas lo que estas haciendo!",
 );
$plang = array_merge($plang, $alang);
?>
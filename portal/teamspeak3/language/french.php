<?php

if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

//Language: French	
//Created by EQdkp Plus Translation Tool on  2010-07-09 13:55
//File: module_teamspeak3
//Source-Language: english

$alang = array( 
"teamspeak3" => "Teamspeak 3",
"lang_pk_ts3_ip" => "Adresse IP du serveur (sans le port)",
"lang_pk_ts3_port" => "Port - Défaut: 9987",
"lang_pk_ts3_telnetport" => "Le port Telnet du serveur - Defaut: 10011",
"lang_pk_ts3_id" => "L'ID du serveur vituel - défaut 1",
"lang_help_pk_ts3_id" => "Entrez -1 pour utiliser les serveurs du port pour se connecter plutot que l'ID serveur",
"lang_pk_ts3_cache" => "Temps minimum en secondes entre 2 requêtes TS3",
"lang_help_pk_ts3_cache" => "Combien de temps est ce que les données TS3 doivent être mises en cache avant une prochaine requête? la valeur 0 désactive le cache. Votre configuration php nécessite l'utilisation d'un cache APC pour utiliser cette fonction",
"lang_pk_ts3_banner" => "Afficher la bannière si l'URL est disponible dans TS?",
"lang_pk_ts3_join" => "Afficher le lien pour rejoindre le serveur?",
"lang_pk_ts3_jointext" => "Texte du lien pour rejoindre le serveur",
"lang_pk_ts3_legend" => "Afficher les infos groupe en bas?",
"lang_pk_ts3_cut_names" => "Couper les noms d'utilisteur",
"lang_help_pk_ts3_cut_names" => "Si vous tronquez les nom d'utilisateurs, mettez la taille désirée - non couper = 0",
"lang_pk_ts3_cut_channel" => "Couper les noms de canaux",
"lang_help_pk_ts3_cut_channel" => "Si vous tronquez les noms de canaux, mettez la taille désirée - non couper = 0",
"lang_pk_only_populated_channel" => "Afficher uniquement les canaux occupés",
"lang_pk_ts3_useron" => "Afficher les utilisateurs en lignes/ max utilisateurs",
"lang_pk_ts3_stats" => "Afficher une boite de stat sous le TS viewer",
"lang_pk_ts3_stats_showos" => "Afficher sur quel OS tourne le serveur?",
"lang_pk_ts3_stats_version" => "Afficher la version du serveur TS3?",
"lang_pk_ts3_stats_numchan" => "Afficher le nombre de canaux?",
"lang_pk_ts3_stats_uptime" => "Afficher le temps en ligne du serveur?",
"lang_pk_ts3_stats_install" => "Afficher quand le serveur a été installé?",
"lang_pk_ts3_timeout" => "Timeout (ne pas changer)",
"lang_help_pk_ts3_timeout" => "Laisser ce champs vide, sauf si vous êtes surs de ce que vous faites!",
 );
$plang = array_merge($plang, $alang);
?>
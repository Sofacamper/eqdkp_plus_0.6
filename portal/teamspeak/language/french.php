<?php

if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

//Language: French	
//Created by EQdkp Plus Translation Tool on  2010-07-09 13:55
//File: module_teamspeak
//Source-Language: english

$alang = array( 
"teamspeak" => "Teamspeak",
"pk_portal_tsvoice" => "Serveur vocal",
"pk_set_ts_title" => "Nom du serveur TS",
"pk_set_ts_serverAddress" => "IP du serveur TS",
"pk_set_ts_serverQueryPort" => "Port de requête",
"pk_set_ts_serverUDPPort" => "Port UDP",
"pk_set_ts_serverPasswort" => "Mot de passe serveur",
"pk_set_ts_channelflags" => "Affiche le type des canaux (R,M,S,P etc.)",
"pk_set_ts_userstatus" => "Affiche le statut des utilisateurs (U,R,SA etc.)",
"pk_set_ts_showchannel" => "Affiche les canaux ? (0 = uniquement Joueur)",
"pk_set_ts_showEmptychannel" => "Affiche les canaux vides ?",
"pk_set_ts_overlib_mouseover" => "Affiche les informations au pointeur de souris (allemand uniquement)",
"pk_set_ts_joinable" => "Afficher le lien pour rejoindre le serveur?",
"pk_set_ts_joinableMember" => "Affiche le lien uniquement aux utilisateurs enregistrés ?",
"pk_ts_shortmemnames" => "Tronquer les noms trop long",
"pk_ts_memnamelength" => "Longueur max du nom des utilisateurs, tronque après x lettres.",
"pk_ts_channel_name" => "Nom du canal parent que vous voulez afficher",
"pk_ts_channel_name_help" => "Seuls le canal choisi et les sous canaux seront affichés. Laisser vide pour tout afficher. Séparer les canaux multiples par des virgules! Ex: Canal A, CanalB. Ne fonctionne pas pour les sous canaux.",
 );
$plang = array_merge($plang, $alang);
?>
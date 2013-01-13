<?php

if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

//Language: French	
//Created by EQdkp Plus Translation Tool on  2010-07-09 13:55
//File: plugin_ticket
//Source-Language: english

$alang = array( 
"ticket" => "Ticket d'aide",
"ticket_open" => "Ouvrir les tickets",
"ticket_usersettings" => "Paramètres",
"ticket_adminsettings" => "Paramètres d'administration",
"ticket_admin_converse" => "Répondre aux tickets",
"ticket_accdenied" => "Acces refusé",
"ticket_admin" => "Administration",
"ticket_submit" => "Soumettre des tickets",
"tk_message_body" => "Corps du message",
"tk_submit_ticket" => "Soumettre un ticket",
"tk_reset" => "Réinitialiser",
"tk_update_ticket" => "Mise à jour du ticket",
"tk_delete_ticket" => "Effacer le ticket",
"tk_replyticket" => "Répondre en soumettant un nouveau ticket",
"ticket_settings_header" => "Paramètres",
"tk_delete" => "Effacer",
"tk_read" => "Lire",
"tk_date" => "Date",
"tk_submit_replyticket" => "Soumettre un ticket de réponse",
"ticket_email" => "Email de notification",
"ticket_email_note" => "Les notification email sont envoyés que si le serveur le permet. Veuillez vérifier votre adresse email dans les paramètres généraux.",
"ticket_color" => "Couleur des réponses non lues",
"helptextdel" => "Les tickets affichés ont été effacés par vous (ou un administrateur). Si l'utilisateur choisi d'effacer le ticket aussi, alors il sera effacé du système. S'il choisi de soumettre un nouveau ticket, alors le ticket apparaitra de nouveau dans la section non effacée.",
"helptext" => "Les tickets affichés en italique sont effacés par l'utilisateur. Si vous les effacez, ils le seront de façon permanente. Si vous répondez à un tel ticket, le tag effacement sera retiré et l'utilisateur pourra revoir son ticket et y répondre.",
"showdeleted" => "Afficher les tickets effacés",
"hidedeleted" => "Afficher les tickets",
"tk_fv_required_message" => "Erreur. Vérifiez le texte du ticket",
"tk_replytoticket" => "Répondre à un ticket",
"tk_from_user" => "De l'utilisateur",
"tk_from_admin" => "De l'adminitrateur",
"tk_submit_st_reply" => "Envoyer un message à l'utilisateur",
"tk_submit_st_reply_button" => "Soumettre",
"tk_to_user" => "A l'utilisateur",
"admin-sends-message" => "Le ticket a été généré à la demande d'un admin. Pour voir le contenu du message, regardez en dessous du corps du message.",
"tk_usernameerror" => "Utilisateur inconnu",
"tk_submit" => "Soumettre",
"tk_replyheader" => "Répondre aux tickets ou envoyer un message à l'utilisateur",
"tk_submit_reply" => "Soumettre une réponse",
"tk_undelete" => "Ticket récupéré",
"edit_admin_emails" => "Editer l'email des admins",
"submit_edited_emails" => "Soumettre",
"ticket_email_general" => "Utiliser les emails de notification",
"ticket_email_general_note" => "Ce sont les paramètres généraux pour toutes les notifications",
"ticket_email_admin" => "Utiliser les notifications email pour les admins",
"ticket_email_admincolor" => "Paramètres de couleur pour les tickets non répondus (admin)",
"ticket_default_user_color" => "Couleurs standards pour les réponses non lues",
"b_help" => "Gras:  [b]texte[/b] (alt+b)",
"i_help" => "italique: [i]texte[/i] (alt+i)",
"u_help" => "sousligné: [u]texte[/u] (alt+u)",
"q_help" => "citation: [quote]texte[/quote] (alt+q)",
"c_help" => "centré: [center]texte[/center] (alt+c)",
"p_help" => "image: [img]http://image_url[/img] (alt+p)",
"w_help" => "URL: [url]http://url[/url] ou [url=http://url]URL texte[/url] (alt+w)",
"ticket_desc_short" => "Plateforme d'aide et de ticket",
"ticket_desc_long" => "autoriser vos membres à écrire un ticket d'aide pour une meilleure visibilité!",
 );
$lang = (is_array($lang))? $lang : array();
$lang = array_merge($lang, $alang);
?>
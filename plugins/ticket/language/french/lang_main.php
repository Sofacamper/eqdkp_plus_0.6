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
"ticket_usersettings" => "Param�tres",
"ticket_adminsettings" => "Param�tres d'administration",
"ticket_admin_converse" => "R�pondre aux tickets",
"ticket_accdenied" => "Acces refus�",
"ticket_admin" => "Administration",
"ticket_submit" => "Soumettre des tickets",
"tk_message_body" => "Corps du message",
"tk_submit_ticket" => "Soumettre un ticket",
"tk_reset" => "R�initialiser",
"tk_update_ticket" => "Mise � jour du ticket",
"tk_delete_ticket" => "Effacer le ticket",
"tk_replyticket" => "R�pondre en soumettant un nouveau ticket",
"ticket_settings_header" => "Param�tres",
"tk_delete" => "Effacer",
"tk_read" => "Lire",
"tk_date" => "Date",
"tk_submit_replyticket" => "Soumettre un ticket de r�ponse",
"ticket_email" => "Email de notification",
"ticket_email_note" => "Les notification email sont envoy�s que si le serveur le permet. Veuillez v�rifier votre adresse email dans les param�tres g�n�raux.",
"ticket_color" => "Couleur des r�ponses non lues",
"helptextdel" => "Les tickets affich�s ont �t� effac�s par vous (ou un administrateur). Si l'utilisateur choisi d'effacer le ticket aussi, alors il sera effac� du syst�me. S'il choisi de soumettre un nouveau ticket, alors le ticket apparaitra de nouveau dans la section non effac�e.",
"helptext" => "Les tickets affich�s en italique sont effac�s par l'utilisateur. Si vous les effacez, ils le seront de fa�on permanente. Si vous r�pondez � un tel ticket, le tag effacement sera retir� et l'utilisateur pourra revoir son ticket et y r�pondre.",
"showdeleted" => "Afficher les tickets effac�s",
"hidedeleted" => "Afficher les tickets",
"tk_fv_required_message" => "Erreur. V�rifiez le texte du ticket",
"tk_replytoticket" => "R�pondre � un ticket",
"tk_from_user" => "De l'utilisateur",
"tk_from_admin" => "De l'adminitrateur",
"tk_submit_st_reply" => "Envoyer un message � l'utilisateur",
"tk_submit_st_reply_button" => "Soumettre",
"tk_to_user" => "A l'utilisateur",
"admin-sends-message" => "Le ticket a �t� g�n�r� � la demande d'un admin. Pour voir le contenu du message, regardez en dessous du corps du message.",
"tk_usernameerror" => "Utilisateur inconnu",
"tk_submit" => "Soumettre",
"tk_replyheader" => "R�pondre aux tickets ou envoyer un message � l'utilisateur",
"tk_submit_reply" => "Soumettre une r�ponse",
"tk_undelete" => "Ticket r�cup�r�",
"edit_admin_emails" => "Editer l'email des admins",
"submit_edited_emails" => "Soumettre",
"ticket_email_general" => "Utiliser les emails de notification",
"ticket_email_general_note" => "Ce sont les param�tres g�n�raux pour toutes les notifications",
"ticket_email_admin" => "Utiliser les notifications email pour les admins",
"ticket_email_admincolor" => "Param�tres de couleur pour les tickets non r�pondus (admin)",
"ticket_default_user_color" => "Couleurs standards pour les r�ponses non lues",
"b_help" => "Gras:  [b]texte[/b] (alt+b)",
"i_help" => "italique: [i]texte[/i] (alt+i)",
"u_help" => "souslign�: [u]texte[/u] (alt+u)",
"q_help" => "citation: [quote]texte[/quote] (alt+q)",
"c_help" => "centr�: [center]texte[/center] (alt+c)",
"p_help" => "image: [img]http://image_url[/img] (alt+p)",
"w_help" => "URL: [url]http://url[/url] ou [url=http://url]URL texte[/url] (alt+w)",
"ticket_desc_short" => "Plateforme d'aide et de ticket",
"ticket_desc_long" => "autoriser vos membres � �crire un ticket d'aide pour une meilleure visibilit�!",
 );
$lang = (is_array($lang))? $lang : array();
$lang = array_merge($lang, $alang);
?>
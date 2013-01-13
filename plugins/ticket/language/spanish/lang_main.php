<?php

if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

//Language: Spanish	
//Created by EQdkp Plus Translation Tool on  2010-07-09 15:10
//File: plugin_ticket
//Source-Language: english

$alang = array( 
"ticket" => "Ticket",
"ticket_open" => "Tickets abiertos",
"ticket_usersettings" => "Ajustes",
"ticket_adminsettings" => "Ajustes de administración",
"ticket_admin_converse" => "Responder tickets",
"ticket_accdenied" => "Acceso denegado",
"ticket_admin" => "Administration",
"ticket_submit" => "Enviar tickets",
"tk_message_body" => "Cuerpo del mensaje",
"tk_submit_ticket" => "Enviar ticket",
"tk_reset" => "Restaurar",
"tk_update_ticket" => "Actualizar ticket",
"tk_delete_ticket" => "borrar ticket",
"tk_replyticket" => "Responder enviando un nuevo ticket",
"ticket_settings_header" => "Settings",
"tk_delete" => "Ajustes",
"tk_read" => "Leer",
"tk_date" => "Fecha",
"tk_submit_replyticket" => "Enviar respuesta de ticket",
"ticket_email" => "Notificación por correo",
"ticket_email_note" => "Las notificaciones sólo se enviarán si el servidor está preparado para ello. Revisa tu dirección de correo en los ajustes generales.",
"ticket_color" => "Color de respuestas sin leer",
"helptextdel" => "Los tickets mostrados aquí han sido borrados por ti (u otro administrador). Si el usuario elige eliminar el ticket también, se borrará completamente del sistema. En caso de que elija enviar otro ticket, aparecerá nuevamente en la sección de tickets restaurados.",
"helptext" => "Los tickets mostrados en cursiva han sido borrados por el usuario. Si los borras, serán eliminados de la base de datos. Si respondes al ticket, se eliminará la marca de borrado y el usuario podrá ver el ticket y las respuestas de nuevo.",
"showdeleted" => "Mostrar tickets borrados",
"hidedeleted" => "Mostrar tickets",
"tk_fv_required_message" => "Error - revisa el texto del ticket",
"tk_replytoticket" => "Responder a un ticket",
"tk_from_user" => "De usuario",
"tk_from_admin" => "De admin",
"tk_submit_st_reply" => "Enviar mensaje al usuario",
"tk_submit_st_reply_button" => "Enviar",
"tk_to_user" => "A usuario",
"admin-sends-message" => "Este ticket ha sido generado a petición de un administrador. Para ver el contenido del mensaje mira el cuerpo del mensaje.",
"tk_usernameerror" => "Nombre de usuario desconocido",
"tk_submit" => "Enviar",
"tk_replyheader" => "Responder Tickets o Enviar Mensaje al usuario",
"tk_submit_reply" => "Enviar respuesta",
"tk_undelete" => "Restaurar ticket",
"edit_admin_emails" => "Editar direcciones de correo de administradores",
"submit_edited_emails" => "Enviar",
"ticket_email_general" => "Usar notificaciones por correo",
"ticket_email_general_note" => "Estos son los ajustes generales para todas las notificaciones",
"ticket_email_admin" => "Usar notificaciones de correo para administradores",
"ticket_email_admincolor" => "Ajustes de color para tickets sin responder (administrador)",
"ticket_default_user_color" => "Ajustes de color estándar para respuestas a tickets sin leer",
"b_help" => "negrita: [b]texto[/b] (alt+b)",
"i_help" => "cursiva: [i]texto[/i] (alt+i)",
"u_help" => "subrayado: [u]texto[/u] (alt+u)",
"q_help" => "cita: [quote]texto[/quote] (alt+q)",
"c_help" => "centrado: [center]texto[/center] (alt+c)",
"p_help" => "imagen: [img]http://image_url[/img] (alt+p)",
"w_help" => "URL: [url]http://url[/url] o [url=http://url]Texto URL[/url]  (alt+w)",
"ticket_desc_short" => "Sistema de Tickets & Helpdesk",
"ticket_desc_long" => "Permite a tus usuarios escribir tickets de suporte para una mejor gestión",
 );
$lang = (is_array($lang))? $lang : array();
$lang = array_merge($lang, $alang);
?>
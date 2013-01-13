<?php
 /*
 * Project:     EQdkp Ticket
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-02-28 16:47:21 +0100 (Sa, 28 Feb 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2007 Achaz, Maintained by PLUS Dev Team
 * @link        http://eqdkp-plus.com
 * @package     ticket
 * @version     $Rev: 4028 $
 * 
 * $Id: common.php 4028 2009-02-28 15:47:21Z wallenium $
 */

#Main
$lang['ticket'] = "Ticket";
$lang['ticket_open'] = "Открыть Письма";
$lang['ticket_usersettings'] = "Настройки";
$lang['ticket_adminsettings'] = "Настройки";
$lang['ticket_admin_converse'] = "Ответы на вопросы";
$lang['ticket_accdenied']= "Доступ Запрещён";

#permissions
$lang['ticket_admin'] = "Администрирование";
$lang['ticket_submit'] = "Отправить Письма";

#index.php und mehr als eine Datei
$lang['tk_message_body'] = "Текст сообщения";
$lang['tk_submit_ticket'] = "Отправить";
$lang['tk_reset'] = "Очистить все";
$lang['tk_update_ticket'] = "Обновить";
$lang['tk_delete_ticket'] = "Удалить";
$lang['tk_replyticket'] = "Ответить";
$lang['ticket_settings_header'] = "Настройки";
$lang['tk_delete'] = "Удалить";
$lang['tk_read'] = "Прочитано";
$lang['tk_date'] = "Дата";
$lang['tk_submit_ticket'] = "Отправить";
$lang['tk_submit_replyticket'] = "Отправить ответ";

#usersettings.php
$lang['ticket_email'] = "Email уведомление";
$lang['ticket_email_note'] = "Email уведомление отправлено. Пожалуйста, проверьте ваш адресс электронной почты в настройках.";
$lang['ticket_color'] = "Цвет непрочитанных писем";

#adminconverse.php
$lang['helptextdel'] = "Вопросы, находящиеся здесь были удалены вами(или Администратором). Если пользователь также удалит это сообщение, то оно будет удалено из системы, если он захочет задать еще один вопрос, тогда вопрос снова появится разделе восстановленных.";
$lang['helptext'] = "Вопросы, которые выделены курсивом, удалены пользователем. Если вы удалите их, то они удалятся из базы данных. Если вы ответите на один из них, то марка удаления будет удалена, и пользователь снова сможет видеть вопросы и ответы";
$lang['showdeleted'] = "Удаленные вопросы";
$lang['hidedeleted'] = "Текущие вопросы";
$lang['tk_fv_required_message'] = "Ошибка - проверьте текст сообщения";
$lang['tk_replytoticket'] = "Ответ на вопрос";
$lang['tk_from_user'] = "От Пользователя";
$lang['tk_from_admin'] = "От Администратора";
$lang['tk_submit_st_reply'] = "Отправить сообщение Пользователю";
$lang['tk_submit_st_reply_button'] = "Отправить";
$lang['tk_to_user'] = "Для Пользователя";
$lang['admin-sends-message'] = "Этот вопрос был задан по требованию Администратора. Рассмотреть сообщения можно ниже текста сообщения.";
$lang['tk_usernameerror'] = "Не правильное имя пользователя";
$lang['tk_submit'] = "Отправить";
$lang['tk_replyheader'] = "Ответ на вопрос/Отправить письмо";
$lang['tk_submit_reply'] = "Отправить";
$lang['tk_undelete'] = "Восстановить сообщение";

#adminSettings
$lang['edit_admin_emails'] = "Редактирование адресс электронной почты Администраторов";
$lang['submit_edited_emails'] = "Отправить";
//$lang['reset'] = "сброс";
$lang['ticket_email_general'] = "Использовать почтовые уведомления";
$lang['ticket_email_general_note'] = "Это - главные настройки для всех уведомлений";
$lang['ticket_email_admin']= "Использовать почтовые уведомления для Администраторов";
$lang['ticket_email_admincolor'] = "Цветовые настройки для неотвеченных писем (Администратор)";
$lang['ticket_default_user_color'] = "Стандартные настойки цвета для непрочитанных ответов на вопросы";

#HTML

// Help lines
$lang['b_help'] = 'Жирный: [b]text[/b] (alt+b)';
$lang['i_help'] = 'Курсив: [i]text[/i] (alt+i)';
$lang['u_help'] = 'Подчеркнутый: [u]text[/u] (alt+u)';
$lang['q_help'] = 'Цитата: [quote]text[/quote] (alt+q)';
$lang['c_help'] = 'По центру: [center]text[/center] (alt+c)';
$lang['p_help'] = 'Изображение: [img]http://image_url[/img] (alt+p)';
$lang['w_help'] = 'Ссылка: [url]http://url[/url] or [url=http://url]URL text[/url]  (alt+w)';

// PLUS Infos
$lang['ticket_desc_short']  = "Helpdesk & Ticket System";
$lang['ticket_desc_long'] = "Let your Users write Support tickets for better overwiew";
?>

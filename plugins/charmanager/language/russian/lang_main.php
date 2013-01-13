<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2008-08-08 15:03:42 +0200 (Fr, 08 Aug 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 2502 $
 * 
 * $Id: versions.php 2502 2008-08-08 13:03:42Z wallenium $
 */

//Main
$lang['charmanager']          = 'Character Manager';
$lang['uc_manage_chars']			= 'Управление участниками';
$lang['uc_credit_name']				= 'EQDKP управление участниками';
$lang['uc_enu_profiles']			= 'Профили';
$lang['cm_short_desc']        = 'User can manage members';
$lang['cm_long_desc']         = 'With the Charmanager plugin, user can add, manage and connect members by themselves. There are additional profil fields, too.';

// Error Messages
$lang['uc_error_p1']          = 'Не получилось добавить ';
$lang['uc_error_p2']          = ' участник существует как ID ';
$lang['uc_error_p3']          = ' ';
$lang['uc_saved_not']         = 'ОШИБКА: Данные не были сохранены. Пожалуйста, попробуйте снова или проинформируйте Администратора';
$lang['uc_error_memberinfos']	= 'Не удалось получить информацию о Участнике';
$lang['uc_error_raidinfos']		= 'Не удалось получить информацию о Рейдe';
$lang['uc_error_iteminfos']		= 'Не удалось получить информацию о Предмете';
$lang['uc_error_itemraidi']		= 'Не удалось получить информацию о Предмете/Рейдe';
$lang['uc_not_loggedin']			= 'Вы не произвели вход';
$lang['uc_not_installed']			= 'Плагин Character Manager не установлен';
$lang['uc_no_prmissions']			= 'У вас недостаточно прав для захода на данную страницу. Пожалуйста, сообщите Администратору.';
$lang['uc_php_version']				= "CharManager requires PHP %1\$s or higher. Your server runs PHP %2\$s";
$lang['uc_plus_version']			= "CharManager requires EQDKP-PLUS %1\$s or higher. Your installed Version is %2\$s";

// Info Boxes
$lang['uc_saved_succ']        = 'Изменения были сохранены';
$lang['us_char added']        = 'Персонаж был добавлен';
$lang['us_char_updated']      = 'Персонаж был обновлен';
$lang['uc_info_box']          = 'Информация';
$lang['uc_pic_changed']				= 'Изображение было успешно изменено';
$lang['uc_pic_added']					= 'Изображение было успешно добавлено';

// Date functionality
$lang['uc_changedate']				= 'm-d-Y';

// Armory Menu
$lang['uc_armorylink1']				= 'Armory';
$lang['uc_armorylink2']				= 'Таланты';
$lang['uc_armorylink3']				= 'Гильдия';

//User Settings
$lang['uc_charmanager']       = 'Управление персонажам (и)';
$lang['uc_change_pic']				= 'Выбрать изображение';
$lang['uc_add_pic']						= 'Добавить изображение';
$lang['uc_add_char']          = 'Добавить персонажа';
$lang['uc_save_char']					= 'Сохранить персонажа';
$lang['overtake_char']        = 'Сделать данного персонажа вашим';
$lang['uc_edit_char']         = 'Редактирование выбранного персонажа';
$lang['uc_conn_members']			= 'Присоединение персонажей';
$lang['uc_connections']				= 'Присоединение персоонажей';
$lang['uc_button_cancel']     = 'Отмена';
$lang['uc_button_edit']				= 'Редактирование';
$lang['uc_tt_n1']							= 'Выберите персонажа, которого вы хотите<br/> редактировать';
$lang['uc_tt_n2']							= 'Присоединить персонажа (ей) <br/>к вашей учетной записи <br/>в DKP-Системе';
$lang['uc_tt_n3']							= 'Добавить персонажа<br/>не существующего в DKP-Системе';
$lang['uc_prifler_expl']			= 'Профили будут показаны как WebLinks, ther\'ll not be any import!';
$lang['uc_ext_import']				= 'Импорт данных с внешнего источника';
$lang['uc_ext_import_sh']			= 'Импорт данных';
$lang['uc_connectme']         = 'Save';
$lang['uc_del_warning']				= 'Should the member be removed? Alle Points and Items will be removed and cannot be restored.';

// Import
$lang['uc_prof_import']				= 'Импорт';
$lang['uc_import_forw']				= 'Продолжение';
$lang['uc_imp_succ']					= 'Дата была успешно импортирована';
$lang['uc_upd_succ']					= 'Дата была успешно обновлена';
$lang['uc_imp_failed']				= 'Произошла ошибка во время процесса импорта. Пожалуйста, попробуйте снова.';

// Armory Import
$lang['uc_armory_loc']				= 'Локализация сервера';
$lang['uc_charname']					= 'Имя персонажа';
$lang['uc_servername']				= 'Название игрового сервера (т.е. Warsong)';
$lang['uc_charfound']					= "Персонаж  <b>%1\$s</b> доступен на Armory.";
$lang['uc_charfound2']				= "Этот профиль персонажа был обновлен последний раз<b>%1\$s</b>.";
$lang['uc_charfound3']				= 'ВНИМАНИЕ: Во время процесса импорта все текущие данные будут перезаписаны!';
$lang['uc_armory_confail']		= 'Нету соединения с Armory. Данные не были отправлены.';

// Edit Profile tabs
$lang['uc_tab_profilers']			= 'Профиль';
$lang['uc_tab_Character']			= 'Персонаж';
$lang['uc_tab_skills']				= 'Таланты';
$lang['uc_tab_import']				= 'Импорт';
$lang['uc_tab_raidinfo']			= 'Информация о Рейде';
$lang['uc_tab_raids']					= 'Рейды';
$lang['uc_tab_items']					= 'Предметы';
$lang['uc_tab_profession']		= 'Профессии';
$lang['uc_tab_notes']         = 'Заметки';

// Professions
$lang['uc_first_prof']				= 'Первичная профессия';
$lang['uc_second_prof']				= 'Вторичная профессия';
$lang['uc_prof_skill']				= 'Уровень умения';
$lang['professionsarray']			= array(
																'alchemy'					=> 'Алхимия',
																'mining'					=> 'Шахтерство',
																'engineering'			=> 'Инженерия',
																'skinning'				=> 'Снятие шкур',
																'herbalism'				=> 'Травничество',
																'leatherworking'	=> 'Кожевничество',
																'blacksmithing'		=> 'Кузнеческое дело',
																'tailoring'				=> 'Шитье',
																'enchanting' 			=> 'Зачаровывание',
																'jewelcrafting'		=> 'Ювелирное дело',
																'inscription'     => 'Inscription'
															);
$lang['uc_gender']						= 'Пол';
$lang['genderarray']					= array(
																'Male'						=> 'Мужской',
																'Female'					=> 'Женский',
															);
$lang['uc_faction']						= 'Фракция';
$lang['factionarray']					= array(
																'Horde'						=> 'Орда',
																'Alliance'				=> 'Альянс',
															);

// resistences
$lang['uc_resitence']				  = 'Сопротивление';
$lang['uc_res_fire']					= 'Огонь';
$lang['uc_res_frost']					= 'Холод';
$lang['uc_res_arcane']				= 'Аркан';
$lang['uc_res_nature']				= 'Природа';
$lang['uc_res_shadow']				= 'Тьма';

// Bars
$lang['uc_bar_health']				= "Здоровье";
$lang['uc_bar_energy']				= "Энергия";
$lang['uc_bar_mana']					= "Мана";
$lang['uc_bar_rage']					= "Ярость";

// Add Picture
$lang['uc_save_pic']					= 'Сохранить';
$lang['uc_load_pic']					= 'Загрузить изображение';
$lang['uc_allowed_types']			= 'Допустимые типы изображений';
$lang['uc_max_resolution']		= 'Макс. Разрешение';
$lang['uc_pixel']							= 'Пиксели';
$lang['uc_not_writable']			= 'Директория \'data/\' не может быть прочитана. Пожалуйста, сообщите Администратору.';

//Admin
$lang['is_adminmenu_uc']			= 'Character Manager';
$lang['uc_manage']            = 'Управление';
$lang['uc_add']            		= 'Добавить';
$lang['uc_connect']						= 'Присоединение персонажей';
$lang['uc_view']							= 'Просмотр профилей';
$lang['uc_edit_all']					= 'Редактировать все';
$lang['uc_config']						= 'Настройки';
$lang['uc_delete']						= 'Delete own characters';

// About Dialog
$lang['about_header']					= 'Разработчики';

// Profile
$lang['uc_char_info']					= 'Информация о персонаже';
$lang['uc_last_5_raids']			= 'Последние 5 Рейдов';
$lang['uc_last_5_items']			= 'Последние 5 Предметов';
$lang['uc_ext_profile']				= 'Внешний профиль';
$lang['uc_buffed']						= 'Buffed.de';
$lang['uc_allakhazam']				= 'Allakhazam';
$lang['uc_curse_profiler']		= 'Curse Profiler';
$lang['uc_ctprofiles']				= 'CT Profiles';
$lang['uc_receives']					= 'Профессии';
$lang['uc_guild']							= 'Гильдия';
$lang['uc_raid_infos'] 				= 'Информация о Рейде';
$lang['uc_talentplaner']			= 'Калькулятор талантов';
$lang['uc_unknown']						= 'Неизвестно';
$lang['uc_lastupdate']				= 'Последнее обновление профиля';
$lang['uc_level_out']					= 'Уровень';
$lang['uc_notes']             = 'Заметки';

// About dialog
$lang['uc_copyright'] 				= 'Copyright';
$lang['uc_created_devteam']		= 'WalleniuM';
$lang['uc_url_web']           = 'Веб-страница';
$lang['uc_dialog_header']			= 'О CharManager';
$lang['uc_additions']         = 'Submissions';

// config
$lang['uc_setting_saved_h']   = 'Settings saved';
$lang['uc_setting_saved']			= 'Настройки были успешно сохранены';
$lang['uc_setting_failed']		= 'Настройки не были сохранены. Пожалуйста, попробуйте снова или свяжитесь с Администратором';
$lang['uc_header_global']			= 'Настройки Charmanager';
$lang['uc_enabl_updatecheck']	= 'Активировать проверку обновлений';
$lang['uc_servername']				= 'Название сервера';
$lang['uc_lock_server']				= 'Заморозить название сервера для участников(они не могут изменить это!)';
$lang['uc_update_all']				= 'Обновить все данные профиля(т.е. Armory)';
$lang['uc_bttn_update']				= 'Обновить';
$lang['uc_cache_update']			= 'Обновить профили пользователей';
$lang['uc_profile_updater']		= 'Загружается информация о пользователях. Это займет несколько минут. Пожалуйста, подождите......';
$lang['uc_server_loc']				= 'Локализация сервера';
$lang['uc_profile_ready']			= 'Профили были успешно импортированы. Вы можете <a href="#" onclick="javascript:closeWindow()" >закрыть</a> это окно.';
$lang['uc_last_updated']			= 'Последнее обновление';
$lang['uc_never_updated']			= 'Не было обновления';
$lang['uc_noprofile_found']		= 'Данные профиля не существуют';
$lang['uc_profiles_complete']	= 'Профили успешно обновлены';
$lang['uc_notyetupdated']			= 'Charakter waiting for Profiler update (inactive)';
$lang['uc_armory_link']				= 'Данные профиля: показывать меню с ссылками на Armory';
$lang['uc_no_resi_save']			= 'Не импортировать сопротивления';
$lang['uc_lp_hideresis']      = 'Скрыть сопротивления персонажей в списке Профилей';
?>
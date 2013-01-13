<?php
 /*
 * Project:     EQdkp CharManager 1
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-05-20 13:18:06 +0200 (mié, 20 may 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     charmanager
 * @version     $Rev: 4915 $
 * 
 * $Id: lang_main.php 4915 2009-05-20 11:18:06Z wallenium $
 */

//Main 
$lang['charmanager']          = 'Gestor de personaje';
$lang['uc_manage_chars']			= 'Gestionar personajes';
$lang['uc_credit_name']				= 'Gestor de Personajes EQDKP';
$lang['uc_enu_profiles']			= 'Perfiles';
$lang['cm_short_desc']        = 'El usuario puede gestionar miembros';
$lang['cm_long_desc']         = 'Con el plugin Gestor de Personaje, los usuarios pueden añdir, gestionar y asignar miembros por si mismos. Hay campos adicionales también.';

// Error Messages
$lang['uc_faild_memberadd']   = "Fallo al añadir el miembro %1\$s existe con la ID %2\$s";
$lang['uc_saved_not']         = 'ERROR: No se pueden guardar los datos. Por favor inténtalo de nuevo o informa al administrador';
$lang['uc_error_memberinfos']	= 'No se puede obtener información del miembro del plugin de Gestión de Personaje';
$lang['uc_error_raidinfos']		= 'No se puede obtener información de la banda del plugin de Gestión de Personaje';
$lang['uc_error_iteminfos']		= 'No se puede obtener información del objeto del plugin de Gestión de Personaje';
$lang['uc_error_itemraidi']		= 'No se puede obtener información del objeto/banda del plugin de Gestión de Personaje';
$lang['uc_not_loggedin']			= 'No estás logado en el sistema';
$lang['uc_not_installed']			= 'El plugin de Gestión de Personajes no está instalado';
$lang['uc_no_prmissions']			= 'No tienes permisos para entrar. Por favor, habla con un administrador.';
$lang['uc_php_version']				= "Gestión de Personajes requiere PHP %1\$s o superior. El servidor usa actualmente PHP %2\$s";
$lang['uc_plus_version']			= "El Gestor de Personajes requiere EQDKP-PLUS %1\$s o superior. La versión actual es %2\$s";

// Info Boxes
$lang['uc_saved_succ']        = 'Los cambios se han guardado';
$lang['us_char added']        = 'Se añadió el personaje';
$lang['us_char_updated']      = 'El personaje se actualizó';
$lang['uc_info_box']          = 'Información';
$lang['uc_pic_changed']				= 'La imagen se cambió correctamente';
$lang['uc_pic_added']					= 'Se añadió correctamente la imagen';

// Date functionality
$lang['uc_changedate']				= 'm-d-Y';

// Armory Menu
$lang['uc_armorylink1']				= 'armeria';
$lang['uc_armorylink2']				= 'talentos y glifos';
$lang['uc_armorylink3']				= 'reputación';
$lang['uc_armorylink4']				= 'logros';
$lang['uc_armorylink5']				= 'estadísticas';
$lang['uc_armorylink6']				= 'hermandad';

//User Settings
$lang['uc_charmanager']       = 'Gestión de Personaje';
$lang['uc_change_pic']				= 'Cambiar imagen';
$lang['uc_add_pic']						= 'Añadir imagen';
$lang['uc_add_char']          = 'Añadir personaje';
$lang['uc_add_char_plain']		= 'Crear nuevo';
$lang['uc_add_char_armory']		= 'Importar';
$lang['uc_save_char']					= 'Guardar personaje';
$lang['overtake_char']        = 'Hacer este personaje tuyo';
$lang['uc_edit_char']         = 'Editar personaje seleccionado';
$lang['uc_conn_members']			= 'Asignar personajes';
$lang['uc_connections']				= 'Asignaciones de personajes';
$lang['uc_button_cancel']     = 'Cancelar';
$lang['uc_button_edit']				= 'Editar';
$lang['uc_tt_n1']							= 'Escoge al personaje que quieres<br/> editar';
$lang['uc_tt_n2']							= 'Asigna tu cuenta de usuario a<br/>personajes que existen en<br/>el sistema DKP';
$lang['uc_tt_n3']							= 'Crear un nuevo personaje que<br/>no exista en el sistema DKP';
$lang['uc_prifler_expl']			= 'Los perfiles se mostrarán como enlaces webs, no se importarán!';
$lang['uc_ext_import_sh']			= 'Importar datos';
$lang['uc_connectme']         = 'Guardar';
$lang['uc_updat_armory']			= 'Actualizar de la Armería';
$lang['uc_add_massupdate']		= 'Actualizar todo';
$lang['uc_need_confirmation']	= '[por confirmar]';

// Member Tasks
$lang['uc_del_warning']				= '¿Se debe borrar el miembro? Los puntos y objetos serán eliminados y no podrán ser recuperados.';
$lang['uc_del_msg_all']				= '¿Eliminar todos los personajes?';
$lang['uc_confirm_msg_all']		= '¿Confirmar todos los personajes?';
$lang['cm_todo_txt']					= "Hay %1\$s tareas de administrador restantes.";
$lang['cm_todo_head']					= 'Tareas de Administrador del Gestor de Personaje';
$lang['uc_delete_manager']		= 'Gestionar tareas de administrador';
$lang['uc_rewoke_char']				= 'Restaurar personaje';
$lang['uc_delete_char']				= 'Borrar personaje';
$lang['uc_delete_allchar']		= 'Borrar todos los personajes';
$lang['uc_confirm_list']			= 'Personaje por confirmar';
$lang['uc_delete_list']				= 'Personaje por borrar';

// Import
$lang['uc_prof_import']				= 'importar';
$lang['uc_import_forw']				= 'continuar';
$lang['uc_imp_succ']					= 'Los datos se importaron correctamente';
$lang['uc_upd_succ']					= 'Los datos se actualizaron correctamente';
$lang['uc_imp_failed']				= 'Ocurrio un error en el proceso de importación. Por favor, inténtelo de nuevo.';

// Armory Import
$lang['uc_armory_loc']				= 'Localización del servidor';
$lang['uc_charname']					= 'nombre del personaje';
$lang['uc_servername']				= 'Nombre del servidor (p.e. Tyrande)';
$lang['uc_charfound']					= "El personaje  <b>%1\$s</b> está disponible en la armería.";
$lang['uc_charfound2']				= "El perfil de este personaje fué actualizado por última vez el <b>%1\$s</b>.";
$lang['uc_charfound3']				= 'ATENCION: ¡Durante el proceso de importación todos los datos se sobreescribirán!';
$lang['uc_armory_confail']		= 'No hay conexión con la Armería. Los datos no pueden ser enviados.';
$lang['uc_armory_imported']		= 'importado';
$lang['uc_armory_impfailed']	= 'fallido';
$lang['uc_armory_impduplex']	= 'duplicado';
$lang['uc_class_filter']			= 'Miembros de la clase';
$lang['uc_class_nofilter']		= 'No filtrar';
$lang['uc_guild_name']				= 'Nombre de Hermandad';
$lang['uc_level_filter']			= 'Todos los miembros con nivel igual o superior a';
$lang['uc_imp_novariables']		= 'Debes establecer un servidor y una localización en los ajustes primero.';
$lang['uc_imp_noguildname']		= 'No se ha indicado nombre de hermandad';
$lang['uc_gimp_header_load']	= 'La hermandad será importada, por favor espera...';
$lang['uc_gimp_header_fnsh']	= 'Importación de hermandad completada';
$lang['uc_gimp_finish_note']	= 'Nota: No se importan todos los campos. Sólo nombre de miembros, raza, clase y nivel han sido importados. Para importar el resto, ejecuta la actualización de Armería manualmente.';
$lang['uc_gimp_infotxt']			= 'El tiempo de ejecución del script puede ser mayor de 60 seg. y la memoria más grande que 32 M. Por favor, mira abajo si la descarga ha finalizado.';
$lang['uc_startdkp']					= 'Añade DKP iniciales';
$lang['uc_noprofile_found']		= 'No hay perfil disponible';
$lang['uc_profiles_complete']	= 'Perfíl actualizado correctamente';
$lang['uc_notyetupdated']			= 'No hay datos nuevos (personaje desactualizado)';
$lang['uc_error_with_id']			= 'Error con la ID del miembro. Se ha saltado este miembro.';

// Edit Profile tabs
$lang['uc_tab_profilers']			= 'Perfil';
$lang['uc_tab_Character']			= 'Personaje';
$lang['uc_tab_skills']				= 'Habilidades';
$lang['uc_tab_raidinfo']			= 'Info de Banda';
$lang['uc_tab_raids']					= 'Bandas';
$lang['uc_tab_items']					= 'Objetos';
$lang['uc_tab_profession']		= 'Profesiones';
$lang['uc_tab_notes']         = 'Notas';

// Professions
$lang['uc_first_prof']				= 'Primera profesión';
$lang['uc_second_prof']				= 'Segunda profesión';
$lang['uc_prof_skill']				= 'Skill';
$lang['professionsarray']			= array(
																'alchemy'					=> 'Alquimia',
																'mining'					=> 'Minería',
																'engineering'			=> 'Ingeniería',
																'skinning'				=> 'Desuello',
																'herbalism'				=> 'Herboristería',
																'leatherworking'	=> 'Peletería',
																'blacksmithing'		=> 'Herrería',
																'tailoring'				=> 'Sastrería',
																'enchanting' 			=> 'Encantamiento',
																'jewelcrafting'		=> 'Joyería',
																'inscription'     => 'Inscripción'
															);
$lang['uc_gender']						= 'Género';
$lang['genderarray']					= array(
																'Male'						=> 'Masculino',
																'Female'					=> 'Femenino',
															);
$lang['uc_faction']						= 'Facción';
$lang['factionarray']					= array(
																'Horde'						=> 'Horda',
																'Alliance'				=> 'Alianza',
															);

// resistences
$lang['uc_resitence']				  = 'Resistencia';
$lang['uc_res_fire']					= 'Fuego';
$lang['uc_res_frost']					= 'Escarcha';
$lang['uc_res_arcane']				= 'Arcano';
$lang['uc_res_nature']				= 'Naturaleza';
$lang['uc_res_shadow']				= 'Sombras';

// Bars
$lang['uc_bar_health']				= "Salud";
$lang['uc_bar_energy']				= "Energía";
$lang['uc_bar_mana']					= "Maná";
$lang['uc_bar_rage']					= "Ira";

// Add Picture
$lang['uc_save_pic']					= 'Guardar';
$lang['uc_load_pic']					= 'Cargar imagen';
$lang['uc_allowed_types']			= 'Tipo de imágenes permitidas';
$lang['uc_max_resolution']		= 'Resolución max.';
$lang['uc_pixel']							= 'píxeles';
$lang['uc_not_writable']			= 'La carpeta \'data/\' no se puede escribir. Por favor, informa a un administrador.';

//Admin
$lang['is_adminmenu_uc']			= 'Gestor de personajes';
$lang['uc_manage']            = 'Administrar';
$lang['uc_add']            		= 'Añadir';
$lang['uc_connect']						= 'Asignar personajes';
$lang['uc_view']							= 'Ver perfiles';
$lang['uc_edit_all']					= 'Editar todo';
$lang['uc_config']						= 'Ajustes';
$lang['uc_delete']						= 'Borrar personajes propios';
$lang['uc_delmanager']				= 'Administrar ToDo';

// About Dialog
$lang['about_header']					= 'Créditos';

// Profile
$lang['uc_char_info']					= 'Información del personaje';
$lang['uc_last_5_raids']			= 'Últimas 5 bandas';
$lang['uc_last_5_items']			= 'Últimos 5 objetos';
$lang['uc_ext_profile']				= 'Perfil externo';
$lang['uc_buffed']						= 'Buffed.de';
$lang['uc_allakhazam']				= 'Allakhazam';
$lang['uc_curse_profiler']		= 'Curse Profiler';
$lang['uc_ctprofiles']				= 'CT Profiles';
$lang['uc_receives']					= 'Profesiones';
$lang['uc_guild']							= 'Hermandad';
$lang['uc_raid_infos'] 				= 'Información de banda';
$lang['uc_talentplaner']			= 'Planificador de talentos';
$lang['uc_unknown']						= 'Desconocido';
$lang['uc_lastupdate']				= 'Última actualización del perfil';
$lang['uc_level_out']					= 'Nivel';
$lang['uc_notes']             = 'Notas';

// About dialog
$lang['uc_copyright'] 				= 'Copyright';
$lang['uc_created_devteam']		= 'por WalleniuM';
$lang['uc_url_web']           = 'Web';
$lang['uc_dialog_header']			= 'Sobre Gestor de personajes';
$lang['uc_additions']         = 'Aportaciones';

// config
$lang['uc_setting_saved_h']   = 'Ajustes guardados';
$lang['uc_setting_saved']			= 'Los ajustes del Gestor de personajes se han guardado correctamente';
$lang['uc_setting_failed']		= 'Los ajustes no se guardaron. Inténtalo de nuevo o contacta a un administrador';
$lang['uc_header_global']			= 'Ajustes del Gestor de Personajes';
$lang['uc_enabl_updatecheck']	= 'Activar revisión de actualización';
$lang['uc_servername']				= 'Nombre del servidor';
$lang['uc_lock_server']				= 'Bloquear nombre de servidor para los usuarios (no podrán cambiarlo)';
$lang['uc_update_all']				= 'Actualizar todos los datos del prefil (p.e. Armería)';
$lang['uc_bttn_update']				= 'Actualizar';
$lang['uc_cache_update']			= 'Actualizar perfiles de miembros';
$lang['uc_profile_updater']		= 'Cargando información del perfil. Por favor, espere...';
$lang['uc_server_loc']				= 'Localización del servidor';
$lang['uc_profile_ready']			= 'Los perfiles se importaron correctamente. Puedes <a href="#" onclick="javascript:closeWindow()" >cerrar</a> esta ventana.';
$lang['uc_last_updated']			= 'Último actualizado';
$lang['uc_never_updated']			= 'Nunca actualizado';
$lang['uc_armory_link']				= 'Lista de perfil: muestra un menú con enlacez a la armería';
$lang['uc_no_resi_save']			= 'No importar resistencias';
$lang['uc_lp_hideresis']      = 'Ocultar resistencias del usuario en la lista del perfil';
$lang['uc_defaultrank']				= 'Rango para personajes recién creados';
$lang['uc_defaultrank_none']	= 'Ninguno';
$lang['uc_reqconfirm']				= 'Los administradores deben confirmar los personajes creados por los usuarios';
$lang['uc_confirm_char']			= 'Confirmar personaje';
$lang['uc_confirm_allchar']		= 'Confirmar todos';
$lang['uc_limport']						= 'Ajustes de importación';
$lang['uc_import_guild']			= 'Importar todos los miembros de la hermandad';
$lang['uc_import_guildb']			= 'Importar hermandad';
$lang['uc_import_srvlang']    = 'Lenguaje de la Armería';
?>
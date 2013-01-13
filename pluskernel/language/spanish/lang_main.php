<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-09-18 17:04:46 +0200 (vie, 18 sep 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: hoofy_leon $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 5879 $
 *
 * $Id: lang_main.php 5879 2009-09-18 15:04:46Z hoofy_leon $
 */

// Do not remove. Security Option!
if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

//---- Main ----
$plang['pluskernel']          	= 'Config PLUS';
$plang['pk_adminmenu']         	= 'Config PLUS';
$plang['pk_settings']						= 'Configuraci�n';
$plang['pk_date_settings']			= 'd.m.y';

//---- Javascript stuff ----
$plang['pk_plus_about']					= 'Acerca de EQDKP PLUS';
$plang['updates']								= 'Actualizaciones disponibles';
$plang['loading']								= 'Cargando...';
$plang['pk_config_header']			= 'Configuraci�n de EQDKP PLUS';
$plang['pk_close_jswin1']      	= 'Cerrar la';
$plang['pk_close_jswin2']     	= 'ventana antes de abrir de nuevo!';
$plang['pk_help_header']				= 'Ayuda';
$plang['pk_plus_comments']  	= 'Comentarios';

//---- Updater Stuff ----
$plang['pk_alt_attention']			= 'Atenci�n';
$plang['pk_alt_ok']							= '�Todo OK!';
$plang['pk_updates_avail']			= 'Actualizaciones disponibles';
$plang['pk_updates_navail']			= 'No hay actualizaciones disponibles';
$plang['pk_no_updates']					= 'Sus versiones est�n al d�a. No hay ninguna versi�n m�s reciente disponible.';
$plang['pk_act_version']				= 'Nueva versi�n';
$plang['pk_inst_version']				= 'Instalada';
$plang['pk_changelog']					= 'Changelog';
$plang['pk_download']						= 'Descargar';
$plang['pk_upd_information']		= 'Informaci�n';
$plang['pk_enabled']						= 'deshabilitada';
$plang['pk_disabled']						= 'habilitada';
$plang['pk_auto_updates1']			= 'La alerta de actualizaci�n autom�tica est�';
$plang['pk_auto_updates2']			= 'Si desactiva esta configuraci�n, por favor compruebe regularmente las actualizaciones para evitar ataques y estar al d�a.';
$plang['pk_module_name']				= 'Nombre del m�dulo';
$plang['pk_plugin_level']				= 'Nivel';
$plang['pk_release_date']				= 'Fecha';
$plang['pk_alt_error']					= 'Error';
$plang['pk_no_conn_header']			= 'Error de conexi�n';
$plang['pk_no_server_conn']			= 'Se ha producido un error al intentar ponerse en contacto con el servidor de actualizaci�n, puede que su servidor no permita conexiones salientes o puede que el error fuera causado por un problema de red. Por favor visite el foro de EQdkp Plus para asegurarse de que est� ejecutando la �ltima versi�n.';
$plang['pk_reset_warning']			= 'Reiniciar p�gina';

//---- Update Levels ----
$plang['pk_level_other']				= 'Otro';
$updatelevel = array (
	'Bugfix'								=> 'Arreglo de error',
	'Feature Release'						=> 'Lanzamiento de nueva caracter�stica',
	'Security Update'						=> 'Actualizaci�n de seguridad',
	'New version'								=> 'Nueva versi�n',
	'Release Candidate'					=> 'Release Candidate',
	'Public Beta'								=> 'Beta p�blica',
	'Closed Beta'								=> 'Beta cerrada',
	'Alpha'											=> 'Alfa',
);

//---- About ----
$plang['pk_version']						= 'Versi�n';
$plang['pk_prodcutname']				= 'Producto';
$plang['pk_modification']				= 'Mod';
$plang['pk_tname']							= 'Plantilla';
$plang['pk_developer']					= 'Desarrollador';
$plang['pk_plugin']							= 'Plugin';
$plang['pk_weblink']						= 'Enlace web';
$plang['pk_phpstring']					= 'Cadena de PHP';
$plang['pk_phpvalue']						= 'Valor';
$plang['pk_donation']						= 'Donaci�n';
$plang['pk_job']								= 'Trabajo';
$plang['pk_sitename']						= 'Sitio';
$plang['pk_dona_name']					= 'Nombre';
$plang['pk_betateam1']					= 'Equipo de pruebas beta (Alemania)';
$plang['pk_betateam2']					= 'orden cronol�gico';
$plang['pk_created by']					= 'Creado por';
$plang['web_url']								= 'Web';
$plang['personal_url']					= 'Privado';
$plang['pk_credits']						= 'Cr�ditos';
$plang['pk_sponsors']						= 'Patrocinadores';
$plang['pk_plugins']						= 'Plugins';
$plang['pk_modifications']			= 'Mods';
$plang['pk_themes']							= 'Estilos';
$plang['pk_additions']					= 'Adiciones de C�digo';
$plang['pk_tab_stuff']					= 'Equipo EQDKP';
$plang['pk_tab_help']						= 'Ayuda';
$plang['pk_tab_tech']						= 'Tecnolog�a';

//---- Settings ----
$plang['pk_save']								= 'Guardar';
$plang['pk_save_title']					= 'Ajustes guardados';
$plang['pk_succ_saved']					= 'La configuraci�n se ha guardado correctamente.';
 // Tabs
$plang['pk_tab_global']					= 'Global';
$plang['pk_tab_multidkp']				= 'multiDKP';
$plang['pk_tab_links']					= 'Enlaces';
$plang['pk_tab_bosscount']			= 'BossCounter';
$plang['pk_tab_listmemb']				= 'Lista de miembros';
$plang['pk_tab_itemstats']			= 'Estad�sticas';
// Global
$plang['pk_set_QuickDKP']				= 'Mostrar QuickDKP';
$plang['pk_set_Bossloot']				= 'Mostrar bot�n de jefe?';
$plang['pk_set_ClassColor']			= 'Nombres de clases coloreados';
$plang['pk_set_Updatecheck']		= 'Habilitar b�squeda de actualizaci�n';
$plang['pk_window_time1']				= 'Mostrar ventana cada';
$plang['pk_window_time2']				= '(Minutos)';
// MultiDKP
$plang['pk_set_multidkp']				= 'Activar MultiDKP';
// Listmembers
$plang['pk_set_leaderboard']		= 'Mostrar la tabla de clasificaciones';
$plang['pk_set_lb_solo']				= 'Mostrar la tabla de clasificaciones por cuenta';
$plang['pk_set_rank']						= 'Mostrar rango';
$plang['pk_set_rank_icon']			= 'Mostrar icono del rango';
$plang['pk_set_level']					= 'Mostrar nivel';
$plang['pk_set_lastloot']				= 'Mostrar �ltimo bot�n';
$plang['pk_set_lastraid']				= 'Mostrar �ltima banda';
$plang['pk_set_attendance30']		= 'Mostrar la asistencia a bandas en 30 d�as';
$plang['pk_set_attendance60']		= 'Mostrar la asistencia a bandas en 60 d�as';
$plang['pk_set_attendance90']		= 'Mostrar la asistencia a bandas en 90 d�as';
$plang['pk_set_attendanceAll']	= 'Mostrar la asistencia a bandas en total';
// Links
$plang['pk_set_links']					= 'Activar enlaces';
$plang['pk_set_linkurl']				= 'URL del enlace';
$plang['pk_set_linkname']				= 'Nombre del enlace';
$plang['pk_set_newwindow']			= 'abrir en nueva ventana ?';
// BossCounter
$plang['pk_set_bosscounter']		= 'Mostrar BossCounter';


# Itemstats #
#
$plang['pk_set_itemstats']			= 'Activar ItemStats';
$plang['pk_is_language']				= 'Lenguaje de ItemStats';
$plang['pk_german']							=	'Alem�n';
$plang['pk_english']						= 'Ingl�s';
$plang['pk_french']							= 'Franc�s';
$plang['pk_set_icon_ext']				= '';
$plang['pk_set_icon_loc']				= '';
$plang['pk_set_en_de']					= 'Traducir objetos de ingl�s a alem�n';
$plang['pk_set_de_en']					= 'Traducir objetos de alem�n a ingl�s';
$plang['pk_is_set_prio']                  = 'Prioridad de la Base de datos de objetos';
$plang['pk_is_help_prio']                 = 'Establece el orden de consultas de la base de datos de objetos.';
$plang['pk_is_set_alla_lang']	            = 'Idioma de los nombres de objetos en Allakhazam.';
$plang['pk_is_help_alla_lang']	          = 'Cu�l debe ser el idioma de los objetos?';
$plang['pk_is_set_lang']		              = 'Idioma estandar de los ID\'s de objeto.';
$plang['pk_is_help_lang']		              = 'Idioma estandar de los IDs de objeto. Ejemplo : [item]17182[/item] escoger� este idioma';
$plang['pk_is_set_autosearch']            = 'B�squeda inmediata';
$plang['pk_is_help_autosearch']           = 'Activada: Si el objeto no est� en Cach�, busca el objeto autom�ticamente. No activada: La informaci�n del objeto s�lo se obtiene al hacer click en la informaci�n del objeto.';
$plang['pk_is_set_integration_mode']      = 'Modo de integraci�n';
$plang['pk_is_help_integration_mode']     = 'Normal: Texto de escaneo y tooltip de ajuste en HTML. Script: Texti de escaneo y establece etiquetas <script>.';
$plang['pk_is_set_tooltip_js']            = 'Vista de Tooltips';
$plang['pk_is_help_tooltip_js']           = 'Overlib: El Tooltip normal. Light: Versi�n light, tiempos de carga m�s r�pidos.';
$plang['pk_is_set_patch_cache']           = 'Ruta de Cache';
$plang['pk_is_help_patch_cache']          = 'Ruta al cach� de objeto del usuario, iniciando en /itemstats/. Por defecto=./xml_cache/';
$plang['pk_is_set_patch_sockets']         = 'Ruta de las im�genes de hueco';
$plang['pk_is_help_patch_sockets']        = 'Ruta a los archivos de im�genes de los huecos de objetos.';
$plang['pk_is_useitemlist']				  = 'Usar Itemlist';
$plang['pk_help_useitemlist'] 			  = 'Itemlist permite una extraci�n m�s r�pida de los IDs de objeto perodebe ser realizada una vez, lo cual puede llevar algo de tiempo y ser demasiado largo, depende del alojamiento.';

################
# new sort
###############

//MultiDKP
//

$plang['pk_set_multi_Tooltip']						= 'Mostrar descripci�n DKP';
$plang['pk_set_multi_smartTooltip']			  = 'Descripci�n elegante';

//Help
$plang['pk_help_colorclassnames']				  = "Si est� activo, mostrar� a los jugadores con los colores del WoW de su clase y su icono de clase.";
$plang['pk_help_quickdkp']								= "Muestra al usuario conectado los puntos de todos los miembros que estan asignados a �l por encima del men�.";
$plang['pk_help_boosloot']								= "Si est� activo, puede hacer click en los nombres de jefe de las notas de banda y el bosscounter para ver una lista detallada de los objetos tirados. Si est� desactivado, se enlazar� con Blasc.de (s�lo activar si se introduce una banda por cada jefe)";
$plang['pk_help_autowarning']             = "Advierte al administrador cuando se conecta, si hay actualizaciones disponibles.";
$plang['pk_help_warningtime']             = "Con qu� frecuencia deber�a aparecer la advertencia?";
$plang['pk_help_multidkp']								= "MultiDKP permite la gesti�n y visi�n de tablas para DKPs por separado. Activa la gesti�n y visi�n de las tablas de MultiDKP. Ejem: Puedes crear dos tablas-DKP, una con los DKP de las bandas de 10 y la otra para los DKP de las bandas de 25.";
$plang['pk_help_dkptooltip']							= "Si est� activo, se mostrar� una lista con informaci�n detallada sobre el c�lculo de los puntos cuando pase el rat�n sobre los distintos puntos.";
$plang['pk_help_smarttooltip']						= "Listado abreviado de los puntos DKP. (activar si tienes m�s de tres eventos por tabla-DKP)";
$plang['pk_help_links']                   = "En este men� puede definir diferentes enlaces, que se mostrar�n en el men� principal.";
$plang['pk_help_bosscounter']             = "Si est� activo, se mostrar� una tabla debajo del men� principal con las muertes de jefes. La administraci�n est� siendo gestionada por el plugin Bossprogress";
$plang['pk_help_lm_leaderboard']					= "Si est� activo, se mostrar� una tabla de clasificaci�n por encima de la tabla de puntuaci�n. Una tabla de clasificaci�n es una tabla en la que el DKP de cada clase se muestra ordenada en orden descendente";
$plang['pk_help_lm_rank']                 = "Se mostrar� una columna suplementaria, que muestra el rango del miembro.";
$plang['pk_help_lm_rankicon']             = "En vez del nombre del rango, se mostrar� un icono. Los iconos que est�n disponibles puede verlos en la carpeta ../images/rank ";
$plang['pk_help_lm_level']								= "Se mostrar� una columna suplementaria, que muestra el nivel del miembro.";
$plang['pk_help_lm_lastloot']             = "Se mostrar� una columna suplementaria, que muestra la fecha del ultimo objeto recibido.";
$plang['pk_help_lm_lastraid']             = "Se mostrar� una columna suplementaria, mostrando la fecha de la �ltima banda en la que haya participado un miembro.";
$plang['pk_help_lm_atten30']							= "Se mostrara una columna suplementaria, mostrando la participacion en bandas durante los ultimos 30 d�as (en porcentaje).";
$plang['pk_help_lm_atten60']							= "Se mostrara una columna suplementaria, mostrando la participacion en bandas durante los ultimos 60 d�as (en porcentaje). ";
$plang['pk_help_lm_atten90']							= "Se mostrara una columna suplementaria, mostrando la participacion en bandas durante los ultimos 90 d�as (en porcentaje). ";
$plang['pk_help_lm_attenall']             = "Se mostrar� una columna suplementaria, mostrando la participacion total en bandas (en porcentaje).";
$plang['pk_help_itemstats_on']						= "Itemstats solicita la informaci�n sobre objetos en EQDKP en las bases de datos WoW (Blasc, Allahkazm, Thottbot). Estos se mostrar�n en el color de la calidad de los objetos. Cuando se activa, los elementos se muestran con una ventana al pasar el raton, similar a WOW.";
$plang['pk_help_itemstats_search']				= "Qu� base de datos deber�a Itemstats usar primero para la informaci�n de consulta, Blasc o Allakhazam?";
$plang['pk_help_itemstats_icon_ext']			= "Extensi�n de las imagenes que se muestran. Por lo general .png o.jpg.";
$plang['pk_help_itemstats_icon_url']      = "Por favor, introduzca la URL donde Itemstats encontrar� las im�genes. Alem�n: http://www.buffed.de/images/wow/32/ en 32x32 o http://www.buffed.de/images/wow/64/ en 64x64 pixeles. Ingl�s en Allakhazam: http://www.buffed.de/images/wow/32/";
$plang['pk_help_itemstats_translate_deeng']		= "Si est� activo, la informaci�n de las herramientas se solicitar�n en Alem�n, incluso cuando el tema est� siendo introducido en Ingl�s.";
$plang['pk_help_itemstats_translate_engde']		= "Si est� activo, la informaci�n de las herramientas se solicitar�n en Ingl�s, incluso cuando el tema est� siendo introducido en Alem�n.";

$plang['pk_set_leaderboard_2row']					= '2 lineas en la tabla de clasificaciones';
$plang['pk_help_leaderboard_2row']        = 'Si esta activo, la tabla de clasificaciones ser� mostrada en dos l�neas con 4 o 5 clases cada una.';

$plang['pk_set_leaderboard_limit']        = 'limitaci�n de la pantalla';
$plang['pk_help_leaderboard_limit']				= 'Si se introduce un n�mero, la tabla de clasificaciones se restringir� al n�mero de miembros indicado. El n�mero 0 no representa ninguna restricci�n.';

$plang['pk_set_leaderboard_zero']         = 'Ocultar jugadores con cero DKP';
$plang['pk_help_leaderboard_zero']        = 'Si est� activo, los jugadores sin DKP no se muestran en la tabla de clasificaciones.';


$plang['pk_set_newsloot_limit']						= 'limite de bot�n en noticias';
$plang['pk_help_newsloot_limit']          = 'Cu�ntos objetos se deben mostrar en las noticias? Esto restringe el numero de objetos que ser�n mostrados en las noticias. El n�mero 0 no representa ninguna restricci�n.';

$plang['pk_set_itemstats_debug']          = 'Modo de depuraci�n';
$plang['pk_help_itemstats_debug']					= 'Si est� activo, Itemstats registrar� todas las transacciones a /itemstats/includes_de/debug.txt. ��Este archivo tiene que ser escribible, CHMOD 777!!';

$plang['pk_set_showclasscolumn']          = 'Mostrar columna de clases';
$plang['pk_help_showclasscolumn']					= 'Si est� activo, se mostrar� una columna suplementaria mostrando la clase del jugador.' ;

$plang['pk_set_show_skill']								= 'mostrar columna de habilidad';
$plang['pk_help_show_skill']              = 'Si est� activo, se mostrar� una columna suplementaria mostrando la habilidad del jugador.';

$plang['pk_set_show_arkan_resi']          = 'mostrar columna de resistencia arcana';
$plang['pk_help_show_arkan_resi']					= 'Si est� activo, se mostrara una columna suplementaria mostrando la resistencia arcana del jugador.';

$plang['pk_set_show_fire_resi']						= 'mostrar columna de resistencia al fuego';
$plang['pk_help_show_fire_resi']          = 'Si est� activo, se mostrar� una columna suplementaria mostrando la resistencia al fuego del jugador.';

$plang['pk_set_show_nature_resi']					= 'mostrar columna de resistencia a la naturaleza';
$plang['pk_help_show_nature_resi']        = 'Si est� activo, se mostrar� una columna suplementaria mostrando la resistencia a la naturaleza del jugador.';

$plang['pk_set_show_ice_resi']            = 'mostrar columna de resistencia a la escarcha';
$plang['pk_help_show_ice_resi']						= 'Si est� activo, se mostrar� una columna suplementaria mostrando la resistencia a la escarcha del jugador.';

$plang['pk_set_show_shadow_resi']					= 'mostrar columna de resistencia a las sombras';
$plang['pk_help_show_shadow_resi']        = 'Si est� activo, se mostrar� una columna suplementaria mostrando la resistencia a las sombras del jugador.';

$plang['pk_set_show_profils']							= 'mostrar columna de enlace al perfil';
$plang['pk_help_show_profils']            = 'Si est� activo, se mostrar� una columna suplementaria mostrando los enlaces al perfil.';

$plang['pk_set_servername']               = 'Nombre de reino';
$plang['pk_help_servername']              = 'Inserte el nombre de su reino aqu�.<br> Ejem: Minahonda, Exodar, Sanguino, etc... ';

$plang['pk_set_server_region']			  = 'regi�n';
$plang['pk_help_server_region']			  = 'Servidor US o EU.';


$plang['pk_help_default_multi']           = 'Elija la Cuenta de DKP por defecto para la tabla de clasificaciones.';
$plang['pk_set_default_multi']            = 'Ajuste por defecto de la tabla de clasificaciones';

$plang['pk_set_round_activate']           = 'Redondear DKP.';
$plang['pk_help_round_activate']          = 'Si est� activo, los puntos DKP aparecen redondeados.<br> 125.00 = 125DKP.';

$plang['pk_set_round_precision']          = 'Poner decimal por ronda.';
$plang['pk_help_round_precision']         = 'Establezca el decimal DKP puesto por ronda. Defecto=0';

$plang['pk_set_dkp_info']			  = 'No mostrar Info DKP en el men� principal.';
$plang['pk_help_dkp_info']			  = 'Si est� activado "DKP Info" se oculta en el men� principal.';

$plang['pk_set_debug']			= 'activar modo depuracion EQdkp';
$plang['pk_set_debug_type']		= 'Modo';
$plang['pk_set_debug_type0']	= 'Depurar off (Debug=0)';
$plang['pk_set_debug_type1']	= 'Depurar en simple (Debug=1)';
$plang['pk_set_debug_type2']	= 'Depurar con Consultas SQL (Debug=2)';
$plang['pk_set_debug_type3']	= 'Depurar prolongada (Debug=3)';
$plang['pk_help_debug']			= 'Si esta activo, Eqdkp Plus se ejecuta en modo depuracion, mostrando informaci�n adicional y los mensajes de error. Desactivar si los plugins abortan con mensajes de error SQL! 1=Tiempo de renderizado, Cuenta de consulta, 2=SQL productos, 3=Aumento de los mensajes de error.';

#RSS News
$plang['pk_set_Show_rss']			= 'Desactivar noticias(RSS)';
$plang['pk_help_Show_rss']			= 'Si esta activo, Eqdkp Plus no muestra el juego de Noticias RSS.';

$plang['pk_set_Show_rss_style']		= 'Posicionamiento de las noticias(RSS)';
$plang['pk_help_Show_rss_style']	= 'D�nde debe colocarse las noticias? En el men� superior horizontal, vertical o ambos?';

$plang['pk_set_Show_rss_lang']		= 'Idioma por defecto de las noticias(RSS) ';
$plang['pk_help_Show_rss_lang']		= 'En qu� idioma quiere obtener las noticias(RSS)? (alem�n solo). Noticias en ingl�s disponibles en el 2009.';

$plang['pk_set_Show_rss_lang_de']	= 'Alem�n';
$plang['pk_set_Show_rss_lang_eng']	= 'Ingl�s';

$plang['pk_set_Show_rss_style_both'] = 'Ambos' ;
$plang['pk_set_Show_rss_style_v']	 = 'menu vertical' ;
$plang['pk_set_Show_rss_style_h']	 = 'superior horizontal' ;

$plang['pk_set_Show_rss_count']		= 'Enumerar noticias(RSS) (0 o "" para todas)';
$plang['pk_help_Show_rss_count']	= 'Cuantas noticias(RSS) de deben mostrar?';

$plang['pk_set_itemhistory_dia']	= 'No mostrar diagramas'; # Ja negierte Abfrage
$plang['pk_help_itemhistory_dia']	= 'Si est� activo, Eqdkp Plus no mostrara los diagramas.';

#Bridge
$plang['pk_set_bridge_help']				= 'En esta ficha se puede realizar la configuraci�n para permitir que un Sistema de Gesti�n de Contenidos (CMS) interactue con Eqdkp Plus.
											   Si elige uno de los sistemas en el campo de abajo, el registro de los miembros de su Foro / CMS podr� iniciar sesi�n en Eqdkp Plus con las mismas credenciales utilizadas en su Foro / CMS.
											   El acceso s�lo est� permitido para un grupo, lo que significa que debe crear un nuevo grupo en su Foro / CMS y que pertenezcan todos los miembros que tendran el acceso a Eqdkp.';

$plang['pk_set_bridge_activate']			= 'Activar un puente al CMS';
$plang['pk_help_bridge_activate']			= 'Cuando el puente se activa, los usuarios del Foro o de la CMS ser�n capaces de iniciar sesi�n en Eqdkp Plus con las mismas credenciales que se utilizan en el Foro / CMS';

$plang['pk_set_bridge_dectivate_eq_reg']	= 'Desactivar el registro en Eqdkp Plus';
$plang['pk_help_bridge_dectivate_eq_reg']	= 'Cuando se activa los nuevos usuarios no ser�n capaces de registrarse en Eqdkp Plus. El registro de nuevos usuarios debe hacerse en el Foro / CMS';

$plang['pk_set_bridge_cms']					= 'CMS soportado';
$plang['pk_help_bridge_cms']				= 'Qu� CMS ser� el puente?';

$plang['pk_set_bridge_acess']				= 'Est� el Foro / CMS en otra base de datos que la de Eqdkp?';
$plang['pk_help_bridge_acess']				= 'Si usa el Foro/CMS en otra Base de datos es necesario que complete los siguientes campos';

$plang['pk_set_bridge_host']				= 'Nombre del host';
$plang['pk_help_bridge_host']				= 'El nombre del host o direccion IP donde se aloja la Base de datos';

$plang['pk_set_bridge_username']			= 'Usuario de la Base de datos';
$plang['pk_help_bridge_username']			= 'Nombre de usuario para conectar a la base de datos';

$plang['pk_set_bridge_password']			= 'Contrase�a de la Base de datos';
$plang['pk_help_bridge_password']			= 'Contrase�a del usuario para conectar con la base de datos';

$plang['pk_set_bridge_database']			= 'Nombre de la Base de datos';
$plang['pk_help_bridge_database']			= 'El nombre de la Base de datos donde estan los Datos del CMS';

$plang['pk_set_bridge_prefix']				= 'Prefijo de tablas de su instalacion del CMS';
$plang['pk_help_bridge_prefix']				= 'Ponga el prefijo de su CMS. ejem: phpbb_ o wcf1_';

$plang['pk_set_bridge_group']				= 'ID de grupo del Grupo CMS';
$plang['pk_help_bridge_group']				= 'Introduzca aqu� el ID del Grupo en el CMS al que se le permite el acceso a Eqdkp.';

$plang['pk_set_bridge_inline']				= 'Integraci�n de foro en EQDKP - BETA';
$plang['pk_help_bridge_inline']				= 'Cuando se introduce una URL aqu�, se mostrar� un v�nculo en el men�. Esto se hace a trav�s de un iFrame, EQdkp Plus no se hace responsable de la apariencia y el comportamiento del sitio incluido en el iframe';

$plang['pk_set_bridge_inline_url']			= 'URL del Foro';
$plang['pk_help_bridge_inline_url']			= 'La URL del Foro';

$plang['pk_set_link_type_header']			= 'Estilo de visualizaci�n';
$plang['pk_set_link_type_help']				= '';
$plang['pk_set_link_type_iframe_help']		= 'C�mo deberia abrirse el enlace? El modo embebido, s�lo funciona con sitios instalados en el mismo servidor';
$plang['pk_set_link_type_self']				= 'Normal';
$plang['pk_set_link_type_link']				= 'Nueva Ventana';
$plang['pk_set_link_type_iframe']			= 'Embebido';

#recruitment
$plang['pk_set_recruitment_tab']			= 'Reclutamiento';
$plang['pk_set_recruitment_header']			= 'Reclutamiento - Est� buscando nuevos miembros?';
$plang['pk_set_recruitment']				= 'Activar reclutamiento';
$plang['pk_help_recruitment']				= 'Si est� activo, se mostrar� una ventana con la necesidad de clases, en la parte superior del menu.';
$plang['pk_recruitment_count']				= 'Cantidad';
$plang['pk_set_recruitment_contact_type']	= 'Enlace URL';
$plang['pk_help_recruitment_contact_type']	= 'Si no se pone URL, se enlazar� con el correo electr�nico de contacto.';
$plang['ps_recruitment_spec']				= 'Especializaci�n';

#comments
$plang['pk_set_comments_disable']			= 'desactivar comentarios';
$plang['pk_hel_pcomments_disable']			= 'Desactivar los comentarios en todas las p�ginas';

#Contact
$plang['pk_contact']						= 'Informacion de Contacto';
$plang['pk_contact_name']					= 'Nombre';
$plang['pk_contact_email']					= 'Email';
$plang['pk_contact_website']				= 'Sitio Web';
$plang['pk_contact_irc']					= 'Canal IRC';
$plang['pk_contact_admin_messenger']		= 'Cuenta Messenger (Skype, ICQ)';
$plang['pk_contact_custominfos']			= 'Informaci�n adicional';
$plang['pk_contact_owner']					= 'Informacion del Propietario:';

#Next_raids
$plang['pk_set_nextraids_deactive']			= 'No mostrar pr�ximas bandas';
$plang['pk_help_nextraids_deactive']		= 'Si est� activo, no se mostraran las pr�ximas bandas en el men�';

$plang['pk_set_nextraids_limit']			= 'L�mite las pr�ximas bandas mostradas';
$plang['pk_help_nextraids_limit']			= '';

$plang['pk_set_lastitems_deactive']			= 'No se muestran los �ltimos Objetos';
$plang['pk_help_lastitems_deactive']		= 'Si est� activo, no se mostraran los �ltimos objetos en el men�';

$plang['pk_set_lastitems_limit']			= 'L�mite los �ltimos objetos mostrados';
$plang['pk_help_lastitems_limit']			= 'Limitar los �ltimos objetos mostrados';

$plang['pk_is_help']						= ' Importante: Cambio peculiar de Itemstats con EQdkp-Plus 0.6.2.4<br>
												Si sus art�culos no se muestran correctamente despu�s de una actualizaci�n, p�ngase una nueva prioridad en la "base de datos del objeto" (recomendamos ponerlo en Armory & WoWHead)
												y actualice los objetos de nuevo.
												<br>Usar el enlace de "Actualizaci�n de Itemstat" debajo de este mensaje.<br>
												El mejor resultado se conseguir� con el ajuste "Armory & WoWHead", ya que s�lo la armer�a de Blizzard trae informaci�n adicional como la probabilidad de conseguirse,
												monstruos y mazmorra por objeto tirado.
												Con el f�n de actualizar el objeto del cach�, siga el enlace de abajo "Actualizar ItemStats", a continuacion seleccione "Limpiar cach�" y despu�s  "Actualizaci�n de Itemtable�.<br><br>
												Importante: Si ha cambiado el orden de bases de datos tendr� que vaciar la cach�. Si no, los objetos existentes no se mostrar�n correctamente.<br><br>';

$plang['pk_set_normal_leaderbaord']			= 'Mostrar la tabla de clasificaciones con Slider';
$plang['pk_help_normal_leaderbaord']		= 'Si esta activo, la tabla de clasificaciones usar� Sliders.';

$plang['pk_set_thirdColumn']				= 'No mostrar la tercera columna';
$plang['pk_help_thirdColumn']				= 'No muestra la tercera columna';

#GetDKP
$plang['pk_getdkp_th']						= 'Configuraci�n GetDKP';

$plang['pk_set_getdkp_rp']					= 'activar calendario';
$plang['pk_help_getdkp_rp']					= 'activa el calendario';

$plang['pk_set_getdkp_link']				= 'mostrar enlace getdkp en el menu principal';
$plang['pk_help_getdkp_link']				= 'muestra el enlace getdkp en el menu principal';

$plang['pk_set_getdkp_active']				= 'desactivar getdkp.php';
$plang['pk_help_getdkp_active']				= 'desactiva getdkp.php';

$plang['pk_set_getdkp_items']				= 'desactivar itemIDs';
$plang['pk_help_getdkp_items']				= 'desactiva itemIDs';

$plang['pk_set_recruit_embedded']			= 'Abrir enlace en un iframe';
$plang['pk_help_recruit_embedded']			= 'Si est� activado, el enlace se abre introducido en un iframe';


$plang['pk_set_dis_3dmember']				= 'desactivar visor de modelos 3D para los miembros';
$plang['pk_help_dis_3dmember']				= 'Desactiva el visor de modelos 3D para los miembros';

$plang['pk_set_dis_3ditem']					= 'desactivar visor de modelos 3D para los Objetos';
$plang['pk_help_dis_3item']					= 'Desactiva el visor de modelos 3D para los Objetos';

$plang['pk_set_disregister']				= 'Desactivar el registro de usuarios';
$plang['pk_help_disregister']				= 'Desactiva el registro de usuarios';

# Portal Manager
$plang['portalplugin_name']         = 'M�dulo';
$plang['portalplugin_version']      = 'Versi�n';
$plang['portalplugin_contact']      = 'Contacto';
$plang['portalplugin_order']        = 'Posici�n';
$plang['portalplugin_orientation']  = 'Orientaci�n';
$plang['portalplugin_enabled']      = 'Activo';
$plang['portalplugin_save']         = 'Guardar cambios';
$plang['portalplugin_management']   = 'Gestionar m�dulos del portal';
$plang['portalplugin_right']        = 'Derecha';
$plang['portalplugin_middle']       = 'Medio';
$plang['portalplugin_left1']        = 'Izquierda encima del men�';
$plang['portalplugin_left2']        = 'Izquierda debajo del men�';
$plang['portalplugin_settings']     = 'Configuraci�n';
$plang['portalplugin_winname']      = 'Configuraci�n de m�dulos del portal';
$plang['portalplugin_edit']         = 'Editar';
$plang['portalplugin_save']         = 'Guardar';
$plang['portalplugin_rights']       = 'Visibilidad';
$plang['portal_rights0']            = 'Todos';
$plang['portal_rights1']            = 'Invitados';
$plang['portal_rights2']            = 'Registtrados';
$plang['portal_collapsable']        = 'Colapsable';

$plang['pk_set_link_type_D_iframe']			= 'Din�mico embebido';

$plang['pk_set_modelviewer_default']	= 'Visor de modelos 3D por defecto';


 /* IMAGE RESIZE */
 // Lytebox settings
 $plang['pk_air_img_resize_options'] = 'Configuraci�n Lytebox';
 $plang['pk_air_img_resize_enable'] = 'Habilitar cambio de tama�o en las imagenes';
 $plang['pk_air_max_post_img_resize_width'] = 'Tama�o m�ximo del ancho de la imagen';
 $plang['pk_air_show_warning'] = 'Activar advertencia, si la imagen es redimensionada';
 $plang['pk_air_lytebox_theme'] = 'Tema del Lytebox';
 $plang['pk_air_lytebox_theme_explain'] = 'Temas: gris (por defecto), rojo, verde, azul, dorado';
 $plang['pk_air_lytebox_auto_resize'] = 'Activar la redimensi�n autom�tica';
 $plang['pk_air_lytebox_auto_resize_explain'] = 'Controla si las im�genes deben o no ser mayor que el tama�o de la ventana del navegador';
 $plang['pk_air_lytebox_animation'] = 'Activar animacion';
 $plang['pk_air_lytebox_animation_explain'] = 'Controla si "animar" o no Lytebox, es decir, el tama�o de transici�n entre im�genes, efectos, etc...';
 $plang['pk_air_lytebox_grey'] = 'Gris';
 $plang['pk_air_lytebox_red'] = 'Rojo';
 $plang['pk_air_lytebox_blue'] = 'Azul';
 $plang['pk_air_lytebox_green'] = 'Verde';
 $plang['pk_air_lytebox_gold'] = 'Dorado';

 $plang['pk_set_hide_shop'] = 'Ocultar enlace a tienda';
 $plang['pk_help_hide_shop'] = 'Oculta en enlace a la tienda';

$plang['pk_set_rss_chekurl'] = 'Comprobar URL-RSS antes de actualizar';
 $plang['pk_help_rss_chekurl'] = 'Controla si comprobar� o no la direcci�n URL-RSS antes de actualizar.';

$plang['pk_set_noDKP'] = 'Ocultar funci�n DKP';
$plang['pk_help_noDKP'] = 'Si est� activo, todas las dem�s funciones de DKP est�n desactivadas y no se mostrar� ning�n aviso de dkp-puntos. No se aplican a bandas y lista de eventos.';

$plang['pk_set_noRoster'] = 'Ocultar lista por clases';
$plang['pk_help_noRoster'] = 'Si est� activo, el enlace de lista por clases no se muestra en el men� y el acceso a esta p�gina se desactiva';

$plang['pk_set_noDKP'] = 'Mostrar la lista de miembros en vez de la descripci�n de puntos dkp';
$plang['pk_help_noDKP'] = 'Si esta activo, la lista de miembros ser� mostrada en vez de la descripci�n de puntos DKP';

$plang['pk_set_noRaids'] = 'Ocultar funciones de banda';
$plang['pk_help_noRaids'] = 'Si est� activo, todas las funciones de la banda son desactivadas. No se aplica a los eventos';

$plang['pk_set_noEvents'] = 'Ocultar eventos';
$plang['pk_help_noEvents'] = 'Si est� activo, todas las funciones de eventos son desactivadas. IMPORTANTE: �Los eventos son necesarios para el Calendario!';

$plang['pk_set_noItemPrices'] = 'Ocultar precios de objetos';
$plang['pk_help_noItemPrices'] = 'Si est� activo, el v�nculo con los precios de objetos de la p�gina se bloquea y desactiva.';

$plang['pk_set_noItemHistoy'] = 'Ocultar historial de Objetos';
$plang['pk_help_noItemHistoy'] = 'Si est� activo, el v�nculo con el historial de objetos de la p�gina se bloquea y desactiva.';

$plang['pk_set_noStats'] = 'Ocultar resumen y estad�sticas';
$plang['pk_help_noStats'] = 'Si est� activo, el v�nculo de resumen y estadisticas de la p�gina es desactivado y bloqueado.';

$plang['pk_set_cms_register_url'] = 'URL de registro del Foro/CMS';
$plang['pk_help_cms_register_url'] = 'Con el puente activado el enlace de registro eqDKP remitir� a esta URL con objetivos de registro.';

$plang['pk_disclaimer'] = 'Renuncia';

$plang['pk_set_link_type_menu']			= 'Men�';
$plang['pk_set_link_type_menuH']		= 'Etiqueta Men�';

//SMS ged�ns
$plang['pk_set_sms_header']			= 'Ajustes de SMS';
$plang['pk_set_sms_info']			= 'S�lo los administradores pueden enviar SMS';
$plang['pk_set_sms_info_temp']		= 'Necesitas comprar informaci�n de acceso para enviar mensajes. <br>comprar aqu�:<br>' ;
$plang['pk_set_sms_username']		= 'Usuario';
$plang['pk_set_sms_pass']			= 'contrase�a';
$plang['pk_set_sms_amount']			= 'Enviar SMS';
$plang['pk_set_sms_deactivate']		= 'Desactivar los SMS';

$plang['pk_faction']		= 'Facci�n';

// Libraries Tab
$plang['pk_set_sms_tab']	= 'SMS';
$plang['pk_set_getdkp_tab']	= 'GetDKP';
$plang['pk_set_cmsbridge_tab']	= 'Puente CMS';
$plang['pk_set_libraries_tab']	= 'Librer�as';
$plang['pk_set_news_tab']	= 'Noticias';
$plang['pk_set_rss_tab']	= 'RSS';
$plang['pk_set_rss_tab_head']	= 'Noticias RSS';
$plang['pk_set_global_tab_head']	= 'Global';
$plang['pk_set_eqdkp_tab_head']	= 'EQdkp';
$plang['pk_set_multidkp_tab_head']	= 'MultiDKP';
$plang['pk_set_links_tab_head']	= 'Enlaces';
$plang['pk_set_leaderboard_tab_head']	= 'Tabla de clasificaci�n';
$plang['pk_set_listmembers_tab_head']	= 'Lista de miembros';
$plang['pk_set_cmplugin_tab_head']	= 'Plugin de gestion de personajes';
$plang['pk_set_itemstats_tab_head']	= 'Itemstats';
$plang['pk_set_updates_tab_head']	= 'Actualizar';
$plang['pk_set_bridgeconfig_tab_head']	= 'Config de puente';
$plang['pk_set_email_header'] = "E-Mail";
$plang['pk_set_recaptcha_header'] = "ReCaptcha";

$plang['lib_email_sender_email'] = 'Enviar desde (Direcci�n)';
$plang['lib_email_sender_name'] = 'Nombre del remite';
$plang['lib_email_sendmail_path'] = 'Ruta de Sendmail';
$plang['lib_email_method'] = 'Mailer';
$plang['lib_email_mail'] = 'PHP-Mail-Funci�n';
$plang['lib_email_sendmail'] = 'Sendmail';
$plang['lib_email_smtp'] = 'Servidor SMTP';
$plang['lib_email_settings'] = 'Configuraci�n de m�todo de env�o';
$plang['lib_email_smtp_user'] = 'Usuario SMTP';
$plang['lib_email_smtp_password'] = 'Contrase�a SMTP';
$plang['lib_email_smtp_host'] = 'Servidor SMTP';
$plang['lib_email_smtp_auth'] = 'Autentificador SMTP';

$plang['lib_recaptcha_okey'] = 'Llave p�blica de reCAPTCHA';
$plang['lib_recaptcha_okey_help']	= 'Introduce la llave p�blica de tu cuenta en reCAPTCHA.net.';
$plang['lib_recaptcha_pkey'] = 'Llave privada de reCAPTCHA';
$plang['lib_recaptcha_pkey_help']	= 'Introduce la llave privada de tu cuenta en reCAPTCHA.net.';

$plang['pk_itemstats_max_execution_time'] = 'segundos m�ximos de tiempo de ejecuci�n para ItemStats';
$plang['pk_itemstats_max_execution_time_explain'] = 'Establece un tiempo m�ximo de ejecuci�n para ItemStats para prevenir errores fatales por exceso de tiempo de ejecuci�n PHP y/o para reducir los tiempos de carga. Los objetos que tengan que ser decorados despu�s de ese tiempo, se mostrar�n en texto plano. Un valor de 0 provocar� un descenso de un 80% de tu tiempo m�ximo de ejecuci�n.';

$plang['pk_externals_tab']	= 'Export';
$plang['pk_externals_th']	= 'Export Settings';
$plang['pk_externals_news']	= 'disable news export';
$plang['pk_externals_items']	= 'disable items export';
$plang['pk_externals_raids']	= 'disable raids export';
$plang['pk_externals_members']	= 'disable member export';

/*
$plang['pk_set_']	= '';
$plang['pk_help_']	= '';
*/
?>
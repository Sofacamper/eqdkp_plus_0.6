<?php
 /*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2006
 * Date:        $Date: 2009-03-17 23:56:20 +0100 (Di, 17 Mrz 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: osr-corgan $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 4277 $
 *
 * $Id: lang_main.php 4277 2009-03-17 22:56:20Z osr-corgan $
 */

// Do not remove. Security Option!
if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}

//---- Main ----
$plang['pluskernel']          			= 'PLUS Config';
$plang['pk_adminmenu']         			= 'PLUS Config';
$plang['pk_settings']					= 'Param�tres';
$plang['pk_date_settings']				= 'd.m.y';

//---- Javascript stuff ----
$plang['pk_plus_about']					= 'A Propos de EQDKP-PLUS';
$plang['updates']						= 'Mises � jour disponibles';
$plang['pk_config_header']				= 'Param�tres EQDKP-PLUS ';
$plang['loading']						= 'Chargement...';
$plang['pk_close_jswin1']      			= 'Fermez la fen�tre';
$plang['pk_close_jswin2']     			= ' avant de l\'ouvrir � nouveau!';
$plang['pk_help_header']				= 'Aide';
$plang['pk_plus_comments']  			= 'Commentaires';

//---- Updater Stuff ----
$plang['pk_alt_attention']				= 'Attention';
$plang['pk_alt_ok']						= 'Tout est OK!';
$plang['pk_updates_avail']				= 'Mises � jour disponibles';
$plang['pk_updates_navail']				= 'Mises � jour non disponibles';
$plang['pk_no_updates']					= 'Vos versions sont toutes � jour. Pas de nouvelles versions disponibles.';
$plang['pk_act_version']				= 'Nouvelle Version';
$plang['pk_inst_version']				= 'Installation';
$plang['pk_changelog']					= 'Modifications';
$plang['pk_download']					= 'T�l�chargement';
$plang['pk_upd_information']			= 'Information';
$plang['pk_enabled']					= 'Autoris�';
$plang['pk_disabled']					= 'non autoris�';
$plang['pk_auto_updates1']				= 'L\'avertissement de mise � jour est automatique';
$plang['pk_auto_updates2']				= 'Si vous d�sactivez ce param�tre, vous devrez v�rifier r�guli�rement les mises � jour pour emp�cher le piratage et rester � jour!!!';
$plang['pk_module_name']				= 'Nom de Module';
$plang['pk_plugin_level']				= 'Level';
$plang['pk_release_date']				= 'Version';
$plang['pk_alt_error']					= 'Erreur';
$plang['pk_no_conn_header']				= 'Erreur de Connexion';
$plang['pk_no_server_conn']				= 'Une erreur s\'est produite lors de la tentative de contact avec le serveur de mise � jour, soit
															votre h�te ne permet pas les connexions sortantes ou l\'erreur a �t� caus�e
															par un probl�me de r�seau. Veuillez visiter le forum Eqdkp-Plus pour v�rifier
															que vous utilisez la derni�re version.';
$plang['pk_reset_warning']				= 'Attention remise a z�ro';

//---- Update Levels ----
$plang['pk_level_other']				= 'Autre';
$updatelevel = array (
	'Bugfix'							=> 'Bugfix',
	'Feature Release'					=> 'Texte de mise a jour',
	'Security Update'					=> 'Mise � jour de s�curit�',
	'New version'						=> 'Nouvelle version',
	'Release Candidate'					=> 'Version Finale',
	'Public Beta'						=> 'Beta Publique',
	'Closed Beta'						=> 'Beta ferm�e',
	'Alpha'								=> 'Alpha',
);

//---- About ----
$plang['pk_version']					= 'Version';
$plang['pk_prodcutname']				= 'Produit';
$plang['pk_modification']				= 'Modif.';
$plang['pk_tname']						= 'Template';
$plang['pk_developer']					= 'D�veloppeurs';
$plang['pk_plugin']						= 'Plugin';
$plang['pk_weblink']					= 'Liens Internet';
$plang['pk_phpstring']					= 'PHP Raccourcis';
$plang['pk_phpvalue']					= 'Valeur';
$plang['pk_donation']					= 'Donation';
$plang['pk_job']						= 'M�tier';
$plang['pk_sitename']					= 'Site';
$plang['pk_dona_name']					= 'Nom';
$plang['pk_betateam1']					= 'Beta de test de l �quipe (Germany)';
$plang['pk_betateam2']					= 'Ordre chronologique';
$plang['pk_created by']					= 'Cr�er par';
$plang['web_url']						= 'internet';
$plang['personal_url']					= 'Priv�e';
$plang['pk_credits']					= 'Cr�dits';
$plang['pk_sponsors']					= 'Donateurs';
$plang['pk_plugins']					= 'PlugIns';
$plang['pk_modifications']				= 'Modifs.';
$plang['pk_themes']						= 'Styles';
$plang['pk_additions']					= 'Code Additions';
$plang['pk_tab_stuff']					= 'L\'�quipe EQDKP';
$plang['pk_tab_help']					= 'Aide';
$plang['pk_tab_tech']					= 'Technique';

//---- Settings ----
$plang['pk_save']						= 'Sauvegarde';
$plang['pk_save_title']					= 'Param�tres de sauvegarde';
$plang['pk_succ_saved']					= 'Les param�tres ont �t� correctement sauvegard�s';
 // Tabs
$plang['pk_tab_global']					= 'Global';
$plang['pk_tab_multidkp']				= 'MultiDKP';
$plang['pk_tab_links']					= 'Links';
$plang['pk_tab_bosscount']				= 'BossCounter';
$plang['pk_tab_listmemb']				= 'MemberList';
$plang['pk_tab_itemstats']				= 'Itemstats';
// Global
$plang['pk_set_QuickDKP']				= 'Afficher QuickDKP';
$plang['pk_set_Bossloot']				= 'Afficher bossloot ?';
$plang['pk_set_ClassColor']				= 'Nom des classes en couleur';
$plang['pk_set_Updatecheck']			= 'Activer la v�rification des mises � jour';
$plang['pk_window_time1']				= 'Afficher la fen�tre toutes les';
$plang['pk_window_time2']				= 'Minutes';
// MultiDKP
$plang['pk_set_multidkp']				= 'Activer MultiDKP';
// Listmembers
$plang['pk_set_leaderboard']			= 'Afficher le classement';
$plang['pk_set_lb_solo']				= 'Afficher le classement par compte';
$plang['pk_set_rank']					= 'Afficher les rangs';
$plang['pk_set_rank_icon']				= 'Afficher l\'ic�ne des rangs';
$plang['pk_set_level']					= 'Afficher le niveau';
$plang['pk_set_lastloot']				= 'Afficher le dernier loot';
$plang['pk_set_lastraid']				= 'Afficher le dernier raid';
$plang['pk_set_attendance30']			= 'Afficher la participation aux raids des 30 dernier jours';
$plang['pk_set_attendance60']			= 'Afficher la participation aux raids des 60 dernier jours';
$plang['pk_set_attendance90']			= 'Afficher la participation aux raids des 90 dernier jours';
$plang['pk_set_attendanceAll']			= 'Afficher la participation � tout les raids';
// Links
$plang['pk_set_links']					= 'Autoriser les liens';
$plang['pk_set_linkurl']				= 'URL du lien';
$plang['pk_set_linkname']				= 'Nom du lien';
$plang['pk_set_newwindow']				= 'Ouvrir dans une nouvelle fen�tre ?';
// BossCounter
$plang['pk_set_bosscounter']			= 'Afficher le Bosscounter';
//Itemstats
$plang['pk_set_itemstats']				= 'Afficher Itemstats';
$plang['pk_is_language']				= 'Langue Itemstats';
$plang['pk_german']						= 'Allemand';
$plang['pk_english']					= 'Anglais';
$plang['pk_french']						= 'Fran�ais';
$plang['pk_set_icon_ext']				= '';
$plang['pk_set_icon_loc']				= '';
$plang['pk_set_en_de']					= 'Traduire les objets de l\'anglais vers l\'allemand';
$plang['pk_set_de_en']					= 'Traduire les objets de l\'allemand vers l\'anglais';

################
# new sort
###############

//MultiDKP
//

$plang['pk_set_multi_Tooltip']					= 'Afficher infobulle DKP';
$plang['pk_set_multi_smartTooltip']			 	= 'Infobulle intelligent';

//Help
$plang['pk_help_colorclassnames']				= "Si activ�, les joueurs seront affich�s dans la couleurs de leur cat�gorie et leur ic�ne de classe dans WOW.";
$plang['pk_help_quickdkp']						= "Voir l'utilisateur connect� sur tous les points qui sont les membres qui lui sont assign�es dans le menu ci-dessus";
$plang['pk_help_boosloot']						= "Si activ�, vous pouvez cliquer sur les noms des boss de raid dans la note et le bosscounter de disposer d'un aper�u d�taill� des �l�ments d�pos�s. Si inactive, il sera li� � Blasc.de (activer seulement si vous entrez dans un raid pour chaque boss)";
$plang['pk_help_autowarning']             		= "Avertit l'administrateur quand il se connecte, si des mises � jour sont disponibles.";
$plang['pk_help_warningtime']             		= "Intervalle entre les avertissements";
$plang['pk_help_multidkp']						= "MultiDKP permet la gestion et la pr�sentation de comptes s�par�s. Active la gestion et l'aper�u des comptes MultiDKP.";
$plang['pk_help_dkptooltip']					= "Si activ�, une info-bulle contenant des informations d�taill�es sur le calcul des points sera montr�, lorsque la souris glisse sur les diff�rents points.";
$plang['pk_help_smarttooltip']					= "Vue raccourcie des infobulles (A activer si vous avez plus de trois �v�nements par compte)";
$plang['pk_help_links']                  		= "Dans ce menu, vous �tes en mesure de d�finir les diff�rents liens qui seront affich� dans le menu principal.";
$plang['pk_help_bosscounter']             		= "Si activ�, un tableau sera affich� sous le menu principal avec le bosskills. L'administration est g�r�e par le plugin Bossprogress";
$plang['pk_help_lm_leaderboard']				= "Si activ�, un classement sera affich� au-dessus de la table des scores. Un classement est un tableau, o� le DKP de chaque classe est affich� et tri� dans l'ordre d�croissant.";
$plang['pk_help_lm_rank']                 		= "Une colonne suppl�mentaire est affich�e, qui affiche le rang du membre.";
$plang['pk_help_lm_rankicon']             		= "Au lieu de classer les noms, une ic�ne est affich�e. Quels articles sont disponibles, vous pouvez v�rifier dans le dossier \ images \ rang";
$plang['pk_help_lm_level']						= "Une colonne suppl�mentaire est affich�e, qui affiche le niveau du membre. ";
$plang['pk_help_lm_lastloot']             		= "Un suppl�ment de colonnes est affich�e, indiquant la date � laquelle un membre a re�u son dernier point.";
$plang['pk_help_lm_lastraid']             		= "Une colonne suppl�mentaire est affich�e, indiquant la date du dernier raid auquels un membre a particip� .";
$plang['pk_help_lm_atten30']					= "Une colonne suppl�mentaire est affich�e, montrant un raid auquels les membre ont particip�s au cours des 30 derniers jours (en pourcentage).";
$plang['pk_help_lm_atten60']					= "Une colonne suppl�mentaire est affich�e, montrant un raid auquels les membre ont particip�s au cours des 30 derniers jours (en pourcentage).";
$plang['pk_help_lm_atten90']					= "Une colonne suppl�mentaire est affich�e, montrant un raid auquels les membre ont particip�s au cours des 30 derniers jours (en pourcentage). ";
$plang['pk_help_lm_attenall']             		= "Une colonne suppl�mentaire est affich�e, montrant un raid auquels toutles membre ont particip�s";
$plang['pk_help_itemstats_on']				 	= "Itemstats demande d'information sur les �l�ments inscrits dans EQDKP dans les bases de donn�es WOW (Blasc, Allahkazm, Thottbot). Ils seront affich�s dans la couleur des articles de qualit� y compris l'aide appel� WOW. Lorsque c'est activer, les �l�ments seront affich�s avec un mouseover tooltip, semblable � WOW.";
$plang['pk_help_itemstats_search']				= "Quelle base de donn�es devrait utiliser Itemstats en premier lieu pour rechercher l'information? Blasc ou Allakhazam?";
$plang['pk_help_itemstats_icon_ext']			= "Extension de fichier des images a afficher. Habituellement. Png ou. Jpg.";
$plang['pk_help_itemstats_icon_url']    		= "S'il vous pla�t entrez l'URL o� les images d'Itemstats sont situ�s. Allemand: http://www.buffed.de/images/wow/32/ en 32x32 ou 64x64 pixels.Anglais dans http://www.buffed.de/images/wow/64/ � Allakzam: http://www.buffed.de/images/wow/32 /> permuter";
$plang['pk_help_itemstats_translate_deeng']		= "Si active, l'information des bulles d'aide vous sera demand� en allemand, m�me lorsque la question est entr� en anglais.";
$plang['pk_help_itemstats_translate_engde']		= "Si active, l'information des bulles d'aide vous sera demand�, en anglais, m�me si la question est entr� en allemand.";

$plang['pk_set_leaderboard_2row']		  = 'Classement � 2 lignes';
$plang['pk_help_leaderboard_2row']        = 'Si activ�, le classement sera affich� sur deux lignes avec 4 ou 5 classes chacune.';

$plang['pk_set_leaderboard_limit']        = 'Limitation de l\'affichage';
$plang['pk_help_leaderboard_limit']		  = 'Si un chiffre est saisi, les membres affich�s dans le classement seront restreint � ce nombre. Le chiffre 0 ne repr�sente aucune restriction.';

$plang['pk_set_leaderboard_zero']         = 'Cacher les membres n\'ayant pas de DKP';
$plang['pk_help_leaderboard_zero']        = 'Si activ�, les joueurs avec n\'ayant pas de DKP ne seront pas affich�s dans le classement';


$plang['pk_set_newsloot_limit']			  = 'Limite des nouveaux loot';
$plang['pk_help_newsloot_limit']          = 'Combien d\'articles doivent �tre affich�s dans les m�dias? Cela restreint le nombre d\'articles qui sera affich� dans les m�dias. Le chiffre 0 ne repr�sente aucune restriction.';

$plang['pk_set_itemstats_debug']          = 'Mode de d�bogage';
$plang['pk_help_itemstats_debug']					= 'Si activ�, Itemstats va enregistrer toutes les transactions de / itemstats / includes_de / debug.txt. Ce fichier doit �tre en �criture, CHMOD 777 !!!';

$plang['pk_set_showclasscolumn']          = 'Afficher les colones de classe';
$plang['pk_help_showclasscolumn']		  = 'Si activ�, une colonne suppl�mentaire est affich�e indiquant la classe du joueur.' ;

$plang['pk_set_show_skill']				  = 'Afficher la colonne de comp�tences';
$plang['pk_help_show_skill']              = 'Si activ�, une colonne suppl�mentaire est affich�e montrant les comp�tences du joueur.';

$plang['pk_set_show_arkan_resi']          = 'Afficher la colone de r�sistance arcanes';
$plang['pk_help_show_arkan_resi']		  = 'Si activ�, une colonne suppl�mentaire est affich�e montrant la r�sistance arcane du joueur.';

$plang['pk_set_show_fire_resi']			  = 'Afficher la colone de r�sistance feu';
$plang['pk_help_show_fire_resi']          = 'Si activ�, une colonne suppl�mentaire est affich�e montrant la r�sistance feu du joueur.';

$plang['pk_set_show_nature_resi']		  = 'Afficher la colone de r�sistance nature';
$plang['pk_help_show_nature_resi']        = 'Si activ�, une colonne suppl�mentaire est affich�e montrant la r�sistance nature du joueur.';

$plang['pk_set_show_ice_resi']            = 'Afficher la colone de r�sistance givre';
$plang['pk_help_show_ice_resi']			  = 'Si activ�, une colonne suppl�mentaire est affich�e montrant la r�sistance Givre du joueur.';

$plang['pk_set_show_shadow_resi']		  = 'Afficher la colone de r�sistance ombre';
$plang['pk_help_show_shadow_resi']        = 'Si activ�, une colonne suppl�mentaire est affich�e montrant la r�sistance ombre du joueur.';

$plang['pk_set_show_profils']			  = 'Afficher la colonne du lien du profil.';
$plang['pk_help_show_profils']            = 'Si activ�, une colonne suppl�mentaire est affich�e montrant les liens pour le profil.';

$plang['pk_set_servername']               = 'Nom du serveur';
$plang['pk_help_servername']              = 'Mettre le nom du serveur';

$plang['pk_set_server_region']			  = 'R�gion';
$plang['pk_help_server_region']			  = 'Serveur US ou EU';


$plang['pk_help_default_multi']           = 'Choisissez la valeur par d�faut pour le classement d�croissant DKP';
$plang['pk_set_default_multi']            = 'D�finir par d�faut pour les classements';

$plang['pk_set_round_activate']           = 'Arrondir les DKP.';
$plang['pk_help_round_activate']          = 'Si activ�, les points de DKP affich�s sont arrondis. 125,00 = 125DKP';

$plang['pk_set_round_precision']          = 'R�glage de la d�cimale pour l\'arrondi.';
$plang['pk_help_round_precision']         = 'R�gler la d�cimale pour arrondir les DKP. 0 = par d�faut';

$plang['pk_is_set_prio']                  = 'Priorit� de recherche des objets dans les bases de donn�es';
$plang['pk_is_help_prio']                 = 'D�finition de l\'ordre de priorit� pour les recherches d\'objets dans les bases de donn�es.';

$plang['pk_is_set_alla_lang']	          = 'Langue utilis�e pour les items sur Allakhazam.';
$plang['pk_is_help_alla_lang']	          = 'Dans quelle langue doivent �tre affich�s les items?';

$plang['pk_is_set_lang']		          = 'Langue par d�faut des ID des objets.';
$plang['pk_is_help_lang']		          = 'Langue par d�faut des ID des objets. Example : [item]17182[/item] choisira cette langue.';

$plang['pk_is_set_autosearch']            = 'Recherche imm�diate';
$plang['pk_is_help_autosearch']           = 'Activ�: Si l\'item n est pas dans le cache, r�cup�rer automatiquement l\'information. Non activ�: R�cuperer les donn�es de l\'item quand on clique dessus.';

$plang['pk_is_set_integration_mode']      = 'Mode d\'int�gration';
$plang['pk_is_help_integration_mode']     = 'Normal: la num�risation du texte et la mise en bulle dans le code html. Texte: num�risation de texte et mettre en <script> tags.';

$plang['pk_is_set_tooltip_js']            = 'Voir le Tooltips';
$plang['pk_is_help_tooltip_js']           = 'Overlib: The normal Tooltip. Light: Light version, faster loading times.';

$plang['pk_is_set_patch_cache']           = 'Cache Path';
$plang['pk_is_help_patch_cache']          = 'Chemin d acc�s au cache item de l utilisateur , � partir de / itemstats /. Default =. / xml_cache /';

$plang['pk_is_set_patch_sockets']         = 'Chemin du repertoire des photos ';
$plang['pk_is_help_patch_sockets']        = 'Chemin vers les fichiers image des articles.';

$plang['pk_set_dkp_info']			  = 'Ne pas afficher les info DKP sur le menu principal.';
$plang['pk_help_dkp_info']			  = 'Si activer DKP infos ne seras pas afficher dans le menu principal';

$plang['pk_set_debug']			= 'Activer le mode de d�bogage (Debug)';
$plang['pk_set_debug_type']		= 'Mode';
$plang['pk_set_debug_type0']	= 'D�bogage non autoris� (Debug=0)';
$plang['pk_set_debug_type1']	= 'D�bogage simple (Debug=1)';
$plang['pk_set_debug_type2']	= 'D�bogage avec requ�tes SQL(Debug=2)';
$plang['pk_set_debug_type3']	= 'D�bogage �tendu (Debug=3)';
$plang['pk_help_debug']			= 'Si activ�, Eqdkp-Plus sera ex�cut� en mode de d�bogage, en montrant plus d\'informations et de messages d\'erreur. D�sactivez si les plugins stoppent avec des messages d\'erreurs SQL! 1 = temps de rendu, requete count, 2 = SQL sorties, 3 = Am�lioration des messages d erreur.';

#RSS News
$plang['pk_set_Show_rss']			= 'D�sactiver les nouvelles RSS';
$plang['pk_help_Show_rss']			= 'Si activ�, les nouvelles RSS Eqdkp Plus du jeu ne seront pas affich�es ';

$plang['pk_set_Show_rss_style']		= 'Position du Game-news';
$plang['pk_help_Show_rss_style']	= 'Positionnez le RSS-Game News. En Haut horizontalement, dans le menu vertical ou les deux?';

$plang['pk_set_Show_rss_lang']		= 'Langue par d�faut pour les nouvelles RSS';
$plang['pk_help_Show_rss_lang']		= 'Dans quelle langue recevoir les nouvelles RSS?';

$plang['pk_set_Show_rss_lang_de']	= 'Allemand';
$plang['pk_set_Show_rss_lang_eng']	= 'Anglais';

$plang['pk_set_Show_rss_style_both'] = 'Les deux' ;
$plang['pk_set_Show_rss_style_v']	 = 'Menu vertical' ;
$plang['pk_set_Show_rss_style_h']	 = 'Haut horizontal' ;

$plang['pk_set_Show_rss_count']		= 'Compteur de nouvelles (0 ou "" pour toutes)';
$plang['pk_help_Show_rss_count']	= 'Combien de nouvelles doivent �tre affich�es?';

$plang['pk_set_itemhistory_dia']	= 'Ne pas afficher le graphique'; # Ja negierte Abfrage
$plang['pk_help_itemhistory_dia']	= 'Si activ�, Eqdkp-Plus ne montrera pas le graphique (� c�t� des objets).';

#Bridge
$plang['pk_set_bridge_help']				= 'Sur cet onglet, vous pouvez r�gler les param�tres pour qu\'un Content Management System (CMS) ou Forum puisse interagir avec Eqdkp-Plus. <br>
												Si vous choisissez l\'un des syst�mes dans le menu d�roulant, les membres enregistr�s de votre CMS/Forum seront en mesure de se connecter � Eqdkp-Plus avec les m�mes droits que dans le CMS/Forum. <br>
												L\'acc�s n\'est autoris� que pour un seul groupe, ce qui signifie que vous devez cr�er un nouveau groupe dans votre CMS/Forum incluant tous les membres qui auront acc�s � Eqdkp-Plus.';
												
$plang['pk_set_bridge_activate']			= 'Activer le lien au CMS/Forum';
$plang['pk_help_bridge_activate']			= 'Lorsque le lien est activ�, les utilisateurs du Forum ou CMS seront en mesure de se connecter � Eqdkp-Plus avec les m�mes pouvoirs que ceux d�finis dans le  CMS/Forum';

$plang['pk_set_bridge_dectivate_eq_reg']	= 'D�sactiver l\'enregistrement � Eqdkp-Plus';
$plang['pk_help_bridge_dectivate_eq_reg']	= 'Quand activ�, les nouveaux utilisateurs ne sont pas en mesure de s\'inscrire � Eqdkp-Plus. L\'enregistrement des nouveaux utilisateurs doit se faire via le CMS/Forum.';

$plang['pk_set_bridge_cms']					= 'Choix du CMS/Forum';
$plang['pk_help_bridge_cms']				= 'Quel CMS/Forum sera li� � Eqdkp-Plus ';

$plang['pk_set_bridge_acess']				= 'Est-ce que le CMS/Forum utilise une autre base de donn�es que celle d\'Eqdkp-Plus?';
$plang['pk_help_bridge_acess']				= 'Si le CMS/Forum utilise une autre base de donn�es, activez cette base de donn�es en remplissant les champs ci-dessous';

$plang['pk_set_bridge_host']				= 'Nom de l\'h�te (ou addresse IP)';
$plang['pk_help_bridge_host']				= 'Nom de l\'h�te (ou addresse IP) sur lequel la base de donn�es est h�berg�e';

$plang['pk_set_bridge_username']			= 'Utilisateur';
$plang['pk_help_bridge_username']			= 'Nom de l\'utilisateur pour se connecter � la base de donn�es';

$plang['pk_set_bridge_password']			= 'Mot de passe';
$plang['pk_help_bridge_password']			= 'Mot de passe de l\'utilisateur pour se connecter � la base de donn�es';

$plang['pk_set_bridge_database']			= 'Nom de la base de donn�es';
$plang['pk_help_bridge_database']			= 'Nom de la base de donn�es o� se trouve le CMS/Forum';

$plang['pk_set_bridge_prefix']				= 'Pr�fixe des tables de l\'installation du CMS/Forum';
$plang['pk_help_bridge_prefix']				= 'Pr�cisez le pr�fixe utilis�. Ex : phpbb_ ou vbb_ etc...';

$plang['pk_set_bridge_group']				= 'ID du groupe du CMS autoris�';
$plang['pk_help_bridge_group']				= 'Entrez ici l\'ID du groupe, dans le CMS, qui est autoris� � acc�der � Eqdkp.';

$plang['pk_set_bridge_inline']				= 'Int�gration d\'un forum dans EQDKP';
/* ** Commented - Line removed in german settings ** $plang['pk_help_bridge_inline']				= 'Lorsque vous entrez une URL ici, un lien sera affich� dans le menu, qui montre le site � l int�rieur de la Eqdkp. Cela se fait avec une iframe dynamique . Le Eqdkp Plus n est pas responsable de l\'appereance et du comportement du site inclus dans l iframe';*/ 

$plang['pk_set_bridge_inline_url']			= 'URL du Forum';
$plang['pk_help_bridge_inline_url']			= 'URL du Forum';

$plang['pk_set_link_type_header']			= 'Style d\'affichage';
$plang['pk_set_link_type_help']				= '';
$plang['pk_set_link_type_iframe_help']		= 'Indique comment le lien doit �tre ouvert. (L\'int�gration dynamique ne fonctionne qu\'avec les sites install�s sur le m�me serveur)';
$plang['pk_set_link_type_self']				= 'Normal';
$plang['pk_set_link_type_link']				= 'Nouvelle fen�tre';
$plang['pk_set_link_type_iframe']			= 'Int�gr�';

#recruitment
$plang['pk_set_recruitment_tab']			= 'Recrutement';
$plang['pk_set_recruitment_header']			= 'Recrutement - Recherchez-vous de nouveaux membres ?';
$plang['pk_set_recruitment']				= 'Activer le recrutement';
$plang['pk_help_recruitment']				= 'Si activ�, un encadr� contenant les classes recherch�es sera affich� au dessus des nouvelles.';
$plang['pk_recruitment_count']				= 'Nombre';
$plang['pk_set_recruitment_contact_type']	= 'URL';
$plang['pk_help_recruitment_contact_type']	= 'Si aucune URL n\'est entr�e, vous serez redirig� vers le contact email';
$plang['ps_recruitment_spec']				= 'Spec';

#comments
$plang['pk_set_comments_disable']			= 'D�sactiver les commentaires';
$plang['pk_hel_pcomments_disable']			= 'D�sactiver les commentaires sur toutes les pages';

#Contact
$plang['pk_contact']						= 'Informations de contact';
$plang['pk_contact_name']					= 'Nom';
$plang['pk_contact_email']					= 'E-mail';
$plang['pk_contact_website']				= 'Site Web';
$plang['pk_contact_irc']					= 'Canal IRC';
$plang['pk_contact_admin_messenger']		= 'Nom Messenger  (Skype, ICQ)';
$plang['pk_contact_custominfos']			= 'Infos suppl�mentaires';
$plang['pk_contact_owner']					= 'Autres Infos:';

#Next_raids
$plang['pk_set_nextraids_deactive']			= 'Ne pas afficher les raids suivants';
$plang['pk_help_nextraids_deactive']		= 'Si active, les prochains raids ne seront pas dans le Menu';

$plang['pk_set_nextraids_limit']			= 'Limite d affichages des prochains raids';
$plang['pk_help_nextraids_limit']			= '';

$plang['pk_set_lastitems_deactive']			= 'Ne pas afficher les dernier items.';
$plang['pk_help_lastitems_deactive']		= 'Si activer les prochains items seront afficher dans le menu';

$plang['pk_set_lastitems_limit']			= 'Limite d affichage du dernier �l�ment';
$plang['pk_help_lastitems_limit']			= 'Limite d affichage du dernier �l�ment';

$plang['pk_is_help']						= ' <b>Important: Changements dans le comportement de Itemstats avec Eqdkp Plus 0.6.2.4!</b><br><br>
												Si, apr�s une mise � jour, vos objets ne sont plus correctement affich�s, modifiez l\'ordre de priorit� des bases de donn�es (Armory & WoWHead recommand�), <br>puis r�cup�rez de nouveau les �l�ments en utilisant le lien "Update Itemstat" ci-dessous. <br>
												Le meilleur r�sultat sera obtenu avec le param�tre "WoWHead & Armory", puisque l\'armurie de Blizzard delivre des informations suppl�mentaires comme droprate,
												Mob et donjon de fa�on diminu�e. <br><br>
												IMPORTANT: Si vous avez modifi� l\'ordre de priorit� des bases de donn�es, vous devez vider le cache, car les objets existants risquent de ne pas s\'afficher correctement!!!<br>
												Pour mettre � jour la cache, cliquez sur le lien "Update Itemstat" ci-dessous, puis le bouton "Clear cache", puis "Update Itemtable". <br>';

$plang['pk_set_normal_leaderbaord']			= 'Voir le classement avec Slider';
$plang['pk_help_normal_leaderbaord']		= 'Si activer, Voir le classement avec Slider.';

$plang['pk_set_thirdColumn']				= 'Ne pas montrer la troisi�me colonne';
$plang['pk_help_thirdColumn']				= 'Ne pas montrer la troisi�me colonne';

#GetDKP
$plang['pk_getdkp_th']						= 'GetDKP configuration';

$plang['pk_set_getdkp_rp']					= 'Activer raidplan';
$plang['pk_help_getdkp_rp']					= 'Activer raidplan';

$plang['pk_set_getdkp_link']				= 'Afficher le lien getdkp dans le menu principal';
$plang['pk_help_getdkp_link']				= 'Afficher le lien getdkp dans le menu principal';

$plang['pk_set_getdkp_active']				= 'D�sactiver getdkp.php';
$plang['pk_help_getdkp_active']				= 'D�sactiver getdkp.php';

$plang['pk_set_getdkp_items']				= 'Annuler itemIDs';
$plang['pk_help_getdkp_items']				= 'Annuler itemIDs';

$plang['pk_set_recruit_embedded']			= 'Ouvrir le lien dans la m�me fen�tre';
$plang['pk_help_recruit_embedded']			= 'Si activ�, le lien sera ouvert dans la m�me fen�tre';


$plang['pk_set_dis_3dmember']				= 'D�sactiver l\'aper�u 3D pour les Membres';
$plang['pk_help_dis_3dmember']				= 'D�sactiver l\'aper�u 3D pour les Membres';

$plang['pk_set_dis_3ditem']					= 'D�sactiver l\'aper�u 3D pour les items';
$plang['pk_help_dis_3item']					= 'D�sactiver l\'aper�u 3D pour les items';

$plang['pk_set_disregister']				= 'D�sactiver l\'enregistrement des utilisateurs ';
$plang['pk_help_disregister']				= 'D�sactiver l\'enregistrement des utilisateurs';

# Portal Manager
$plang['portalplugin_name']         = 'Module';
$plang['portalplugin_version']      = 'Version';
$plang['portalplugin_contact']      = 'Contact';
$plang['portalplugin_order']        = 'Tri';
$plang['portalplugin_orientation']  = 'Orientation';
$plang['portalplugin_enabled']      = 'Activer';
$plang['portalplugin_save']         = 'Sauver les changements';
$plang['portalplugin_management']   = 'G�rer les modules des portails';
$plang['portalplugin_right']        = 'Droite';
$plang['portalplugin_middle']       = 'Milieu';
$plang['portalplugin_left1']        = 'En haut a gauche du menu.';
$plang['portalplugin_left2']        = 'En bas a gauche du menu';
$plang['portalplugin_settings']     = 'Configuration';
$plang['portalplugin_winname']      = 'Configuration du module du portail';
$plang['portalplugin_edit']         = 'Editer';
$plang['portalplugin_save']         = 'Sauver';
$plang['portalplugin_rights']       = 'Visibilit�e';
$plang['portal_rights0']            = 'Tous';
$plang['portal_rights1']            = 'Invit�s';
$plang['portal_rights2']            = 'Enregistr�';
$plang['portal_collapsable']        = 'Collapsable';

$plang['pk_set_link_type_D_iframe']			= 'Int�gr� de fa�on dynamique';

$plang['pk_set_modelviewer_default']	= 'Aper�u 3D par d�fault';


 /* IMAGE RESIZE */
 // Lytebox settings
$plang['pk_air_img_resize_options'] 			= 'Configuration de Lytebox';
$plang['pk_air_img_resize_enable'] 				= 'Activer le redimensionnement de l\'image';
$plang['pk_air_max_post_img_resize_width'] 		= 'Largeur Maximum de l\'image';
$plang['pk_air_show_warning'] 					= 'Afficher un avertissement si l\'image a �t� redimensionn�e';
$plang['pk_air_lytebox_theme'] 					= 'Th�me de Lytebox';
$plang['pk_air_lytebox_theme_explain'] 			= 'Th�mes: gris (par d�faut), rouge, vert, bleu, or';
$plang['pk_air_lytebox_auto_resize'] 			= 'Activer le redimensionnement automatique';
$plang['pk_air_lytebox_auto_resize_explain'] 	= 'Contr�les ou non si les images doivent �tre redimensionn�es si elle sont plus grande que la dimensionsla fen�tre du navigateur ';
$plang['pk_air_lytebox_animation'] 				= 'Activer l\'animation au chargement de l\'image';
$plang['pk_air_lytebox_animation_explain'] 		= 'Contr�les ou non "animate" Lytebox, c est-�-dire la transition entre les images, de redimensionner, fondu in/out des effets, etc';
$plang['pk_air_lytebox_grey'] 					= 'Gris';
$plang['pk_air_lytebox_red'] 					= 'Rouge';
$plang['pk_air_lytebox_blue'] 					= 'Bleu';
$plang['pk_air_lytebox_green'] 					= 'Vert';
$plang['pk_air_lytebox_gold'] 					= 'Or';

$plang['pk_set_hide_shop'] = 'Cacher le lien de la boutique';
$plang['pk_help_hide_shop'] = 'Cache le lien de la boutique';

$plang['pk_set_rss_chekurl'] = 'V�rifier URL avant la mise � jour';
$plang['pk_help_rss_chekurl'] = 'V�rifie si oui ou non les RSS-Web sont contr�l�es avant mise � jour.';

$plang['pk_set_features'] = 'Fonctions DKP'; 

$plang['pk_set_noDKP'] = 'Cacher les fonctions DKP';
$plang['pk_help_noDKP'] = 'Si activ�,toutes les autres fonctions DKP sont d�sactiv�es et aucune information aux points DKP ne sera indiqu��. Ne s\'applique pas � la liste des raids et �v�nements. ';

$plang['pk_set_noRoster'] = 'Cacher le roster';
$plang['pk_help_noRoster'] = 'Si activ�, la page roster ne sera pas affich�e dans le menu principal et l\'acc�s � cette page sera bloqu�';

$plang['pk_set_noDKP'] = 'Voir le roster au lieu de l\'apercu des points DKP ';
$plang['pk_help_noDKP'] = 'Si activ�, le roster sera affich� � la place des points de DKP';

$plang['pk_set_noRaids'] = 'Cacher les fonctions du raid';
$plang['pk_help_noRaids'] = 'Si activ�, les fonctions du raids seront cach�es. Ne s\'applique pas � l\'historique des �v�nements';

$plang['pk_set_noEvents'] = 'Cacher les Ev�nements';
$plang['pk_help_noEvents'] = 'Si activ�, toute les fonctions "Ev�nements" seront d�sactiv�es. IMPORTANT: Les "Ev�nements" sont n�cessaires pour raidplaner!';

$plang['pk_set_noItemPrices'] = 'Cacher le Prix des objets';
$plang['pk_help_noItemPrices'] = 'Si activ�, le lien vers la page du prix des objets sera d�sactiv� et bloqu�.';

$plang['pk_set_noItemHistoy'] = 'Cacher l\'historique des objets';
$plang['pk_help_noItemHistoy'] = 'Si activ�, le lien vers la page d\'historique des objets sera d�sactiv� et bloqu�.';

$plang['pk_set_noStats'] = 'Masquer les r�sum�s et statistiques.';
$plang['pk_help_noStats'] = 'Si activ�, le lien vers la page de statistiques et de r�sum�s sera cach� et bloqu�.';

$plang['pk_set_cms_register_url'] = 'Lien d\'enregistrement sur le CMS/Forum';
$plang['pk_help_cms_register_url'] = 'Lien vers le CMS/Forum vous permettant de vous y enregistrer';

$plang['pk_disclaimer'] = 'D�ni/Avertissement';

$plang['pk_set_link_type_menu']			= 'Menu';
$plang['pk_set_link_type_menuH']		= 'Tabulations';

//SMS ged�ns
$plang['pk_set_sms_header']			= 'Param�ttres SMS ';
$plang['pk_set_sms_info']			= 'Seul les administrateurs peuvent envoyer des SMS';
$plang['pk_set_sms_info_temp']		= 'Vous devez �tre connect� pour envoyer des SMS. <br>Acheter ici:<br>' ;
$plang['pk_set_sms_username']		= 'Utilisateur';
$plang['pk_set_sms_pass']			= 'Mot de passe';
$plang['pk_set_sms_amount']			= 'Envoyer SMS';
$plang['pk_set_sms_deactivate']		= 'D�sactiver la fonction SMS';

$plang['pk_faction']		= 'Faction';

// Libraries Tab
$plang['pk_set_sms_tab']	= 'SMS';
$plang['pk_set_getdkp_tab']	= 'GetDKP';
$plang['pk_set_cmsbridge_tab']	= 'CMS-Bridge';
$plang['pk_set_libraries_tab']	= 'Librairies';
$plang['pk_set_news_tab']	= 'News';
$plang['pk_set_rss_tab']	= 'RSS';
$plang['pk_set_rss_tab_head']	= 'RSS News';
$plang['pk_set_global_tab_head']	= 'Global';
$plang['pk_set_eqdkp_tab_head']	= 'EQdkp';
$plang['pk_set_multidkp_tab_head']	= 'MultiDKP';
$plang['pk_set_links_tab_head']	= 'Liens';
$plang['pk_set_leaderboard_tab_head']	= 'Classement';
$plang['pk_set_listmembers_tab_head']	= 'Liste des membres';
$plang['pk_set_cmplugin_tab_head']	= 'Plugin Charmanager';
$plang['pk_set_itemstats_tab_head']	= 'Itemstats';
$plang['pk_set_updates_tab_head']	= 'Update';
$plang['pk_set_bridgeconfig_tab_head']	= 'Bridge Config';
$plang['pk_set_email_header'] = "E-Mail";
$plang['pk_set_recaptcha_header'] = "ReCaptcha";

$plang['lib_email_sender_email'] = 'Message de (Addresse)';
$plang['lib_email_sender_name'] = 'Nom';
$plang['lib_email_sendmail_path'] = 'Emplacement de Sendmail';
$plang['lib_email_method'] = 'M�thode d\'envoi';
$plang['lib_email_mail'] = 'Fonction PHP-Mail';
$plang['lib_email_sendmail'] = 'Sendmail';
$plang['lib_email_smtp'] = 'Serveur SMTP';
$plang['lib_email_settings'] = 'Param�tres de la m�thode d\'envoi';
$plang['lib_email_smtp_user'] = 'Utilisateur SMTP';
$plang['lib_email_smtp_password'] = 'Mot de passe SMTP';
$plang['lib_email_smtp_host'] = 'H�te SMTP';
$plang['lib_email_smtp_auth'] = 'Activer l\'authentification SMTP';

$plang['lib_recaptcha_okey'] = 'Cl� publique de reCATPCHA';
$plang['lib_recaptcha_okey_help']	= 'Entrez ici la cl� publique de votre compte sur reCAPTCHA.net.';
$plang['lib_recaptcha_pkey'] = 'Cl� priv�e de reCATPCHA';
$plang['lib_recaptcha_pkey_help']	= 'Entrez ici la cl� priv�e de votre compte sur reCAPTCHA.net.';

$plang['pk_itemstats_max_execution_time'] = 'seconds ItemStats max execution time';
$plang['pk_itemstats_max_execution_time_explain'] = 'Set a maximum execution time for your ItemStats to prevent fatal errors caused by exceeding the php maximum execution time and/or to bound your page loading times. Items which should have been decorated after this time will be shown as plaintext. A value of 0 will fallback to 80% of your php maximum execution time.';

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

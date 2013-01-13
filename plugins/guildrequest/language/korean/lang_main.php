<?PHP
/********************************************\
* Guildrequest Plugin for EQdkp plus         *
* ------------------------------------------ * 
* Project Start: 01/2009                     *
* Author: BadTwin                            *
* Copyright: Andreas (BadTwin) Schrottenbaum *
* Link: http://eqdkp-plus.com                *
* Version: 0.0.2                             *
\********************************************/

global $eqdkp;
$lang['guildrequest']                     = '���-��û';
$lang['request'] 							    				= '��û';
$lang['gr_short_desc']                    = '���ݴ� ���-�÷�����';
$lang['gr_long_desc']                     = '���ݴ� ����� ���� �÷����� �Դϴ�.';

// Userdaten f�r den Gastuser
$lang['gr_user_aspirant']                 = '����� ��û';
$lang['gr_user_email']                    = '���-��û �÷����� �մԿ�-��������';

//Editor
$lang['editor_language']	= 'en';

// Admin Menu
$lang['gr_manage']												= '����';
$lang['gr_view']                          = '��û ����';
$lang['gr_write']                         = '��û ����';

// Bewerbung erstellen
$lang['gr_write_headline']                = '��û ����';
$lang['gr_write_incorrect_mail']          = '�߸��� ���� �ּҸ� �Է��Ͽ����ϴ�.';
$lang['gr_write_allfields']               = '��� ������ ä������ �մϴ�!';
$lang['gr_write_sendrequest']             = '��û ������';
$lang['gr_write_reset']                   = '�ʱ�ȭ';
$lang['gr_write_error']                   = '����';
$lang['gl_write_succ']                    = '���� ���۵�';
$lang['gr_mailsent']                      = '������ ��ũ�� ���� ��û�� Ȯ���ϼ���.';
$lang['gr_mail_topic']                    = '��û�� Ȯ���ϼ���  '.preg_replace("'", "\'", $eqdkp->config['guildtag']);
$lang['gr_mail_text1']                    = '���� ��ũ�� Ŭ���ؼ� ��û�� Ȯ���ϼ��� :';
$lang['gr_mail_text2']                    = '���� �Ϸ�Ǽ���.';
$lang['gr_username_f']                    = '����� �̸�:';
$lang['gr_email_f']                       = '�̸���:';
$lang['gr_password_f']                    = '��й�ȣ:';
$lang['gr_text_f']                        = '�ؽ�Ʈ:';
$lang['gr_settings']                      = '����';
$lang['gr_user_double']                   = '���� �̸��� ����ڰ� �̹� ��û�߽��ϴ�. �ٸ� �̸��� ����ϼ���.';
$lang['gr_welcome_text']                  = ' '.preg_replace("'", "\'", $eqdkp->config['guildtag']).'. �� ������ �����ּż� �����մϴ�. �Ʒ��� ��û������ �����ֽñ� �ٶ��ϴ�:';

// Best�tigung
$lang['gr_activate_succ']                 = '��û ����!';

// Login
$lang['gr_login_headline']                = '��û - �α���';
$lang['gr_login_succ']                    = '�α��� ����';
$lang['gr_login_not_activated']           = '��� ������ Ȯ������ �����̽��ϴ�.';
$lang['gr_login_wrong']                   = '�� ����� �̸� �Ǵ� �н������Դϴ�.';
$lang['gr_login_empty']                   = '��� ��ĭ�� ä���ּ���!';
$lang['gr_login_submit']                  = '�α���';
$lang['gr_login_reset']                   = '�ʱ�ȭ';
$lang['gr_showrequest_headline']          = '��û: ';
$lang['gr_answer_f']                      = '�亯:';
$lang['gr_closed_headline']               = '��û ���� ������ �Ǿ����ϴ�.';

// Member-Ansicht
$lang['gr_vr_not_voted']                  = '���� ��ǥ���� �����̽��ϴ�!';
$lang['gr_vr_voted']                      = '��ǥ�� ���������� �̷�������ϴ�!';
$lang['gr_goback']                        = '���ư���';
$lang['gr_poll_headline']                 = '�����ڸ� ���ݴ������ �ʴ��ұ��?';
$lang['gr_poll_yes']                      = '��';
$lang['gr_poll_no']                       = '�ƴϿ�';
  // Admin-Ansicht
  $lang['gr_poll_ad_opened']              = '����';
  $lang['gr_poll_ad_closed']              = '����';
  $lang['gr_poll_ad_save']                = '�����';
  $lang['gr_ad_adminonly']                = '���ܵ� ��û - �����ڸ� �� �� �ֽ��ϴ�.:';
  $lang['gr_ad_delete']                   = '����';
  $lang['gr_ad_activate']                 = 'Ȱ��ȭ';
  $lang['gr_not_activated']               = 'Ȱ��ȭ���� ���� ��û:';
  $lang['gr_no_requests']                 = '��û�� �����ϴ�.';
  // Info-Boxen
  $lang['gr_vr_ad_opened_f']              = '����';
  $lang['gr_vr_ad_opened']                = '��û�� ���Ƚ��ϴ�.';
  $lang['gr_vr_ad_closed_f']              = '����';
  $lang['gr_vr_ad_closed']                = '��û�� ���ܵǾ����ϴ�.';
  $lang['gr_vr_ad_activated_f']           = 'Ȱ��ȭ��';
  $lang['gr_vr_ad_activated']             = '��û�� Ȱ��ȭ�Ǿ����ϴ�.';
  $lang['gr_vr_ad_deleted_f']             = '������';
  $lang['gr_vr_ad_deleted']               = '��û�� �����Ǿ����ϴ�.';

  
// Administrationsbereich
$lang['gr_ad_config_headline']            = '��û - ����';
$lang['gr_ad_poll_activated']             = '��ǥ�� Ȱ��ȭ �Ǿ����ϴ�.';
$lang['gr_ad_headline_f']                 = 'ȯ�� ����:';
$lang['gr_ad_mail1_f']                    = '��ϸ����� ù��° �κ�:';
$lang['gr_ad_mail2_f']                    = '��ϸ����� �ι�° �κ�:';
$lang['gr_ad_update_succ']                = '������ ����Ǿ����ϴ�!';
$lang['gr_ad_update_succ_hl']             = '����!';

// Portal Module
$lang['gr_pm_one_not_voted']              = '��� ��ǥ�� �ֽ��ϴ�.';
$lang['gr_pm_not_voted_1']                = '';
$lang['gr_pm_not_voted_2']                = ' ���� ��� ��ǥ�� �ֽ��ϴ�!!';

$lang['gr_pu_new_query']                  = '�� ��û: ';




$lang['gr_form_manage']                   = '�� �����ϱ�';
$lang['gr_ad_form_singletext']            = '�ܹ�';
$lang['gr_ad_form_textfield']             = '�ؽ�Ʈ�ʵ�';
$lang['gr_ad_form_dropdown']              = '����ٿ�';
$lang['gr_ad_fieldname_f']                = '�ʵ��̸�';
$lang['gr_ad_fieldtype_f']                = '�ʵ�Ÿ��';
$lang['gr_ad_requiredfield_f']            = '�䱸��';
$lang['gr_ad_editdropdown']               = '���� �ɼ�';
$lang['gr_ad_editoptions']                = '���� �ɼ�';
$lang['gr_ad_succ_head']                  = '���� �����';
$lang['gr_ad_succ_text']                  = '��������� ���������� ����Ǿ����ϴ�.';
$lang['gr_ad_err_dropdown']               = '�ʵ��̸��� �������� �����̽��ϴ�.';
$lang['gr_ad_succ_del']                   = '�ʵ尡 ���������� �����Ǿ����ϴ�.';
$lang['gr_ad_sort_f']                     = '����';
$lang['gr_ad_preview_f']                  = '�̸�����';
$lang['gr_vr_view']                       = '������ ����';
$lang['gr_comment']                       = '�ڸ�Ʈ ����';
$lang['gr_ad_closingmail']                = '�����ڿ��� �˸���';
$lang['gr_ad_closingtext']                = '���� ����� ���Խ��ϴ�. ����� ������ �����ϴ�:';
$lang['gr_ad_replyadress']                = '�� ������ �ڵ����� �����˴ϴ�. ȸ���� ���� �ּҷ� ���ּ���: ';
$lang['gr_sendermail']                    = '���� ���:';
$lang['gr_ad_popup_activated']		  			= 'Ȱ��ȭ ���� ���� �������� ������ �˾��� ���ðڽ��ϱ�? (���� �����Ⱑ ���� �������� �ʿ��մϴ�)';
$lang['gr_ad_notactivated_popup']					= '�������� Ȱ��ȭ���� �ʾҽ��ϴ�.';
$lang['gr_poll_voted_yet']                = '�߰� ���';
$lang['gr_vote']                          = '��ǥ';
$lang['gr_ad_form_headline']              = '����';
$lang['gr_ad_form_spaceline']             = '�� ����';
?>

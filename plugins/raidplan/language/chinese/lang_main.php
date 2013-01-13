<?php
 /*
 * Project:     EQdkp RaidPlanner
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2005
 * Date:        $Date: 2008-11-03 13:24:11 +0100 (Mon, 03 Nov 2008) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     raidplan
 * @version     $Rev: 2963 $
 * 
 * $Id: lang_main.php 2963 2008-11-03 12:24:11Z wallenium $
 *
 * Chinese Simplified translated by 雪夜之狼@Feathermoon（羽月）,CN3
 * Email:xionglingfeng@Gmail.com
 */
	
if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
} 
	
$lang = array(
	'raidplan' 	=> 'Raid 计划',
	'rp_raidplaner' 	=> 'Raid 计划',
	
	// User Menu
	'rp_usermenu_raidplaner'	=> 'Raid 计划',
	'rp_raid_id' 	=> 'Raid ID',
	'rp_statistic'=> '统计',
	'rp_statistic2' => 'RP: 统计',
	'rp_usersettings'	=> '设置',
	
	// Admin Menu
	'rp_manage'	=> '管理',
	'rp_addraid'	=> '新 Raid',
	'rp_addwildcard'	=> '添加 Wildcard',
	'rp_editraid'	=> '编辑 Raid',
	'rp_wildcardmanager'=> 'Wildcard 管理器',
	'rp_wildcard_perm'=> '编辑 Wildcard',
	
	// Submit Buttons
	'rp_wildcard_raid'		=> 'Wildcard Raid',
	
	// Delete Confirmation Texts
	'rp_confirm_delete_subscription' 	=> '你确认你想取消报名这次raid?',
	'rp_confirm_delete_raid' 	=> '你确认你想删除这次raid?',
	'rp_confirm_reset_conf' 	=> '你确认你想重置你的设置到默认值?',
	'rp_confirm_delete_guest' 	=> '你确认你想删除这个来宾?',
	'rp_button_delete'	=> '删除',
	'rp_button_cancel'	=> '取消',
	'rp_button_reset'	=> '重置',
	'rp_button_close' => '关闭',
	'rp_button_clean' => '清除数据库',
	'rp_button_clean2'=> '清除',
	
	// Page Foot Counts
	'rp_listraids_footcount'	=> "... 找到 %1\$d raid(s) / %2\$d 每页 / %3\$s显示全部</a>",
	'rp_listrecentraids_footcount' 	=> "... 找到 %1\$d raid(s) / 持续 %2\$d 天",
	
	// Buttons
	'rp_signup'		=> '报名',
	'rp_bunsign' 	=> '未报名',
	'rp_signoff'		=> '取消报名',
	'rp_not_sure'		=> '不确定',
	'rp_change_status'=> '改变状态',
	'rp_distribute_class_set'	=> '设置职业分配',
	'rp_confirmall' => '确认全部报名',
	'rp_save'	=> '保存',
	'rp_confirm_signup' 	=> '确认报名',
	'rp_cancel_confirmation'	=> '取消确认',
	'rp_one_down'	=> '下降一层',
	'rp_saveas_templ'	=> '保存为模板',
	'rp_del_template'	=> '删除模板',
	'rp_butn_sendmail'=> '创建 Newsletter',
	'rp_addguest' => '添加来宾',
	'rp_saveguest'=> '保存来宾',
	'rp_autodet_buttn'=> '发现时区',
	'rp_close_raid' => '关闭 Raid',
	'rp_open_raid'=> '打开 Raid',
	'rp_reset_defaults'	=> '重置到默认',
	'rp_open_usersettings'=> '设置默认成员',
	
	// Wildcard Manager
	'rp_eventname'=> '事件',
	'rp_membername' => '人物',
	'rp_selectraid' => '选择 Raid',
	'rp_set_wildcards'=> '为这次 raid 设置 Wildcard',
	'rp_no_wc_users'=> '没有找到匹配的人物。',
	'rp_added_on_date'=> 'Added on',
	'rp_changed_on_date'=> 'on',
	
	// Status
	'status0'	 	=> '已确认',
	'status1'	 	=> '已报名',
	'status2'	=> '取消报名',
	'status3'		 	=> '未确认',
	'status4'		 	=> '未报名',
	'status5'		 	=> '无可用',
	
	// Status Tooltip
	'rp_tt_status0'	 	=> '已确认成员',
	'rp_tt_status1'	 	=> '已报名成员',
	'rp_tt_status2'	=> '取消报名成员',
	'rp_tt_status3'		 	=> '未确认成员',
	'rp_tt_needed'=> '必须的成员',
	
	// Misc
	'rp_leader'	=> 'Raid Leader',
	'rp_needed'	 	=> '需要的',
	'rp_start_time'	 	 	=> '开始 - 时间',
	'rp_finish_time'	=> '完成 - 时间',
	'rp_invite_time'	 	 	=> '邀请 - 时间',
	'rp_signup_deadline'		=> '报名截止',
	'rp_signup_deadline_date'	=> '报名截止日期',
	'rp_signup_deadline_time'	=> '报名截止时间',
	'rp_current_raid'	=> '当前 Raids',
	'rp_raidaddedon'	=> 'Raid 添加在',
	'rp_recent_raid' 	=> '最近 Raids',
	'rp_admin_set' 	=> "这个出席者已被管理员报名 '%1\$s'",
	'rp_grpl_set'	=> "这个出席者已被职业领队报名 '%1\$s'",
	'rp_use_template'	=> 'Raid 模板',
	'rp_templatename'	=> '模板名称',
	'rp_templatename2'	=> '(如果没有保存模板则忽略)',
	'rp_repeat_every'	=> '重复周期',
	'rp_repeat_disabled'=> '重复已在设置中被禁用',
	'rp_delete_repeat'	=> '删除那个 raid 的全部克隆',
	'rp_edit_repeat'=> '保存改变到那个 Raid 的全部克隆',
	'rp_repeat_descr' => '选择设置并且使用下面的按钮!',
	'rp_vr_link'	=> '信息',
	'rp_link' => '链接',
	'rp_group'	=> '组',
	'rp_nogroup'=> '无组',
	'rp_no_group'	=> '没有分配到一个组',
	'rp_nogrouplua' => '未分组',
	'rp_groupnr'	=> '分配到组 No.',
	'rp_wcexpired'=> 'Wildcard 已过期',
	'rp_wclieftime' => 'Wildcard 生存期',
	'rp_guests' => '来宾',
	'rp_raidchanged'=> 'Raid 改变 by',
	'rp_editnote' => '编辑注释',
	'rp_addnote'=> '添加注释',
	'rp_none' => '无',
	'rp_ungrouped'=> '未分组',
	'rp_title_delguest' => '删除来宾',
	'rp_title_editguest'=> '编辑来宾',
	'rp_admin_gclickedit' => '点击来宾名称进行删除或编辑。',
	'uc_cm_notes' => '人物管理器注释',
	'rp_adminsignin'=> '报名 by',
	'rp_is_repeatable'=> 'Raid 是周期性的',
	'rp_not_updating' => '日期将不会被更新!',
	'rp_char_as_default'=> '默认成员',
	'rp_help_def_char'=> '设置选中的成员为默认。如果你已经选择了一个默认成员，你可以使用快速报名，并且它将自动被选择在 raid 报名。',
	'rp_no_default_char'=> '你没有选择一个默认成员或默认角色。因为这个，你也许不能使用 Raidplan 的全部特性(p.e Mass signin).',
	'rp_multi_perform'=> '表现',
	'rp_multi_notes'=> '注释',
	'rp_transform'=> '改变到 raid',
	
	'rp_help_multinote' => '点击显示注释字段。你可以添加一个注释到全部 raids 。',
	'rp_help_multisignin' => '如果你已经设置了一个默认人物,把鼠标悬停在状态旗上, 选择一个或多个 raids 来报名并使用这个按钮。',
	'rp_role_distribution'=> '角色分配',
	'rp_class_distribution' => '职业分配',
	'rp_no_distributon' => '无分配',
	'rp_addition_role'=> '角色',
	'rp_addition_class' => '职业',
	'rp_addition_no'=> '不包含',
	
	// Member Settings
	'rp_defchar_config' => '默认成员设置',
	
	// Page Titles
	'rp_title_statistic'	=> 'Raid 状态',
	'rp_title_usersettings'	=> 'Raid 计划设置',
	'rp_title_listraids'	=> 'Raid 列表',
	'rp_title_viewraid'	=> 'Raid 详细',
	
	'rp_legend' => '过去',
	'rp_signup_over'	=> '报名已关闭',
	'rp_signup_possible'	=> '报名允许',
	'rp_signup_24h'	=> '报名将在 24h 内关闭',
	
	// viewmember
	'rp_rank'	=> '头衔',
	'rp_class' 	=> '职业',
	'rp_chars_of'	=> '玩家人物:',
	'rp_char' 	=> '人物',
	'rp_vm_chars_found'	=> "有 %1\$s 人物 of 这个成员。",
	
	// Statistic Page
	'rp_firstraid'=> '第一次 Raid',
	'rp_attended' => '出席',
	'rp_total'=> '总共',
	'rp_signed_off' => '取消报名',
	'rp_signed_in'=> '报名',
	'rp_sincefirst' => '自从第一次出席 Raid',
	'rp_days' => '天',
	'rp_total_raids'=> '总共计划的 Raids 持续',
	'rp_total_run'=> '总共运行 Raids 持续',
	'rp_x_days' => "%1\$d 天",
	'rp_percent'=> "%1\$d%%",
	'rp_raidstat_tab1'=> 'Raid 出席',
	'rp_raidstat_tab2'=> 'Pool',
	
	// Calendar
	'rp_last_month'	=> '上个月',
	'rp_next_month'	=> '这个月',
	'rp_count_futureraids'=> '将来的 raids',
	'rp_notfound'	=> '未找到',
	'rp_monday'	=> '星期一',
	'rp_tuesday'	=> '星期二',
	'rp_wednesday'	=> '星期三',
	'rp_thursday'	=> '星期四',
	'rp_friday'	=> '星期五',
	'rp_saturday'	=> '星期六',
	'rp_sunday'	=> '星期日',
	'rp_january'	=> '一月',
	'rp_february'	=> '二月',
	'rp_march'	=> '三月',
	'rp_april'	=> '四月',
	'rp_may'	=> '五月',
	'rp_june'	=> '六月',
	'rp_july'	=> '七月',
	'rp_august'	=> '八月',
	'rp_september'	=> '九月',
	'rp_october'	=> '十月',
	'rp_november'	=> '十一月',
	'rp_december'	=> '十二月',
	'rp_weekly'	=> '周',
	'rp_14days'	=> '两周',
	
	//overlib windows
	'rp_status_header' 	=> 'Raid 状态',
	'rp_status_signintime' 	=> '报名时间:',
	'rp_status_closed' 	=> '报名已关闭',
	'rp_status_quit' 	=> '这个 Raid 已被 Raid leader 关闭',
	'rp_status_quit_sh' 	=> 'Raid 已关闭',
	'rp_note_header' 	=> '注释',
	'rp_noraidnote'	=> '无 Raid 注释',
	'rp_time_header' 	=> '开始报名时间',
	'rp_chtime_header'	=> '已改变',
	'rp_usrtt_header'	=> '信息',
	'rp_status' 		=> '状态',
	
	'rp_start'	=> 'Go',
	'rp_end'=> '结束',
	'rp_day'	=> '日',
	'rp_invite'	=> '邀请',
	
	// Image alternates
	'rp_rolled'		=> 'Rolled',
	'rp_wildcard'		=> 'Wildcard',
	
	// Submission Success Messages
	'rp_raid_status0'		=> "成员 %1\$s 成功确认 raid %2\$s。",
	'rp_raid_status1'		=> "成员 %1\$s 成功报名 raid %2\$s。请检查你稍候被确认",
	'rp_raid_status2'		=> "成员 %1\$s 成功取消报名 raid %2\$s。",
	'rp_raid_status3'		=> "成员 %1\$s 成功设置到 \"不确定\" 列表在 raids %2\$s。",
	'rp_raid_status10'	=> "成员报名 %1\$s 在 Raid %2\$s 成功更新",
	
	'rp_update_raid_success'=> "%1\$d raid 在 %2\$s 已更新到数据库。",
	'rp_admin_update_confstatus'=> "成员 %1\$s 成功解锁。",
	'rp_admin_unlock_member'=> "成员确认 %1\$s 成功更新。",
	'rp_raid_signup_deleted'=> "成员报名 %1\$s 已删除。",
	'rp_class_distribution_set'	=> '职业分配集成功设置',
	'rp_view_raid'	=> '查看 Raid',
	
	// Submission Error Messages
	'rp_member_allready_subscribed'	=> '成员已经订阅。更新终止。',
	'rp_class_distribution_notset' 	=> '请选择一个新 Raid 创建一个职业列表, 不是一个旧的。',
	'rp_err_invalid_action_prov'=> '错误! 提供了无效操作!.',
	
	// AutoInvite
	'rp_Macro_output_Listing' => '宏输出列表...',
	'rp_nonqued_user' => '不在队列用户',
	'rp_queued_users' => '队列中用户',
	'rp_MacroListingComplete' => '宏输出列表完成。',
	'rp_copypaste_ig' => '复制和粘贴下面的宏并在游戏中运行。',
	'rp_lua_created'=> 'LUA 文件已创建',
	'rp_lua_notreadable'=> 'LUA 文件不可写, 更改目录属性 \'lua_dl\' 和目录中的内容使用 \'chmod 777\'!',
	'rp_download' => '下载',
	'rp_dl_autoinv_add' => '(右击, 选择另存为, 命名为 AutoInvite.lua)',
	'rp_lua_output' => '开始 LUA 输出',
	'rp_cvs_output' => '开始 CSV 输出',
	'rp_no_raidid'=> '错误: 无 RaidID',
	'rp_csv_random' => 'Random',
	
	// Export Thing
	'rp_export_header'=> '导出 Raid 数据',
	'rp_export' => '导出',
	'rp_luaexport'=> 'LUA 导出',
	'rp_cvsexport'=> 'CVS 导出',
	'rp_macroexport'=> '宏 导出',
	'rp_export_text'=> '请为当前 raid 选择一种导出数据的方法。',
	
	// Dropdown Options Menu
	'rp_jsdm_options' => '选项',
	
	// Error Messages
	'rp_error_invalid_mode_provided'	=> '一个有效的模式没有被提供。',
	'rp_not_logged_in'	=> '你必须登录才能加入一个 raid!',
	'rp_no_user_assigned'	=> '管理员没有设置你的人物!',
	'rp_no_user_assigned_cm1'	=> '请点击 ',
	'rp_no_user_assigned_cm2'	=> '这里',
	'rp_no_user_assigned_cm3'	=> '分配一个新人物或改变你的成员职业。',
	'rp_class_distribution_not_set'	=> '职业/ 角色 分配没有正确设置!',
	'rp_deadline_reached'	 	=> '这是一个 旧 Raid 或已到报名终止期',
	'rp_no_class_setup'	=> '这个 raid 没有任何职业限制设置。',
	'error_no_users_to_confirm'	=> '在这个 raid 中没有需要确认的报名成员。.',
	'rp_role_distri_error'=> '角色分配不正确。角色字段也许是空的。请报告给系统管理员',
	
	// user settings
	'rp_header_vrserttings'	=> '用户 Raid 查看设置',
	'rp_char_saved' => '人物设置已成功保存',
	'rp_usettings_off'	=> '用户设置已被管理员覆盖。改变将不会被保存',
	'rp_user_receivemail' => '在新 raid 建立时发送Email 通知?',
	
	// config things
	'config' 	=> '设置',
	'rp_config_saved' => '设置已成功保存',
	'rp_expand_all'	=> '全部展开',
	'rp_contract_all' => '全部收缩',
	'rp_header_global' 	=> '一般 Raidplan 配置',
	'rp_header_expert'=> '专家设置',
	'rp_header_layout'=> '外观/样式',
	'rp_header_wcroll'=> '角色/Wildcard',
	'rp_header_groups'=> '组 & 来宾',
	'rp_header_time'	=> '时间 & 日期',
	'rp_header_mail'=> 'Email 通知',
	'rp_header_roles' => '角色分配',
	'rp_header_automatics'=> '自动化功能',
	'rp_header_usettings' => '用户- / 成员 设置',
	'rp_header_notes' => '注释',
	'rp_header_colors'=> '颜色设置',
  'rp_help'                         => 'Help',
  'rp_help_desc'                    => 'To get help, we included a manual into raidplan. The manual is in pdf format. The <a href="http://adobe.de" target="blank">Adobe Reader</a> is required. <br/>The help file contains help for upgraders of earlier raidplan versions. Please use the help file before asking in the forums.<br/><br/><a href="../doc/manual.pdf"><img src="../images/global/help.png"/> Read manual</a>',
	'rp_prefix_addraid' => '添加 raid',
	'rp_show_ranks'	=> '在 raid planner 中显示头衔',
	'rp_colored_members'	=> '彩色成员名称',
	'rp_send_email'	=> '发送新 raids 的 Email 到全部用户',
	'rp_roll_system' 	=> '使用角色分配系统?',
	'rp_wildcard_sys'	=> '使用 wildcard 系统?',
	'rp_last_x_days' 	=> '显示最近 raids: 持续 x 天',
	'rp_mode_caption'	=> 'Raid 模式列表',
	'rp_mode_calendar'	=> '日历',
	'rp_mode_classic'	=> 'Raid 列表',
	'rp_mode_mixed'	=> '两者',
	'rp_updatecheck'	=> '启用检测新版本插件',
	'rp_enableteam'	=> '自动确认头衔为 \'固定组\'的成员',
	'rp_hours_offset'	=> '时间段 (in h) 在 raid 开始和邀请之间 (Standard: 0h:15m)',
	'rp_hours_offset2'	=> '时间段 (in h) 在 raid 开始和报名时间之间 (Standard: 0h:30m)',
	'rp_cal_ab'	=> 'raid 列表位置',
	'rp_ab_above'	=> '日历上方',
	'rp_ab_beyond'	=> '日历侧方',
	'rp_cal_fweekday'	=> '第一工作日',
	'rp_cal_hide_ico'	=> '在日历模式中隐藏区域/事件图标',
	'rp_sort_txt'	=> '排序 Raid 日期:',
	'rp_sort_desc'	=> '递减',
	'rp_sort_asc'	=> '递增',
	'rp_repeat_value_p1'	=> '添加 raids',
	'rp_repeat_value_p2'	=> '在未来 x 日',
	'rp_repeat_enable'	=> '自动在可重复 raids 上克隆 raid',
	'rp_daysoffset'	=> '预选日在未来 x 天',
	'rp_short_classnames'	=> '在 raid 查看隐藏职业名: 仅图标和信息条',
	'rp_enable_levelcap'=> '查看 raid 中成员列表的最低等级',
	'rp_hide_hiddenranks' => '隐藏属于隐藏头衔的成员',
	'rp_enable_classbrk'=> '启用分隔线在 x 职业',
	'rp_enable_officers'=> '为职业领队的头衔/组的特殊权限',
	'rp_disbale_officer_ac'	=> '<b>不要</b> 自动确认职业领队',
	'rp_enable_groups'=> '为一个 raid 使用超过一个组',
	'rp_hide_raidname'	=> '在日历模式隐藏 Raid 名称',
	'rp_timezone_offset'=> 'Timezone of the server',
	'rp_timezone_offset'=> '服务器时区',
	'rp_timezone_check' => '检查 - 今天的日期:',
	'rp_resetday' => 'Weekday to reset the Groups saved to Members',
	'rp_enableresetday' => '在一个特殊的工作日刷新已保存的成员的分组',
	'rp_savegroups' => '保存组分配到当前成员',
	'rp_hidenorsigned'=> '隐藏 \'未报名成员\' 行',
	'rp_hiderows' => '在查看 raid 中隐藏下面的状态行',
	'rp_wcexpire' => 'Wildcard 将在 x 小时后过期',
	'rp_rolltooltip'=> '随机值应当被显示在一个信息条上吗?',
	'rp_useguests'=> '在 Raid 查看启用手动来宾邀请',
	'rp_adminnotes'	=> '在管理员报名成员上禁用 管理-/组 队长注释',
	'rp_saveperevent' => '保存每事件',
	'rp_dbversion'=> '数据库结构版本',
	'rp_force_update' => '强制数据库更新',
	'rp_updwarntxt' => '数据库版本应当被重置吗?在那之后你将能更新表!',
	'rp_resetctext' => '颜色值应当被重置到默认吗?',
	'rp_overwriteusett'	=> '覆盖用户设置',
	'rp_disable_cmnotes'=> '在成员信息条禁用人物管理器注释',
	'rp_disable_memnote'	=> '禁用成员附加 raids 注释',
	'rp_help_12hourformat'	=> '近用于支持 12h 制的语言。在德国 p.e. 只有 24h 制, 这个选项将没有功能。',
	'rp_send_activesonly' => '只对活跃用户发送 emails',
	'rp_flush_usersett' => '重置用户设置',
	'rp_truncate_warn'=> '全部用户设置将被移除并且标准设置将被使用。',
	'rp_collatecheck' => '数据库校验',
	'rp_check_collation'=> '检查数据库',
	'rp_hide_memnotes_guest'=> '为来宾隐藏报名成员注释',
	'rp_autoadd_byrank' => '在 raid 创建时自动添加那个头衔的成员',
	'rp_autoadd_confirm'=> ' 在入口设置状态以确认',
	'rp_change_signedstatus'=> '在注册时间结束后更改状态',
	'rp_change_possible_time' => '允许更改状态直到 raid 开始前 xx 分钟',
	'rp_hide_rpversion' => '为安全原因在页脚隐藏 Raidplan 版本',
	'rp_remove_lock'=> '禁止管理员取消用户报名',
	'rp_conf_active'=> '活跃?',
	'rp_conf_rank'=> '头衔',
	'rp_use_comments' => '使用评论系统让用户添加评论到 raids',
	'rp_status_colors'=> 'Raid 状态颜色',
	'rp_class_colors' => '职业颜色',
	'rp_reset_colors' => '重置职业颜色值到默认值。',
	'rp_default_raid_sort'=> 'raid 查看的默认排序',
	'rp_raid_duration'=> '默认 raid 的持续时间(小时)',
	
	// Cleanup old Raids @config
	'rp_cleantxt1'=> '移除全部旧 raids 早于',
	'rp_cleantxt2'=> '天',
	'rp_cleanwarn'=> 'Raid 统计清除后将不会工作,因为你已经删除了计算需要的数据!',
	'rp_cleanraids' => '清除数据库',
	'rp_confirmclean' => '你确实想移除选中的 raids?统计将变得不准确。',
	
	// Help
	'rp_help_header'	=> '信息',
	'rp_help_hraidname'	=> '仅当事件图标显示时。如果你启用\'隐藏事件图标\'，这个选项将无效。',
	'rp_help_moregroups'	=> 'If you\'ve got 2 or more groups for the same raid event, you can assign groups to members',
	'rp_help_linebreak'	=> '如果你正在使用一个有很多职业的游戏，如 Everquest 2， raid 查看将不会变得太长',
	'rp_help_cloning'	=> '为周期性raid 。如果你启用这个选项，每个站点访问者将插入 <b>一个</b> raid。这有可能引起性能问题。',
	'rp_help_permteam'	=> '组成员自动确认 raids。',
	'rp_help_timezone'=> '时间 GMT (+-0)。如果你的服务器在本地，如德国 ，你必须选择 GMT +1 并且启用夏令时。',
	'rp_help_resetday'=> 'Usefull if your game uses instance reset days, and the users should get another group after that. On the reset day, the member-group table will be flushed.',
	'rp_help_savedgrp'=> 'If enabled and a group is set in the Frontend, it will be saved and displayed in the next raid automatically. Good for static raid groups.',
	'rp_help_hideunsigned'=> 'This Option will hide the unsigned members row in the raid view',
	'rp_help_hiderows'=> 'Hide the selected rows in the raid view, you must click on the [+] to show them again. This will save space.',
	'rp_help_wcexpire'=> 'Until now, this must be done via a cronjob',
	'rp_help_rolltooltip' => 'If activated, the Random Value will be shown in a Tooltip instead of next to the dice-image.',
	'rp_help_guestadd'=> 'If enabled, admins have the possibility to add guests to raids. This should avoid \'Ghost-Members\'.',
	'rp_help_admnote' 	=> 'If a member is signed in by an admin/Groupm leader, a special Adminnote is set. If you enable this option, this Note will not be set.',
	'rp_help_vreset'=> 'If you\'re upgrading from a previos Alpha/Beta Version, you need to force an update by hand. Click on the button behind the Version to force a n update of the Database tables.',
	'rp_help_overwrusett'	=> 'The users will not longer be able to change the layout of the Raidlist. The Default settings will be used.',
	'rp_help_officers'	=> 'Class leader are allowed to confirm members of their class, gets automatically confirmed and much more.',
	'rp_help_collatecheck'=> 'Check the collation of your SQL Database. This tool can help if the confirmed members are not shown in the raidview.',
	'rp_help_hidegnotes'=> 'If enabled, only registered users are able to see the members notes. Guests are not able to read them',
	'rp_help_ranktoadd' => 'This option is for raid groups with fixed groups. The Members with the selected Rank are added to a raid on creation. If they\'ve got no time, they have to unsign.',
	'rp_help_signedstatus'=> 'Member can sign out and change their status when the signin time is over! Notes can be edited either.',
	'rp_help_removelock'=> 'If you want to prevent random-cheating by Admins',
	'rp_distri_select'=> 'Select standard distribution on raid add',
	
	// Warning
	'rp_warn_header'	=> '警告',
	'rp_warn_noadmapprov' => '管理员/职业领队将不能够批准/添加成员到 Raids.',
	'rp_warn_dbchanges' => '如果你更新一个alpha/beta版本，一些更新步骤也许会失，那是因为数据库中已经存在的改变。',
	'rp_warn_disablnotes'	=> '仅禁用，如果你不想让你的出席者为他们的报名添加注释。',
	'rp_warn_ranktoadd' => '如果角色分配已被选择，仅仅选择了默认人物或角色的成员被插入 raids。',
	'rp_warn_removelock'=> '你将不能改变成员 (sign off a member and sign on a twink)!',
	'rp_warn_hiderows'=> 'raid 查看的设置保存在 Cookies 中。 这个设置仅为第一次查看 raidplan 页面。',
	
	// Information
	'rp_sett_altereduser' => '这个设置能被每个用户在用户设置中改变!',
	
	// Log things
	'action_rpraid_added' 	=> 'Raidplaner: Raid 已添加',
	'action_rpraid_del' 	=> 'Raidplaner: Raid 已删除',
	'action_rpraid_changed'	=> 'Raidplaner: Raid 已改变',
	'action_rptempl_add'	=> 'Raidplaner: 已保存为模板',
	'action_rpstat_cha'	=> 'Raidplaner: 成员状态已改变',
	'action_rptempl_del'	=> 'Raidplaner: 已移除模板',
	
	'rp_raid_act_change'	=> 'Raid 在 Raidplan 已改变',
	'rp_raid_act_add'	=> '新 raid 在 Raidplan 已添加',
	'rp_raid_act_del'	=> 'Raid 已在 Raidplan 中删除',
	'rp_raid_templ_change'	=> 'Raid 模板已改变',
	'rp_raid_templ_del'	=> 'Raid 模板已删除',
	'rp_raid_templ_del2'	=> 'Raid 模板已删除',
	'rp_raid_status_changed1'	=> '成员状态已改变',
	'rp_raid_status_changed2'	=> ': 状态已改变',
	
	// Time Zone Settings
	'rp_calendar_lang'=> 'cn',
	'rp_status_day'	=> '日',
	'rp_status_hours'	=> '时',
	'rp_status_minutes'	=> '分',
	'rp_time_format'=> '日期格式',
	'rp_format_ddmmyyy' => 'DD.MM.YYYY',
	'rp_format_mmddyyy' => 'MM-DD-YYYY',
	'rp_12hourformat'	=> '使用 12h 格式?',
	'rp_dstcheck'	=> '自动更正 DST?',
	'rp_dsthelp' 	=> '如果选择, 在下一次加载 Raidplan 首页时你的 DST 将被自动调整',
	'time_-12' 	=> '(GMT - 12:00 hours) Enewetak, Kwajalein',
	'time_-11' 	=> '(GMT - 11:00 hours) Midway Island, Samoa',
	'time_-10' 	=> '(GMT - 10:00 hours) Hawaii',
	'time_-9.5'	=> '(GMT - 9:30 hours) French Polynesia',
	'time_-9'	=> '(GMT - 9:00 hours) Alaska',
	'time_-8'	=> '(GMT - 8:00 hours) Pacific Time (US &amp; Canada)',
	'time_-7'	=> '(GMT - 7:00 hours) Mountain Time (US &amp; Canada)',
	'time_-6'	=> '(GMT - 6:00 hours) Central Time (US &amp; Canada), Mexico City',
	'time_-5'	=> '(GMT - 5:00 hours) Eastern Time (US &amp; Canada), Bogota, Lima',
	'time_-4'	=> '(GMT - 4:00 hours) Atlantic Time (Canada), Caracas, La Paz',
	'time_-3.5' 	=> '(GMT - 3:30 hours) Newfoundland',
	'time_-3' 	=> '(GMT - 3:00 hours) Brazil, Buenos Aires, Falkland Is.',
	'time_-2' 	=> '(GMT - 2:00 hours) Mid-Atlantic, Ascention Is., St Helena',
	'time_-1' 	=> '(GMT - 1:00 hours) Azores, Cape Verde Islands',
	'time_0'	=> '(GMT) Casablanca, Dublin, London, Lisbon, Monrovia',
	'time_1'	=> '(GMT + 1:00 hours) Brussels, Copenhagen, Madrid, Paris',
	'time_2'	=> '(GMT + 2:00 hours) Kaliningrad, South Africa',
	'time_3'	=> '(GMT + 3:00 hours) Baghdad, Riyadh, Moscow, Nairobi',
	'time_3.5'	=> '(GMT + 3:30 hours) Tehran',
	'time_4'	=> '(GMT + 4:00 hours) Abu Dhabi, Baku, Muscat, Tbilisi',
	'time_4.5'	=> '(GMT + 4:30 hours) Kabul',
	'time_5'	=> '(GMT + 5:00 hours) Ekaterinburg, Karachi, Tashkent',
	'time_5.5'	=> '(GMT + 5:30 hours) Bombay, Calcutta, Madras, New Delhi',
	'time_5.75'	=> '(GMT + 5:45 hours) Kathmandu',
	'time_6'	=> '(GMT + 6:00 hours) Almaty, Colombo, Dhaka',
	'time_6.5'	=> '(GMT + 6:30 hours) Yangon, Naypyidaw, Bantam',
	'time_7'	=> '(GMT + 7:00 hours) Bangkok, Hanoi, Jakarta',
	'time_8'	=> '(GMT + 8:00 hours) Hong Kong, Perth, Singapore, Taipei',
	'time_8.75'	=> '(GMT + 8:45 hours) Caiguna, Eucla',
	'time_9'	=> '(GMT + 9:00 hours) Osaka, Sapporo, Seoul, Tokyo, Yakutsk',
	'time_9.5'	=> '(GMT + 9:30 hours) Adelaide, Darwin',
	'time_10' 	=> '(GMT + 10:00 hours) Melbourne, Papua New Guinea, Sydney',
	'time_10.5'	=> '(GMT + 10:30 hours) Lord Howe Island',
	'time_11' 	=> '(GMT + 11:00 hours) Magadan, New Caledonia, Solomon Is.',
	'time_11.5'	=> '(GMT + 11:30 hours) Burnt Pine, Kingston',
	'time_12' 	=> '(GMT + 12:00 hours) Auckland, Fiji, Marshall Island',
	'time_12.75' 	=> '(GMT + 12:45 hours) Chatham Islands',
	'time_13' 	=> '(GMT + 13:00 hours) Kamchatka, Anadyr',
	'time_14' 	=> '(GMT + 14:00 hours) Kiritimati',
	
	// Comment System
	'rp_no_comments'=> '无条目',
	'rp_comments_raid'=> '提交这个 raid',
	'rp_write_comment'=> '写一个评论',
	'rp_send_comment' => '保存评论',
	'rp_save_wait'=> '请稍候,评论正在保存...',
	
	//About/credits
	'rp_credits'=> 'Credits',
	'rp_about_header'	=> 'Raidplaner Credits',
	'rp_created by' 	=> 'written by',
	'rp_contact_info'	=> 'Contact Information',
	'rp_url_web' 	=> 'Web',
	'rp_additions'	=> 'Additions to Raidplaner',
	'rp_copyright'	=> 'Copyright',
	'rp_created_devteam'	=> 'by WalleniuM',
	
	// E-Mail System
	'rp_sendheader' => '一个新的 Raid 已添加',
	'rp_nohtml_msg' => '查看这个消息，请使用一个兼容 HTML 的 email 查看器!',
	'rp_mail_method'=> '邮件方法',
	'rp_mail_mail'=> 'Mail()',
	'rp_mail_sendmail'=> 'Sendmail',
	'rp_mail_smtp'=> 'SMTP',
	'rp_mail_settings'=> '发送方法设置',
	'rp_nomailsettings' => '无发送方法设置',
	'rp_smtp_user'=> 'SMTP 用户名',
	'rp_smtp_password'=> 'SMTP 密码',
	'rp_smtp_host'=> 'SMTP 主机',
	'rp_smtp_auth'=> '需要 SMTP 认证',
	'rp_sendmail_path'=> 'Sendmail 路径',
	'rp_sender_address' => '发送者 email 地址',
);
?>

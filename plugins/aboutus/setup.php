<?php
$i = 0;
//「管理員」名稱
$plugin_setup[$i]['name']    = "teacher_title";
$plugin_setup[$i]['text']    = _MD_TCW_ABOUTUS_S1_TEXT;
$plugin_setup[$i]['desc']    = _MD_TCW_ABOUTUS_S1_DESC;
$plugin_setup[$i]['type']    = "text";
$plugin_setup[$i]['default'] = _MD_TCW_ABOUTUS_S1_DEFAULT;

//「班級」名稱
$i++;
$plugin_setup[$i]['name']    = "class_title";
$plugin_setup[$i]['text']    = _MD_TCW_ABOUTUS_S2_TEXT;
$plugin_setup[$i]['desc']    = _MD_TCW_ABOUTUS_S2_DESC;
$plugin_setup[$i]['type']    = "text";
$plugin_setup[$i]['default'] = _MD_TCW_ABOUTUS_S2_DEFAULT;

//「學生」名稱
$i++;
$plugin_setup[$i]['name']    = "student_title";
$plugin_setup[$i]['text']    = _MD_TCW_ABOUTUS_S3_TEXT;
$plugin_setup[$i]['desc']    = _MD_TCW_ABOUTUS_S3_DESC;
$plugin_setup[$i]['type']    = "text";
$plugin_setup[$i]['default'] = _MD_TCW_ABOUTUS_S3_DEFAULT;

//設定成員顯示模式
$i++;
$plugin_setup[$i]['name']    = "mem_list_mode";
$plugin_setup[$i]['text']    = _MD_TCW_ABOUTUS_S4_TEXT;
$plugin_setup[$i]['desc']    = _MD_TCW_ABOUTUS_S4_DESC;
$plugin_setup[$i]['type']    = "radio";
$plugin_setup[$i]['default'] = 'classroom';
$plugin_setup[$i]['options'] = array(_MD_TCW_ABOUTUS_S4_OPT1 => 'classroom', _MD_TCW_ABOUTUS_S4_OPT2 => 'table', _MD_TCW_ABOUTUS_S4_OPT3 => 'mem_detail');

//欲使用欄位設定
$i++;
$plugin_setup[$i]['name']    = "mem_column";
$plugin_setup[$i]['text']    = _MD_TCW_ABOUTUS_S5_TEXT;
$plugin_setup[$i]['desc']    = _MD_TCW_ABOUTUS_S5_DESC;
$plugin_setup[$i]['type']    = "checkbox";
$plugin_setup[$i]['default'] = array('MemNickName', 'MemUnicode', 'MemBirthday', 'MemNum');
$plugin_setup[$i]['options'] = array(_MD_TCW_ABOUTUS_S5_OPT1 => 'MemNickName', _MD_TCW_ABOUTUS_S5_OPT2 => 'MemUnicode', _MD_TCW_ABOUTUS_S5_OPT3 => 'MemBirthday', _MD_TCW_ABOUTUS_S5_OPT4 => 'MemExpertises', _MD_TCW_ABOUTUS_S5_OPT5 => 'MemNum', _MD_TCW_ABOUTUS_S5_OPT6 => 'MemClassOrgan', _MD_TCW_ABOUTUS_S5_OPT7 => 'AboutMem');

//是否開放家長功能
$i++;
$plugin_setup[$i]['name']    = "mem_parents";
$plugin_setup[$i]['text']    = _MD_TCW_ABOUTUS_S6_TEXT;
$plugin_setup[$i]['desc']    = _MD_TCW_ABOUTUS_S6_DESC;
$plugin_setup[$i]['type']    = "radio";
$plugin_setup[$i]['default'] = '0';
$plugin_setup[$i]['options'] = array(_YES => '1', _NO => '0');

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

<?php
$i = 0;
//是否顯示全域事件
$plugin_setup[$i]['name'] = 'show_global_event';
$plugin_setup[$i]['text'] = _MD_TCW_CALENDAR_S1_TEXT;
$plugin_setup[$i]['desc'] = _MD_TCW_CALENDAR_S1_DESC;
$plugin_setup[$i]['type'] = 'yesno';
$plugin_setup[$i]['default'] = 1;
$i++;

//第一天為星期日或星期一
$plugin_setup[$i]['name'] = 'week_first_day';
$plugin_setup[$i]['text'] = _MD_TCW_CALENDAR_S2_TEXT;
$plugin_setup[$i]['desc'] = _MD_TCW_CALENDAR_S2_DESC;
$plugin_setup[$i]['type'] = 'yesno';
$plugin_setup[$i]['default'] = 1;
$i++;

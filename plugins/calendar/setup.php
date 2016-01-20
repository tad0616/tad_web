<?php
$i = 0;
//是否顯示全域事件
$plugin_setup[$i]['name']    = "show_global_event";
$plugin_setup[$i]['text']    = _MD_TCW_CALENDAR_S1_TEXT;
$plugin_setup[$i]['desc']    = _MD_TCW_CALENDAR_S1_DESC;
$plugin_setup[$i]['type']    = "yesno";
$plugin_setup[$i]['default'] = 1;
$i++;

//是否使用FB留言框
$plugin_setup[$i]['name']    = "use_fb_comments";
$plugin_setup[$i]['text']    = _MD_TCW_USE_FB_COMMENT_TEXT;
$plugin_setup[$i]['desc']    = _MD_TCW_USE_FB_COMMENT_DESC;
$plugin_setup[$i]['type']    = "yesno";
$plugin_setup[$i]['default'] = 1;
$i++;

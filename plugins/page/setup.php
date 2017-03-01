<?php
$i = 0;
//是否使用FB留言框
$plugin_setup[$i]['name']    = "use_fb_comments";
$plugin_setup[$i]['text']    = _MD_TCW_USE_FB_COMMENT_TEXT;
$plugin_setup[$i]['desc']    = _MD_TCW_USE_FB_COMMENT_DESC;
$plugin_setup[$i]['type']    = "yesno";
$plugin_setup[$i]['default'] = 1;

$i++;
//列表是否顯示內文文章
$plugin_setup[$i]['name']    = "list_pages_title";
$plugin_setup[$i]['text']    = _MD_TCW_PAGE_LIST_PAGES_TITLE;
$plugin_setup[$i]['desc']    = _MD_TCW_PAGE_LIST_PAGES_TITLE_DESC;
$plugin_setup[$i]['type']    = "yesno";
$plugin_setup[$i]['default'] = 1;

<?php
$i = 0;
//是否使用FB留言框
$plugin_setup[$i]['name']    = "use_fb_comments";
$plugin_setup[$i]['text']    = _MD_TCW_USE_FB_COMMENT_TEXT;
$plugin_setup[$i]['desc']    = _MD_TCW_USE_FB_COMMENT_DESC;
$plugin_setup[$i]['type']    = "yesno";
$plugin_setup[$i]['default'] = 0;

$i++;
//是否自動播放影片
$plugin_setup[$i]['name']    = "auto_play_images";
$plugin_setup[$i]['text']    = _MD_TCW_AUTO_PLAY_IMAGES;
$plugin_setup[$i]['desc']    = _MD_TCW_AUTO_PLAY_IMAGES_DESC;
$plugin_setup[$i]['type']    = "text";
$plugin_setup[$i]['default'] = 0;
$i++;

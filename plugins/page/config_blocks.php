<?php
include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/page/langs/{$xoopsConfig['language']}.php";

$i = 0;

$blockConfig['page'][$i]['name'] = _MD_TCW_PAGE_BLOCK_LIST;
$blockConfig['page'][$i]['func'] = 'get_page_list';
$blockConfig['page'][$i]['tpl']  = 'tad_web_page_block_b3.html';

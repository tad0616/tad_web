<?php
// if (!isset($_SESSION['page_cates'])) {
$arr[_MD_TCW_PAGE_ALLCATE] = 'all';
if($WebID){
    $web_cate                  = new web_cate($WebID, "page", "tad_web_page");
    $tad_web_cate_arr          = $web_cate->get_tad_web_cate_arr();
    // if (isset($_GET['debug']) and $_GET['debug'] == 1) {
    //     die(var_export($tad_web_cate_arr));
    // }
    foreach ($tad_web_cate_arr as $cate) {
        $arr[$cate['CateName']] = $cate['CateID'];
    }
    $arr[$cate['CateName']] = $cate['CateID'];
}
$page_cates             = $arr;
// }

$blocksArr['list_page']['name']                 = _MD_TCW_PAGE_BLOCK_LIST;
$blocksArr['list_page']['plugin']               = 'page';
$blocksArr['list_page']['tpl']                  = 'list_page.tpl';
$blocksArr['list_page']['position']             = 'block4';
$blocksArr['list_page']['config']['limit']      = 20;
$blocksArr['list_page']['colset']['limit']      = array('label' => _MD_TCW_BLOCK_LIMIT, 'type' => 'text');
$blocksArr['list_page']['config']['show_count'] = 0;
$blocksArr['list_page']['colset']['show_count'] = array('label' => _MD_TCW_PAGE_BLOCK_SHOW_COUNT, 'type' => 'radio', 'options' => array(_YES => 1, _NO => 0));
$blocksArr['list_page']['config']['CateID']     = '';
$blocksArr['list_page']['colset']['CateID']     = array('label' => _MD_TCW_PAGE_CATE, 'type' => 'radio', 'options' => $page_cates);

$blocksArr['page_menu']['name']                 = _MD_TCW_PAGE_BLOCK_MENU;
$blocksArr['page_menu']['plugin']               = 'page';
$blocksArr['page_menu']['tpl']                  = 'page_menu.tpl';
$blocksArr['page_menu']['position']             = 'side';
$blocksArr['page_menu']['config']['limit']      = 40;
$blocksArr['page_menu']['colset']['limit']      = array('label' => _MD_TCW_BLOCK_LIMIT, 'type' => 'text');
$blocksArr['page_menu']['config']['show_count'] = 0;
$blocksArr['page_menu']['colset']['show_count'] = array('label' => _MD_TCW_PAGE_BLOCK_SHOW_COUNT, 'type' => 'radio', 'options' => array(_YES => 1, _NO => 0));

//不能刪，否則會導致無法設定
$blockConfig['page'] = $blocksArr;

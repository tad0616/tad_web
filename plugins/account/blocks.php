<?php
//列出帳目
function list_account($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }

    $web_cate = new web_cate($WebID, 'account', 'tad_web_account');
    $web_cate->set_button_value(_MD_TCW_ACCOUNT_BOOK_TOOL);
    $web_cate->set_default_option_text(_MD_TCW_ACCOUNT_SELECT_BOOK);
    $cate_menu = $web_cate->get_tad_web_cate_arr(false);
    // die(var_export($cate_menu));
    $block['cate_menu'] = $cate_menu;
    $block['main_data'] = true;
    // include_once "class.php";

    // $tad_web_account = new tad_web_account($WebID);

    // $block = $tad_web_account->list_all("", $config['limit'], 'return');
    return $block;
}

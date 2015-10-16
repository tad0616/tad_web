<?php
include_once "header.php";

// if (!$isMyWeb and $MyWebs) {
//     redirect_header($_SERVER['PHP_SELF'] . "?WebID={$MyWebs[0]}", 3, _MD_TCW_AUTO_TO_HOME);
// } elseif (empty($_REQUEST['WebID'])) {
//     redirect_header("index.php?WebID={$_GET['WebID']}", 3, _MD_TCW_NOT_OWNER);
// }

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op             = system_CleanVars($_REQUEST, 'op', '', 'string');
$WebID          = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$MemID          = system_CleanVars($_REQUEST, 'MemID', 0, 'int');
$color_setup    = system_CleanVars($_REQUEST, 'color_setup', '', 'array');
$filename       = system_CleanVars($_REQUEST, 'filename', '', 'string');
$ConfigValue    = system_CleanVars($_REQUEST, 'ConfigValue', '', 'array');
$head_top       = system_CleanVars($_REQUEST, 'head_top', '', 'string');
$head_left      = system_CleanVars($_REQUEST, 'head_left', '', 'string');
$logo_top       = system_CleanVars($_REQUEST, 'logo_top', '', 'string');
$logo_left      = system_CleanVars($_REQUEST, 'logo_left', '', 'string');
$col_name       = system_CleanVars($_REQUEST, 'col_name', '', 'string');
$col_val        = system_CleanVars($_REQUEST, 'col_val', '', 'string');
$display_blocks = system_CleanVars($_REQUEST, 'display_blocks', '', 'string');
$other_web_url  = system_CleanVars($_REQUEST, 'other_web_url', '', 'string');

switch ($op) {

    //標題設定
    case "save_head":
        save_web_config("web_head", $filename, $WebID);
        output_head_file($WebID);
        die($filename);
        exit;
        break;

    case "save_head_bg":
        save_web_config("head_top", $head_top, $WebID);
        save_web_config("head_left", $head_left, $WebID);
        output_head_file($WebID);
        exit;
        break;

    //logo設定
    case "save_logo":
        save_web_config("logo_top", $logo_top, $WebID);
        save_web_config("logo_left", $logo_left, $WebID);
        output_head_file($WebID);
        exit;
        break;

    case "save_logo_pic":
        save_web_config("web_logo", $filename, $WebID);
        output_head_file($WebID);
        exit;
        break;

    //標題設定
    case "save_bg":
        save_web_config("web_bg", $filename, $WebID);
        output_head_file($WebID);
        exit;
        break;

    //儲存設定值
    case "save_color":
        save_web_config($col_name, $col_val, $WebID);
        exit;
        break;
}

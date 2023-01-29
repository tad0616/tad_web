<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;

require_once __DIR__ . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$WebID = Request::getInt('WebID');
$MemID = Request::getInt('MemID');
$color_setup = Request::getArray('color_setup');
$filename = Request::getString('filename');
$ConfigValue = Request::getArray('ConfigValue');
$head_top = Request::getString('head_top');
$head_left = Request::getString('head_left');
$logo_top = Request::getString('logo_top');
$logo_left = Request::getString('logo_left');
$col_name = Request::getString('col_name');
$col_val = Request::getString('col_val');
$display_blocks = Request::getString('display_blocks');
$other_web_url = Request::getString('other_web_url');
$keyman = Request::getString('keyman');
header('HTTP/1.1 200 OK');
switch ($op) {
    //標題設定
    case 'save_head':
        save_web_config('web_head', $filename, $WebID);
        output_head_file($WebID);
        output_head_file_480($WebID);
        die($filename);

    case 'save_head_bg':
        save_web_config('head_top', $head_top, $WebID);
        save_web_config('head_left', $head_left, $WebID);
        output_head_file($WebID);
        output_head_file_480($WebID);
        exit;
        break;

    //logo設定
    case 'save_logo':
        save_web_config('logo_top', $logo_top, $WebID);
        save_web_config('logo_left', $logo_left, $WebID);
        output_head_file($WebID);
        output_head_file_480($WebID);
        exit;

    case 'save_logo_pic':
        save_web_config('web_logo', $filename, $WebID);
        output_head_file($WebID);
        output_head_file_480($WebID);
        exit;

    //標題設定
    case 'save_bg':
        save_web_config('web_bg', $filename, $WebID);
        // output_head_file($WebID);
        exit;

    //儲存設定值
    case 'save_color':
        save_web_config($col_name, $col_val, $WebID);
        exit;

    //篩選使用者
    case 'keyman':
        die(keyman($WebID, $keyman));
        exit;

}

function keyman($WebID, $keyman)
{
    global $xoopsDB;
    $web_admin_arr = get_web_roles($WebID, 'admin');
    $web_admins = !empty($web_admin_arr) ? implode(',', $web_admin_arr) : '';
    $where = !empty($keyman) ? "where name like '%{$keyman}%' or uname like '%{$keyman}%'" : '';

    $sql = 'select uid,uname,name from ' . $xoopsDB->prefix('users') . " $where order by uname";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $myts = \MyTextSanitizer::getInstance();
    $user_ok = $user_yet = '';
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $name = $myts->htmlSpecialChars($name);
        $uname = $myts->htmlSpecialChars($uname);
        $name = empty($name) ? '' : " ({$name})";
        if (!empty($web_admin_arr) and in_array($uid, $web_admin_arr) or $uid == $WebOwnerUid) {
            $user_ok .= "<option value=\"$uid\">{$uid} {$name} {$uname} </option>";
        } else {
            $user_yet .= "<option value=\"$uid\">{$uid} {$name} {$uname} </option>";
        }
    }

    return $user_yet;
}

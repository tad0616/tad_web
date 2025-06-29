<?php
use Xmf\Request;
use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\FancyBox;
use XoopsModules\Tadtools\MColorPicker;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Wcag;
use XoopsModules\Tad_web\Power;
use XoopsModules\Tad_web\Tools as TadWebTools;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

if (!empty($WebID) and $isMyWeb) {
    $xoopsOption['template_main'] = 'tad_web_block.tpl';
} elseif (!$isMyWeb and $MyWebs) {
    $WebID = (int) $MyWebs[0];
    redirect_header($_SERVER['PHP_SELF'] . "?WebID={$WebID}", 3, _MD_TCW_AUTO_TO_HOME);
} else {
    redirect_header("index.php?WebID={$WebID}", 3, _MD_TCW_NOT_OWNER . '<br>' . __FILE__ . ' : ' . __LINE__);
}
//權限設定
$power = new Power($WebID);
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op            = Request::getString('op');
$WebID         = Request::getInt('WebID');
$BlockID       = Request::getInt('BlockID');
$shareBlockID  = Request::getInt('shareBlockID');
$config        = Request::getArray('config');
$BlockTitle    = Request::getString('BlockTitle');
$BlockName     = Request::getString('BlockName');
$BlockPosition = Request::getString('BlockPosition');
$BlockShare    = Request::getInt('BlockShare');
$block_pic     = Request::getArray('block_pic');
$use_block_pic = Request::getString('use_block_pic');
$BlockEnable   = Request::getInt('BlockEnable');
$plugin        = Request::getString('plugin');
$ShareFrom     = Request::getInt('ShareFrom');

common_template($WebID, $web_all_config);

switch ($op) {
    //新增資料
    case 'save_block_config':
        save_block_config($WebID, $BlockID, $BlockName, $BlockTitle, $BlockPosition, $config, $BlockShare, $shareBlockID, $BlockEnable, $ShareFrom);

        clear_block_cache($WebID);
        header("location: block.php?WebID={$WebID}");
        exit;

    case 'config':
        config_block($WebID, $BlockID, $plugin);
        break;

    case 'add_block':
        config_block($WebID, $BlockID, $plugin, 'add');
        break;

    case 'mk_block_pic':
        mk_block_pic($WebID, $block_pic, $use_block_pic);

        clear_block_cache($WebID);
        header("location: block.php?WebID={$WebID}");
        exit;

    case 'delete_block':
        delete_block($BlockID, $WebID);
        clear_block_cache($WebID);
        header("location: block.php?WebID={$WebID}");
        exit;

    case 'copy':
        $newBlockID = copy_block($BlockID, $plugin, $WebID);
        clear_block_cache($WebID);
        header("location: block.php?WebID={$WebID}&op=config&plugin={$plugin}&BlockID={$newBlockID}");
        exit;

    case 'demo':
        demo_block($BlockID, $WebID);
        break;

    //預設動作
    default:
        // chk_newblock($WebID);
        block_setup($WebID);
        $xoopsTpl->assign('block1', get_position_blocks($WebID, 'block1', $plugin));
        $xoopsTpl->assign('block2', get_position_blocks($WebID, 'block2', $plugin));
        $xoopsTpl->assign('block3', get_position_blocks($WebID, 'block3', $plugin));
        $xoopsTpl->assign('block4', get_position_blocks($WebID, 'block4', $plugin));
        $xoopsTpl->assign('block5', get_position_blocks($WebID, 'block5', $plugin));
        $xoopsTpl->assign('block6', get_position_blocks($WebID, 'block6', $plugin));
        $xoopsTpl->assign('side', get_position_blocks($WebID, 'side', $plugin));
        $xoopsTpl->assign('uninstall', get_position_blocks($WebID, 'uninstall', $plugin));
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/
function config_block($WebID, $BlockID, $plugin, $mode = 'config')
{
    global $xoopsDB, $xoopsTpl, $power;

    $power->set_col_md(3, 9);
    $power_form = $power->power_menu('read', 'BlockID', $BlockID);
    $xoopsTpl->assign('power_form', $power_form);

    $shareBlockCount = '';
    $webs            = [];
    $shareBlockID    = get_share_block_id($BlockID);

    if ($BlockID) {
        $sql    = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_blocks') . '` WHERE `BlockID`=?';
        $result = Utility::query($sql, 'i', [$BlockID]) or Utility::web_error($sql, __FILE__, __LINE__);
        $block  = $xoopsDB->fetchArray($result);

        //若為分享區塊，找出目前有在使用的單位
        if (!empty($shareBlockID)) {
            $sql = "SELECT b.*
            FROM " . $xoopsDB->prefix('tad_web_blocks') . " AS a
            LEFT JOIN " . $xoopsDB->prefix('tad_web') . " AS b ON a.WebID = b.WebID
            WHERE a.`BlockName` = ?
            AND a.plugin = 'custom'
            AND a.BlockEnable = '1'
            AND a.BlockPosition != ''
            AND a.BlockPosition != 'uninstall'";

            $result = Utility::query($sql, 's', [$block['BlockName']]) or Utility::web_error($sql, __FILE__, __LINE__);

            $shareBlockCount = 0;
            while (false !== ($all = $xoopsDB->fetchArray($result))) {
                $webs[$shareBlockCount] = $all;
                $shareBlockCount++;
            }
        }
    }

    $block_config_form = $editor = '';
    //新增
    if ('add' === $mode) {
        Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/block");
        Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/block/image");
        Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/block/file");
        if (!isset($block)) {
            $block['BlockTitle']   = '';
            $block['BlockID']      = '';
            $block['BlockContent'] = '';
            $config['show_title']  = '1';
        }
        $CkEditor = new CkEditor("tad_web/{$WebID}/block", 'BlockContent[html]', $block['BlockContent']);
        $CkEditor->setHeight(250);
        $editor = $CkEditor->render();
    } else {
        //修改
        $block_plugin = isset($block['plugin']) ? $block['plugin'] : $plugin;
        $config       = isset($block['plugin']) ? json_decode($block['BlockConfig'], true) : [];

        if ('custom' === $block_plugin || 'share' === $block_plugin) {
            $CkEditor = new CkEditor("tad_web/{$WebID}/block", 'BlockContent[html]', $block['BlockContent']);
            $CkEditor->setHeight(250);
            $editor        = $CkEditor->render();
            $iframeContent = strip_tags($block['BlockContent']);
        } else {
            $func = isset($block['BlockName']) ? $block['BlockName'] : '';
            require_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$block_plugin}/config_blocks.php";
            $block_config_form = array2form($blockConfig[$block_plugin][$func]['colset'], $config);

        }
    }
    $block['config'] = $config;
    // if ($WebID == '10') {
    //     die(var_export($config));
    // }
    $xoopsTpl->assign('block_config_form', $block_config_form);
    $xoopsTpl->assign('editor', $editor);

    $xoopsTpl->assign('block', $block);
    $xoopsTpl->assign('iframeContent', $iframeContent);
    // $xoopsTpl->assign('block_config', $config);
    $xoopsTpl->assign('mode', $mode);
    $xoopsTpl->assign('shareBlockID', $shareBlockID);
    $xoopsTpl->assign('shareBlockCount', sprintf(_MD_TCW_USE_BLOCK_SITE, $shareBlockCount));
    $xoopsTpl->assign('use_share_web', $webs);

    $SweetAlert = new SweetAlert();
    $SweetAlert->render('delete_block_func', "block.php?WebID={$WebID}&op=delete_block&BlockID=", 'BlockID');
}

//區塊設定表單
function array2form($form_arr = [], $config = [])
{

    if (empty($form_arr)) {
        return;
    }
    $form_code = '';
    foreach ($form_arr as $config_name => $form) {
        $form_code .= '<div class="form-group row mb-3">';
        $form_code .= '
        <label class="col-sm-3 col-form-label text-sm-right text-sm-end control-label">
            ' . $form['label'] . '
        </label>
        <div class="col-sm-9">';
        switch ($form['type']) {
            case 'select':
                $form_code .= '<select name="config[' . $config_name . ']" class="form-control form-select">';
                foreach ($form['options'] as $title => $value) {
                    $selected = (is_array($config) && $value == $config[$config_name]) ? 'selected' : '';
                    $form_code .= '<option value="' . $value . '" ' . $selected . '>' . $title . '</option>';
                }
                $form_code .= '</select>';
                break;
            case 'checkbox':
                // die(var_export($form['options']));
                foreach ($form['options'] as $title => $value) {
                    $checked = in_array($value, $config[$config_name]) ? 'checked' : '';
                    $form_code .= '<label class="checkbox"><input type="checkbox" name="config[' . $config_name . '][]" value="' . $value . '" ' . $checked . '>' . $title . '</label>';
                }
                break;
            case 'radio':
                foreach ($form['options'] as $title => $value) {
                    $checked = $value == $config[$config_name] ? 'checked' : '';
                    $form_code .= '<label class="radio"><input type="radio" name="config[' . $config_name . ']" value="' . $value . '" ' . $checked . '>' . $title . '</label>';
                }
                break;
            case 'textarea':
                $form_code .= '<textarea name="config[' . $config_name . ']" class="form-control">' . $config[$config_name] . '</textarea>';
                break;
            case 'datetime':
                $form_code .= '<script type="text/javascript" src="' . XOOPS_URL . '/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
                <input type="text" name="config[' . $config_name . ']" class="form-control" onClick="WdatePicker({dateFmt:\'MM/dd/yyyy HH:mm:ss\', startDate:\'%y-%M-%d\'})" value="' . $config[$config_name] . '">';
                break;
            default:
                $form_code .= '<input type="text" name="config[' . $config_name . ']" class="form-control" value="' . $config[$config_name] . '" placeholder="' . $form['placeholder'] . '">';
                break;
        }
        $form_code .= '
            </div>
        </div>';
    }

    return $form_code;
}

// 儲存區塊設定
function save_block_config($WebID = '', $BlockID = '', $BlockName = '', $BlockTitle = '', $BlockPosition = '', $config = [], $BlockShare = '', $shareBlockID = '', $BlockEnable = '', $ShareFrom = '')
{
    global $xoopsDB, $power;

    $content_type = $config['content_type'];
    $BlockContent = Wcag::amend($_POST['BlockContent'][$content_type]);

    $new_block_config = json_encode($config, JSON_UNESCAPED_UNICODE);
    $text_color       = TadWebTools::get_web_config('block_pic_text_color', $WebID);
    $border_color     = TadWebTools::get_web_config('block_pic_border_color', $WebID);
    $text_size        = TadWebTools::get_web_config('block_pic_text_size', $WebID);
    $font             = TadWebTools::get_web_config('block_pic_font', $WebID);

    //新增的話
    //原始自訂區塊名稱 custom_{$WebID}_{$BlockID}
    //分享區塊名稱 share_{$WebID}_{$BlockID}

    //新增
    if (empty($BlockID)) {
        $BlockSort = max_blocks_sort($WebID, $BlockPosition);
        $sql       = 'INSERT INTO `' . $xoopsDB->prefix('tad_web_blocks') . '` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `WebID`, `plugin`, `ShareFrom`) VALUES(?, 0, ?, ?, ?, ?, ?, ?, ?, ?, 0)';
        Utility::query($sql, 'ssssssiis', ["custom_{$WebID}", $BlockTitle, $BlockContent, $BlockEnable, $new_block_config, $BlockPosition, $BlockSort, $WebID, 'custom']) or Utility::web_error($sql, __FILE__, __LINE__);
        //取得最後新增資料的流水編號
        $BlockID = $xoopsDB->getInsertId();

        //更新原有區塊名稱
        $sql = "UPDATE `" . $xoopsDB->prefix('tad_web_blocks') . "`
        SET `BlockName` = ?
        WHERE `BlockID` = ?";
        $params = ["custom_{$WebID}_{$BlockID}", $BlockID];

        Utility::query($sql, 'si', $params) or Utility::web_error($sql, __FILE__, __LINE__);

        //共享區塊
        if ('1' == $BlockShare) {
            $sql = "INSERT INTO `" . $xoopsDB->prefix('tad_web_blocks') . "`
            (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `WebID`, `plugin`, `ShareFrom`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [
                "share_{$WebID}_{$BlockID}",
                0,
                $BlockTitle,
                $BlockContent,
                0,
                $new_block_config,
                '',
                $BlockSort,
                $WebID,
                'share',
                $BlockID,
            ];

            Utility::query($sql, 'sisssssiisi', $params) or Utility::web_error($sql, __FILE__, __LINE__);

            //取得最後新增資料的流水編號
            $shareBlockID = $xoopsDB->getInsertId();

            //更新共享區塊名稱
            $sql = "UPDATE `" . $xoopsDB->prefix('tad_web_blocks') . "`
            SET `BlockName` = ?
            WHERE `BlockID` = ?";
            $params = ["share_{$WebID}_{$shareBlockID}", $shareBlockID];

            Utility::query($sql, 'si', $params) or Utility::web_error($sql, __FILE__, __LINE__);

        }
    } else {
        //更新區塊

        $sql = "UPDATE `" . $xoopsDB->prefix('tad_web_blocks') . "`
        SET `BlockConfig` = ?,
            `BlockTitle` = ?,
            `BlockPosition` = ?,
            `BlockEnable` = ?,
            `BlockContent` = ?
        WHERE `BlockID` = ?
        AND `WebID` = ?";
        $params = [$new_block_config, $BlockTitle, $BlockPosition, $BlockEnable, $BlockContent, $BlockID, $WebID];
        Utility::query($sql, 'sssssii', $params) or Utility::web_error($sql, __FILE__, __LINE__);

        //共享區塊若不再共享（直接刪除之）
        if (!empty($shareBlockID) and '1' != $BlockShare) {
            delete_share_block($shareBlockID, $WebID);
        } elseif (empty($shareBlockID) and '1' == $BlockShare) {
            //自訂區塊若改為共享
            $sql = "INSERT INTO `" . $xoopsDB->prefix('tad_web_blocks') . "`
            (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `WebID`, `plugin`, `ShareFrom`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [
                "share_{$WebID}_{$BlockID}",
                0,
                $BlockTitle,
                $BlockContent,
                0,
                $new_block_config,
                'uninstall',
                0,
                $WebID,
                'share',
                $BlockID,
            ];

            Utility::query($sql, 'sisssssiisi', $params) or Utility::web_error($sql, __FILE__, __LINE__);

            //取得最後新增資料的流水編號
            $shareBlockID = $xoopsDB->getInsertId();

            //更新共享區塊名稱
            $sql = "UPDATE `" . $xoopsDB->prefix('tad_web_blocks') . "`
            SET `BlockName` = ?
            WHERE `BlockID` = ?";
            $params = ["share_{$WebID}_{$shareBlockID}", $shareBlockID];

            Utility::query($sql, 'si', $params) or Utility::web_error($sql, __FILE__, __LINE__);

        }

    }
    //儲存權限
    $power->save_power('BlockID', $BlockID, 'read');
    // mkTitleImg($WebID, "block_{$BlockID}", $BlockTitle, $text_color, $border_color, $text_size, $font);
}

//自動取得tad_web_blocks的最新排序
function max_custom_block_num($WebID)
{
    global $xoopsDB;
    $sql    = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_web_blocks') . '` WHERE `WebID` =? AND `plugin`=?';
    $result = Utility::query($sql, 'is', [$WebID, 'custom']) or Utility::web_error($sql, __FILE__, __LINE__);

    list($count) = $xoopsDB->fetchRow($result);

    return ++$count;
}

//產生區塊標題
function mk_block_pic($WebID = '', $block_pic = [], $use_block_pic = '')
{
    global $xoopsDB;
    foreach ($block_pic as $item => $val) {
        save_web_config($item, $val, $WebID);
    }
    save_web_config('use_block_pic', $use_block_pic, $WebID);
    $sql    = 'SELECT `BlockID`, `BlockName`, `BlockTitle` FROM `' . $xoopsDB->prefix('tad_web_blocks') . '` WHERE `WebID` = ?';
    $result = Utility::query($sql, 'i', [$WebID]) or Utility::web_error($sql, __FILE__, __LINE__);

    while (list($BlockID, $BlockName, $BlockTitle) = $xoopsDB->fetchRow($result)) {
        mkTitleImg($WebID, "block_{$BlockID}", $BlockTitle, $block_pic['block_pic_text_color'], $block_pic['block_pic_border_color'], $block_pic['block_pic_text_size'], $block_pic['block_pic_font']);
    }
}

// 取得區塊設定需要的一些共同設定值
function block_setup($WebID = '')
{
    global $xoopsTpl;

    $MColorPicker = new MColorPicker('.color-picker');
    $MColorPicker->render('bootstrap');

    $text_color   = TadWebTools::get_web_config('block_pic_text_color', $WebID);
    $border_color = TadWebTools::get_web_config('block_pic_border_color', $WebID);
    $text_size    = TadWebTools::get_web_config('block_pic_text_size', $WebID);
    $font         = TadWebTools::get_web_config('block_pic_font', $WebID);

    $block_pic_text_color   = empty($text_color) ? '#ABBF6B' : $text_color;
    $block_pic_border_color = empty($border_color) ? '#ffffff' : $border_color;
    $block_pic_text_size    = empty($text_size) ? '18' : $text_size;
    $block_pic_font         = empty($font) ? 'DroidSansFallback.ttf' : $font;

    $xoopsTpl->assign('block_pic_text_color', $block_pic_text_color);
    $xoopsTpl->assign('block_pic_border_color', $block_pic_border_color);
    $xoopsTpl->assign('block_pic_text_size', $block_pic_text_size);
    $xoopsTpl->assign('block_pic_font', $block_pic_font);

    $FancyBox = new FancyBox('.edit_block', '480px');
    $FancyBox->render(false);
}

//刪除區塊
function delete_block($BlockID, $WebID)
{
    global $xoopsDB, $MyWebs, $power;
    if (!$_SESSION['tad_web_adm'] and !in_array($WebID, $MyWebs)) {
        return;
    }
    //若有分享區塊，先刪除之
    $share_block_id = get_share_block_id($BlockID);
    if ($share_block_id) {
        delete_share_block($share_block_id, $WebID);
    }

    //刪除自己
    $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_blocks') . '` WHERE `BlockID`=?';
    Utility::query($sql, 'i', [$BlockID]) or Utility::web_error($sql, __FILE__, __LINE__);

    //刪除權限
    $power->delete_power('BlockID', $BlockID, 'read');
}

//刪除分享區塊
function delete_share_block($BlockID, $WebID)
{
    global $xoopsDB, $MyWebs;
    if (!$_SESSION['tad_web_adm'] and !in_array($WebID, $MyWebs)) {
        return;
    }

//刪除自己
    $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_blocks') . '` WHERE `BlockID`=? AND `plugin`=?';
    Utility::query($sql, 'is', [$BlockID, 'share']) or Utility::web_error($sql, __FILE__, __LINE__);

//刪除別人的分享紀錄
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_blocks') . '` SET `ShareFrom`=? WHERE `ShareFrom`=? AND `plugin`=?';
    Utility::query($sql, 'iis', [0, $BlockID, 'custom']) or Utility::web_error($sql, __FILE__, __LINE__);

}

//取得某區塊是否有分享的區塊ID
function get_share_block_id($BlockID)
{
    global $xoopsDB;
    $sql    = 'SELECT `BlockID` FROM `' . $xoopsDB->prefix('tad_web_blocks') . '` WHERE `ShareFrom`=?';
    $result = Utility::query($sql, 'i', [$BlockID]) or Utility::web_error($sql, __FILE__, __LINE__);

    list($BlockID) = $xoopsDB->fetchRow($result);

    return $BlockID;
}

//複製區塊
function copy_block($BlockID, $plugin, $WebID)
{
    global $xoopsDB;
    $sql    = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_blocks') . '` WHERE `BlockID`=?';
    $result = Utility::query($sql, 'i', [$BlockID]) or Utility::web_error($sql, __FILE__, __LINE__);

    $block = $xoopsDB->fetchArray($result);

    $block['BlockContent'] = Wcag::amend($block['BlockContent']);

    $BlockCopy = max_blocks_copy($WebID, $block['BlockName']);
    $BlockSort = max_blocks_sort($WebID, $block['BlockPosition']);

    $block['BlockTitle'] = $block['BlockTitle'] . '-' . $BlockCopy;
    $sql                 = 'INSERT INTO `' . $xoopsDB->prefix('tad_web_blocks') . '` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `WebID`, `plugin`, `ShareFrom`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    Utility::query($sql, 'sisssssiisi', [$block['BlockName'], $BlockCopy, $block['BlockTitle'], $block['BlockContent'], $block['BlockEnable'], $block['BlockConfig'], $block['BlockPosition'], $BlockSort, $WebID, $block['plugin'], $block['ShareFrom']]) or Utility::web_error($sql, __FILE__, __LINE__);
    //取得最後新增資料的流水編號
    $BlockID = $xoopsDB->getInsertId();

    return $BlockID;
}

//自動取得tad_web_blocks的最新排序
function max_blocks_copy($WebID, $BlockName)
{
    global $xoopsDB;
    $sql             = 'SELECT MAX(`BlockCopy`) FROM `' . $xoopsDB->prefix('tad_web_blocks') . '` WHERE `WebID` =? AND `BlockName`=?';
    $result          = Utility::query($sql, 'is', [$WebID, $BlockName]) or Utility::web_error($sql, __FILE__, __LINE__);
    list($BlockCopy) = $xoopsDB->fetchRow($result);

    return ++$BlockCopy;
}

//展示區塊
function demo_block($BlockID, $WebID)
{
    global $xoopsDB, $xoopsTpl, $plugin_menu_var;

    $myts      = \MyTextSanitizer::getInstance();
    $block_tpl = get_all_blocks('tpl');
    $dir       = XOOPS_ROOT_PATH . '/modules/tad_web/plugins/';

    $sql    = 'SELECT * FROM `' . $xoopsDB->prefix('tad_web_blocks') . '` WHERE `BlockID`=?';
    $result = Utility::query($sql, 'i', [$BlockID]) or Utility::web_error($sql, __FILE__, __LINE__);

    $all = $xoopsDB->fetchArray($result);
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    // die(var_export($all));
    $blocks_arr           = $all;
    $config               = json_decode($BlockConfig, true);
    $blocks_arr['config'] = $config;
    // die(var_export($blocks_arr));
    if ('custom' === $plugin or 'share' === $plugin) {
        if ('iframe' === $config['content_type']) {
            $blocks_arr['BlockContent'] = "<iframe title=\"{$BlockTitle}\" src=\"{$BlockContent}\" style=\"width: 100%; height: 300px; overflow: auto; border:none;\"></iframe>";
        } elseif ('js' === $config['content_type']) {
            $blocks_arr['BlockContent'] = $BlockContent;
        } else {
            $blocks_arr['BlockContent'] = $myts->displayTarea($BlockContent, 1);
        }
    } else {
        if (file_exists("{$dir}{$plugin}/blocks.php")) {
            require_once "{$dir}{$plugin}/blocks.php";
        }
        $blocks_arr['tpl']          = $block_tpl[$BlockName];
        $blocks_arr['BlockContent'] = $BlockContent = call_user_func($BlockName, $WebID, $config);
        $blocks_arr['config']       = $config;
        $blocks_arr['plugin']       = $plugin_menu_var[$plugin];
    }

    if ('share' === $plugin) {
        $info = get_tad_web($blocks_arr['WebID']);
        $xoopsTpl->assign('share_info', $info);
    }

    $xoopsTpl->assign('theme_display_mode', 'blank');

    $xoopsTpl->assign('block', $blocks_arr);
}

// 檢查是否有新區塊
function chk_newblock($WebID)
{
    global $xoopsDB;

    //取得應有的所有區塊
    $all_blocks = get_all_blocks();

    $block_plugin = get_all_blocks('plugin');
    $block_config = get_all_blocks('config');
    $db_blocks    = [];
    //找出目前已安裝的區塊
    $sql    = 'SELECT `BlockID`,`BlockName`,`BlockConfig` FROM `' . $xoopsDB->prefix('tad_web_blocks') . '` WHERE `WebID`=? AND `plugin`!=? AND `plugin`!=?';
    $result = Utility::query($sql, 'iss', [$WebID, 'custom', 'share']) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($BlockID, $BlockName, $BlockConfig) = $xoopsDB->fetchRow($result)) {
        $db_blocks[$BlockName]                  = $BlockName;
        $db_blocks_config[$BlockName][$BlockID] = $BlockConfig;
    }

    //安裝新區塊
    foreach ($all_blocks as $BlockName => $BlockTitle) {
        //若該區塊還沒有安裝在該網站
        if (!in_array($BlockName, $db_blocks)) {
            if (is_array($block_config[$BlockName])) {
                $config = json_encode($block_config[$BlockName], JSON_UNESCAPED_UNICODE);
            } else {
                $config = '';
            }
            $config = str_replace('{{WebID}}', $WebID, $config);
            $sql    = 'INSERT INTO `' . $xoopsDB->prefix('tad_web_blocks') . '` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `WebID`, `plugin`, `ShareFrom`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            Utility::query($sql, 'sisssssiisi', [$BlockName, 0, $BlockTitle, '', '1', $config, 'uninstall', 0, $WebID, $block_plugin[$BlockName], 0], 0) or Utility::web_error($sql, __FILE__, __LINE__);
        } else {
            //檢查區塊設定值是否需要更新

            //找出某區塊安裝在該網站的 $BlockID 以及現有設定
            foreach ($db_blocks_config[$BlockName] as $BlockID => $BlockConfig) {
                $new_config = $db_config = [];

                //已安裝區塊的設定值陣列
                $db_config = json_decode($BlockConfig, true);

                if (is_array($block_config[$BlockName])) {
                    foreach ($block_config[$BlockName] as $config_name => $def_value) {
                        if (isset($db_config[$config_name]) and '' != $db_config[$config_name]) {
                            // echo "有預設值：{$db_config[$config_name]}<br>";
                            $new_config[$config_name] = $db_config[$config_name];
                        } else {
                            // echo "沒有預設值：{$def_value}<br>";
                            $new_config[$config_name] = $def_value;
                        }
                    }
                }

                // echo "新設定值為：" . var_export($new_config, true) . "<br>";
                //更新設定值

                $new_block_config = json_encode($new_config, JSON_UNESCAPED_UNICODE);
                $new_block_config = str_replace('{{WebID}}', $WebID, $new_block_config);
                $sql              = 'UPDATE `' . $xoopsDB->prefix('tad_web_blocks') . '` SET `BlockConfig` = ? WHERE `BlockID` = ?';
                Utility::query($sql, 'si', [$new_block_config, $BlockID]) or Utility::web_error($sql, __FILE__, __LINE__);
            }
        }
    }
}

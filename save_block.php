<?php

use Xmf\Request;
use XoopsModules\Tadtools\Utility;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/function.php';

$op = Request::getString('op');
$PositionName = Request::getString('PositionName');
$BlockID = Request::getInt('BlockID');
$BlockEnable = Request::getString('BlockEnable');
$order_arr = Request::getArray('order_arr');
$plugin = Request::getString('plugin');
$WebID = Request::getInt('WebID');

switch ($op) {
    case 'save_position':
        // 安裝共享區塊
        $newBlockID = '';
        if ('share' === $plugin and 'uninstall' !== $PositionName) {
            //讀出共享區塊內容
            $block = get_block($BlockID);

            //複製一份給目前網站
            $BlockSort = max_blocks_sort($WebID, $PositionName);
            $myts = \MyTextSanitizer::getInstance();

            $BlockTitle = $myts->addSlashes($block['BlockTitle']);
            $BlockContent = $myts->addSlashes($block['BlockContent']);
            $BlockConfig = $myts->addSlashes($block['BlockConfig']);
            $BlockName = $myts->addSlashes($block['BlockName']);
            $sql = 'insert into `' . $xoopsDB->prefix('tad_web_blocks') . "` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `WebID`, `plugin`, `ShareFrom`) values('{$BlockName}', 0, '{$BlockTitle}', '{$BlockContent}', '1', '{$BlockConfig}', '{$PositionName}', '$BlockSort', '{$WebID}', 'custom','{$block['BlockID']}')";

            $text_color = get_web_config('block_pic_text_color', $WebID);
            $border_color = get_web_config('block_pic_border_color', $WebID);
            $text_size = get_web_config('block_pic_text_size', $WebID);
            $font = get_web_config('block_pic_font', $WebID);
            mkTitlePic($WebID, "block_{$BlockID}", $BlockTitle, $text_color, $border_color, $text_size, $font);
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            //取得最後新增資料的流水編號
            $newBlockID = $xoopsDB->getInsertId();
        } else {
            $sql = 'update ' . $xoopsDB->prefix('tad_web_blocks') . " set `BlockPosition`='{$PositionName}' where `BlockID`='{$BlockID}'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            // $log .= "<div>$sql</div>";
        }

        $sort = 1;
        $shareBlockID = '';
        if ('share' === $plugin) {
            $shareBlockID = $BlockID;
            $copyBlockID = get_share_to_custom_blockid($BlockID, $WebID);
        }
        foreach ($order_arr as $BlockID) {
            if ($BlockID == $shareBlockID) {
                $BlockID = $copyBlockID;
            }
            $sql = 'update ' . $xoopsDB->prefix('tad_web_blocks') . " set `BlockSort`='{$sort}' where `BlockID`='{$BlockID}'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            $sort++;
        }

        if (!empty($newBlockID)) {
            echo $newBlockID;
        } else {
            echo _MD_TCW_SAVED . ' (' . date('Y-m-d H:i:s') . ')';
        }
        break;
    case 'save_sort':
        $sort = 1;
        if ('share' !== $plugin) {
            foreach ($order_arr as $BlockID) {
                // if ($BlockID == $shareBlockID) {
                //     $BlockID = $copyBlockID;
                // }
                $sql = 'update ' . $xoopsDB->prefix('tad_web_blocks') . " set `BlockSort`='{$sort}' where `BlockID`='{$BlockID}'";
                $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                $sort++;
            }
        }
        echo _MD_TCW_SAVED . ' (' . date('Y-m-d H:i:s') . ')';
        break;
    case 'save_enable':
        $sql = 'update ' . $xoopsDB->prefix('tad_web_blocks') . " set `BlockEnable`='{$BlockEnable}' where `BlockID`='{$BlockID}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $text_color = get_web_config('block_pic_text_color', $WebID);
        $border_color = get_web_config('block_pic_border_color', $WebID);
        $text_size = get_web_config('block_pic_text_size', $WebID);
        $font = get_web_config('block_pic_font', $WebID);
        $sql = 'select BlockTitle from ' . $xoopsDB->prefix('tad_web_blocks') . " where `BlockID`='{$BlockID}'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($BlockTitle) = $xoopsDB->fetchRow($result);
        mkTitlePic($WebID, "block_{$BlockID}", $BlockTitle, $text_color, $border_color, $text_size, $font);
        echo _MD_TCW_SAVED . ' (' . date('Y-m-d H:i:s') . ')';
        break;
}

clear_block_cache($WebID);

//以共享區塊編號取得在某網站的副本編號
function get_share_to_custom_blockid($BlockID, $WebID)
{
    global $xoopsDB;
    $sql = 'select BlockID from ' . $xoopsDB->prefix('tad_web_blocks') . " where `ShareFrom`='{$BlockID}' and `WebID`='{$WebID}' and `plugin`='custom'";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($BlockID) = $xoopsDB->fetchRow($result);

    return $BlockID;
}

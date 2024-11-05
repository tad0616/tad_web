<?php

use Xmf\Request;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Wcag;
use XoopsModules\Tad_web\Tools as TadWebTools;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/function.php';
$xoopsLogger->activated = false;

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

            $BlockTitle = $block['BlockTitle'];
            $BlockContent = $block['BlockContent'];
            $BlockContent = Wcag::amend($BlockContent);
            $BlockConfig = $block['BlockConfig'];
            $BlockName = $block['BlockName'];
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_web_blocks') . '` (`BlockName`, `BlockCopy`, `BlockTitle`, `BlockContent`, `BlockEnable`, `BlockConfig`, `BlockPosition`, `BlockSort`, `WebID`, `plugin`, `ShareFrom`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            Utility::query($sql, 'sisssssiisi', [$BlockName, 0, $BlockTitle, $BlockContent, '1', $BlockConfig, $PositionName, $BlockSort, $WebID, 'custom', $block['BlockID']]) or Utility::web_error($sql, __FILE__, __LINE__);

            $text_color = TadWebTools::get_web_config('block_pic_text_color', $WebID);
            $border_color = TadWebTools::get_web_config('block_pic_border_color', $WebID);
            $text_size = TadWebTools::get_web_config('block_pic_text_size', $WebID);
            $font = TadWebTools::get_web_config('block_pic_font', $WebID);
            mkTitleImg($WebID, "block_{$BlockID}", $BlockTitle, $text_color, $border_color, $text_size, $font);
            //取得最後新增資料的流水編號
            $newBlockID = $xoopsDB->getInsertId();
        } else {
            $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_blocks') . '` SET `BlockPosition`=? WHERE `BlockID`=?';
            Utility::query($sql, 'si', [$PositionName, $BlockID]) or Utility::web_error($sql, __FILE__, __LINE__);
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
            $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_blocks') . '` SET `BlockSort`=? WHERE `BlockID`=?';
            Utility::query($sql, 'ii', [$sort, $BlockID]) or Utility::web_error($sql, __FILE__, __LINE__);
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
                $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_blocks') . '` SET `BlockSort`=? WHERE `BlockID`=?';
                Utility::query($sql, 'ii', [$sort, $BlockID]) or Utility::web_error($sql, __FILE__, __LINE__);

                $sort++;
            }
        }
        echo _MD_TCW_SAVED . ' (' . date('Y-m-d H:i:s') . ')';
        break;
    case 'save_enable':
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_web_blocks') . '` SET `BlockEnable`=? WHERE `BlockID`=?';
        Utility::query($sql, 'si', [$BlockEnable, $BlockID]) or Utility::web_error($sql, __FILE__, __LINE__);
        $text_color = TadWebTools::get_web_config('block_pic_text_color', $WebID);
        $border_color = TadWebTools::get_web_config('block_pic_border_color', $WebID);
        $text_size = TadWebTools::get_web_config('block_pic_text_size', $WebID);
        $font = TadWebTools::get_web_config('block_pic_font', $WebID);
        $sql = 'SELECT `BlockTitle` FROM `' . $xoopsDB->prefix('tad_web_blocks') . '` WHERE `BlockID` = ?';
        $result = Utility::query($sql, 'i', [$BlockID]) or Utility::web_error($sql, __FILE__, __LINE__);

        list($BlockTitle) = $xoopsDB->fetchRow($result);
        mkTitleImg($WebID, "block_{$BlockID}", $BlockTitle, $text_color, $border_color, $text_size, $font);
        echo _MD_TCW_SAVED . ' (' . date('Y-m-d H:i:s') . ')';
        break;
}

clear_block_cache($WebID);

//以共享區塊編號取得在某網站的副本編號
function get_share_to_custom_blockid($BlockID, $WebID)
{
    global $xoopsDB;
    $sql = 'SELECT `BlockID` FROM `' . $xoopsDB->prefix('tad_web_blocks') . '` WHERE `ShareFrom`=? AND `WebID`=? AND `plugin`=?';
    $result = Utility::query($sql, 'iis', [$BlockID, $WebID, 'custom']) or Utility::web_error($sql, __FILE__, __LINE__);

    list($BlockID) = $xoopsDB->fetchRow($result);

    return $BlockID;
}

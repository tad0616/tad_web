<?php
use XoopsModules\Tadtools\Utility;
global $xoopsDB;
$sql = 'SELECT `ActionID`, `ActionName`, `ActionDate` FROM `' . $xoopsDB->prefix('tad_web_action') . '` WHERE `WebID`=? ORDER BY `ActionDate` DESC';
$result = Utility::query($sql, 'i', [$WebID]) or Utility::web_error($sql, __FILE__, __LINE__);
$action_id_arr[_MD_TCW_ACTION_BLOCK_SLIDE_RAND] = '';
$action_id_arr[_MD_TCW_ACTION_BLOCK_SLIDE_LATEST] = 'latest';
while (list($ActionID, $ActionName, $ActionDate) = $xoopsDB->fetchRow($result)) {
    $action_id_arr["{$ActionDate} {$ActionName}"] = $ActionID;
}

$blocksArr['list_action']['name'] = _MD_TCW_ACTION_BLOCK_LIST;
$blocksArr['list_action']['plugin'] = 'action';
$blocksArr['list_action']['tpl'] = 'list_action.tpl';
$blocksArr['list_action']['position'] = 'block4';
$blocksArr['list_action']['config']['limit'] = 10;
$blocksArr['list_action']['colset']['limit'] = ['label' => _MD_TCW_ACTION_BLOCK_LIMIT, 'type' => 'text'];

$blocksArr['action_slide']['name'] = _MD_TCW_ACTION_BLOCK_SLIDE;
$blocksArr['action_slide']['plugin'] = 'action';
$blocksArr['action_slide']['tpl'] = 'action_slide.tpl';
$blocksArr['action_slide']['position'] = 'side';
$blocksArr['action_slide']['config']['action_id'] = '';
$blocksArr['action_slide']['colset']['action_id'] = ['label' => _MD_TCW_ACTION_BLOCK_ALBUM, 'type' => 'select', 'options' => $action_id_arr];

$blocksArr['action_photos']['name'] = _MD_TCW_ACTION_BLOCK_PHOTOS;
$blocksArr['action_photos']['plugin'] = 'action';
$blocksArr['action_photos']['tpl'] = 'action_photos.tpl';
$blocksArr['action_photos']['position'] = 'block4';
$blocksArr['action_photos']['config']['action_id'] = '';
$blocksArr['action_photos']['colset']['action_id'] = ['label' => _MD_TCW_ACTION_BLOCK_ALBUM, 'type' => 'select', 'options' => $action_id_arr];
$blocksArr['action_photos']['config']['limit'] = 12;
$blocksArr['action_photos']['colset']['limit'] = ['label' => _MD_TCW_ACTION_BLOCK_LIMIT, 'type' => 'text', 'placeholder' => _MD_TCW_ACTION_BLOCK_PHOTOS_PLACEHOLDER];
$blocksArr['action_photos']['config']['order'] = '';
$blocksArr['action_photos']['colset']['order'] = ['label' => _MD_TCW_ACTION_BLOCK_ORDER, 'type' => 'select', 'options' => [_MD_TCW_ACTION_BLOCK_ORDER_RAND => 'rand', _MD_TCW_ACTION_BLOCK_ORDER_DEFAULT => '']];

//不能刪，否則會導致無法設定
$blockConfig['action'] = $blocksArr;

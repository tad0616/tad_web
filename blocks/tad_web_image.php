<?php
use XoopsModules\Tadtools\ResponsiveSlides;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;

if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

//區塊主函式 (相簿(tad_web_image))
function tad_web_image()
{
    global $xoopsDB;
    $and_webid = '';
    if (!empty($_GET['WebID'])) {
        $WebID = (int) $_GET['WebID'];
        $and_webid = "AND a.`WebID`='{$WebID}'  ";
    }

    $sql = 'SELECT a.`ActionName`, a.`ActionID`, b.`WebTitle`, a.`WebID` FROM `' . $xoopsDB->prefix('tad_web_action') . '` AS a JOIN `' . $xoopsDB->prefix('tad_web') . '` AS b ON a.`WebID`=b.`WebID` WHERE b.`WebEnable`=? ' . $and_webid . ' ORDER BY RAND() LIMIT 0,1';
    $result = Utility::query($sql, 's', ['1']) or Utility::web_error($sql, __FILE__, __LINE__);

    list($ActionName, $ActionID, $WebTitle, $WebID) = $xoopsDB->fetchRow($result);

    if (empty($ActionID)) {
        return;
    }
    $slide_images = [];

    $block['WebTitle'] = $WebTitle;
    $block['WebID'] = $WebID;
    $block['ActionID'] = $ActionID;
    $block['ActionName'] = $ActionName;

    $tad_web_action_image = new TadUpFiles('tad_web');

    $subdir = isset($WebID) ? "/{$WebID}" : '';
    $tad_web_action_image->set_dir('subdir', "/{$subdir}");
    $tad_web_action_image->set_col('ActionID', $ActionID);
    $photos = $tad_web_action_image->get_file();

    $ResponsiveSlides = new ResponsiveSlides(120, false);
    $i = 1;
    foreach ($photos as $pic) {
        if ($pic['description'] == $pic['original_filename']) {
            $pic['description'] = '';
        }
        $ResponsiveSlides->add_content($i, $pic['description'], '', $pic['path'], '', XOOPS_URL . "/modules/tad_web/action.php?WebID=$WebID&ActionID={$ActionID}");
        $i++;
    }

    $slide_images = $ResponsiveSlides->render();

    $block['slide_images'] = $slide_images;

    return $block;
}

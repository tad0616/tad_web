<?php
use XoopsModules\Tadtools\FancyBox;
use XoopsModules\Tadtools\ResponsiveSlides;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\Power;

//活動剪影
function list_action($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        return;
    }
    require_once __DIR__ . '/class.php';

    $tad_web_action = new tad_web_action($WebID);

    $block = $tad_web_action->list_all('', $config['limit'], 'return');

    return $block;
}

//活動剪影秀
function action_slide($WebID, $config = [])
{
    global $xoopsDB;
    require_once __DIR__ . '/class.php';
    $power = new Power($WebID);
    $tad_web_action = new tad_web_action($WebID);

    if ($config['action_id'] == 'latest') {
        $order = "order by ActionDate desc";
    } elseif (is_numeric($config['action_id'])) {
        $order = "and ActionID='{$config['action_id']}'";
    } else {
        $order = "order by rand()";
    }

    $sql = 'select ActionName, ActionID, gphoto_link from ' . $xoopsDB->prefix('tad_web_action') . " where WebID='{$WebID}' $order";

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($ActionName, $ActionID, $gphoto_link) = $xoopsDB->fetchRow($result)) {
        //檢查權限
        $the_power = $power->check_power('read', 'ActionID', $ActionID);
        if (!$the_power) {
            continue;
        }
        break;
    }

    if (empty($ActionID)) {
        $block['main_data'] = $block['ActionID'] = $block['ActionName'] = '';

        return $block;
    }
    $slide_images = '';

    $ResponsiveSlides = new ResponsiveSlides(120, false);
    $i = 1;
    if ($gphoto_link != '') {
        list($url, $key) = explode('?key=', $gphoto_link);
        $photos = $tad_web_action->tad_gphotos_list($ActionID, $url, $key);
        foreach ($photos as $pic) {
            $ResponsiveSlides->add_content($i, '', '', $pic['image_url'], '', XOOPS_URL . "/modules/tad_web/action.php?WebID=$WebID&ActionID={$ActionID}");
            $i++;
        }
    } else {
        $tad_web_action_image = new TadUpFiles('tad_web');
        $tad_web_action_image->set_dir('subdir', "/{$WebID}");
        $tad_web_action_image->set_col('ActionID', $ActionID);
        $photos = $tad_web_action_image->get_file();
        foreach ($photos as $pic) {
            if ($pic['description'] == $pic['original_filename']) {
                $pic['description'] = '';
            }
            $ResponsiveSlides->add_content($i, $pic['description'], '', $pic['path'], '', XOOPS_URL . "/modules/tad_web/action.php?WebID=$WebID&ActionID={$ActionID}");
            $i++;
        }

    }

    $slide_images = $ResponsiveSlides->render();

    $block['main_data'] = $slide_images;
    $block['ActionID'] = $ActionID;
    $block['ActionName'] = $ActionName;

    return $block;
}

//活動相片
function action_photos($WebID, $config = [])
{
    global $xoopsDB;
    require_once __DIR__ . '/class.php';
    $power = new Power($WebID);
    $tad_web_action = new tad_web_action($WebID);
    // Utility::dd($config);

    if ($config['action_id'] == 'latest') {
        $order = "order by ActionDate desc";
    } elseif (is_numeric($config['action_id'])) {
        $order = "and ActionID='{$config['action_id']}'";
    } else {
        $order = "order by rand()";
    }

    $sql = 'select ActionName, ActionID, gphoto_link from ' . $xoopsDB->prefix('tad_web_action') . " where WebID='{$WebID}' $order";

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($ActionName, $ActionID, $gphoto_link) = $xoopsDB->fetchRow($result)) {
        //檢查權限
        $the_power = $power->check_power('read', 'ActionID', $ActionID);
        if (!$the_power) {
            continue;
        }
        break;
    }

    if (empty($ActionID)) {
        $block['main_data'] = $block['ActionID'] = $block['ActionName'] = '';

        return $block;
    }

    $limit = (int) $config['limit'];

    $i = 1;
    if ($gphoto_link != '') {
        list($url, $key) = explode('?key=', $gphoto_link);
        $photos = $tad_web_action->tad_gphotos_list($ActionID, $url, $key);
    } else {
        $tad_web_action_image = new TadUpFiles('tad_web');
        $tad_web_action_image->set_dir('subdir', "/{$WebID}");
        $tad_web_action_image->set_col('ActionID', $ActionID);
        $photos = $tad_web_action_image->get_file();
    }

    $fancybox = new FancyBox('.fancybox_ActionID', 640, 480);
    $fancybox->render(false, null, false);
    if ($config['order'] == 'rand') {
        shuffle($photos);
    }

    $action_photos = '<ul>';
    foreach ($photos as $pic) {
        if ($gphoto_link != '') {
            $image_url = $pic['image_url'];
            $image_link = '<a href="' . $pic['image_link'] . '" target="_blank">' . _MD_TCW_ACTION_VIEW_ORIGINAL_IMAGE . '</a>';
        } else {
            $image_url = $pic['path'];
            $image_link = '';
        }
        $action_photos .= '
        <li style="width:120px;height:180px;float:left;list-style:none;margin-right:6px;">
        <a href="' . $image_url . '?type=.jpg" class="thumbnail fancybox_ActionID" rel="ActionID_' . $ActionID . '" style="display:inline-block; width: 120px; height: 120px; overflow: hidden; background-color: #cfcfcf; background-size: cover;border-radius: 5px; background-image: url(' . $image_url . '); background-repeat: no-repeat; background-position: center center; margin-bottom: 4px;">&nbsp;</a>
        ' . $image_link . '
        </li>';
        $i++;
        if ($limit > 0 and $i > $limit) {
            break;
        }
    }
    $action_photos .= '</ul>
    <div style="clear:both;"></div>';

    $block['main_data'] = $action_photos;
    $block['ActionID'] = $ActionID;
    $block['ActionName'] = $ActionName;

    return $block;
}

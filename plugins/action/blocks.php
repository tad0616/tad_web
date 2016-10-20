<?php
//活動剪影
function list_action($WebID, $config = array())
{

    global $xoopsDB, $xoopsTpl, $TadUpFiles;
    if (empty($WebID)) {
        retuen;
    }
    include_once "class.php";

    $tad_web_action = new tad_web_action($WebID);

    $block = $tad_web_action->list_all("", $config['limit'], 'return');
    return $block;
}

//活動剪影秀
function action_slide($WebID, $config = array())
{
    global $xoopsDB;
    $power = new power($WebID);

    $sql = "select ActionName,ActionID from " . $xoopsDB->prefix("tad_web_action") . " where WebID='{$WebID}' order by rand()";

    $result = $xoopsDB->query($sql) or web_error($sql);
    while (list($ActionName, $ActionID) = $xoopsDB->fetchRow($result)) {
        //檢查權限
        $the_power = $power->check_power("read", "ActionID", $ActionID);
        if (!$the_power) {
            continue;
        } else {
            break;
        }
    }

    if (empty($ActionID)) {
        $block['main_data'] = $block['ActionID'] = $block['ActionName'] = '';
        return $block;
    }
    $slide_images = "";

    if (file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/TadUpFiles2.php")) {
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/TadUpFiles2.php";
        $tad_web_action_image = new TadUpFiles2("tad_web");
    } else {
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/TadUpFiles.php";
        $tad_web_action_image = new TadUpFiles("tad_web");
    }
    $tad_web_action_image->set_dir('subdir', "/{$WebID}");
    $tad_web_action_image->set_col("ActionID", $ActionID);
    $photos = $tad_web_action_image->get_file();
    // die(var_export($photos));
    if (file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/ResponsiveSlides.php")) {
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/ResponsiveSlides.php";
        $ResponsiveSlides = new slider(120, false);
        $i                = 1;
        foreach ($photos as $pic) {
            if ($pic['description'] == $pic['original_filename']) {
                $pic['description'] = "";
            }
            $ResponsiveSlides->add_content($i, $pic['description'], '', $pic['path'], '', XOOPS_URL . "/modules/tad_web/action.php?WebID=$WebID&ActionID={$ActionID}");
            $i++;
        }

        $slide_images = $ResponsiveSlides->render();
    }

    $block['main_data']  = $slide_images;
    $block['ActionID']   = $ActionID;
    $block['ActionName'] = $ActionName;

    return $block;
}

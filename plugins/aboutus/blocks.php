<?php
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_web\WebCate;

function list_web_adm($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl;
    if (empty($WebID)) {
        return;
    }

    $sql = 'SELECT `WebOwnerUid` FROM `' . $xoopsDB->prefix('tad_web') . "` WHERE `WebID` = '$WebID'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($uid) = $xoopsDB->fetchRow($result)) {
        $admin[$uid] = $uid;
    }

    $sql = 'SELECT `uid` FROM `' . $xoopsDB->prefix('tad_web_roles') . "` WHERE `WebID` = '$WebID' and `role` = 'admin'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($uid) = $xoopsDB->fetchRow($result)) {
        $admin[$uid] = $uid;
    }

    $admin_str = implode("','", $admin);

    $sql = 'SELECT `uid`,`name`,`uname`,`email` FROM `' . $xoopsDB->prefix('users') . "` WHERE `uid` in('{$admin_str}')";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $i = 0;
    while (list($uid, $name, $uname, $email) = $xoopsDB->fetchRow($result)) {
        $admin_arr[$i]['uid'] = $uid;
        $admin_arr[$i]['name'] = $name;
        $admin_arr[$i]['uname'] = $uname;
        $admin_arr[$i]['email'] = $email;
        $i++;
    }

    $xoopsTpl->assign('admin_arr', $admin_arr);
}

//以流水號秀出某筆tad_web_mems資料內容
function list_web_student($WebID, $config = [])
{
    global $xoopsDB, $xoopsTpl, $MyWebs, $op, $TadUpFiles, $isMyWeb;
    // require_once XOOPS_ROOT_PATH . '/modules/tad_web/function.php';
    $Web = get_tad_web($WebID, true);
    // die('WebID=' . $WebID);
    $setup = get_plugin_setup_values($WebID, 'aboutus');
    // die('WebID=' . $WebID . var_export($setup));
    require_once XOOPS_ROOT_PATH . '/modules/tad_web/class/cate.php';
    $WebCate = new WebCate($WebID, 'aboutus', 'tad_web_link_mems');

    $DefCateID = get_web_config('default_class', $WebID);
    if (empty($DefCateID)) {
        $DefCateID = $WebCate->tad_web_cate_max_id();
    }

    $cate = $WebCate->get_tad_web_cate($DefCateID);
    $block['cate'] = $cate;
    $block['CateID'] = $DefCateID;

    $mode = '';

    //班級圖片
    $TadUpFiles->set_col('ClassPic', $DefCateID, 1);
    $class_pic_thumb = $TadUpFiles->get_pic_file('thumb');
    $block['class_pic_thumb'] = $class_pic_thumb;

    $ys = get_seme();
    $block['ys'] = $ys;

    $sql = 'select a.*,b.* from ' . $xoopsDB->prefix('tad_web_link_mems') . ' as a left join ' . $xoopsDB->prefix('tad_web_mems') . " as b on a.MemID=b.MemID where a.WebID ='{$WebID}' and a.MemEnable='1' and a.CateID='{$DefCateID}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $i = 0;

    $class_total = $class_boy = $class_girl = 0;

    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        foreach ($all as $k => $v) {
            $$k = $v;
            // $all_main[$i][$k] = $v;
        }

        if ('1' == $MemSex) {
            $class_boy++;
        } else {
            $class_girl++;
        }

        $class_total++;

        $i++;
    }

    $block['WebOwner'] = $Web['WebOwner'];
    $block['class_total'] = $class_total;
    $block['class_boy'] = $class_boy;
    $block['class_girl'] = $class_girl;
    $block['student_amount'] = sprintf(_MD_TCW_MEM_AMOUNT, $setup['student_title']);
    $block['teacher_name'] = sprintf(_MD_TCW_OWNER_NAME, $setup['teacher_title']);
    $block['main_data'] = true;

    return $block;
}

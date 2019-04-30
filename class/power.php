<?php
use XoopsModules\Tadtools\Utility;

/*
//起始函數
$this->power    = new power($WebID);

//權限設定
$power_form = $this->power->power_menu('read', "NewsID", $NewsID);
$xoopsTpl->assign('power_form', $power_form);
<{$power_form}>

//檢查權限（列出全部）
$power = $this->power->check_power("read", "NewsID", $NewsID);
if (!$power) {
continue;
}

//檢查權限（單一）
$power = $this->power->check_power("read", "NewsID", $NewsID);
if (!$power) {
redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
}

//儲存權限
$this->power->save_power("NewsID", $NewsID, 'read');

//刪除權限
$this->power->delete_power("NewsID", $NewsID, 'read');

//*****搜尋部份*****

//起始函數
include_once XOOPS_ROOT_PATH . "/class/power.php";
$power = new power($WebID);

$power_result = $power->check_power("read", $id_col, $myrow[$id_col]);
if (!$power_result) {
continue;
}
 */
class power
{
    public $WebID = 0;
    public $col_name;
    public $col_sn = 0;
    public $power_name = '';
    public $label = '';
    public $label_col_md = '2';
    public $menu_col_md = '4';

    public function __construct($WebID = '0')
    {
        if (!empty($WebID)) {
            $this->set_WebID($WebID);
        }

        if (!empty($col_name)) {
            $this->set_col_name($col_name);
        }
    }

    public function set_WebID($WebID = '')
    {
        $WebID = (int) $WebID;

        $this->WebID = $WebID;
    }

    public function set_col_name($col_name = '')
    {
        $this->col_name = $col_name;
    }

    public function set_col_sn($col_sn = '')
    {
        $this->col_sn = $col_sn;
    }

    public function set_power_name($power_name = '')
    {
        $this->power_name = $power_name;
    }

    public function set_col_md($label_md, $menu_md)
    {
        $this->label_col_md = $label_md;
        $this->menu_col_md = $menu_md;
    }

    //權限選單
    public function power_menu($power_name = 'read', $col_name = '', $col_sn = '')
    {
        global $xoopsDB;
        if ('read' === $power_name) {
            $label = _MD_TCW_POWER_FOR;
        }

        $power_val = empty($col_sn) ? '' : $this->get_power($power_name, $col_name, $col_sn);

        $select_users = 'users' === $power_val ? 'selected' : '';
        $select_web_users = 'web_users' === $power_val ? 'selected' : '';
        $select_web_admin = 'web_admin' === $power_val ? 'selected' : '';

        $menu = '
        <!--權限設定-->
        <div class="form-group" style="background: #FCECDB;">
            <label class="col-sm-' . $this->label_col_md . ' control-label">
              ' . $label . '
            </label>
            <div class="col-sm-' . $this->menu_col_md . '">
              <select name="' . $power_name . '" class="form-control">
                <option value="">' . _MD_TCW_POWER_FOR_ALL . '</option>
                <option value="users" ' . $select_users . '>' . _MD_TCW_POWER_FOR_USERS . '</option>
                <option value="web_users" ' . $select_web_users . '>' . _MD_TCW_POWER_FOR_WEB_USERS . '</option>
                <option value="web_admin" ' . $select_web_admin . '>' . _MD_TCW_POWER_FOR_WEB_ADMIN . '</option>
              </select>
            </div>
        </div>
        ';

        return $menu;
    }

    //新增資料到tad_web_power中
    public function save_power($col_name = '', $col_sn = '', $power_name = '', $power_val = '')
    {
        global $xoopsDB, $xoopsUser;

        $myts = \MyTextSanitizer::getInstance();
        $power_name = $myts->addSlashes($power_name);
        $power_val = empty($power_val) ? $myts->addSlashes($_REQUEST[$power_name]) : $myts->addSlashes($power_val);

        $sql = 'replace into `' . $xoopsDB->prefix('tad_web_power') . "` (
          `WebID`,
          `col_name`,
          `col_sn`,
          `power_name`,
          `power_val`
        ) values(
          '{$this->WebID}',
          '{$col_name}',
          '{$col_sn}',
          '{$power_name}',
          '{$power_val}'
        )";
        $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    //取得tad_web_power資料陣列
    public function get_power($power_name = '', $col_name = '', $col_sn = '')
    {
        global $xoopsDB;
        $sql = 'select power_val from `' . $xoopsDB->prefix('tad_web_power') . "` where `WebID` = '{$this->WebID}' and col_name='{$col_name}' and col_sn='{$col_sn}' and power_name='{$power_name}'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($power_val) = $xoopsDB->fetchRow($result);

        return $power_val;
    }

    //刪除tad_web_power某筆資料資料
    public function delete_power($col_name = '', $col_sn = '', $power_name = '')
    {
        global $xoopsDB;

        $sql = 'delete from `' . $xoopsDB->prefix('tad_web_power') . "` where `WebID` = '{$this->WebID}' and col_name='{$col_name}' and col_sn='{$col_sn}' and power_name='{$power_name}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    //檢查權限
    public function check_power($power_name = '', $col_name = '', $col_sn = '')
    {
        global $isMyWeb, $LoginWebID, $xoopsUser;
        $power = $this->get_power($power_name, $col_name, $col_sn);

        if ('users' === $power and !$xoopsUser and empty($LoginWebID)) {
            // die("沒有登入");
            return false;
        } elseif ('web_users' === $power and $LoginWebID != $this->WebID and !$isMyWeb) {
            // die("非本站使用者" . $LoginWebID . "!=" . $this->WebID);
            return false;
        } elseif ('web_admin' === $power and !$isMyWeb) {
            return false;
        }

        return true;
    }
}

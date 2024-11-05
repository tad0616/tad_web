<?php

namespace XoopsModules\Tad_web;

use XoopsModules\Tadtools\Utility;

/*
use XoopsModules\Tad_web\Power;
//起始函數
$this->Power    = new  Power($WebID);

//權限設定
$power_form = $this->Power->power_menu('read', "NewsID", $NewsID,'news');
$xoopsTpl->assign('power_form', $power_form);
<{$power_form|default:''}>

//檢查權限（列出全部）
$power = $this->Power->check_power("read", "NewsID", $NewsID,'');
if (!$power) {
continue;
}

//檢查權限（單一）
$power = $this->Power->check_power("read", "NewsID", $NewsID,'');
if (!$power) {
redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOW_READ_POWER);
}

//儲存權限
$this->Power->save_power("NewsID", $NewsID, 'read', $plugin);

//刪除權限
$this->Power->delete_power("NewsID", $NewsID, 'read', $plugin);

//*****搜尋部份*****

use XoopsModules\Tad_web\Power;
//起始函數
$power = new Power($WebID);

$power_result = $power->check_power("read", $id_col, $myrow[$id_col],'');
if (!$power_result) {
continue;
}
 */
class Power
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
        $this->Power_name = $power_name;
    }

    public function set_col_md($label_md, $menu_md)
    {
        $this->label_col_md = $label_md;
        $this->menu_col_md = $menu_md;
    }

    //權限選單
    public function power_menu($power_name = 'read', $col_name = '', $col_sn = '', $plugin = '')
    {
        global $xoopsDB;
        if ('read' === $power_name) {
            $label = _MD_TCW_POWER_FOR;
        }

        $power_val = empty($col_sn) ? '' : $this->get_power($power_name, $col_name, $col_sn, $plugin);

        $select_users = 'users' === $power_val ? 'selected' : '';
        $select_web_users = 'web_users' === $power_val ? 'selected' : '';
        $select_web_admin = 'web_admin' === $power_val ? 'selected' : '';

        $menu = '
        <!--權限設定-->
        <div class="form-group row mb-3" style="background: #FCECDB;">
            <label class="col-sm-' . $this->label_col_md . ' col-form-label text-sm-right text-sm-end control-label">
                ' . $label . '
            </label>
            <div class="col-sm-' . $this->menu_col_md . '">
                <select name="' . $power_name . '" class="form-select">
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
    public function save_power($col_name = '', $col_sn = '', $power_name = '', $power_val = '', $plugin = '')
    {
        global $xoopsDB;

        $power_val = empty($power_val) ? $_REQUEST[$power_name] : $power_val;

        $sql = 'REPLACE INTO `' . $xoopsDB->prefix('tad_web_power') . '` ( `WebID`, `col_name`, `col_sn`, `power_name`, `power_val`, `plugin` ) VALUES( ?, ?, ?, ?, ?, ? )';
        Utility::query($sql, 'isisss', [$this->WebID, $col_name, $col_sn, $power_name, $power_val, $plugin]) or Utility::web_error($sql, __FILE__, __LINE__);
        clear_power_cache($this->WebID);
    }

    //取得tad_web_power資料陣列
    public function get_power($power_name = 'read', $def_col_name = '', $def_col_sn = '', $plugin = '')
    {
        global $xoopsDB;
        $power_cache_file = XOOPS_VAR_PATH . "/tad_web/{$this->WebID}/web_power.json";
        if (\file_exists($power_cache_file)) {
            $powers = \json_decode(\file_get_contents($power_cache_file), true);
        } else {
            $sql = 'SELECT `col_name`, `col_sn`, `power_val` FROM `' . $xoopsDB->prefix('tad_web_power') . '` WHERE `WebID` =? AND `power_name` =?';
            $result = Utility::query($sql, 'is', [$this->WebID, $power_name]) or Utility::web_error($sql, __FILE__, __LINE__);

            while (list($col_name, $col_sn, $power_val) = $xoopsDB->fetchRow($result)) {
                $powers[$col_name][$col_sn] = $power_val;
            }

            \file_put_contents($power_cache_file, \json_encode($powers, 256));
        }

        if (isset($def_col_sn[$def_col_name])) {
            if (!empty($def_col_sn)) {
                return $powers[$def_col_name][$def_col_sn];
            } else {
                return $powers[$def_col_name];
            }
        }

    }

    //刪除tad_web_power某筆資料資料
    public function delete_power($col_name = '', $col_sn = '', $power_name = '', $plugin = '')
    {
        global $xoopsDB;

        $and_plugin = $plugin ? "AND `plugin`='{$plugin}'" : '';
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_web_power') . '` WHERE `WebID` = ? AND `col_name` = ? AND `col_sn` = ? AND `power_name` = ? ' . $and_plugin;
        Utility::query($sql, 'isis', [$this->WebID, $col_name, $col_sn, $power_name]) or Utility::web_error($sql, __FILE__, __LINE__);
        clear_power_cache($this->WebID);
    }

    //檢查權限
    public function check_power($power_name = '', $col_name = '', $col_sn = '', $plugin = '')
    {
        global $isMyWeb, $LoginWebID, $xoopsUser;
        // if ($col_sn == 58505) {
        //     Utility::test("$power_name, $col_name, $col_sn, $plugin", 'power', 'dd');
        // }

        $power = $this->get_power($power_name, $col_name, $col_sn, $plugin);
        // if ($col_sn == 58505) {
        //     Utility::test($power, 'power', 'dd');
        // }
        if ('users' === $power and !$xoopsUser and empty($LoginWebID)) {
            // if ($col_sn == 58505) {
            //     Utility::test('users', 'power', 'die');
            // }

            return false;
        } elseif ('web_users' === $power and $LoginWebID != $this->WebID and !$isMyWeb) {
            // if ($col_sn == 58505) {
            //     Utility::test('web_users', 'power', 'die');
            // }

            return false;
        } elseif ('web_admin' === $power and !$isMyWeb) {
            // if ($col_sn == 58505) {
            //     Utility::test('web_admin', 'power', 'die');
            // }

            return false;
        }

        return true;
    }

}

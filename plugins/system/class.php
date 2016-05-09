<?php
class tad_web_system
{

    public $WebID = 0;

    public function tad_web_system($WebID)
    {
        $this->WebID = $WebID;
    }

    //系統
    public function list_all($CateID = "", $limit = null, $mode = "assign")
    {
        global $xoopsDB, $xoopsTpl, $MyWebs;
        $main_data = "";
        $total     = "";

        if ($mode == "return") {
            $data['main_data'] = $main_data;
            $data['total']     = $total;
            return $data;
        } else {
            $xoopsTpl->assign('system_data', $main_data);
            $xoopsTpl->assign('system', get_db_plugin($this->WebID, 'system'));
            return $total;
        }
    }

    //以流水號秀出某筆tad_web_works資料內容
    public function show_one()
    {
        global $xoopsDB, $xoopsTpl, $TadUpFiles, $isMyWeb;
        return;

    }

    //tad_web_works編輯表單
    public function edit_form()
    {
        global $xoopsDB, $xoopsUser, $MyWebs, $isMyWeb, $xoopsTpl, $TadUpFiles;

        if (!$isMyWeb and $MyWebs) {
            redirect_header($_SERVER['PHP_SELF'] . "?op=WebID={$MyWebs[0]}&op=edit_form", 3, _MD_TCW_AUTO_TO_HOME);
        } elseif (!$isMyWeb and !$_SESSION['isAssistant']['system']) {
            redirect_header("index.php?WebID={$this->WebID}", 3, _MD_TCW_NOT_OWNER);
        }
        get_quota($this->WebID);

        return;

    }

    //新增資料
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles;

        return;
    }

    //更新某一筆資料
    public function update()
    {
        global $xoopsDB, $TadUpFiles;

        return;
    }

    //刪除某筆資料資料
    public function delete()
    {
        global $xoopsDB, $TadUpFiles;
        return;
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
        return;
    }

    //取得資料總數
    public function get_total()
    {
        global $xoopsDB;
        return;
    }

    //新增tad_web_works計數器
    public function add_counter()
    {
        global $xoopsDB;
        return;
    }

    //以流水號取得某筆資料
    public function get_one_data()
    {
        global $xoopsDB;
        return;
    }
}

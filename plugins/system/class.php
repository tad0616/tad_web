<?php
class tad_web_system
{
    public $WebID = 0;

    public function __construct($WebID)
    {
        $this->WebID = $WebID;
    }

    //系統
    public function list_all($CateID = '', $limit = null, $mode = 'assign')
    {
        global $xoopsDB, $xoopsTpl, $MyWebs, $isMyWeb;
        $main_data = [];
        $total = '';

        if ('return' === $mode) {
            $data['main_data'] = $main_data;
            $data['total'] = $total;

            return $data;
        }
        $xoopsTpl->assign('system_data', $main_data);
        $xoopsTpl->assign('system', get_db_plugin($this->WebID, 'system'));

        return $total;
    }

    //以流水號秀出某筆tad_web_works資料內容
    public function show_one()
    {
    }

    //tad_web_works編輯表單
    public function edit_form()
    {
        chk_self_web($this->WebID, $_SESSION['isAssistant']['system']);
        get_quota($this->WebID);
    }

    //新增資料
    public function insert()
    {
        global $xoopsDB, $xoopsUser, $TadUpFiles;
    }

    //更新某一筆資料
    public function update()
    {
        global $xoopsDB, $TadUpFiles;
    }

    //刪除某筆資料資料
    public function delete()
    {
        global $xoopsDB, $TadUpFiles;
    }

    //刪除所有資料
    public function delete_all()
    {
        global $xoopsDB, $TadUpFiles;
    }

    //取得資料總數
    public function get_total()
    {
        global $xoopsDB;
    }

    //新增tad_web_works計數器
    public function add_counter()
    {
        global $xoopsDB;
    }

    //以流水號取得某筆資料
    public function get_one_data()
    {
        global $xoopsDB;
    }
}

<?php
/*

$web_cate = new web_cate($WebID, "news","tad_web_news");

//設定「CateID」欄位預設值
$CateID    = (!isset($DBV['CateID'])) ? "" : $DBV['CateID'];
//表單
$cate_menu = $web_cate->cate_menu($CateID);
$xoopsTpl->assign('cate_menu', $cate_menu);

//儲存
$CateID = $web_cate->save_tad_web_cate($CateID, $newCateName);

//取得單一分類資料
$cate = $web_cate->get_tad_web_cate($CateID);
$xoopsTpl->assign('cate', $cate);

<ol class="breadcrumb">
<li><a href="news.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_NEWS}></a> <span class="divider">/</span></li>
<{if $cate.CateID}><li><a href="news.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a> <span class="divider">/</span></li><{/if}>
<li><{$NewsInfo}></li>
</ol>

//取得tad_web_cate所有資料陣列
$web_cate = new web_cate($WebID, "news","tad_web_news");
$web_cate->set_WebID($WebID);
$cate = $web_cate->get_tad_web_cate_arr();

<{if $news.cate.CateID}>
<span class="label label-info"><a href="news.php?WebID=<{$news.WebID}>&CateID=<{$news.cate.CateID}>" style="color: #FFFFFF;"><{$news.cate.CateName}></a></span>
<{/if}>
 */

class web_cate
{
    public $WebID = 0;
    public $ColName;
    public $ColSN    = 0;
    public $Tbl      = 0;
    public $demo_txt = '';

    public function web_cate($WebID = "0", $ColName = "", $Tbl = "")
    {
        global $xoopsDB;
        if (!empty($WebID)) {
            $this->set_WebID($WebID);
        }

        if (!empty($ColName)) {
            $this->set_ColName($ColName);
        }

        if (!empty($Tbl)) {
            $this->set_Tbl($Tbl);
        }

    }

    public function set_WebID($WebID = "")
    {
        $WebID = intval($WebID);

        $this->WebID = $WebID;
    }

    public function set_ColName($ColName = "")
    {
        $this->ColName = $ColName;
    }

    public function set_Tbl($Tbl = "")
    {
        $this->Tbl = $Tbl;
    }

    public function set_demo_txt($demo_txt = "")
    {
        $this->demo_txt = $demo_txt;
    }

    //分類選單
    public function cate_menu($defCateID = "", $mode = "form", $newCate = true, $change_page = false, $show_label = true, $show_tools = false, $show_select = true)
    {
        global $xoopsDB;

        if (empty($this->WebID)) {
            return;
        }

        $option = "";
        $sql    = "select * from `" . $xoopsDB->prefix("tad_web_cate") . "` where `WebID` = '{$this->WebID}' and `ColName`='{$this->ColName}' and `CateEnable`='1' order by CateSort";
        //echo $sql . '<hr>';
        $result = $xoopsDB->query($sql) or web_error($sql);
        while ($data = $xoopsDB->fetchArray($result)) {
            foreach ($data as $k => $v) {
                $$k = $v;
            }
            $selected = ($defCateID == $CateID) ? "selected" : "";
            $option .= "<option value='{$CateID}' $selected>{$CateName}</option>";
        }

        $row          = $_SESSION['bootstrap'] == '3' ? 'row' : 'row-fluid';
        $span         = $_SESSION['bootstrap'] == '3' ? 'col-md-' : 'span';
        $form_group   = $_SESSION['bootstrap'] == '3' ? 'form-group' : 'control-group';
        $form_control = $_SESSION['bootstrap'] == '3' ? 'form-control' : 'span12';

        $tools = $show_tools ? "<div class=\"{$span}2\"><a href='cate.php?WebID={$this->WebID}&ColName={$this->ColName}' class='btn btn-warning' >" . _MD_TCW_CATE_TOOLS . "</a></div>" : "";

        if ($option and $show_select) {
            $cate_menu = "
            <div class=\"{$span}3\">
              <select name='CateID' id='CateID' class='{$form_control}'>
                <option value=''>" . _MD_TCW_SELECT_CATE . "</option>
                {$option}
              </select>
            </div>
            ";
        } else {
            $cate_menu = "";
        }

        $demo_txt = "";
        if (!empty($this->demo_txt)) {
            $demo_txt = ", {$this->demo_txt}";
        }

        if ($newCate) {
            $new_input = "
            <div class=\"{$span}7\" id=\"newCate\">
              <input type='text' name='newCateName' placeholder='" . _MD_TCW_NEW_CATE . " {$demo_txt}' class='{$form_control}'>
            </div>";
        } else {
            $new_input = "";
        }

        $label_title = ($show_select) ? _MD_TCW_SELECT_CATE : _MD_TCW_NEW_CATE;

        $label = $show_label ? "<label class=\"{$span}2 control-label\">
          {$label_title}
          </label>" : "";

        $row = ($mode == "form") ? $form_group : $row;

        $change_page_js = $change_page ? "location.href='{$_SERVER['PHP_SELF']}?WebID={$this->WebID}&CateID=' + $('#CateID').val();" : "";

        $newCate_js = ($mode == "form") ? "if(\$('#CateID').val()==''){\$('#newCate').show(); }else{ \$('#newCate').hide();}" : "";

        $hide_newCate_js = empty($defCateID) ? '' : "\$('#newCate').hide();";

        $menu = "
        <script>
        $(function() {
            {$hide_newCate_js}
            $('#CateID').change(function(){
                {$change_page_js}
                {$newCate_js}
            });
        });
        </script>
        <div class=\"{$row}\" style=\"margin-bottom: 10px;\">
          $label
          $cate_menu
          $new_input
          $tools
        </div>
        ";
        return $menu;
    }

    //新增資料到tad_web_cate中
    public function save_tad_web_cate($CateID = "", $newCateName = "")
    {
        global $xoopsDB, $xoopsUser;
        if (empty($newCateName)) {
            return $_POST['CateID'];
        }

        $myts     = MyTextSanitizer::getInstance();
        $CateName = $myts->addSlashes($_POST['newCateName']);
        $CateSort = $this->tad_web_cate_max_sort();

        $sql = "insert into `" . $xoopsDB->prefix("tad_web_cate") . "` (
          `WebID`,
          `CateName`,
          `ColName`,
          `ColSN`,
          `CateSort`,
          `CateEnable`,
          `CateCounter`
        ) values(
          '{$this->WebID}',
          '{$CateName}',
          '{$this->ColName}',
          '{$this->ColSN}',
          '{$CateSort}',
          '1',
          0
        )";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $CateID = $xoopsDB->getInsertId();

        return $CateID;
    }

    //自動取得tad_web_cate的最新排序
    public function tad_web_cate_max_sort()
    {
        global $xoopsDB;
        $sql    = "select max(`CateSort`) from `" . $xoopsDB->prefix("tad_web_cate") . "` where WebID='{$this->WebID}' and  ColName='{$this->ColName}' and ColSN='{$this->ColSN}'";
        $result = $xoopsDB->query($sql)
        or web_error($sql);
        list($sort) = $xoopsDB->fetchRow($result);
        return ++$sort;
    }

    //更新tad_web_cate某一筆資料
    public function update_tad_web_cate($CateID = '', $newCateName = '')
    {
        global $xoopsDB, $isAdmin, $xoopsUser;

        $myts     = MyTextSanitizer::getInstance();
        $CateName = $myts->addSlashes($newCateName);

        $sql = "update `" . $xoopsDB->prefix("tad_web_cate") . "` set
       `CateName` = '{$CateName}' where `CateID`='{$CateID}'";
        $xoopsDB->queryF($sql) or web_error($sql);

        return $CateID;
    }

    //取得tad_web_cate資料陣列
    public function get_tad_web_cate($CateID = "")
    {
        global $xoopsDB;
        if (empty($CateID)) {
            return;
        }
        $sql    = "select * from `" . $xoopsDB->prefix("tad_web_cate") . "` where `CateID` = '{$CateID}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }

    //取得tad_web_cate所有資料陣列
    public function get_tad_web_cate_arr()
    {
        global $xoopsDB;
        $counter    = $this->tad_web_cate_data_counter();
        $arr        = "";
        $andWebID   = empty($this->WebID) ? '' : "and `WebID` = '{$this->WebID}'";
        $andColName = empty($this->ColName) ? '' : "and `ColName`='{$this->ColName}'";
        $sql        = "select * from `" . $xoopsDB->prefix("tad_web_cate") . "` where 1 $andWebID $andColName order by CateSort";
        // echo $sql . '<br>';
        $result = $xoopsDB->query($sql) or web_error($sql);
        while ($data = $xoopsDB->fetchArray($result)) {
            $CateID          = $data['CateID'];
            $data['counter'] = isset($counter[$CateID]) ? $counter[$CateID] : 0;
            $arr[$CateID]    = $data;
        }
        return $arr;
    }

    //搬移tad_web_cate某筆資料資料
    public function move_tad_web_cate($CateID = '', $move2CateID = 0)
    {
        global $xoopsDB;

        if (empty($CateID)) {
            return;
        }

        if (empty($this->Tbl)) {
            $table = "tad_web_{$this->ColName}";
        } else {
            $table = $this->Tbl;
        }

        $sql = "update `" . $xoopsDB->prefix($table) . "` set
       `CateID` = '{$move2CateID}' where `CateID`='{$CateID}'";
        $xoopsDB->queryF($sql) or web_error($sql);

    }

    //刪除tad_web_cate某筆資料資料
    public function delete_tad_web_cate($CateID = '', $move2CateID = 0)
    {
        global $xoopsDB;

        if (empty($CateID)) {
            return;
        }

        if (!empty($move2CateID)) {
            $this->move_tad_web_cate($CateID, $move2CateID);
        } else {
            $this->delete_tad_web_cate_data($CateID);
        }

        $sql = "delete from `" . $xoopsDB->prefix("tad_web_cate") . "`
        where `CateID` = '{$CateID}'";
        $xoopsDB->queryF($sql) or web_error($sql);

    }

    //刪除tad_web_cate某筆資料資料
    public function delete_tad_web_cate_data($CateID = '')
    {
        global $xoopsDB;

        if (empty($CateID)) {
            return;
        }

        if (empty($this->Tbl)) {
            $table = "tad_web_{$this->ColName}";
        } else {
            $table = $this->Tbl;
        }

        $sql = "delete from `" . $xoopsDB->prefix($table) . "`
        where `CateID` = '{$CateID}'";
        $xoopsDB->queryF($sql) or web_error($sql);

    }

    //取得各分類下的檔案數
    public function tad_web_cate_data_counter()
    {
        global $xoopsDB;

        if (empty($this->Tbl)) {
            $table = "tad_web_{$this->ColName}";
        } else {
            $table = $this->Tbl;
        }
        $counter = "";
        $sql     = "select count(*),CateID from `" . $xoopsDB->prefix($table) . "` where `WebID` = '{$this->WebID}' group by CateID";
        $result  = $xoopsDB->query($sql) or web_error($sql);
        while (list($count, $CateID) = $xoopsDB->fetchRow($result)) {
            $counter[$CateID] = $count;
        }
        return $counter;
    }

}

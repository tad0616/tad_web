<?php
/*
//起始函數
$this->tags    = new tags($WebID);

//外掛頁面

$tag  = system_CleanVars($_REQUEST, 'tag', '', 'string');

$tad_web_news->list_all($CateID, null, null, $tag);

//list_all 列出全部
list_all($CateID = "", $limit = null, $mode = "assign", $tag = '')

} elseif (!empty($tag)) {
$sql = "select distinct a.* from " . $xoopsDB->prefix("tad_web_news") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID join " . $xoopsDB->prefix("tad_web_tags") . " as c on c.col_name='NewsID' and c.col_sn=a.NewsID where b.`WebEnable`='1' and c.`tag_name`='{$tag}' $andWebID $andCateID order by a.NewsID desc";

//show_one 取得標籤
$xoopsTpl->assign("tags", $this->tags->list_tags("NewsID", $NewsID, 'news'));
<{if $tags}><li><{$tags}></li><{/if}>

//edit_form 標籤設定
$tags_form = $this->tags->tags_menu("NewsID", $NewsID);
$xoopsTpl->assign('tags_form', $tags_form);
<{$tags_form}>

//儲存標籤
$this->tags->save_tags("NewsID", $NewsID, $_POST['tag_name'],$_POST['tags']);

//刪除標籤
$this->tags->delete_tags("NewsID", $NewsID, $tag_name);

 */
class tags
{
    public $WebID = 0;
    public $col_name;
    public $col_sn       = 0;
    public $label        = '';
    public $label_col_md = '2';
    public $menu_col_md  = '10';

    public function __construct($WebID = "0")
    {
        if (!empty($WebID)) {
            $this->set_WebID($WebID);
        }

        if (!empty($col_name)) {
            $this->set_col_name($col_name);
        }
    }

    public function set_WebID($WebID = "")
    {
        $WebID = intval($WebID);

        $this->WebID = $WebID;
    }

    public function set_col_name($col_name = "")
    {
        $this->col_name = $col_name;
    }

    public function set_col_sn($col_sn = "")
    {
        $this->col_sn = $col_sn;
    }

    public function set_col_md($label_md, $menu_md)
    {
        $this->label_col_md = $label_md;
        $this->menu_col_md  = $menu_md;
    }

    //標籤選單
    public function tags_menu($col_name = "", $col_sn = "")
    {
        global $xoopsDB;

        $tag_name       = '';
        $plugin_tag_arr = $this->get_tags($col_name, $col_sn);
        $tag_arr        = array_keys($plugin_tag_arr);
        if (!empty($col_sn)) {
            $tag_name = implode(',', $tag_arr);
        }

        $tags_select = '';
        $tag_all_arr = $this->get_tags();
        foreach ($tag_all_arr as $tag => $count) {
            $checked = (in_array($tag, $tag_arr) and !empty($tag_name)) ? 'checked' : '';
            $tags_select .= "
            <label class='checkbox-inline'>
                <input type='checkbox' name='tags[]' value='{$tag}' {$checked}>{$tag} <span class='badge'>{$count}</span>
            </label>";
        }

        $menu = '
        <!--標籤設定-->
        <div class="form-group">
            <label class="col-md-' . $this->label_col_md . ' control-label">
              ' . _MD_TCW_TAGS . '
            </label>
            <div class="col-md-' . $this->menu_col_md . '">
                <input type="text" name="tag_name" class="form-control" placeholder="' . _MD_TCW_INPUT_TAGS . '">
            </div>
        </div>
        ';

        if ($tags_select) {
            $menu .= '
            <div class="form-group">
                <label class="col-md-' . $this->label_col_md . '">
                </label>
                <div class="col-md-' . $this->menu_col_md . '">
                    ' . $tags_select . '
                </div>
            </div>
            ';
        }
        return $menu;
    }

    //新增資料到tad_web_tags中
    public function save_tags($col_name = "", $col_sn = "", $tag_name = "", $tags = array())
    {
        global $xoopsDB, $xoopsUser;

        $myts = MyTextSanitizer::getInstance();
        $sql  = "delete from `" . $xoopsDB->prefix("tad_web_tags") . "` where `WebID`='{$this->WebID}' and `col_name`='{$col_name}' and `col_sn`='{$col_sn}'";
        $xoopsDB->queryF($sql) or web_error($sql);
        if ($tags) {
            foreach ($tags as $tag) {
                $tag = trim($tag);
                $tag = $myts->addSlashes($tag);
                if (empty($tag)) {
                    continue;
                }
                $sql = "insert into `" . $xoopsDB->prefix("tad_web_tags") . "` (
                  `WebID`,
                  `col_name`,
                  `col_sn`,
                  `tag_name`
                ) values(
                  '{$this->WebID}',
                  '{$col_name}',
                  '{$col_sn}',
                  '{$tag}'
                )";
                $xoopsDB->query($sql) or web_error($sql);
            }
        }

        $tags = explode(',', $tag_name);
        foreach ($tags as $tag) {
            $tag = trim($tag);
            $tag = $myts->addSlashes($tag);
            if (empty($tag)) {
                continue;
            }
            $sql = "replace into `" . $xoopsDB->prefix("tad_web_tags") . "` (
              `WebID`,
              `col_name`,
              `col_sn`,
              `tag_name`
            ) values(
              '{$this->WebID}',
              '{$col_name}',
              '{$col_sn}',
              '{$tag}'
            )";
            $xoopsDB->query($sql) or web_error($sql);
        }

    }

    //取得tad_web_tags資料陣列
    public function list_tags($col_name = "", $col_sn = "", $plugin = "")
    {
        global $xoopsDB;
        $tags_arr  = $this->get_tags($col_name, $col_sn);
        $list_tags = '';
        foreach ($tags_arr as $tag => $count) {
            // $tags_link[] = "<a href='{$plugin}.php?WebID={$this->WebID}&tag={$tag}'>{$tag}</a>";
            $tags_link[] = "<a href='tag.php?WebID={$this->WebID}&tag={$tag}'>{$tag}</a>";
        }
        $list_tags = implode(' , ', $tags_link);
        return $list_tags;
    }

    //取得tad_web_tags資料陣列
    public function get_tags($col_name = "", $col_sn = "")
    {
        global $xoopsDB;
        $tags_arr     = array();
        $and_col_name = empty($col_name) ? '' : "and `col_name`='{$col_name}'";
        $and_col_sn   = empty($col_sn) ? '' : "and `col_sn`='{$col_sn}'";
        $sql          = "select tag_name , count(*) from `" . $xoopsDB->prefix("tad_web_tags") . "` where `WebID` = '{$this->WebID}' {$and_col_name} {$and_col_sn}  group by tag_name";

        $result = $xoopsDB->query($sql) or web_error($sql);
        while (list($tag_name, $count) = $xoopsDB->fetchRow($result)) {
            $tags_arr[$tag_name] = $count;
        }

        return $tags_arr;
    }

    //刪除tad_web_tags某筆資料資料
    public function delete_tags($col_name = "", $col_sn = "", $tag_name = "")
    {
        global $xoopsDB;
        $and_tag_name = empty($tag_name) ? '' : "and `tag_name`='{$tag_name}'";

        $sql = "delete from `" . $xoopsDB->prefix("tad_web_tags") . "` where `WebID` = '{$this->WebID}' and col_name='{$col_name}' and col_sn='{$col_sn}' {$and_tag_name}";
        $xoopsDB->queryF($sql) or web_error($sql);

    }

}

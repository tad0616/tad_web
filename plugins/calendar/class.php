<?php
class tad_web_calendar
{

    public $WebID = 0;

    public function tad_web_calendar($WebID)
    {
        $this->WebID = $WebID;
    }

    public function list_all($CateID = "", $limit = null)
    {
        global $xoopsTpl;
        $xoopsTpl->assign('calendar', get_db_plugin($this->WebID, 'calendar'));
    }

}

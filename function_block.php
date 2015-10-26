<?php
//是否為網站擁有者
if (!function_exists('MyWebID')) {
    function MyWebID()
    {
        global $xoopsUser, $xoopsDB;
        if ($xoopsUser) {
            $uid    = $xoopsUser->uid();
            $sql    = "select WebID from " . $xoopsDB->prefix("tad_web") . " where WebOwnerUid='$uid'";
            $result = $xoopsDB->query($sql) or web_error($sql);
            $total  = $xoopsDB->getRowsNum($result);
            if (empty($total)) {
                return;
            }

            while (list($WebID) = $xoopsDB->fetchRow($result)) {
                $MyWebs[$WebID] = $WebID;
            }

            $sql    = "select WebID from " . $xoopsDB->prefix("tad_web_roles") . " where uid='$uid'";
            $result = $xoopsDB->query($sql) or web_error($sql);
            while (list($WebID) = $xoopsDB->fetchRow($result)) {
                $MyWebs[$WebID] = $WebID;
            }
        }
        return $MyWebs;
    }
}

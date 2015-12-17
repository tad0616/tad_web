<?php
//是否為網站擁有者
if (!function_exists('MyWebID')) {
    function MyWebID()
    {
        global $xoopsUser, $xoopsDB;
        $MyWebs = array();
        if ($xoopsUser) {
            $uid    = $xoopsUser->uid();
            $sql    = "select WebID from " . $xoopsDB->prefix("tad_web") . " where WebOwnerUid='$uid' and `WebEnable`='1'";
            $result = $xoopsDB->query($sql) or web_error($sql);

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

//取得網站設定值
if (!function_exists('get_web_config')) {
    function get_web_config($ConfigName = null, $defWebID = null)
    {
        global $xoopsDB;

        $andWebID = is_null($defWebID) ? "" : "and `WebID`='$defWebID'";

        $sql = "select `ConfigValue`,`WebID` from " . $xoopsDB->prefix("tad_web_config") . " where `ConfigName`='{$ConfigName}' $andWebID ";
        //die($sql);
        $result = $xoopsDB->queryF($sql) or web_error($sql);

        $ConfigValue = "";
        if (!is_null($defWebID)) {
            if ($xoopsDB->getRowsNum($result)) {
                list($ConfigValue, $WebID) = $xoopsDB->fetchRow($result);
            }

        } else {
            while (list($Value, $WebID) = $xoopsDB->fetchRow($result)) {
                $ConfigValue[$WebID] = $Value;
            }
        }
        return $ConfigValue;

    }
}

<?php
//是否為網站擁有者
if (!function_exists('MyWebID')) {
    function MyWebID($WebEnable = '1')
    {
        global $xoopsUser, $xoopsDB;
        $MyWebs = array();
        if ($xoopsUser) {
            $uid          = $xoopsUser->uid();
            $andWebEnable = $WebEnable == 'all' ? "" : "and `WebEnable`='{$WebEnable}'";
            $sql          = "select WebID from " . $xoopsDB->prefix("tad_web") . " where WebOwnerUid='$uid' {$andWebEnable}";
            $result       = $xoopsDB->query($sql) or web_error($sql);

            while (list($WebID) = $xoopsDB->fetchRow($result)) {
                $MyWebs[$WebID] = $WebID;
            }

            $sql    = "select a.WebID from " . $xoopsDB->prefix("tad_web_roles") . " as a left join " . $xoopsDB->prefix("tad_web") . " as b on a.WebID=b.WebID where a.uid='$uid' and b.WebEnable='{$WebEnable}'";
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

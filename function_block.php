<?php
use XoopsModules\Tadtools\Utility;

//是否為網站擁有者
if (!function_exists('MyWebID')) {
    function MyWebID($WebEnable = '1')
    {
        global $xoopsUser, $xoopsDB;
        $MyWebs = [];
        if ($xoopsUser) {
            $uid = $xoopsUser->uid();
            $andWebEnable = 'all' === $WebEnable ? '' : "and `WebEnable`='{$WebEnable}'";
            $sql = 'select WebID from ' . $xoopsDB->prefix('tad_web') . " where WebOwnerUid='$uid' {$andWebEnable}";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            while (list($WebID) = $xoopsDB->fetchRow($result)) {
                $MyWebs[$WebID] = (int) $WebID;
            }

            $andWebEnable = 'all' === $WebEnable ? '' : "and b.`WebEnable`='{$WebEnable}'";
            $sql = 'select a.WebID from ' . $xoopsDB->prefix('tad_web_roles') . ' as a left join ' . $xoopsDB->prefix('tad_web') . " as b on a.WebID=b.WebID where a.uid='$uid' {$andWebEnable}";
            $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            while (list($WebID) = $xoopsDB->fetchRow($result)) {
                $MyWebs[$WebID] = (int) $WebID;
            }
        }

        return $MyWebs;
    }
}

//取得網站設定值
if (!function_exists('get_web_config')) {
    function get_web_config($ConfigName = null, $defWebID = null, $form = 'file')
    {
        global $xoopsDB;
        $andWebID = '';
        $ConfigValues = [];
        if (null !== $defWebID) {
            if ('file' !== $form) {
                $sql = 'select `ConfigValue` from ' . $xoopsDB->prefix('tad_web_config') . " where `ConfigName`='$ConfigName' and WebID='$defWebID'";
                $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                list($ConfigValue) = $xoopsDB->fetchRow($result);

                return $ConfigValues;
            }
            $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$defWebID}/web_config.php";
            // unlink($file);
            if (file_exists($file)) {
                require $file;
            } else {
                $content = "<?php\n";
                $sql = 'select `ConfigName`,`ConfigValue` from ' . $xoopsDB->prefix('tad_web_config') . " where `WebID`='$defWebID' ";
                $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                while (list($ConfigName, $ConfigValue) = $xoopsDB->fetchRow($result)) {
                    $web_config[$ConfigName] = $ConfigValue;
                    $content .= "\$web_config['$ConfigName'] = '$ConfigValue';\n";
                }
                Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$defWebID}/");
                file_put_contents($file, $content);
            }
            // die(var_export($web_config));
            if (isset($web_config[$ConfigName])) {
                return $web_config[$ConfigName];
            }
        } else {
            $sql = 'select `WebID`,`ConfigValue` from ' . $xoopsDB->prefix('tad_web_config') . " where `ConfigName`='$ConfigName' ";
            $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

            while (list($WebID, $ConfigValue) = $xoopsDB->fetchRow($result)) {
                $ConfigValues[$WebID] = $ConfigValue;
            }

            return $ConfigValues;
        }
    }
}

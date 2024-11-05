<?php

namespace XoopsModules\Tad_web;

use XoopsModules\Tadtools\Utility;

class Tools
{

    public static function get_web_config($ConfigName = null, $defWebID = null, $from = 'file')
    {
        global $xoopsDB;
        $ConfigValues = [];
        if (null !== $defWebID) {
            if ('file' !== $from) {
                $sql = 'SELECT `ConfigValue` FROM `' . $xoopsDB->prefix('tad_web_config') . '` WHERE `ConfigName` =? AND `WebID` =?';
                $result = Utility::query($sql, 'si', [$ConfigName, $defWebID]) or Utility::web_error($sql, __FILE__, __LINE__);
                list($ConfigValue) = $xoopsDB->fetchRow($result);
                return $ConfigValue;
            }

            $file = XOOPS_ROOT_PATH . "/uploads/tad_web/{$defWebID}/web_config.php";
            if (file_exists($file)) {
                require $file;
            } else {
                $content = "<?php\n";
                $sql = 'SELECT `ConfigName`,`ConfigValue` FROM `' . $xoopsDB->prefix('tad_web_config') . '` WHERE `WebID`=?';
                $result = Utility::query($sql, 'i', [$defWebID]) or Utility::web_error($sql, __FILE__, __LINE__);
                while (list($ConfigName, $ConfigValue) = $xoopsDB->fetchRow($result)) {
                    $web_config[$ConfigName] = $ConfigValue;
                    $content .= "\$web_config['$ConfigName'] = '$ConfigValue';\n";
                }
                Utility::mk_dir(XOOPS_ROOT_PATH . "/uploads/tad_web/{$defWebID}/");
                file_put_contents($file, $content);
            }

            if (isset($web_config[$ConfigName])) {
                return $web_config[$ConfigName];
            }
        } else {
            $sql = 'SELECT `WebID`,`ConfigValue` FROM `' . $xoopsDB->prefix('tad_web_config') . '` WHERE `ConfigName`=?';
            $result = Utility::query($sql, 's', [$ConfigName]) or Utility::web_error($sql, __FILE__, __LINE__);

            while (list($WebID, $ConfigValue) = $xoopsDB->fetchRow($result)) {
                $ConfigValues[$WebID] = $ConfigValue;
            }

            return $ConfigValues;
        }
    }

    public static function MyWebID($WebEnable = '1')
    {
        global $xoopsUser, $xoopsDB;
        $MyWebs = [];
        if ($xoopsUser) {
            if (!isset($_SESSION['MyWebs'][$WebEnable])) {
                $uid = $xoopsUser->uid();
                $andWebEnable = 'all' === $WebEnable ? '' : "AND `WebEnable`='{$WebEnable}'";
                $sql = 'SELECT `WebID` FROM `' . $xoopsDB->prefix('tad_web') . '` WHERE `WebOwnerUid` =? ' . $andWebEnable;
                $result = Utility::query($sql, 'i', [$uid]) or Utility::web_error($sql, __FILE__, __LINE__);

                while (list($WebID) = $xoopsDB->fetchRow($result)) {
                    $MyWebs[$WebID] = (int) $WebID;
                }

                $andWebEnable = 'all' === $WebEnable ? '' : "and b.`WebEnable`='{$WebEnable}'";
                $sql = 'SELECT a.`WebID` FROM `' . $xoopsDB->prefix('tad_web_roles') . '` AS a LEFT JOIN `' . $xoopsDB->prefix('tad_web') . '` AS b ON a.`WebID`=b.`WebID` WHERE a.`uid`=? ' . $andWebEnable;
                $result = Utility::query($sql, 'i', [$uid]) or Utility::web_error($sql, __FILE__, __LINE__);
                while (list($WebID) = $xoopsDB->fetchRow($result)) {
                    $MyWebs[$WebID] = (int) $WebID;
                }
                $_SESSION['MyWebs'][$WebEnable] = $MyWebs;
            }
            return $_SESSION['MyWebs'][$WebEnable];
        }
        return $MyWebs;
    }

}

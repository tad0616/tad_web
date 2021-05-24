<?php
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$sql = 'SELECT ConfigName,ConfigValue,WebID FROM  ' . $xoopsDB->prefix('tad_web_config') . " where ConfigName='login_config'";
$result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
$main = '';
while (list($ConfigName, $ConfigValue, $WebID) = $xoopsDB->fetchRow($result)) {
    if (strpos($ConfigValue, 'hcc') !== false) {
        if (strpos($ConfigValue, 'hcc_oidc') === false) {
            $ConfigValue = str_replace('hcc', 'hcc_oidc', $ConfigValue);
        } else {
            $ConfigValue = str_replace('hcc;', '', $ConfigValue);
        }
        $sql = "update " . $xoopsDB->prefix('tad_web_config') . " set ConfigValue='$ConfigValue' where ConfigName='login_config' and WebID='$WebID'";
        // $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        echo $sql . '<br>';
        clear_tad_web_config($WebID);
    }
}
echo Utility::html5($main);

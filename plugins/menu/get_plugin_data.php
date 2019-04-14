<?php
include_once '../../../../mainfile.php';
include_once '../../function.php';

include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$ColName = system_CleanVars($_REQUEST, 'ColName', '', 'string');
$WebID = system_CleanVars($_REQUEST, 'WebID', 0, 'int');
$CateID = system_CleanVars($_REQUEST, 'CateID', 0, 'int');
$dirname = system_CleanVars($_REQUEST, 'dirname', '', 'string');

if ('PluginContent' === $op) {
    $plugin_name = "tad_web_{$dirname}";
    include_once XOOPS_ROOT_PATH . "/modules/tad_web/plugins/{$dirname}/class.php";
    $$plugin_name = new $plugin_name($WebID);

    $data = $$plugin_name->list_all($CateID, '', 'return', '');

    foreach ($data['main_data'] as $key => $d) {
        if ($d['id_name']) {
            echo "<option value='{$d['id_name']}={$d['id']}'>{$d['title']}</option>\n";
        }
    }
} else {
    $sql = 'select CateID, CateName from ' . $xoopsDB->prefix('tad_web_cate') . " where `CateEnable`='1' and `ColName`='{$ColName}' and `WebID`='{$WebID}' order by CateSort";
    $result = $xoopsDB->queryF($sql) or die("<option value=''>$sql</option>");
    while (list($CateID, $CateName) = $xoopsDB->fetchRow($result)) {
        echo "<option value='$CateID'>$CateName</option>\n";
    }
}

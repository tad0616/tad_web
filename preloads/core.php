<?php
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class to allow <{if $homepage}>Your code here<{/if}> in templates
 * @author trabis
 */
class Tad_WebCorePreload extends XoopsPreloadItem
{
    public function eventCoreHeaderStart($args)
    {
        global $xoopsDB;

        $sql = "select `tt_bootstrap_color` from `" . $xoopsDB->prefix("tadtools_setup") . "`  where `tt_theme`='{$GLOBALS['xoopsConfig']['theme_set']}'";

        $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

        list($tt_bootstrap_color) = $xoopsDB->fetchRow($result);
        if ($_GET['WebID']) {
            $_SESSION['bootstrap']     = 3;
            $_SESSION['web_bootstrap'] = 3;
        } else {
            $_SESSION['bootstrap']     = 2;
            $_SESSION['web_bootstrap'] = 2;
            if ($tt_bootstrap_color == "bootstrap3") {
                $_SESSION['bootstrap']     = 3;
                $_SESSION['web_bootstrap'] = 3;
            } else {
                $c = explode('/', $tt_bootstrap_color);
                if ($c[0] == "bootstrap3") {
                    $_SESSION['bootstrap']     = 3;
                    $_SESSION['web_bootstrap'] = 3;
                }
            }
        }

        if ($_REQUEST['WebID'] and strpos($_SERVER['PHP_SELF'], "modules/tad_web") !== false and strpos($_SERVER['REQUEST_URI'], "?WebID=") !== false) {

            $GLOBALS['xoopsConfig']['theme_set_allowed'][] = "for_tad_web_theme";
            $_REQUEST['xoops_theme_select']                = "for_tad_web_theme";
        } else {
            $_REQUEST['xoops_theme_select'] = $GLOBALS['xoopsConfig']['theme_set'];
        }
    }
}

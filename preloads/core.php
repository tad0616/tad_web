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
        include_once XOOPS_ROOT_PATH . "/modules/tad_web/function_block.php";
        $WebID = isset($_REQUEST['WebID']) ? intval($_REQUEST['WebID']) : '';

        $sql = "select `tt_bootstrap_color` from `" . $xoopsDB->prefix("tadtools_setup") . "`  where `tt_theme`='{$GLOBALS['xoopsConfig']['theme_set']}'";

        $result = $xoopsDB->query($sql) or web_error($sql);

        list($tt_bootstrap_color) = $xoopsDB->fetchRow($result);
        if (isset($_GET['WebID']) and !empty($_GET['WebID'])) {
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

        if (!empty($WebID) and strpos($_SERVER['PHP_SELF'], "modules/tad_web") !== false and strpos($_SERVER['REQUEST_URI'], "?WebID=") !== false) {
            $defalut_theme = get_web_config('defalut_theme', $WebID);
            if (empty($defalut_theme)) {
                $defalut_theme = 'for_tad_web_theme';
            }

            $GLOBALS['xoopsConfig']['theme_set_allowed'][] = $defalut_theme;
            $_REQUEST['xoops_theme_select']                = $defalut_theme;
        } else {
            $_REQUEST['xoops_theme_select'] = $GLOBALS['xoopsConfig']['theme_set'];
        }
    }
}

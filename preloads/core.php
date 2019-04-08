<?php
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class to allow <{if $homepage}>Your code here<{/if}> in templates
 * @author trabis
 */
class Tad_WebCorePreload extends XoopsPreloadItem
{
    public static function eventCoreHeaderStart($args)
    {
        global $xoopsDB;
        include_once XOOPS_ROOT_PATH . "/modules/tad_web/function_block.php";
        $WebID = isset($_REQUEST['WebID']) ? (int)$_REQUEST['WebID'] : '';

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

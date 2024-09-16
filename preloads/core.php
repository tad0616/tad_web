<?php

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class to allow <{if $homepage|default:false}>Your code here<{/if}> in templates
 * @author trabis
 */
class Tad_WebCorePreload extends XoopsPreloadItem
{
    public static function eventCoreHeaderStart($args)
    {
        require_once XOOPS_ROOT_PATH . '/modules/tad_web/function_block.php';
        $WebID = isset($_REQUEST['WebID']) ? (int) $_REQUEST['WebID'] : 0;

        if (!empty($WebID) and false !== mb_strpos($_SERVER['PHP_SELF'], 'modules/tad_web') and false !== mb_strpos($_SERVER['REQUEST_URI'], '?WebID=')) {
            $defalut_theme = get_web_config('defalut_theme', $WebID, '');
            if (empty($defalut_theme)) {
                $defalut_theme = 'for_tad_web_theme';
            }
            $GLOBALS['xoopsConfig']['theme_set_allowed'][] = $defalut_theme;
            $_REQUEST['xoops_theme_select'] = $defalut_theme;
        } else {
            $_REQUEST['xoops_theme_select'] = $GLOBALS['xoopsConfig']['theme_set'];
        }
    }

    // to add PSR-4 autoloader

    /**
     * @param $args
     */
    public static function eventCoreIncludeCommonEnd($args)
    {
        require __DIR__ . '/autoloader.php';
    }
}

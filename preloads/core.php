<?php
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class to allow <{if $homepage}>Your code here<{/if}> in templates
 * @author trabis
 */
class Tad_WebCorePreload extends XoopsPreloadItem
{
    function eventCoreHeaderStart($args)
    {
      if($_REQUEST['WebID'] and strpos($_SERVER['PHP_SELF'], "modules/tad_web")!==false and strpos($_SERVER['REQUEST_URI'], "?WebID=")!==false){

        $GLOBALS['xoopsConfig']['theme_set_allowed'][] = "tad_web_theme1";
        $_REQUEST['xoops_theme_select'] = "tad_web_theme1";
      }else{
        $_REQUEST['xoops_theme_select'] = $GLOBALS['xoopsConfig']['theme_set'];
      }
    }
}
?>
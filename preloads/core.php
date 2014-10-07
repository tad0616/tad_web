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
<<<<<<< HEAD

      if(strpos($_SERVER['PHP_SELF'], "modules/tad_web")!==false and strpos($_SERVER['REQUEST_URI'], "?WebID=")!==false){
=======
      if(strpos($_SERVER['PHP_SELF'], "modules/tad_web")!==false){
>>>>>>> 92b005c8e45495e772daa96fb67faed2bcdd7024
        $GLOBALS['xoopsConfig']['theme_set_allowed'][] = "blank_theme";
        $_REQUEST['xoops_theme_select'] = "blank_theme";
      }else{
        $_REQUEST['xoops_theme_select'] = $GLOBALS['xoopsConfig']['theme_set'];
      }
    }
}
?>
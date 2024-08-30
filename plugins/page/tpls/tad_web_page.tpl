<{if $op=="edit_form"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/page/tpls/op_`$op`.tpl"}>
<{elseif $op=="show_one"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/page/tpls/op_`$op`.tpl"}>
<{elseif $page_data}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/page/tpls/tad_web_common_page.tpl"}>
<{elseif $op=="setup"}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/page/tpls/op_default.tpl"}>
<{/if}>
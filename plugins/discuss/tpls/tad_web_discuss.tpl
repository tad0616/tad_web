<{if $op=="edit_form"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/discuss/tpls/op_`$op`.tpl"}>
<{elseif $op=="show_one"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/discuss/tpls/op_`$op`.tpl"}>
<{elseif $op=="list_all"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/discuss/tpls/op_`$op`.tpl"}>
<{elseif $op=="setup"}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/discuss/tpls/op_default.tpl"}>
<{/if}>
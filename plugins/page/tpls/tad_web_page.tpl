<{if $op=="edit_form"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/page/tpls/op_`$op`.tpl"}>
<{elseif $op=="show_one"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/page/tpls/op_`$op`.tpl"}>
<{elseif $page_data}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/page/tpls/tad_web_common_page.tpl"}>
<{elseif $op=="setup"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/page/tpls/op_default.tpl"}>
<{/if}>
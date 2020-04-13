<{if $op=="edit_form"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/homework/tpls/b3/$op.tpl"}>
<{elseif $op=="show_one"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/homework/tpls/b3/$op.tpl"}>
<{elseif $op=="list_all"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/homework/tpls/b3/$op.tpl"}>
<{elseif $op=="setup"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/homework/tpls/b3/default.tpl"}>
<{/if}>
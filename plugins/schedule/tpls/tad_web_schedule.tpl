<{if $op=="edit_form"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/schedule/tpls/op_`$op`.tpl"}>
<{elseif $op=="show_one"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/schedule/tpls/op_`$op`.tpl"}>
<{elseif $op=="setup_subject"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/schedule/tpls/op_`$op`.tpl"}>
<{elseif $schedule_data}>
    <{if $WebID}>
        <{$cate_menu}>
    <{/if}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/schedule/tpls/tad_web_common_schedule.tpl"}>
<{elseif $op=="setup"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/schedule/tpls/op_default.tpl"}>
<{/if}>
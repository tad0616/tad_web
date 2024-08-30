<{if $op=="edit_form"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/schedule/tpls/op_`$op`.tpl"}>
<{elseif $op=="show_one"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/schedule/tpls/op_`$op`.tpl"}>
<{elseif $op=="setup_subject"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/schedule/tpls/op_`$op`.tpl"}>
<{elseif $schedule_data}>
    <{if $WebID}>
        <{$cate_menu}>
    <{/if}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/schedule/tpls/tad_web_common_schedule.tpl"}>
<{elseif $op=="setup"}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/schedule/tpls/op_default.tpl"}>
<{/if}>
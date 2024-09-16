<{if $op=="edit_form"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/calendar/tpls/op_`$op`.tpl"}>
<{elseif $op=="show_one"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/calendar/tpls/op_`$op`.tpl"}>
<{elseif $calendar_data}>
    <{if $WebID|default:false}>
        <{$cate_menu}>
    <{/if}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/calendar/tpls/tad_web_common_calendar.tpl"}>
<{elseif $op=="setup"}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/calendar/tpls/op_default.tpl"}>
<{/if}>
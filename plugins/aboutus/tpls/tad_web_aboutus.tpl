<{if $op=="show_one"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif ($op=="edit_form" or $op=="new_class") and $isMyWeb}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="edit_class_stu"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="edit_position"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="edit_stu"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="show_stu"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="import_excel_form"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="import_excel"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="export_config"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="parents_account"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="show_parents_signup"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="show_enable_parent"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="forget_parent_passwd"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="show_parent"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="mem_slot"}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{else}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/tad_web_common_aboutus.tpl"}>
<{/if}>
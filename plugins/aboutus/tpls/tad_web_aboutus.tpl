<{if $op=="show_one"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif ($op=="edit_form" or $op=="new_class") and $isMyWeb}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="edit_class_stu"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="edit_position"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="edit_stu"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="show_stu"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="import_excel_form"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="import_excel"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="export_config"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="parents_account"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="show_parents_signup"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="show_enable_parent"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="forget_parent_passwd"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="show_parent"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{elseif $op=="mem_slot"}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/op_`$op`.tpl"}>
<{else}>

       <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/b4/tad_web_common_aboutus.tpl"}>

<{/if}>
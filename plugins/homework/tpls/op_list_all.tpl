<{if $WebID}>
    <div class="row">
        <div class="col-md-8">
            <{$cate_menu}>
        </div>
        <div class="col-md-4 text-right text-end">
            <{if $isCanEdit}>
                <a href="cate.php?WebID=<{$WebID}>&ColName=homework&table=tad_web_homework" class="btn btn-warning <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-folder-open"></i> <{$smarty.const._MD_TCW_CATE_TOOLS}></a>
                <a href="homework.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
            <{/if}>
        </div>
    </div>
<{/if}>

<{if $homework_data}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/homework/tpls/tad_web_common_homework.tpl"}>
<{else}>
    <h2><a href="index.php?WebID=<{$WebID}>">&#xf015;</a> <{$homework.PluginTitle}></h2>
    <div class="alert alert-info"><{$smarty.const._MD_TCW_EMPTY}></div>
<{/if}>

<div class="clearfix"></div>
<{includeq file="$xoops_rootpath/modules/tad_web/plugins/calendar/tpls/tad_web_common_calendar.tpl"}>
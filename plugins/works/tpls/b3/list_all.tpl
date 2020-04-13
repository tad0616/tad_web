<{if $WebID}>
    <div class="row">
    <div class="col-sm-8">
        <{$cate_menu}>
    </div>
    <div class="col-sm-4 text-right">
        <{if $isMyWeb and $WebID}>
        <a href="cate.php?WebID=<{$WebID}>&ColName=works&table=tad_web_works" class="btn btn-warning <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-folder-open"></i> <{$smarty.const._MD_TCW_CATE_TOOLS}></a>
        <a href="works.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_WORKS_SHORT}></a>
        <{/if}>
    </div>
    </div>
<{/if}>
<{if $works_data}>

    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/works/tpls/b3/tad_web_common_works.tpl"}>
<{else}>
    <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$works.PluginTitle}></h2>
    <div class="alert alert-info"><{$smarty.const._MD_TCW_EMPTY}></div>
<{/if}>
<{if $WebID|default:false}>
    <div class="row">
        <div class="col-md-8">
            <{$cate_menu|default:''}>
        </div>
        <div class="col-md-4 text-right text-end">
            <{if $isMyWeb and $WebID}>
                <a href="cate.php?WebID=<{$WebID|default:''}>&ColName=discuss&table=tad_web_discuss" class="btn btn-warning <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-folder-open"></i> <{$smarty.const._MD_TCW_CATE_TOOLS}></a>
            <{/if}>

            <{if $isMyWeb or $LoginMemID or $LoginParentID}>
                <a href="discuss.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_DISCUSS_ADD}></a>
            <{/if}>
        </div>
    </div>
<{/if}>

<{if $discuss_data|default:false}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/discuss/tpls/tad_web_common_discuss.tpl"}>
<{else}>
    <h2><a href="index.php?WebID=<{$WebID|default:''}>"><i class="fa fa-home"></i></a> <{$discuss.PluginTitle}></h2>
    <div class="alert alert-info"><{$smarty.const._MD_TCW_EMPTY}></div>
<{/if}>
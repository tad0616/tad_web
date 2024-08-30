<{if $WebID}>
    <div class="row">
        <div class="col-md-8">
            <{$cate_menu}>
        </div>
        <div class="col-md-4 text-right text-end">
            <{if $isMyWeb and $WebID}>
                <a href="cate.php?WebID=<{$WebID}>&ColName=action&table=tad_web_action" class="btn btn-warning <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-folder-open"></i> <{$smarty.const._MD_TCW_CATE_TOOLS}></a>
                <a href="action.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
            <{/if}>
        </div>
    </div>
<{/if}>

<{if $action_data}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/action/tpls/tad_web_common_action.tpl"}>
<{else}>
    <h2><a href="index.php?WebID=<{$WebID}>">&#xf015;</a> <{$action.PluginTitle}></h2>
    <div class="alert alert-info"><{$smarty.const._MD_TCW_EMPTY}></div>
<{/if}>
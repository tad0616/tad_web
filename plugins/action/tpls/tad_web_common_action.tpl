<{if $web_display_mode=='index' and $action_data}>
    <{if "$xoops_rootpath/uploads/tad_web/0/image/`$dirname`.png"|file_exists}>
        <a href="<{$xoops_url}>/modules/tad_web/<{$dirname}>.php"><img src="<{$xoops_url}>/uploads/tad_web/0/image/<{$dirname}>.png" alt="<{$action.PluginTitle}>"></a>
    <{else}>
        <h3><a href="<{$xoops_url}>/modules/tad_web/action.php"><{$action.PluginTitle}></a></h3>
    <{/if}>
<{elseif $web_display_mode=='index_plugin'}>
    <h2><a href="<{$xoops_url}>/modules/tad_web/">&#xf015;</a> <{$action.PluginTitle}></h2>
<{elseif $web_display_mode=='home_plugin'}>
    <h2><a href="index.php?WebID=<{$WebID}>">&#xf015;</a> <{$action.PluginTitle}></h2>
<{/if}>

<{if $action_data}>
    <div style="clear: both;"></div>
        <{foreach from=$action_data item=act}>
            <div style="width: 156px; height: 260px; float:left; margin: 5px 2px; overflow: hidden;">
                <a href='action.php?WebID=<{$act.WebID}>&ActionID=<{$act.ActionID}>'>
                    <div style="width: 150px; height: 160px; background-color: <{if $act.gphoto_link!=""}>#fff589<{else}>#F1F7FF<{/if}> ; border:1px dotted green; margin: 0px auto;">
                        <div style="width: 140px; height: 140px; background: <{if $act.gphoto_link!=""}>#fff589<{else}>#F1F7FF<{/if}> url('<{$act.ActionPic}>') center center no-repeat; border:8px solid <{if $act.gphoto_link!=""}>#fff589<{else}>#F1F7FF<{/if}>; margin: 0px auto;background-size:cover;"><span class="sr-only visually-hidden"><{$act.ActionID}></span>
                        </div>
                    </div>
                </a>
                <div class="text-center" style="margin: 8px auto;">
                    <a href='action.php?WebID=<{$act.WebID}>&ActionID=<{$act.ActionID}>'><{$act.ActionName}></a>
                    <{*if $act.isCanEdit*}>
                    <{if ($WebID && $isMyWeb) || $isAdmin || ($act.cate.CateID && $act.cate.CateID == $smarty.session.isAssistant.act)}>
                        <a href="javascript:delete_action_func(<{$act.ActionID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>
                        <a href="action.php?WebID=<{$WebID}>&op=edit_form&ActionID=<{$act.ActionID}>"  class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                    <{/if}>
                </div>
                <{if $web_display_mode=="index" or $web_display_mode=="index_plugin"}>
                    <div class="text-center" style="font-size: 80%;">
                        <{$act.WebTitle}>
                    </div>
                <{/if}>
            </div>
        <{/foreach}>
    <div style="clear: both;"></div>

    <{if $action_data}>
        <{if $web_display_mode=='index_plugin' or $web_display_mode=='home_plugin'}>
            <{$bar}>
        <{/if}>
    <{/if}>

    <div style="text-align:right; margin: 0px 0px 10px;">
        <{if $web_display_mode=='index'}>
            <a href="action.php" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
            <{elseif $web_display_mode=='home' or $ActionDefCateID}>
            <a href="action.php?WebID=<{$WebID}>" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
        <{/if}>

        <{if $isMyWeb and $WebID}>
            <a href="action.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
        <{/if}>
    </div>
<{/if}>

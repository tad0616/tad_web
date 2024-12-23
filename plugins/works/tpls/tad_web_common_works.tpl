<{if $web_display_mode=='index' and $works_data}>
    <{if "$xoops_rootpath/uploads/tad_web/0/image/`$dirname`.png"|file_exists}>
        <a href="<{$xoops_url}>/modules/tad_web/<{$dirname|default:''}>.php"><img src="<{$xoops_url}>/uploads/tad_web/0/image/<{$dirname|default:''}>.png" alt="<{$works.PluginTitle}>"></a>
    <{else}>
        <h3><a href="<{$xoops_url}>/modules/tad_web/works.php"><{$works.PluginTitle}></a></h3>
    <{/if}>
<{elseif $web_display_mode=='index_plugin'}>
    <h2><a href="<{$xoops_url}>/modules/tad_web/"><i class="fa fa-home"></i></a> <{$works.PluginTitle}></h2>
<{elseif $web_display_mode=='home_plugin'}>
    <h2><a href="index.php?WebID=<{$WebID|default:''}>"><i class="fa fa-home"></i></a> <{$works.PluginTitle}></h2>
<{/if}>

<{if $works_data|default:false}>
    <table class="footable table common_table">
        <thead>
            <tr>
                <th data-hide="phone" style="width:100px;text-align:center;">
                    <{$smarty.const._MD_TCW_WORKS_DATE}>
                </th>
                <th data-class="expand">
                    <{$smarty.const._MD_TCW_WORKS_NAME}>
                </th>
                <th data-hide="phone" class="common_counter" style="text-align: center;">
                    <{$smarty.const._MD_TCW_WORKS_COUNT}>
                </th>
                <{if $web_display_mode=="index" or $web_display_mode=="index_plugin"}>
                    <th data-hide="phone" class="common_team" style="text-align: center;">
                        <{$smarty.const._MD_TCW_TEAMID}>
                    </th>
                <{/if}>
            </tr>
        </thead>
        <{foreach from=$works_data item=work}>
            <tr>
                <td style="text-align:center;"><{$work.WorksDate}></td>
                <td>
                    <{if isset($work.cate.CateID)}>
                        <span class="badge badge-info bg-info"><a href="works.php?WebID=<{$work.WebID}>&CateID=<{$work.cate.CateID}>" style="color: #FFFFFF;"><{$work.cate.CateName}></a></span>
                    <{/if}>
                    <a href='works.php?WebID=<{$work.WebID}>&WorksID=<{$work.WorksID}>'><{$work.WorkName}></a>
                    <{if $work.hide|default:false}><span class="badge badge-danger bg-danger"><{$work.hide}></span><{/if}>
                    <{*if $work.isCanEdit*}>
                    <{if ($WebID && $isMyWeb) || $smarty.session.tad_web_adm|default:false || (isset($work.cate.CateID) && isset($smarty.session.isAssistant.work) && $work.cate.CateID == $smarty.session.isAssistant.work)}>
                        <a href="javascript:delete_works_func(<{$work.WorksID}>);" class="text-danger"><i class="fa fa-trash"></i><span class="sr-only visually-hidden">delete</span></a>
                        <a href="works.php?WebID=<{$WebID|default:''}>&op=edit_form&WorksID=<{$work.WorksID}>" class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                    <{/if}>
                </td>
                <td style="text-align:center;"><{$work.WorksCount}></td>
                <{if $web_display_mode=="index" or $web_display_mode=="index_plugin"}>
                    <td style="text-align:center;" class="common_team_content">
                        <{$work.WebTitle}>
                    </td>
                <{/if}>
            </tr>
        <{/foreach}>
    </table>

    <{if $works_data|default:false}>
        <{if $web_display_mode=='index_plugin' or $web_display_mode=='home_plugin'}>
            <{$bar|default:''}>
        <{/if}>
    <{/if}>

    <div style="text-align:right; margin: 0px 0px 10px;">
        <{if $web_display_mode=='index'}>
            <a href="works.php" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_WORKS_SHORT}></a>
        <{elseif $web_display_mode=='home' or $WorksDefCateID}>
            <a href="works.php?WebID=<{$WebID|default:''}>" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_WORKS_SHORT}></a>
        <{/if}>

        <{if $isMyWeb and $WebID}>
            <a href="works.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_WORKS_SHORT}></a>
        <{/if}>
    </div>
<{/if}>
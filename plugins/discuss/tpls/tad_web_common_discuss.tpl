<{if $web_display_mode=='index' and $discuss_data}>
    <{if "$xoops_rootpath/uploads/tad_web/0/image/`$dirname`.png"|file_exists}>
        <a href="<{$xoops_url}>/modules/tad_web/<{$dirname|default:''}>.php"><img src="<{$xoops_url}>/uploads/tad_web/0/image/<{$dirname|default:''}>.png" alt="<{$discuss.PluginTitle}>"></a>
    <{else}>
        <h3><a href="<{$xoops_url}>/modules/tad_web/discuss.php"><{$discuss.PluginTitle}></a></h3>
    <{/if}>
<{elseif $web_display_mode=='index_plugin'}>
    <h2><a href="<{$xoops_url}>/modules/tad_web/"><i class="fa fa-home"></i></a> <{$discuss.PluginTitle}></h2>
<{elseif $web_display_mode=='home_plugin'}>
    <h2><a href="index.php?WebID=<{$WebID|default:''}>"><i class="fa fa-home"></i></a> <{$discuss.PluginTitle}></h2>
<{/if}>

<{if $discuss_data|default:false}>
    <{if $mode=="need_login"}>
        <div class="my-border">
        <{$smarty.const._MD_TCW_NEED_LOGIN}>
        </div>
    <{else}>
        <table class="footable table common_table">
            <thead>
                <tr>
                    <th data-hide="phone" style="width:100px;text-align:center;">
                        <{$smarty.const._MD_TCW_LASTTIME}>
                    </th>
                    <th data-class="expand">
                        <{$smarty.const._MD_TCW_DISCUSSTITLE}>
                    </th>
                    <th data-hide="phone" style="text-align:center;width:100px;">
                        <{$smarty.const._MD_TCW_DISCUSS_UID}>
                    </th>
                    <th data-hide="phone" class="common_counter" style="text-align: center;">
                        <{$smarty.const._MD_TCW_ACTIONCOUNT}>
                    </th>
                    <{if $web_display_mode=="index" or $web_display_mode=="index_plugin"}>
                        <th data-hide="phone" class="common_team" style="text-align: center;">
                        <{$smarty.const._MD_TCW_TEAMID}>
                        </th>
                    <{/if}>
                </tr>
            </thead>
            <{if $discuss_data|default:false}>
                <{foreach from=$discuss_data item=discuss}>
                    <tr>
                        <td style="text-align:center;"><{$discuss.LastTime}></td>
                        <td>
                            <{if isset($discuss.cate.CateID)}>
                                <span class="badge badge-info bg-info"><a href="discuss.php?WebID=<{$discuss.WebID}>&CateID=<{$discuss.cate.CateID}>" style="color: #FFFFFF;"><{$discuss.cate.CateName}></a></span>
                            <{/if}>
                            <a href="discuss.php?WebID=<{$discuss.WebID}>&DiscussID=<{$discuss.DiscussID}>"><{$discuss.DiscussTitle}></a>
                            <{$discuss.show_re_num}>

                            <{if ($WebID && $isMyWeb) || $smarty.session.tad_web_adm|default:false || ($smarty.session.LoginMemID && $discuss.MemID == $smarty.session.LoginMemID) || ($smarty.session.LoginParentID && $discuss.ParentID == $smarty.session.LoginParentID)}>
                                <a href="javascript:delete_discuss_func(<{$discuss.DiscussID}>);" class="text-danger"><i class="fa fa-trash"></i><span class="sr-only visually-hidden">delete</span></a>
                                <a href="discuss.php?WebID=<{$discuss.WebID}>&op=edit_form&DiscussID=<{$discuss.DiscussID}>" class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                            <{/if}>
                        </td>
                        <td style="text-align:center;"><{$discuss.MemName}></td>
                        <td style="text-align:center;"><{$discuss.DiscussCounter}></td>
                        <{if $web_display_mode=="index" or $web_display_mode=="index_plugin"}>
                            <td style="text-align:center;" class="common_team_content">
                                <{$discuss.WebTitle}>
                            </td>
                        <{/if}>
                    </tr>
                <{/foreach}>
            <{else}>
                <tr>
                    <td colspan=5 class="text-center">
                        <{$smarty.const._MD_TCW_EMPTY}>
                    </td>
                </tr>
            <{/if}>
        </table>

        <{if $discuss_data|default:false}>
            <{if $web_display_mode=='index_plugin' or $web_display_mode=='home_plugin'}>
                <{$bar|default:''}>
            <{/if}>
        <{/if}>

        <div style="text-align:right; margin: 0px 0px 10px;">
            <{if $web_display_mode=='index'}>
                <a href="discuss.php" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_DISCUSS_SHORT}></a>
            <{elseif $web_display_mode=='home' or $DiscussDefCateID}>
                <a href="discuss.php?WebID=<{$WebID|default:''}>" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_DISCUSS_SHORT}></a>
            <{/if}>

            <{if $isMyWeb or $LoginMemID or $LoginParentID}>
                <a href="discuss.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_DISCUSS_ADD}></a>
            <{/if}>
        </div>
    <{/if}>
<{/if}>
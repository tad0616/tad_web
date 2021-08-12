<{if $web_display_mode=='index' and $link_data}>
    <{if "$xoops_rootpath/uploads/tad_web/0/image/`$dirname`.png"|file_exists}>
        <a href="<{$xoops_url}>/modules/tad_web/<{$dirname}>.php"><img src="<{$xoops_url}>/uploads/tad_web/0/image/<{$dirname}>.png" alt="<{$link.PluginTitle}>"></a>
    <{else}>
        <h3><a href="<{$xoops_url}>/modules/tad_web/link.php"><{$link.PluginTitle}></a></h3>
    <{/if}>
<{elseif $web_display_mode=='index_plugin'}>
    <h2><a href="<{$xoops_url}>/modules/tad_web/">&#xf015;</a> <{$link.PluginTitle}></h2>
<{elseif $web_display_mode=='home_plugin'}>
    <h2><a href="index.php?WebID=<{$WebID}>">&#xf015;</a> <{$link.PluginTitle}></h2>
<{/if}>

<{if $link_data}>
    <{if $isMyWeb}>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
                    var order = $(this).sortable('serialize');
                    $.post('<{$xoops_url}>/modules/tad_web/plugins/link/save_sort.php', order, function(theResponse){
                        $('#save_msg').html(theResponse);
                    });
                }
                });
            });
        </script>
    <{/if}>
    <div id="save_msg"></div>
    <table class="footable table common_table">
        <thead>
            <tr>
                <th data-class="expand"><{$smarty.const._MD_TCW_LINKTITLE}></th>
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
        <tbody id="sort">
            <{foreach item=link from=$link_data name=l}>
                <tr id="LinkID_<{$link.LinkID}>">
                    <td>
                        <i class="fa fa-external-link"></i>
                        <span class="badge badge-warning"><{$smarty.foreach.l.iteration}></span>
                        <{if isset($link.cate.CateID)}>
                            <span class="badge badge-info"><a href="link.php?WebID=<{$link.WebID}>&CateID=<{$link.cate.CateID}>" style="color: #FFFFFF;"><{$link.cate.CateName}></a></span>
                        <{/if}>
                        <a href="link.php?WebID=<{$link.WebID}>&LinkID=<{$link.LinkID}>" target="_blank"><{$link.LinkTitle}></a>

                        <{*if $link.isMyWeb or $link.isAssistant*}>
                        <{*if $link.isCanEdit*}>
                        <{if ($WebID && $isMyWeb) || $isAdmin || ($link.cate.CateID && $link.cate.CateID == $smarty.session.isAssistant.link)}>
                            <a href="javascript:delete_link_func(<{$link.LinkID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only">delete</span></a>
                            <a href="link.php?WebID=<{$link.WebID}>&op=edit_form&LinkID=<{$link.LinkID}>" class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only">edit</span></a>
                        <{/if}>
                        <div style="margin: 6px 0px;"><a href="link.php?WebID=<{$link.WebID}>&LinkID=<{$link.LinkID}>" target="_blank"><{$link.LinkShortUrl}></a></div>
                        <{if $link.LinkDesc}>
                            <div style="font-size: 80%;color:#666699; line-height:1.5;"><{$link.LinkDesc}></div>
                        <{/if}>
                    </td>
                    <td style="text-align:center;">
                        <{$link.LinkCounter}>
                    </td>
                    <{if $web_display_mode=="index" or $web_display_mode=="index_plugin"}>
                        <td style="text-align:center;" class="common_team_content">
                            <{$link.WebTitle}>
                        </td>
                    <{/if}>
                </tr>
            <{/foreach}>
        </tbody>
    </table>

    <{if $link_data}>
        <{if $web_display_mode=='index_plugin' or $web_display_mode=='home_plugin'}>
            <{$bar}>
        <{/if}>
    <{/if}>

    <div style="text-align:right; margin: 0px 0px 10px;">
        <{if $web_display_mode=='index'}>
            <a href="link.php" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_LINK_SHORT}></a>
        <{elseif $web_display_mode=='home' or $LinkDefCateID}>
            <a href="link.php?WebID=<{$WebID}>" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_LINK_SHORT}></a>
        <{/if}>

        <{if $isMyWeb and $WebID}>
            <a href="link.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_LINK_SHORT}></a>
        <{/if}>
    </div>
<{/if}>
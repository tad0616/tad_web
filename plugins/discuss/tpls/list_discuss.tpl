<{assign var="bc" value=$block.BlockContent}>
<{if $mode=="need_login"}>
    <div class="my-border">
        <{$smarty.const._MD_TCW_NEED_LOGIN}>
    </div>
<{elseif $bc.main_data}>
    <{if $isMyWeb}>
        <{$sweet_delete_discuss_func_code}>
    <{/if}>
    <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>

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
            </tr>
        </thead>
        <{if $bc.main_data}>
            <{foreach item=discuss from=$bc.main_data}>
                <tr>
                    <td style="text-align:center;">
                        <{$discuss.LastTime}>
                    </td>
                    <td>
                        <{if isset($discuss.cate.CateID)}>
                            <span class="badge badge-info"><a href="discuss.php?WebID=<{$discuss.WebID}>&CateID=<{$discuss.cate.CateID}>" style="color: #FFFFFF;"><{$discuss.cate.CateName}></a></span>
                        <{/if}>
                        <a href="discuss.php?WebID=<{$discuss.WebID}>&DiscussID=<{$discuss.DiscussID}>"><{$discuss.DiscussTitle}></a>
                        <{$discuss.show_re_num}>
                        <{if $bc.isMineDiscuss}>
                            <a href="javascript:delete_discuss_func(<{$discuss.DiscussID}>);" class="text-danger"><i class="fa fa-trash-o"></i></a>
                            <a href="discuss.php?WebID=<{$discuss.WebID}>&op=edit_form&DiscussID=<{$discuss.DiscussID}>" class="text-warning"><i class="fa fa-pencil"></i></a>
                        <{/if}>
                    </td>
                    <td style="text-align:center;">
                        <{$discuss.MemName}>
                    </td>
                    <td style="text-align:center;">
                        <{$discuss.DiscussCounter}>
                    </td>
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
<{/if}>
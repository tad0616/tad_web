<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>

    <table class="footable table common_table">
        <thead>
            <tr>
                <th data-class="expand">
                    <{$smarty.const._MD_TCW_LINKTITLE}>
                </th>
                <th data-hide="phone" class="common_counter" style="text-align: center;">
                    <{$smarty.const._MD_TCW_ACTIONCOUNT}>
                </th>
            </tr>
        </thead>
        <{foreach item=link from=$bc.main_data}>
            <tr>
                <td>
                    <i class="fa fa-external-link"></i>
                    <{if isset($link.cate.CateID)}>
                        <span class="badge badge-info bg-info"><a href="link.php?WebID=<{$link.WebID}>&CateID=<{$link.cate.CateID}>" style="color: #FFFFFF;"><{$link.cate.CateName}></a></span>
                    <{/if}>
                    <a href="link.php?WebID=<{$link.WebID}>&LinkID=<{$link.LinkID}>" target="_blank"><{$link.LinkTitle}></a>

                    <{*if $link.isMyWeb or $link.isAssistant*}>
                    <{*if $link.isCanEdit*}>
                    <{if ($WebID && $isMyWeb) || $isAdmin || ($link.cate.CateID && $link.cate.CateID == $smarty.session.isAssistant.link)}>
                        <a href="javascript:delete_link_func(<{$link.LinkID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>
                        <a href="link.php?WebID=<{$link.WebID}>&op=edit_form&LinkID=<{$link.LinkID}>" class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                    <{/if}>

                    <{if $link.hide_link!='1'}>
                        <div style="margin: 6px 0px;"><a href="link.php?WebID=<{$link.WebID}>&LinkID=<{$link.LinkID}>" target="_blank"><{$link.LinkShortUrl}></a></div>
                    <{/if}>

                    <{if $link.hide_desc!='1' and $link.LinkDesc}>
                        <div style="margin: 6px 0px; font-size: 80%;color:#666699; line-height:1.5;"><{$link.LinkDesc}></div>
                    <{/if}>
                </td>
                <td style="text-align:center;">
                    <{$link.LinkCounter}>
                </td>
            </tr>
        <{/foreach}>
    </table>
<{/if}>
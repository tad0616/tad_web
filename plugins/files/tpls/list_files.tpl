<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data|default:false}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>

    <table class="footable table common_table">
        <thead>
            <tr>
                <th data-class="expand">
                    <{$smarty.const._MD_TCW_FILENAME}>
                </th>
                <th data-hide="phone" nowrap style="width:60px;">
                    <{$smarty.const._MD_TCW_FILES_UID}>
                </th>
            </tr>
        </thead>
        <{foreach item=file from=$bc.main_data}>
            <tr>
                <td>
                    <div style="word-wrap:break-word;">
                        <{if isset($file.cate.CateID)}>
                            <span class="badge badge-info bg-info"><a href="files.php?WebID=<{$file.WebID}>&CateID=<{$file.cate.CateID}>" style="color: #FFFFFF;"><{$file.cate.CateName}></a></span>
                        <{/if}>

                        <{$file.showurl}>

                        <{*if $file.isCanEdit*}>
                        <{if ($WebID && $isMyWeb) || $smarty.session.tad_web_adm|default:false || (isset($file.cate.CateID) && isset($smarty.session.isAssistant.file) && $file.cate.CateID == $smarty.session.isAssistant.file)}>
                            <a href="javascript:delete_files_func(<{$file.fsn}>);" class="text-danger"><i class="fa fa-trash"></i><span class="sr-only visually-hidden">delete</span></a>
                            <a href="files.php?WebID=<{$file.WebID}>&op=edit_form&fsn=<{$file.fsn}>" class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                        <{/if}>
                    </div>
                </td>
                <td style="text-align:center;" nowrap>
                    <{$file.uid_name}>
                </td>
            </tr>
        <{/foreach}>
    </table>
<{/if}>
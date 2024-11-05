<{if $all_content|default:false}>
    <{if $smarty.session.tad_web_adm|default:false}>
        <{$delete_tad_web_notice_func|default:''}>
    <{/if}>

    <div id="tad_web_notice_save_msg"></div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>
                    <!--通知標題-->
                    <{$smarty.const._MA_TADWEB_NOTICETITLE}>
                </th>
                <th>
                    <!--通知對象-->
                    <{$smarty.const._MA_TADWEB_NOTICEWHO}>
                </th>
                <th>
                    <!--通知日期-->
                    <{$smarty.const._MA_TADWEB_NOTICEDATE}>
                </th>
                <{if $smarty.session.tad_web_adm|default:false}>
                    <th><{$smarty.const._TAD_FUNCTION}></th>
                <{/if}>
            </tr>
        </thead>

        <tbody id="tad_web_notice_sort">
            <{foreach from=$all_content item=data}>
                <tr id="tr_<{$data.NoticeID}>">
                    <td>
                        <!--通知標題-->
                        <a href="<{$action|default:''}>?NoticeID=<{$data.NoticeID}>"><{$data.NoticeTitle}></a>
                    </td>

                    <td>
                        <!--通知對象-->
                        <{$data.NoticeWho}>
                    </td>

                    <td>
                        <!--通知日期-->
                        <{$data.NoticeDate}>
                    </td>

                    <{if $smarty.session.tad_web_adm|default:false}>
                        <td>
                            <a href="javascript:delete_tad_web_notice_func(<{$data.NoticeID}>);" class="btn btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}></a>
                            <a href="<{$xoops_url}>/modules/tad_web/admin/notice.php?op=tad_web_notice_form&NoticeID=<{$data.NoticeID}>" class="btn btn-sm btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>  <{$smarty.const._TAD_EDIT}></a>
                            <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;margin:0px 4px;" alt="<{$smarty.const._TAD_SORTABLE}>" title="<{$smarty.const._TAD_SORTABLE}>">
                        </td>
                    <{/if}>
                </tr>
            <{/foreach}>
        </tbody>
    </table>


    <{if $smarty.session.tad_web_adm|default:false}>
        <div class="text-right text-end">
            <a href="<{$xoops_url}>/modules/tad_web/admin/notice.php?op=tad_web_notice_form" class="btn btn-info"><i class="fa fa-plus-square" aria-hidden="true"></i>  <{$smarty.const._TAD_ADD}></a>
        </div>
    <{/if}>

    <{$bar|default:''}>
<{else}>
    <{if $smarty.session.tad_web_adm|default:false}>
        <div class="jumbotron bg-light p-5 rounded-lg m-3 text-center">
            <a href="<{$xoops_url}>/modules/tad_web/admin/notice.php?op=tad_web_notice_form" class="btn btn-info"><i class="fa fa-plus-square" aria-hidden="true"></i>  <{$smarty.const._TAD_ADD}></a>
        </div>
    <{/if}>
<{/if}>
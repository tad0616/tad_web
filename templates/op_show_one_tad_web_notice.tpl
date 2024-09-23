<{if $isAdmin|default:false}>
    <{$delete_tad_web_notice_func|default:''}>
<{/if}>

<h2 class="text-center"><{$NoticeTitle|default:''}></h2>

<!--通知內容-->
<div class="row">
    <label class="col-md-3 text-right text-end">
        <{$smarty.const._MA_TADWEB_NOTICECONTENT}>
    </label>
    <div class="col-md-9">
        <div class="my-border">
            <{$NoticeContent|default:''}>
        </div>
    </div>
</div>

<!--通知網站-->
<!--div class="row">
    <label class="col-md-3 text-right text-end">
        <{$smarty.const._MA_TADWEB_NOTICEWEB}>
    </label>
    <div class="col-md-9">
        <div class="my-border">
            <{$NoticeWeb|default:''}>
        </div>
    </div>
</div-->

<!--通知對象-->
<div class="row">
    <label class="col-md-3 text-right text-end">
        <{$smarty.const._MA_TADWEB_NOTICEWHO}>
    </label>
    <div class="col-md-9">
        <{$NoticeWho|default:''}>
    </div>
</div>

<!--通知日期-->
<div class="row">
    <label class="col-md-3 text-right text-end">
        <{$smarty.const._MA_TADWEB_NOTICEDATE}>
    </label>
    <div class="col-md-9">
        <{$NoticeDate|default:''}>
    </div>
</div>

<div class="text-right text-end">
    <{if $isAdmin|default:false}>
        <a href="javascript:delete_tad_web_notice_func(<{$NoticeID|default:''}>);" class="btn btn-danger"><{$smarty.const._TAD_DEL}></a>
        <a href="<{$xoops_url}>/modules/tad_web/admin/notice.php?op=tad_web_notice_form&NoticeID=<{$NoticeID|default:''}>" class="btn btn-warning"><{$smarty.const._TAD_EDIT}></a>
        <a href="<{$xoops_url}>/modules/tad_web/admin/notice.php?op=tad_web_notice_form" class="btn btn-primary"><{$smarty.const._TAD_ADD}></a>
    <{/if}>
    <a href="<{$action|default:''}>" class="btn btn-success"><{$smarty.const._TAD_HOME}></a>
</div>
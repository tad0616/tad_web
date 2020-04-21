<div class="container">
    <form action="<{$action}>" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
        <!--通知標題-->
        <div class="form-group row">
            <label class="col-md-2 col-form-label text-sm-right control-label">
                <{$smarty.const._MA_TADWEB_NOTICETITLE}>
            </label>
            <div class="col-md-6">
                <input type="text" name="NoticeTitle" id="NoticeTitle" class="form-control validate[required]" value="<{$NoticeTitle}>" placeholder="<{$smarty.const._MA_TADWEB_NOTICETITLE}>">
            </div>
        </div>

        <!--通知內容-->
        <div class="form-group row">
            <label class="col-md-2 col-form-label text-sm-right control-label">
                <{$smarty.const._MA_TADWEB_NOTICECONTENT}>
            </label>
            <div class="col-md-6">
                <{$NoticeContent_editor}>
            </div>
        </div>

        <!--通知網站-->
        <!--div class="form-group row">
            <label class="col-md-2 col-form-label text-sm-right control-label">
                <{$smarty.const._MA_TADWEB_NOTICEWEB}>
            </label>
            <div class="col-md-6">
                <textarea name="NoticeWeb" rows=8 id="NoticeWeb" class="form-control " placeholder="<{$smarty.const._MA_TADWEB_NOTICEWEB}>"><{$NoticeWeb}></textarea>
            </div>
        </div-->

        <!--通知對象-->
        <div class="form-group row">
            <label class="col-md-2 col-form-label text-sm-right control-label">
                <{$smarty.const._MA_TADWEB_NOTICEWHO}>
            </label>
            <div class="col-md-10">
                <div class="form-check form-check-inline checkbox-inline">
                    <label class="form-check-label" for="NoticeWho_def0">
                        <input class="form-check-input" type="checkbox" name="NoticeWho[]" id="NoticeWho_def0" value="master" <{if 'master'|in_array:$NoticeWho}>checked<{/if}>>
                        <{$smarty.const._MA_TADWEB_NOTICEWHO_DEF0}>
                    </label>
                </div>
                <div class="form-check form-check-inline checkbox-inline">
                    <label class="form-check-label" for="NoticeWho_def1">
                        <input class="form-check-input" type="checkbox" name="NoticeWho[]" id="NoticeWho_def1" value="mem" <{if 'mem'|in_array:$NoticeWho}>checked<{/if}>>
                        <{$smarty.const._MA_TADWEB_NOTICEWHO_DEF1}>
                    </label>
                </div>
                <div class="form-check form-check-inline checkbox-inline">
                    <label class="form-check-label" for="NoticeWho_def2">
                        <input class="form-check-input" type="checkbox" name="NoticeWho[]" id="NoticeWho_def2" value="parent" <{if 'parent'|in_array:$NoticeWho}>checked<{/if}>>
                        <{$smarty.const._MA_TADWEB_NOTICEWHO_DEF2}>
                    </label>
                </div>
            </div>
        </div>

        <div class="text-center">
            <!--通知編號-->
            <input type='hidden' name="NoticeID" value="<{$NoticeID}>">

            <{$token_form}>

            <input type="hidden" name="op" value="<{$next_op}>">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
</div>
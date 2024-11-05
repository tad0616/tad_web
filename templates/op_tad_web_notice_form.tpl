<div class="container">
    <form action="<{$action|default:''}>" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
        <!--通知標題-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MA_TADWEB_NOTICETITLE}>
            </label>
            <div class="col-md-6">
                <input type="text" name="NoticeTitle" id="NoticeTitle" class="form-control validate[required]" value="<{$NoticeTitle|default:''}>" placeholder="<{$smarty.const._MA_TADWEB_NOTICETITLE}>">
            </div>
        </div>

        <!--通知內容-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MA_TADWEB_NOTICECONTENT}>
            </label>
            <div class="col-md-6">
                <{$NoticeContent_editor|default:''}>
            </div>
        </div>

        <!--通知網站-->
        <!--div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MA_TADWEB_NOTICEWEB}>
            </label>
            <div class="col-md-6">
                <textarea name="NoticeWeb" rows=8 id="NoticeWeb" class="form-control " placeholder="<{$smarty.const._MA_TADWEB_NOTICEWEB}>"><{$NoticeWeb|default:''}></textarea>
            </div>
        </div-->

        <!--通知對象-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
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
            <input type='hidden' name="NoticeID" value="<{$NoticeID|default:''}>">

            <{$token_form|default:''}>

            <input type="hidden" name="op" value="<{$next_op|default:''}>">
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>  <{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
</div>
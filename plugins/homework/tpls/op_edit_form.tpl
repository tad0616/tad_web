<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
function chang_title(){
    var new_date = $('#toCal').val();
    var new_title="<{$WebTitle}> " + new_date + " <{$smarty.const._MD_TCW_HOMEWORK_SHORT}>";
    $('#HomeworkTitle').val(new_title);
};
</script>

<h2><{$smarty.const._MD_TCW_HOMEWORK_ADD}></h2>

<div class="my-border">
    <form action="homework.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
        <!--分類-->
        <{$cate_menu_form}>

        <div class="form-group row">
            <!--加到行事曆-->
            <label class="col-md-2 col-form-label text-sm-right control-label">
                <{$smarty.const._MD_TCW_HOMEWORK_CAL_DATE}>
            </label>
            <div class="col-md-4">
                <input type="text" name="toCal" value="<{$toCal}>" id="toCal" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d}', onpicked:function(){chang_title();} })" class="form-control" placeholder="<{$smarty.const._HOMEWORK_TOCAL_DESC}>">
            </div>

            <!--發布時間-->
            <label class="col-md-2 col-form-label text-sm-right control-label">
                <{$smarty.const._MD_TCW_HOMEWORK_POST_DATE}>
            </label>
            <div class="col-md-4">
                <select name="HomeworkPostDate" id="HomeworkPostDate" class="form-control">
                    <option value="<{$HomeworkPostDate}>"><{$smarty.const._MD_TCW_HOMEWORK_POST_NOW}></option>
                    <option value="8" <{if $HomeworkPostDate==8}>selected<{/if}>><{$smarty.const._MD_TCW_HOMEWORK_POST_8}></option>
                    <option value="12" <{if $HomeworkPostDate==12}>selected<{/if}>><{$smarty.const._MD_TCW_HOMEWORK_POST_12}></option>
                    <option value="16" <{if $HomeworkPostDate==16}>selected<{/if}>><{$smarty.const._MD_TCW_HOMEWORK_POST_16}></option>
                </select>
            </div>
        </div>

        <!--標題-->
        <div class="form-group row">
            <label class="col-md-2 col-form-label text-sm-right control-label">
                <{$smarty.const._MD_TCW_HOMEWORK_TITLE}>
            </label>
            <div class="col-md-10">
                <input name="HomeworkTitle" id="HomeworkTitle" class="validate[required] form-control" type="text" value="<{$HomeworkTitle}>" placeholder="<{$smarty.const._MD_TCW_HOMEWORKTITLE}>">
            </div>
        </div>

        <{if $HomeworkContent_editor}>
            <!--內容-->
            <{$HomeworkContent_editor}>
        <{else}>
            <!--今日作業-->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-sm-right control-label">
                    <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TODAY_WORK}>" src="<{$xoops_url}>/modules/tad_web/images/today_homework.png" class="img-fluid">
                </label>
                <div class="col-md-9">
                    <{$HomeworkContent_editor1}>
                </div>
            </div>

            <!--攜帶物品-->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-sm-right control-label">
                    <img alt="<{$smarty.const._MD_TCW_HOMEWORK_BRING}>" src="<{$xoops_url}>/modules/tad_web/images/bring.png" class="img-fluid">
                </label>
                <div class="col-md-9">
                    <{$HomeworkContent_editor2}>
                </div>
            </div>

            <!--叮嚀-->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-sm-right control-label">
                    <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TEACHER_SAY}>" src="<{$xoops_url}>/modules/tad_web/images/teacher_say.png" class="img-fluid">
                </label>
                <div class="col-md-9">
                    <{$HomeworkContent_editor3}>
                </div>
            </div>

            <!--其他-->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-sm-right control-label">
                    <{$smarty.const._MD_TCW_HOMEWORK_OTHER}>
                </label>
                <div class="col-md-9">
                    <{$HomeworkContent_editor4}>
                </div>
            </div>
        <{/if}>

        <!--相關附件-->
        <div class="form-group row">
            <label class="col-md-2 col-form-label text-sm-right control-label">
                <{$smarty.const._MD_TCW_HOMEWORK_FILES}>
            </label>
            <div class="col-md-10">
                <{$upform}>
            </div>
        </div>

        <div class="text-center">
            <!--編號-->
            <input type="hidden" name="HomeworkID" value="<{$HomeworkID}>">
            <input type="hidden" name="WebID" value="<{$WebID}>">
            <input type="hidden" name="uid" value="<{$uid}>">
            <input type="hidden" name="op" value="<{$next_op}>">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
</div>
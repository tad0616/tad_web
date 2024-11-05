<script type="text/javascript">
function chang_title(){
    var new_date = $('#toCal').val();
    var new_title="<{$WebTitle|default:''}> " + new_date + " <{$smarty.const._MD_TCW_HOMEWORK_SHORT}>";
    $('#HomeworkTitle').val(new_title);
};
</script>

<h2><{$smarty.const._MD_TCW_HOMEWORK_ADD}></h2>

<div class="my-border">
    <form action="homework.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
        <!--分類-->
        <{$cate_menu_form|default:''}>

        <div class="form-group row mb-3">
            <!--加到行事曆-->
            <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_HOMEWORK_CAL_DATE}>
            </label>
            <div class="col-md-3">
                <input type="text" name="toCal" value="<{$toCal|default:''}>" id="toCal" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d}', onpicked:function(){chang_title();} })" class="form-control" placeholder="<{$smarty.const._HOMEWORK_TOCAL_DESC}>">
            </div>

            <!--發布時間-->
            <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_HOMEWORK_POST_DATE}>
            </label>
            <div class="col-md-3">
                <select name="HomeworkPostDate" id="HomeworkPostDate" class="form-select">
                    <option value="0"><{$smarty.const._MD_TCW_HOMEWORK_POST_NOW}></option>
                    <option value="8" <{if $HomeworkPostDate==8}>selected<{/if}>><{$smarty.const._MD_TCW_HOMEWORK_POST_8}></option>
                    <option value="12" <{if $HomeworkPostDate==12}>selected<{/if}>><{$smarty.const._MD_TCW_HOMEWORK_POST_12}></option>
                    <option value="16" <{if $HomeworkPostDate==16}>selected<{/if}>><{$smarty.const._MD_TCW_HOMEWORK_POST_16}></option>
                    <option value="18" <{if $HomeworkPostDate==18}>selected<{/if}>><{$smarty.const._MD_TCW_HOMEWORK_POST_18}></option>
                </select>
            </div>
        </div>

        <!--標題-->
        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_HOMEWORK_TITLE}>
            </label>
            <div class="col-md-9">
                <input name="HomeworkTitle" id="HomeworkTitle" class="validate[required] form-control" type="text" value="<{$HomeworkTitle|default:''}>" placeholder="<{$smarty.const._MD_TCW_HOMEWORKTITLE}>">
            </div>
        </div>

        <{if $HomeworkContent_editor|default:false}>
            <!--內容-->
            <{$HomeworkContent_editor|default:''}>
        <{else}>
            <!--今日作業-->
            <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                    <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TODAY_WORK}>" src="<{$xoops_url}>/modules/tad_web/images/today_homework.png" class="img-fluid">
                </label>
                <div class="col-md-9">
                    <{$HomeworkContent_editor1|default:''}>
                </div>
            </div>

            <!--攜帶物品-->
            <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                    <img alt="<{$smarty.const._MD_TCW_HOMEWORK_BRING}>" src="<{$xoops_url}>/modules/tad_web/images/bring.png" class="img-fluid">
                </label>
                <div class="col-md-9">
                    <{$HomeworkContent_editor2|default:''}>
                </div>
            </div>

            <!--叮嚀-->
            <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                    <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TEACHER_SAY}>" src="<{$xoops_url}>/modules/tad_web/images/teacher_say.png" class="img-fluid">
                </label>
                <div class="col-md-9">
                    <{$HomeworkContent_editor3|default:''}>
                </div>
            </div>

            <!--其他-->
            <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                    <{$smarty.const._MD_TCW_HOMEWORK_OTHER}>
                </label>
                <div class="col-md-9">
                    <{$HomeworkContent_editor4|default:''}>
                </div>
            </div>
        <{/if}>

        <!--相關附件-->
        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_HOMEWORK_FILES}>
            </label>
            <div class="col-md-9">
                <{$upform|default:''}>
            </div>
        </div>

        <div class="text-center">
            <!--編號-->
            <input type="hidden" name="HomeworkID" value="<{$HomeworkID|default:''}>">
            <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
            <input type="hidden" name="uid" value="<{$uid|default:''}>">
            <input type="hidden" name="op" value="<{$next_op|default:''}>">
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>  <{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
</div>
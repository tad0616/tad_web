<h2><{$HomeworkTitle}></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="homework.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_HOMEWORK}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item">
            <{if $cate.CateName}><a href="homework.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
        </li>
    <{/if}>
    <li class="breadcrumb-item"><{$HomeworkInfo}></li>
</ol>

<div style="min-height: 100px; overflow: hidden; line-height: 1.8; background-color: #FFFFFF; border: 2px solid #99C454; border-radius: 5px; margin:10px auto;">
    <{if $HomeworkContent}>
        <{$HomeworkContent}>
    <{else}>
        <div class="row">
            <{if $today_homework}>
                <div class="col-md-<{$ColWidth}>">
                    <div style="border-bottom: 1px solid #cfcfcf;">
                        <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TODAY_WORK}>" src="<{$xoops_url}>/modules/tad_web/images/today_homework.png" class="img-fluid img-responsive" style="margin:6px auto;">
                    </div>
                    <{$today_homework}>
                </div>
            <{/if}>
            <{if $bring}>
                <div class="col-md-<{$ColWidth}>">
                    <div style="border-bottom: 1px solid #cfcfcf;">
                        <img alt="<{$smarty.const._MD_TCW_HOMEWORK_BRING}>" src="<{$xoops_url}>/modules/tad_web/images/bring.png" class="img-fluid img-responsive" style="margin:6px auto;">
                    </div>
                    <{$bring}>
                </div>
            <{/if}>
            <{if $teacher_say}>
                <div class="col-md-<{$ColWidth}>">
                    <div style="border-bottom: 1px solid #cfcfcf;">
                        <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TEACHER_SAY}>" src="<{$xoops_url}>/modules/tad_web/images/teacher_say.png" class="img-fluid img-responsive" style="margin:6px auto;">
                    </div>
                    <{$teacher_say}>
                </div>
            <{/if}>
        </div>
        <{if $other}>
            <div class="alert alert-info"><{$other}></div>
        <{/if}>
    <{/if}>
</div>

<{if $HomeworkFiles}>
    <{$HomeworkFiles}>
<{/if}>



<{if $isCanEdit}>
    <div class="text-right text-end" style="margin: 30px 0px;">
        <a href="javascript:delete_homework_func(<{$HomeworkID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
        <a href="homework.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
        <a href="homework.php?WebID=<{$WebID}>&op=edit_form&HomeworkID=<{$HomeworkID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
    </div>
<{/if}>
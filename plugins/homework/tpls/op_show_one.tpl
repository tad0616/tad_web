<h2><{$HomeworkTitle|default:''}></h2>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="homework.php?WebID=<{$WebID|default:''}>"><{$smarty.const._MD_TCW_HOMEWORK}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item">
            <{if $cate.CateName|default:false}><a href="homework.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
        </li>
    <{/if}>
    <li class="breadcrumb-item"><{$HomeworkInfo|default:''}></li>
</ol>

<div style="min-height: 100px; overflow: hidden; line-height: 1.8; background-color: #FFFFFF; border: 2px solid #99C454; border-radius: 5px; margin:10px auto;">
    <{if $HomeworkContent|default:false}>
        <{$HomeworkContent|default:''}>
    <{else}>
        <div class="row">
            <{if $today_homework|default:false}>
                <div class="col-md-<{$ColWidth|default:''}>">
                    <div style="border-bottom: 1px solid #cfcfcf;">
                        <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TODAY_WORK}>" src="<{$xoops_url}>/modules/tad_web/images/today_homework.png" class="img-fluid img-responsive" style="margin:6px auto;">
                    </div>
                    <{$today_homework|default:''}>
                </div>
            <{/if}>
            <{if $bring|default:false}>
                <div class="col-md-<{$ColWidth|default:''}>">
                    <div style="border-bottom: 1px solid #cfcfcf;">
                        <img alt="<{$smarty.const._MD_TCW_HOMEWORK_BRING}>" src="<{$xoops_url}>/modules/tad_web/images/bring.png" class="img-fluid img-responsive" style="margin:6px auto;">
                    </div>
                    <{$bring|default:''}>
                </div>
            <{/if}>
            <{if $teacher_say|default:false}>
                <div class="col-md-<{$ColWidth|default:''}>">
                    <div style="border-bottom: 1px solid #cfcfcf;">
                        <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TEACHER_SAY}>" src="<{$xoops_url}>/modules/tad_web/images/teacher_say.png" class="img-fluid img-responsive" style="margin:6px auto;">
                    </div>
                    <{$teacher_say|default:''}>
                </div>
            <{/if}>
        </div>
        <{if $other|default:false}>
            <div class="alert alert-info"><{$other|default:''}></div>
        <{/if}>
    <{/if}>
</div>

<{if $HomeworkFiles|default:false}>
    <{$HomeworkFiles|default:''}>
<{/if}>



<{if $isCanEdit|default:false}>
    <div class="text-right text-end" style="margin: 30px 0px;">
        <a href="javascript:delete_homework_func(<{$HomeworkID|default:''}>);" class="btn btn-danger"><i class="fa fa-trash"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
        <a href="homework.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
        <a href="homework.php?WebID=<{$WebID|default:''}>&op=edit_form&HomeworkID=<{$HomeworkID|default:''}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
    </div>
<{/if}>
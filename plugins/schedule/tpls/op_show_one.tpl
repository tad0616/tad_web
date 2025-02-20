<h2><{$ScheduleName|default:''}></h2>

<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="schedule.php?WebID=<{$WebID|default:''}>"><{$smarty.const._MD_TCW_SCHEDULE}></a></li>
<{if isset($cate.CateID)}>
    <li class="breadcrumb-item"><a href="schedule.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{if $cate.CateName|default:false}><{$cate.CateName}><{else}><{$smarty.const._MD_TCW_SCHEDULE_UNNAMED_CATEGORY}><{/if}></a></li>
<{/if}>
<li class="breadcrumb-item"><{$ScheduleInfo|default:''}></li>
</ol>

<{$schedule_template|default:''}>

<div class="text-right text-end" style="margin: 30px 0px;">
    <{if $isMyWeb or $isCanEdit}>
        <a href="javascript:delete_schedule_func(<{$ScheduleID|default:''}>);" class="btn btn-danger"><i class="fa fa-trash"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_SCHEDULE_SHORT}></a>
        <a href="schedule.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_SCHEDULE_SHORT}></a>
        <a href="schedule.php?WebID=<{$WebID|default:''}>&op=edit_form&ScheduleID=<{$ScheduleID|default:''}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_SCHEDULE_SHORT}></a>
    <{/if}>
    <a href="<{$xoops_url}>/modules/tad_web/plugins/schedule/pdf.php?WebID=<{$WebID|default:''}>&ScheduleID=<{$ScheduleID|default:''}>" class="btn btn-primary"><{$smarty.const._MD_TCW_SCHEDULE_PDF}></a>
</div>
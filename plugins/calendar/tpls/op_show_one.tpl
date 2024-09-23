<h2><{$CalendarDate|default:''}><{$CalendarName|default:''}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="calendar.php?WebID=<{$WebID|default:''}>"><{$smarty.const._MD_TCW_CALENDAR}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item">
            <{if $cate.CateName|default:false}><a href="calendar.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
        </li>
    <{/if}>
    <li class="breadcrumb-item"><{$CalendarInfo|default:''}></li>
</ol>

<{if $CalendarDesc|default:false}>
    <div class="alert" style="background-color: #F5F9DB;"><{$CalendarDesc|default:''}></div>
<{/if}>



<{if $isMyWeb|default:false}>
    <div class="text-right text-end" style="margin: 30px 0px;">
        <a href="javascript:delete_calendar_func(<{$CalendarID|default:''}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
        <a href="setup.php?WebID=<{$WebID|default:''}>&plugin=calendar" class="btn btn-success"><i class="fa fa-wrench"></i> <{$smarty.const._MD_TCW_SETUP}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
        <a href="calendar.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
        <a href="calendar.php?WebID=<{$WebID|default:''}>&op=edit_form&CalendarID=<{$CalendarID|default:''}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
    </div>
<{/if}>
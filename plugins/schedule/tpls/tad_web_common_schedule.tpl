<{if $web_display_mode=='index' and $schedule_data}>
    <{if "$xoops_rootpath/uploads/tad_web/0/image/`$dirname`.png"|file_exists}>
        <a href="<{$xoops_url}>/modules/tad_web/<{$dirname|default:''}>.php"><img src="<{$xoops_url}>/uploads/tad_web/0/image/<{$dirname|default:''}>.png" alt="<{$schedule.PluginTitle}>"></a>
    <{else}>
        <h3><a href="<{$xoops_url}>/modules/tad_web/schedule.php"><{$schedule.PluginTitle}></a></h3>
    <{/if}>
<{elseif $web_display_mode=='index_plugin'}>
    <h2><a href="<{$xoops_url}>/modules/tad_web/"><i class="fa fa-home"></i></a> <{$schedule.PluginTitle}></h2>
<{/if}>

<{if $schedule_data|default:false}>
    <link href="<{$xoops_url}>/modules/tad_web/plugins/schedule/schedule.css" rel="stylesheet">
    <{if $WebID==""}>
        <div class="row">
            <{foreach item=act from=$schedule_data}>
                <{if $act.ScheduleDisplay=='1'}>
                    <div class="col-md-4">
                        <a href="schedule.php?WebID=<{$act.WebID}>&ScheduleID=<{$act.ScheduleID}>"><i class="fa fa-table"></i> <{$act.ScheduleName}>
                        </a>
                    </div>
                <{/if}>
            <{/foreach}>
        </div>
    <{else}>
        <{foreach item=act from=$schedule_data}>
            <{if $act.ScheduleDisplay=='1'}>
                <div style="margin: 8px auto;">
                    <h2>
                        <{if $act.cate.CateName|default:false}><a href='schedule.php?WebID=<{$act.WebID}>&CateID=<{$act.CateID}>'><{$act.cate.CateName}></a><{/if}>
                        <{if $act.ScheduleName|default:false}><a href='schedule.php?WebID=<{$act.WebID}>&ScheduleID=<{$act.ScheduleID}>'><{$act.ScheduleName}></a><{/if}>
                        <a href="<{$xoops_url}>/modules/tad_web/plugins/schedule/pdf.php?WebID=<{$WebID|default:''}>&ScheduleID=<{$act.ScheduleID}>"  class="text-success"><i class="fa fa-download "></i><span class="sr-only visually-hidden">download pdf</span></a>
                        <small>
                            <{*if $act.isCanEdit*}>
                            <{if ($WebID && $isMyWeb) || $smarty.session.tad_web_adm|default:false || (isset($act.cate.CateID) && isset($smarty.session.isAssistant.act) && $act.cate.CateID == $smarty.session.isAssistant.act)}>
                                <a href="javascript:delete_schedule_func(<{$act.ScheduleID}>);" class="text-danger"><i class="fa fa-trash"></i><span class="sr-only visually-hidden">delete</span></a>
                                <a href="schedule.php?WebID=<{$WebID|default:''}>&op=edit_form&ScheduleID=<{$act.ScheduleID}>"  class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                            <{/if}>
                        </small>
                    </h2>
                    <{$act.schedule}>
                </div>
            <{else}>
                <h2><{$smarty.const._MD_TCW_SCHEDULE_OTHER_LIST}></h2>
                <div>
                    <{if $act.cate}><a href='schedule.php?WebID=<{$act.WebID}>&CateID=<{$act.CateID}>'><{$act.cate.CateName}></a><{/if}>
                    <{if $act.ScheduleName}><a href='schedule.php?WebID=<{$act.WebID}>&ScheduleID=<{$act.ScheduleID}>'><{$act.ScheduleName}></a><{/if}>
                    <a href="<{$xoops_url}>/modules/tad_web/plugins/schedule/pdf.php?WebID=<{$WebID|default:''}>&ScheduleID=<{$act.ScheduleID}>"  class="text-success"><i class="fa fa-download "></i><span class="sr-only visually-hidden">download pdf</span></a>
                    <small>
                        <{*if $act.isCanEdit*}>
                        <{if ($WebID && $isMyWeb) || $smarty.session.tad_web_adm|default:false || (isset($act.cate.CateID) && isset($smarty.session.isAssistant.act) && $act.cate.CateID == $smarty.session.isAssistant.act)}>
                            <a href="javascript:delete_schedule_func(<{$act.ScheduleID}>);" class="text-danger"><i class="fa fa-trash"></i><span class="sr-only visually-hidden">delete</span></a>
                            <a href="schedule.php?WebID=<{$WebID|default:''}>&op=edit_form&ScheduleID=<{$act.ScheduleID}>"  class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                        <{/if}>
                    </small>
                </div>
            <{/if}>
        <{/foreach}>
    <{/if}>
    <div style="clear: both;"></div>

    <div style="text-align:right; margin: 0px 0px 10px;">
        <{if $isMyWeb and $WebID}>
            <a href="schedule.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_SCHEDULE_SHORT}></a>
        <{/if}>
    </div>
<{/if}>

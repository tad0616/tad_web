<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>

    <link href="<{$xoops_url}>/modules/tad_web/plugins/schedule/schedule.css" rel="stylesheet">

    <{if $WebID==""}>

        <{if $web_display_mode=='index' or $web_display_mode=='home'}>
            <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
        <{/if}>
        <{assign var="i" value=0}>
        <{assign var="total" value=1}>
        <{foreach item=act from=$bc.main_data}>
            <{if $act.ScheduleDisplay=='1'}>
                <{if $i==0}>
                <div class="row">
                <{/if}>
                    <div class="col-md-3 d-grid gap-2">
                        <a href="schedule.php?WebID=<{$act.WebID}>&ScheduleID=<{$act.ScheduleID}>" class="btn btn-link btn-block"><i class="fa fa-table"></i> <{$act.ScheduleName}>
                        </a>
                    </div>
                <{assign var="i" value=$i+1}>
                <{if $i == 4 || $total==$bc.schedule_amount}>
                    </div>
                    <{assign var="i" value=0}>
                <{/if}>
                <{assign var="total" value=$total+1}>
            <{/if}>
        <{/foreach}>
    <{else}>
        <{foreach item=act from=$bc.main_data}>
            <{if $act.ScheduleDisplay=='1'}>
                <div style="margin: 8px auto;">
                    <h2>
                        <{if $act.cate.CateName}><a href='schedule.php?WebID=<{$act.WebID}>&CateID=<{$act.CateID}>'><{$act.cate.CateName}></a><{/if}>
                        <{if $act.ScheduleName}><a href='schedule.php?WebID=<{$act.WebID}>&ScheduleID=<{$act.ScheduleID}>'><{$act.ScheduleName}></a><{/if}>
                        <a href="<{$xoops_url}>/modules/tad_web/plugins/schedule/pdf.php?WebID=<{$WebID}>&ScheduleID=<{$act.ScheduleID}>"  class="text-success"><i class="fa fa-download "></i><span class="sr-only visually-hidden">download pdf</span></a>
                        <small>
                        <{*if $act.isCanEdit*}>
                        <{if ($WebID && $isMyWeb) || $isAdmin || ($act.cate.CateID && $act.cate.CateID == $smarty.session.isAssistant.act)}>
                            <a href="javascript:delete_schedule_func(<{$act.ScheduleID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>
                            <a href="schedule.php?WebID=<{$WebID}>&op=edit_form&ScheduleID=<{$act.ScheduleID}>"  class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                        <{/if}>
                        </small>
                    </h2>
                    <{$act.schedule}>
                </div>
            <{/if}>
        <{/foreach}>
    <{/if}>
    <div style="clear: both;"></div>
<{/if}>

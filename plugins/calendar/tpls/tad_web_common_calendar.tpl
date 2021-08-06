<{if $web_display_mode=='index' and $calendar_data}>
    <h3><a href="<{$xoops_url}>/modules/tad_web/calendar.php"><{$calendar.PluginTitle}></a></h3>
<{elseif $web_display_mode=='index_plugin'}>
    <h2><a href="<{$xoops_url}>/modules/tad_web/">&#xf015;</a> <{$calendar.PluginTitle}></h2>
<{/if}>

<{$fullcalendar_code}>
<div id="calendar"></div>

<div style="text-align:right; margin: 0px 0px 10px;">
    <{if $web_display_mode=='index'}>
        <a href="calendar.php" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
    <{elseif $web_display_mode=='home' or $CalendarDefCateID}>
        <a href="calendar.php?WebID=<{$WebID}>" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
    <{/if}>

    <{if $isMyWeb and $WebID}>
        <a href="setup.php?WebID=<{$WebID}>&plugin=calendar" class="btn btn-success <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-wrench"></i> <{$smarty.const._MD_TCW_SETUP}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
        <a href="calendar.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
    <{/if}>
</div>
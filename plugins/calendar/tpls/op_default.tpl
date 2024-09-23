<h2><a href="index.php?WebID=<{$WebID|default:''}>"><i class="fa fa-home"></i></a> <{$calendar.PluginTitle}></h2>
<{if $isMyWeb and $WebID}>
    <a href="setup.php?WebID=<{$WebID|default:''}>&plugin=calendar" class="btn btn-success"><i class="fa fa-wrench"></i> <{$smarty.const._MD_TCW_SETUP}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
    <a href="calendar.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
<{else}>
    <div class="text-center">
        <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
    </div>
<{/if}>
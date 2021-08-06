<h2><a href="index.php?WebID=<{$WebID}>">&#xf015;</a> <{$schedule.PluginTitle}></h2>
<{if $isMyWeb  or $isCanEdit}>
    <a href="schedule.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_SCHEDULE_SHORT}></a>
<{else}>
    <div class="text-center">
        <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
    </div>
<{/if}>
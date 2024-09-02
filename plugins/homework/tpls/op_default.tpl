<h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$homework.PluginTitle}></h2>
<{if $isCanEdit}>
    <a href="homework.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
<{else}>
    <div class="text-center">
        <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
    </div>
<{/if}>
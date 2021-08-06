<h2><a href="index.php?WebID=<{$WebID}>">&#xf015;</a> <{$works.PluginTitle}></h2>
<{if $isMyWeb and $WebID}>
    <a href="works.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_WORKS_SHORT}></a>
<{else}>
    <div class="text-center">
        <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
    </div>
<{/if}>
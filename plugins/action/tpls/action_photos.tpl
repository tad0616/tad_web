<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
    <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <h4><a href="<{$xoops_url}>/modules/tad_web/action.php?WebID=<{$WebID}>&ActionID=<{$bc.ActionID}>"><{$bc.ActionName}></a></h4>
    <{$bc.main_data}>
<{/if}>
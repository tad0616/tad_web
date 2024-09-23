<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data|default:false}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <h4><a href="<{$xoops_url}>/modules/tad_web/action.php?WebID=<{$WebID|default:''}>&ActionID=<{$bc.ActionID}>"><{$bc.ActionName}></a></h4>
    <{$bc.main_data}>
<{/if}>
<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <h3><a href="works.php?WebID=<{$WebID}>&WorksID=<{$bc.main_data.0.WorksID}>"><{$bc.main_data.0.WorkName}></a><{if $bc.main_data.0.hide}><small><{$bc.main_data.0.hide}></small><{/if}></h3>
    <div class="text-center">
        <{$bc.main_data.0.pics}>
    </div>
<{/if}>

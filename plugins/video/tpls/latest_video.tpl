<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data|default:false}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <{$bc.main_data}>
    <a href="video.php?WebID=<{$WebID|default:''}>&VideoID=<{$bc.VideoID}>"><{$bc.VideoName}></a>
<{/if}>
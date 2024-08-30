<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <{$bc.main_data}>
    <a href="video.php?WebID=<{$WebID}>&VideoID=<{$bc.VideoID}>"><{$bc.VideoName}></a>
<{/if}>
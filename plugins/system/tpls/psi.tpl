<{assign var="bc" value=$block.BlockContent}>
<{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
<div style="background-color:white;">
    <{if $bc.config|is_array}>
    <iframe src='https://airtw.moenv.gov.tw/AirQuality_APIs/WebWidget.aspx?site=<{$bc.config.psi_site}>' width='100%' height='530px' scrolling='yes' style="border:none;" title="<{$smarty.const._MD_TCW_SYSTEM_BLOCK_PSI}>"></iframe>
    <{else}>
    <{/if}>
</div>
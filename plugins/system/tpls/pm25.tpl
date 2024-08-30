<{assign var="bc" value=$block.BlockContent}>
<{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
<div style="background-color:white;">
    <iframe src='https://airtw.moenv.gov.tw/AirQuality_APIs/WebWidget.aspx?site=<{$bc.config.pm25_site}>&mode=easy' width='100%' height='220px' scrolling='yes' style="border:none;" title="pm2.5"></iframe>
</div>
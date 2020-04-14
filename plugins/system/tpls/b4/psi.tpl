<{assign var="bc" value=$block.BlockContent}>
<{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
<iframe src='https://airtw.epa.gov.tw/AirQuality_APIs/WebWidget.aspx?site=<{$bc.config.psi_site}>' width='100%' height='530px' scrolling='yes' style="border:none;" title="<{$smarty.const._MD_TCW_SYSTEM_BLOCK_PSI}>"></iframe>
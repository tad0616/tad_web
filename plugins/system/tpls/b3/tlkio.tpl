<{assign var="bc" value=$block.BlockContent}>
<{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
<div class="well">
  <div id="tlkio" data-channel="<{$bc.config.tlkio_name}>" data-theme="theme--<{$bc.config.tlkio_theme}>" style="width:100%;height:<{$bc.config.tlkio_height}>px;"></div><script async src="https://tlk.io/embed.js" type="text/javascript"></script>
</div>
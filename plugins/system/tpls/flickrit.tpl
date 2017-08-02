<{assign var="bc" value=$block.BlockContent}>
<{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
<div style="position: relative; padding-bottom: 76%; height: 0; overflow: hidden;"><iframe id="iframe" src="//flickrit.com/<{$bc.config.flickrit_type}>.php?height=75&size=big&<{$bc.config.flickrit_kind}>=<{$bc.config.flickrit_setid}>&credit=1&thumbnails=0&transition=0&layoutType=responsive&sort=0" scrolling="no" frameborder="0"style="width:100%; height:100%; position: absolute; top:0; left:0;" ></iframe></div>

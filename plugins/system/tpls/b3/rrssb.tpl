<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
  <div style="margin:10px auto;"><{$bc.main_data}></div>
<{/if}>

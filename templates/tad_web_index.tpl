<{$toolbar}>
<{$FooTableJS}>
<link rel="stylesheet" type="text/css" media="screen" href="<{$xoops_url}>/modules/tad_web/module.css" />
<{if $op=="notice"}>
  <h3><{$Notice.NoticeTitle}></h3>
  <{$Notice.NoticeContent}>

<{elseif $show_arr}>
  <{foreach from=$show_arr item=dirname}>
    <{if "$xoops_rootpath/modules/tad_web/plugins/`$dirname`/tpls/tad_web_common_`$dirname`.tpl"|file_exists}>
      <div class="row">
        <div class="col-sm-12">
          <{includeq file="$xoops_rootpath/modules/tad_web/plugins/`$dirname`/tpls/tad_web_common_`$dirname`.tpl"}>
        </div>
      </div>
    <{/if}>
  <{/foreach}>
<{else}>
  <div class="row">
    <div class="col-sm-12">
      <img src="<{$xoops_url}>/modules/tad_web/images/comingsoon.png" alt="coming soon" class="img-rounded img-responsive" >
    </div>
  </div>
<{/if}>
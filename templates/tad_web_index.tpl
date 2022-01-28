<{$toolbar}>

<{if $op=="notice"}>
    <h3><{$Notice.NoticeTitle}></h3>
    <{$Notice.NoticeContent}>
<{elseif $show_arr}>
    <h3 class="sr-only visually-hidden">Over View</h3>
    <{foreach from=$show_arr item=dirname}>
        <{if "$xoops_rootpath/modules/tad_web/plugins/`$dirname`/tpls/tad_web_common_`$dirname`.tpl"|file_exists}>
            <{includeq file="$xoops_rootpath/modules/tad_web/plugins/`$dirname`/tpls/tad_web_common_`$dirname`.tpl"}>
        <{/if}>
    <{/foreach}>
<{else}>
    <h3 class="sr-only visually-hidden">coming Soon</h3>
    <img src="<{$xoops_url}>/modules/tad_web/images/comingsoon.png" alt="coming soon" class="img-rounded img-fluid img-responsive" >
<{/if}>
<{if $smarty.session.bootstrap==4}>
    <{includeq file="$xoops_rootpath/modules/tad_web/templates/blocks/b4/`$this_file`"}>
<{else}>
    <{includeq file="$xoops_rootpath/modules/tad_web/templates/blocks/b3/`$this_file`"}>
<{/if}>
<{if $tag|default:false}>
    <h2><{$tag|default:''}> <small>(<a href="tag.php?WebID=<{$WebID|default:''}>"><{$smarty.const._MD_TCW_TAGS_LIST}></a>)</small></h2>

    <{foreach from=$show_arr item=dirname}>
        <{if "$xoops_rootpath/modules/tad_web/plugins/`$dirname`/tpls/tad_web_common_`$dirname`.tpl"|file_exists}>
            <{include file="$xoops_rootpath/modules/tad_web/plugins/`$dirname`/tpls/tad_web_common_`$dirname`.tpl"}>
        <{/if}>
    <{/foreach}>
<{else}>
    <h2><{$smarty.const._MD_TCW_TAGS_LIST}></h2>
    <ul class="list-group">
        <{foreach from=$tags_arr key=tag item=count}>
            <li class="list-group-item">
                <span class="badge badge-info bg-info"><{$count|default:''}></span>
                <a href="<{$xoops_url}>/modules/tad_web/tag.php?WebID=<{$WebID|default:''}>&tag=<{$tag|default:''}>"><{$tag|default:''}></a>
            </li>
        <{/foreach}>
    </ul>
<{/if}>
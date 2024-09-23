<h2><{$smarty.const._MD_TCW_LEADERBOARD_RANK}></h2>
<{assign var="i" value=1}>
<ul class="list-group">
    <{foreach from=$king_rank key=WebID item=rank}>
        <{if $i <= 10}>
            <li class="list-group-item">
                <span class="badge badge-info bg-info"><{$rank|default:''}></span>
                <span class="badge badge-info bg-info"><{$i|default:''}></span>
                <a href="<{$xoops_url}>/modules/tad_web/index.php?WebID=<{$WebID|default:''}>" target="_blank"><{$WebNames.$WebID}> (<{$WebTitles.$WebID}>)</a>
            </li>
        <{/if}>
        <{assign var="i" value=$i+1}>
    <{/foreach}>
</ul>


<{foreach from=$all_top item=plugin}>
    <h2><{$plugin.pluginName}> <{$smarty.const._MD_TCW_LEADERBOARD}></h2>
    <ul class="list-group">
        <{foreach from=$plugin.top key=rank item=top}>
            <li class="list-group-item">
                <span class="badge badge-info bg-info"><{$top.count}></span>
                <span class="badge badge-info bg-info"><{$rank|default:''}></span>
                <a href="<{$xoops_url}>/modules/tad_web/<{$plugin.dirname}>.php?WebID=<{$top.WebID}>" target="_blank"><{$top.WebName}> (<{$top.WebTitle}>)</a>
            </li>
        <{/foreach}>
    </ul>
<{/foreach}>
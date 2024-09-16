<{if $op=="search"}>
    <h2><span style="color:rgb(134, 71, 90)"><{$search_keyword}></span> <{$smarty.const._MD_TCW_SEARCH_RESULT}></h2>
    <{foreach from=$all_result key=plugin item=plugin_data}>
        <{if $plugin_data|default:false}>
            <h3><{$plugin}></h3>
            <ul class="list-group">
                <{foreach from=$plugin_data item=result}>
                    <li class="list-group-item">
                        <a href="<{$result.link}>" target="_blank"><{$result.time}> <{$result.title}></a>
                    </li>
                <{/foreach}>
            </ul>
        <{/if}>
    <{/foreach}>
<{/if}>
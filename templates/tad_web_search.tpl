<{if $op=="search"}>
  <h2><{$smarty.const._MD_TCW_SEARCH_RESULT}></h2>
  <{foreach from=$result key=plugin item=data}>
    <{if $data}>
      <h3><{$plugin}></h3>
      <ul class="list-group">
        <{foreach from=$data item=result}>
          <li class="list-group-item">
            <a href="<{$result.link}>" target="_blank"><{$result.time}> <{$result.title}></a>
          </li>
        <{/foreach}>
      </ul>
    <{/if}>
  <{/foreach}>
<{/if}>
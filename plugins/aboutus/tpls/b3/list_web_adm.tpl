<{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
<ul class="list-group">
  <{foreach from=$admin_arr item=admin}>
    <li class="list-group-item">
      <a href="mailto:<{$admin.email}>"><{if $admin.name}><{$admin.name}> (<{$admin.uname}>)<{else}><{$admin.uname}><{/if}></a>
    </li>
  <{/foreach}>
</ul>
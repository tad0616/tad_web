<{if $menu_var.discuss.id}>
  <div class="btn-group btn-block">
    <a href="discuss.php?WebID=<{$LoginWebID}>" class="btn btn-primary "><{$menu_var.discuss.title}></a>
    <a href="discuss.php?WebID=<{$LoginWebID}>&op=edit_form" class="btn btn-success"><{$smarty.const._MD_TCW_ADD}><{$menu_var.discuss.short}></a>
  </div>
<{/if}>

<a href="aboutus.php?op=parent_logout&WebID=<{$LoginWebID}>" class="btn btn-danger btn-block"><{$smarty.const._MD_TCW_EXIT}></a>
<div class="d-grid gap-2">
    <{if $menu_var.discuss.id|default:false}>
        <div class="btn-group btn-block">
            <a href="discuss.php?WebID=<{$LoginWebID|default:''}>" class="btn btn-primary "><{$menu_var.discuss.title}></a>
            <a href="discuss.php?WebID=<{$LoginWebID|default:''}>&op=edit_form" class="btn btn-success"><{$smarty.const._MD_TCW_ADD}><{$menu_var.discuss.short}></a>
        </div>
    <{/if}>

    <a href="aboutus.php?op=parent_logout&WebID=<{$LoginWebID|default:''}>" class="btn btn-danger btn-block"><{$smarty.const._MD_TCW_EXIT}></a>
</div>

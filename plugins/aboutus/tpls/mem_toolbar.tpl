<div class="d-grid gap-2">
    <a href="aboutus.php?WebID=<{$LoginWebID|default:''}>&CateID=<{$default_class|default:''}>&MemID=<{$LoginMemID|default:''}>&op=edit_stu" class="btn btn-success btn-block"><{$smarty.const._MD_TCW_ABOUTUS_EDIT_ACCOUNT}></a>

    <{if $menu_var.discuss.id|default:false}>
        <div class="d-grid gap-2">
                <a href="discuss.php?WebID=<{$LoginWebID|default:''}>" class="btn btn-primary "><{$menu_var.discuss.title}></a>
                <a href="discuss.php?WebID=<{$LoginWebID|default:''}>&op=edit_form" class="btn btn-success"><{$smarty.const._MD_TCW_ADD}><{$menu_var.discuss.short}></a>
            </div>
    <{/if}>

    <a href="aboutus.php?op=mem_logout&WebID=<{$LoginWebID|default:''}>" class="btn btn-danger btn-block"><{$smarty.const._MD_TCW_EXIT}></a>
</div>
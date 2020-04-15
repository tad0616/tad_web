<a href="aboutus.php?WebID=<{$LoginWebID}>&CateID=<{$default_class}>&MemID=<{$LoginMemID}>&op=edit_stu" class="btn btn-success btn-block"><{$smarty.const._MD_TCW_ABOUTUS_EDIT_ACCOUNT}></a>

<{if $menu_var.discuss.id}>
    <div class="btn-group btn-block">
        <a href="discuss.php?WebID=<{$LoginWebID}>" class="btn btn-primary "><{$menu_var.discuss.title}></a>
        <a href="discuss.php?WebID=<{$LoginWebID}>&op=edit_form" class="btn btn-success"><{$smarty.const._MD_TCW_ADD}><{$menu_var.discuss.short}></a>
    </div>
<{/if}>

<a href="aboutus.php?op=mem_logout&WebID=<{$LoginWebID}>" class="btn btn-danger btn-block"><{$smarty.const._MD_TCW_EXIT}></a>
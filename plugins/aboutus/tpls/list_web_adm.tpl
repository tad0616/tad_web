<{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
<ul class="list-group">
    <{if $admin_arr|default:false}>
        <{foreach from=$admin_arr item=admin}>
            <li class="list-group-item">
                <a href="mailto:<{$admin.email}>"><{if $admin.name|default:''}><{$admin.name|default:''}> (<{$admin.uname|default:''}>)<{else}><{$admin.uname|default:''}><{/if}></a>
            </li>
        <{/foreach}>
    <{/if}>
</ul>
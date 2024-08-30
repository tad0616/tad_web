<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <ul class="list-group">
        <{foreach from=$bc.cate_menu item=account}>
            <li  class="list-group-item">
                <span class="badge badge-info"><{$account.PageCount}></span>
                <a href='account.php?WebID=<{$account.WebID}>&CateID=<{$account.CateID}>'><{$account.CateName}></a>
            </li>
        <{/foreach}>
    </ul>
<{/if}>
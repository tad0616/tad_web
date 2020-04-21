<{if $isMyWeb and $WebID}>
    <div class="block_config_tool">
        <a href="block.php?WebID=<{$WebID}>&op=config&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="btn btn-warning btn-sm btn-xs" title="<{$smarty.const._MD_TCW_BLOCKS_SETUP}>"><i class="fa fa-wrench"></i> <{$smarty.const._MD_TCW_BLOCKS_SETUP}></a>

        <{assign var="block_plugin" value=$block.plugin}>

        <{if $block_plugin!='custom'}>
            <{if $menu_var.$block_plugin.add=='1'}>
                <a href="<{$block_plugin}>.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info btn-sm btn-xs" title="<{$smarty.const._MD_TCW_ADD}>"><i class="fa fa-plus"></i></a>
            <{else}>
                無新增
            <{/if}>
            <{if $menu_var.$block_plugin.menu=='1'}>
                <a href="<{$block_plugin}>.php?WebID=<{$WebID}>" class="btn btn-success btn-sm btn-xs" title="<{$smarty.const._MD_TCW_MORE}>"><i class="fa fa-eye"></i></a>
            <{else}>
                無選單
            <{/if}>
        <{/if}>
    </div>
<{/if}>
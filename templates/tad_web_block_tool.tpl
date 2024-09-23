<{if $isMyWeb and $WebID}>
    <div class="block_config_tool">
        <a href="block.php?WebID=<{$WebID|default:''}>&op=config&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="btn btn-warning btn-sm btn-xs" title="<{$smarty.const._MD_TCW_BLOCKS_SETUP}>"><i class="fa fa-wrench"></i> <{$smarty.const._MD_TCW_BLOCKS_SETUP}></a>

        <{assign var="block_plugin" value=$block.plugin}>

        <{if $block_plugin!='custom'}>
            <{if $plugin_menu_var.$block_plugin.add|default:false=='1'}>
                <a href="<{$block_plugin|default:''}>.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info btn-sm btn-xs" title="<{$smarty.const._MD_TCW_ADD}>"><i class="fa fa-plus"></i></a>
            <{/if}>
            <{if $plugin_menu_var.$block_plugin.menu|default:false=='1'}>
                <a href="<{$block_plugin|default:''}>.php?WebID=<{$WebID|default:''}>" class="btn btn-success btn-sm btn-xs" title="<{$smarty.const._MD_TCW_MORE}>"><i class="fa fa-ellipsis-h"></i></a>
            <{else}>
            <{/if}>
        <{/if}>
    </div>
<{/if}>
<{if $block.who_can_read=='' || ($block.who_can_read=='users' && $xoops_isuser|default:false) || ($block.who_can_read=='web_users' && $LoginWebID==$WebID) || ($block.who_can_read=='web_admin' && $isMyWeb)}>
    <div class="tad_web_block">
        <!-- <{$block.BlockTitle}> -->
        <{if $block.plugin=="xoops"}>
            <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>

            <{if $xoops_version < 20511}>
                <{include file="$xoops_rootpath/modules/tad_web/templates/block.tpl"}>
            <{else}>
                <{include file="$xoops_rootpath/modules/tad_web/templates/xoblock.tpl"}>
            <{/if}>

        <{elseif $block.plugin=="custom" || $block.plugin=="share"}>
            <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_custom.tpl"}>
        <{else}>
            <{if $block.tpl && "$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/`$block.tpl`"|file_exists}>
                <{include file="$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/`$block.tpl`"}>
            <{/if}>
        <{/if}>

        <{if $isMyWeb and $WebID and ((isset($block.BlockContent.main_data) and $block.BlockContent.main_data) or $block.plugin=="custom")}>
            <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_tool.tpl"}>
        <{/if}>
    </div>
<{/if}>
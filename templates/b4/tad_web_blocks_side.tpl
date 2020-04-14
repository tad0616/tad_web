<{foreach from=$side_block item=block}>
    <div class="tad_web_block">
        <!-- <{$block.BlockTitle}> -->
        <{if $block.plugin=="xoops"}>
            <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
            <{block id=$block.BlockName}>
        <{elseif $block.plugin=="custom"}>
            <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_custom.tpl"}>
        <{else}>
            <{if "$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/b4/`$block.tpl`"|file_exists}>
                <{includeq file="$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/b4/`$block.tpl`"}>
            <{/if}>
        <{/if}>

        <{if $isMyWeb and $WebID and isset($block.BlockContent.main_data) and $block.BlockContent.main_data!=""}>
            <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_tool.tpl"}>
        <{/if}>
    </div>
<{/foreach}>
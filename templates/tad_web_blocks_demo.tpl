<div class="tad_web_block">
    <!-- <{$block.BlockTitle}> -->
    <{if $block.plugin=="xoops"}>
        <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>

        <{if $xoops_version < 20511}>
            <{include file="$xoops_rootpath/modules/tad_web/templates/block.tpl"}>
        <{else}>
            <{if isset($block.options)}>
                <{xoBlock id=$block.BlockName options=$block.options}>
            <{else}>
                <{xoBlock id=$block.BlockName}>
            <{/if}>
        <{/if}>

    <{elseif $block.plugin=="custom"}>
        <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_custom.tpl"}>
    <{elseif $block.plugin=="share"}>
        <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_custom.tpl"}>
        <div class="alert alert-info">from: <a href="<{$xoops_url}>/modules/tad_web/index.php?WebID=<{$share_info.WebID}>" target="_blank"><{$share_info.WebName}>(<{$share_info.WebTitle}>)</a></div>
    <{else}>
        <{if $block.BlockContent.main_data}>
            <{if "$xoops_rootpath/modules/tad_web/plugins/`$block.plugin.dirname`/tpls/`$block.tpl`"|file_exists}>
                <{include file="$xoops_rootpath/modules/tad_web/plugins/`$block.plugin.dirname`/tpls/`$block.tpl`"}>
            <{elseif "$xoops_rootpath/modules/tad_web/plugins/system/tpls/`$block.tpl`"|file_exists}>
                <{include file="$xoops_rootpath/modules/tad_web/plugins/system/tpls/`$block.tpl`"}>
            <{else}>
                no template<br>
                <{"$xoops_rootpath/modules/tad_web/plugins/`$block.plugin.dirname`/tpls/`$block.tpl`"}>
            <{/if}>
        <{else}>
            <{$smarty.const._MD_TCW_BLOCK_EMPTY}>
        <{/if}>
    <{/if}>
</div>
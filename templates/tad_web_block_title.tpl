<{if $block.config.show_title=='1' or $block.config.show_title!='0'}>
    <{if $use_block_pic=='1'}>
        <img src="<{$xoops_url}>/uploads/tad_web/<{$WebID}>/image/block_<{$block.BlockID}>.png" alt="<{$block.BlockTitle}>">
    <{else}>
        <{if $block.BlockTitle}><h3><{$block.BlockTitle}></h3><{/if}>
    <{/if}>
<{/if}>
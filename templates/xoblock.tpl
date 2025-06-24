<{if isset($block.options)}>
    <{xoBlock id=$block.BlockName options=$block.options}>
<{else}>
    <{xoBlock id=$block.BlockName}>
<{/if}>
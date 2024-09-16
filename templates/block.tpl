<{if $block.options|default:false}>
    <{block name="$block.BlockName" id=$block.BlockName options=$block.options}>
<{else}>
    <{block name="$block.BlockName" id=$block.BlockName}>
<{/if}>
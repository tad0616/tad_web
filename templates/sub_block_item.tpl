<li id="<{$block.BlockID}>" data-toggle="tooltip" title="<{$block.PluginTitle}> (<{$block.plugin}>)" class="ui-state-highlight <{if $block.BlockShare=="1"}>share_block<{elseif $block.plugin=="custom"}>custom_block<{/if}>">
    <span id="blktool_<{$block.BlockID}>" class="pull-right"><{if $block.plugin!="custom" and $block.plugin!="share" and $block.BlockCopy==0}><a href="block.php?WebID=<{$WebID}>&op=copy&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-green"><i class="fa fa-files-o"></i></a><{/if}> <a href="block.php?WebID=<{$WebID}>&op=config&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-danger"><i class="fa fa-pencil"></i><span class="sr-only">edit</span></a></span>
    <{$block.icon}>

    <{if $block.BlockTitle}>
        <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockTitle}></a>
    <{else}>
        <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockID}></a>
    <{/if}>
</li>
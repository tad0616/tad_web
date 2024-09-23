<li id="<{$block.BlockID}>" data-toggle="tooltip" title="<{$block.PluginTitle}> (<{$block.plugin}>)" class="ui-state-highlight <{if $block.BlockShare=="1"}>share_block<{elseif $block.plugin=="custom"}>custom_block<{/if}>">
    <span id="blktool_<{$block.BlockID}>" class="pull-right float-right pull-end"><{if $block.plugin!="custom" and $block.plugin!="share" and $block.BlockCopy==0}><a href="block.php?WebID=<{$WebID|default:''}>&op=copy&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-green"><i class="fa fa-files-o"></i></a><{/if}> <a href="block.php?WebID=<{$WebID|default:''}>&op=config&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-danger"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a></span>
    <{$block.icon}>

    <{if $block.BlockTitle|default:false}>
        <a href="block.php?WebID=<{$WebID|default:''}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockTitle}></a>
    <{else}>
        <a href="block.php?WebID=<{$WebID|default:''}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockID}></a>
    <{/if}>
</li>
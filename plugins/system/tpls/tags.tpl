<{assign var="bc" value=$block.BlockContent}>
<{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
<{if $bc.main_data|default:false}>
    <{if $bc.config.tags_mode=="cloud"}>
        <script type="text/javascript" src="<{$xoops_url}>/modules/tad_web/class/jQCloud/jqcloud-1.0.4.js"></script>
        <link rel="stylesheet" type="text/css" href="<{$xoops_url}>/modules/tad_web/class/jQCloud/jqcloud.css">
        <script type="text/javascript">
            var word_list = [
            <{foreach from=$bc.tags_arr key=tag item=count}>
                {text: "<{$tag|default:''}>", weight: <{$count|default:''}>, link: "<{$xoops_url}>/modules/tad_web/tag.php?WebID=<{$WebID|default:''}>&tag=<{$tag|default:''}>"},
            <{/foreach}>
            ];
            $(function() {
                $("#my_words").jQCloud(word_list);
            });
        </script>
        <div id="my_words" style="width: 100%; min-height: <{$bc.config.min_height}>px;"></div>
    <{else}>
        <ul class="list-group">
            <{foreach from=$bc.tags_arr key=tag item=count}>
                <li class="list-group-item">
                    <span class="badge badge-info bg-info"><{$count|default:''}></span>
                    <a href="<{$xoops_url}>/modules/tad_web/tag.php?WebID=<{$WebID|default:''}>&tag=<{$tag|default:''}>"><{$tag|default:''}></a>
                </li>
            <{/foreach}>
        </ul>
    <{/if}>
<{/if}>
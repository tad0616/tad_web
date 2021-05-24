<{foreach from=$side_block item=block}>
    <{includeq file="$xoops_rootpath/modules/tad_web/templates/sub_tad_web_block.tpl"}>
<{/foreach}>

<script type="text/javascript">
    $(document).ready(function(){
        $('.tad_web_block').hover(function(){
            $('.block_config_tool',this).css('display','block');
        },function(){
            $('.block_config_tool',this).css("display","none");
        });
    });
</script>
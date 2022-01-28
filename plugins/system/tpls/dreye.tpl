<{assign var="bc" value=$block.BlockContent}>
<{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
<script type="text/javascript">
    $(document).ready(function(){
        $("#get_dreyedict").click(function(event) {
            $("#get_dreyedict").attr("href","https://yun.dreye.com/dict_new/dict_min.php?w="+$('#search_dreyedict').val());
        });

        $("#search_dreyedict").keypress(function(e){
            code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13)
            {
                $.colorbox({
                href:"https://yun.dreye.com/dict_new/dict_min.php?w="+$('#search_dreyedict').val(),
                iframe:true ,
                width:'640' ,
                height:'90%'});
            }
        });
    });
</script>
<div class="input-group">
    <label for="search_dreyedict" class="sr-only visually-hidden"><{$smarty.const._MD_TCW_SEARCH_DREYEDICT_KEYWORD}></label>
    <input type="text" id="search_dreyedict" class="form-control" placeholder="<{$smarty.const._MD_TCW_SEARCH_DREYEDICT_KEYWORD}>">
    <div class="input-group-append input-group-btn">
        <a href="#" class="btn btn-primary" id="get_dreyedict"><{$smarty.const._MD_TCW_SEARCH_DREYEDICT}></a>
    </div>
</div>
<{assign var="bc" value=$block.BlockContent}>
<{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
<script type="text/javascript">
    $(document).ready(function(){
        $("#get_wiki").click(function(event) {
            $("#get_wiki").attr("href","https://zh.m.wikipedia.org/wiki/"+$('#search_wiki').val());
        });

        $("#search_wiki").keypress(function(e){
            code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13)
            {
                $.colorbox({
                href:"https://zh.m.wikipedia.org/wiki/"+$('#search_wiki').val(),
                iframe:true ,
                width:'80%' ,
                height:'90%'});
            }
        });
    });
</script>
<div class="input-group">
    <label for="search_wiki" class="sr-only visually-hidden"><{$smarty.const._MD_TCW_SEARCH_WIKI_KEYWORD}></label>
    <input type="text" id="search_wiki" class="form-control" placeholder="<{$smarty.const._MD_TCW_SEARCH_WIKI_KEYWORD}>">
    <div class="input-group-append input-group-btn">
        <a href="#" class="btn btn-primary" id="get_wiki"><{$smarty.const._MD_TCW_SEARCH_WIKI}></a>
    </div>
</div>
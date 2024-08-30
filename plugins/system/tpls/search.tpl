<{assign var="bc" value=$block.BlockContent}>
<{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>

<form action="<{$xoops_url}>/modules/tad_web/search.php" method="get" role="form" id="tad_web_search">
    <input type="hidden" name="WebID" value="<{$WebID}>">
    <div class="input-group">
        <input type="text" name="search_keyword" class="form-control" title="Search for..."placeholder="Search for...">
        <div class="input-group-append input-group-btn">
            <input type="hidden" name="op" value="search">
            <button class="btn btn-primary" type="submit"><{$smarty.const._MD_TCW_SEARCH}></button>
        </div>
    </div>
</form>
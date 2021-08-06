<script type="text/javascript">
    $(document).ready(function(){
        $('#list_page img').css('width','').css('height','').addClass('img-fluid');
    });
</script>

<h2><{$PageTitle}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="page.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_PAGE}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item"><a href="page.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a></li>
    <{/if}>
    <li class="breadcrumb-item"><{$PageInfo}></li>
    <{if $tags}>
        <li class="breadcrumb-item"><{$tags}></li>
    <{/if}>
</ol>

<{if $PageContent}>
    <div id="list_page" style="min-height: 100px; overflow: auto; border-radius: 5px; margin:10px auto; padding: 20px;<{if $PageCSS}><{$PageCSS}><{else}>line-height: 2; font-size:120%; background: #FFFFFF;<{/if}>"><{$PageContent}></div>
<{/if}>

<{$files}>

<div class="row" id="page_tool">
    <div class="col-md-6 text-left"><a href="page.php?WebID=<{$WebID}>&PageID=<{$prev_next.prev.PageID}>" class="btn btn-secondary btn-block">&#xf053; <{$prev_next.prev.PageTitle}></a></div>
    <div class="col-md-6 text-right"><a href="page.php?WebID=<{$WebID}>&PageID=<{$prev_next.next.PageID}>" class="btn btn-secondary btn-block"><{$prev_next.next.PageTitle}> &#xf054;</a></div>
</div>

<{$fb_comments}>

<div id="adm_bar" class="text-right" style="margin: 30px 0px;">
    <{if $isMyWeb or $isCanEdit}>
        <a href="javascript:delete_page_func(<{$PageID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_PAGE_SHORT}></a>
        <a href="page.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_PAGE_SHORT}></a>
        <a href="page.php?WebID=<{$WebID}>&op=edit_form&PageID=<{$PageID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_PAGE_SHORT}></a>
    <{/if}>
    <a class="btn btn-success print-preview"><i class="fa fa-print"></i> <{$smarty.const._MD_TCW_PRINT}></a>
</div>
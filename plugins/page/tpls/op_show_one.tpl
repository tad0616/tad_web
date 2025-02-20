<script type="text/javascript">
    $(document).ready(function(){
        $('#list_page img').css('width','').css('height','').addClass('img-fluid');
    });
</script>

<h2><{$PageTitle|default:''}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="page.php?WebID=<{$WebID|default:''}>"><{$smarty.const._MD_TCW_PAGE}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item">
            <{if $cate.CateName|default:false}><a href="page.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
        </li>
    <{/if}>
    <li class="breadcrumb-item"><{$PageInfo|default:''}></li>
    <{if $tags|default:false}>
        <li class="breadcrumb-item"><{$tags|default:''}></li>
    <{/if}>
</ol>

<{if $PageContent|default:false}>
    <div id="list_page" style="min-height: 100px; overflow: auto; border-radius: 5px; margin:10px auto; padding: 20px;<{if $PageCSS|default:false}><{$PageCSS|default:''}><{else}>line-height: 2; font-size:120%; background: #FFFFFF;<{/if}>"><{$PageContent|default:''}></div>
<{/if}>

<{$files|default:''}>

<div class="row" id="page_tool">
    <{if $prev_next.next.PageID|default:false}>
    <div class="col-md-6 text-left text-start d-grid gap-2"><a href="page.php?WebID=<{$WebID|default:''}>&PageID=<{$prev_next.prev.PageID}>" class="btn btn-secondary btn-block"><i class="fa fa-chevron-left"></i> <{$prev_next.prev.PageTitle}></a></div>
    <{/if}>
    <{if $prev_next.next.PageID|default:false}>
    <div class="col-md-6 text-right text-end d-grid gap-2"><a href="page.php?WebID=<{$WebID|default:''}>&PageID=<{$prev_next.next.PageID}>" class="btn btn-secondary btn-block"><{$prev_next.next.PageTitle}> <i class="fa fa-chevron-right"></i></a></div>
    <{/if}>
</div>



<div id="adm_bar" class="text-right text-end" style="margin: 30px 0px;">
    <{if $isMyWeb or $isCanEdit}>
        <a href="javascript:delete_page_func(<{$PageID|default:''}>);" class="btn btn-danger"><i class="fa fa-trash"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_PAGE_SHORT}></a>
        <a href="page.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_PAGE_SHORT}></a>
        <a href="page.php?WebID=<{$WebID|default:''}>&op=edit_form&PageID=<{$PageID|default:''}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_PAGE_SHORT}></a>
    <{/if}>
    <a class="btn btn-success print-preview"><i class="fa fa-print"></i> <{$smarty.const._MD_TCW_PRINT}></a>
</div>
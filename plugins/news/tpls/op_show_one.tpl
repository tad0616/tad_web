<script type="text/javascript">
    $(document).ready(function(){
        $('#list_new img').css('width','').css('height','').addClass('img-fluid');
    });
</script>

<h2><{$NewsTitle|default:''}><{if $NewsEnable!=1}><small>[<{$smarty.const._MD_TCW_NEWS_DRAFT}>]</small><{/if}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="news.php?WebID=<{$WebID|default:''}>"><{$smarty.const._MD_TCW_NEWS}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item">
            <{if $cate.CateName|default:false}><a href="news.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
        </li>
    <{/if}>
    <li class="breadcrumb-item"><{$NewsInfo|default:''}></li>
    <{if $tags|default:false}>
        <li class="breadcrumb-item"><{$tags|default:''}></li>
    <{/if}>
</ol>

<{if $NewsContent|default:false}>
    <div id="list_new" style="background-color: #fefefe; line-height: 2; font-size:120%; ">
        <{$NewsContent|default:''}>
    </div>
<{/if}>

<{if $NewsContent==''}>
    <div class="my-border" style="font-size:2em;">
        <{$NewsUrlTxt|default:''}>
    </div>
<{else}>
    <{$NewsUrlTxt|default:''}>
<{/if}>

<{$NewsFiles|default:''}>

<div class="row" id="News_tool">
    <{if $prev_next.prev.NewsID|default:false}>
    <div class="col-md-6 text-left text-start d-grid gap-2"><a href="news.php?WebID=<{$WebID|default:''}>&NewsID=<{$prev_next.prev.NewsID}>" class="btn btn-secondary btn-block"><i class="fa fa-chevron-left"></i> <{$prev_next.prev.NewsTitle}></a></div>
    <{/if}>
    <{if $prev_next.next.NewsID|default:false}>
    <div class="col-md-6 text-right text-end d-grid gap-2"><a href="news.php?WebID=<{$WebID|default:''}>&NewsID=<{$prev_next.next.NewsID}>" class="btn btn-secondary btn-block"><{$prev_next.next.NewsTitle}> <i class="fa fa-chevron-right"></i></a></div>
    <{/if}>
</div>



<div id="adm_bar" class="text-right text-end" style="margin: 30px 0px;">
    <{if $isMyWeb or $isCanEdit}>
        <a href="javascript:delete_news_func(<{$NewsID|default:''}>);" class="btn btn-danger"><i class="fa fa-trash"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
        <a href="news.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
        <a href="news.php?WebID=<{$WebID|default:''}>&op=edit_form&NewsID=<{$NewsID|default:''}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
    <{/if}>

    <a class="btn btn-success print-preview"><i class="fa fa-print"></i> <{$smarty.const._MD_TCW_PRINT}></a>
</div>
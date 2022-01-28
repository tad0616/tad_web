<script type="text/javascript">
    $(document).ready(function(){
        $('#list_new img').css('width','').css('height','').addClass('img-fluid');
    });
</script>

<h2><{$NewsTitle}><{if $NewsEnable!=1}><small>[<{$smarty.const._MD_TCW_NEWS_DRAFT}>]</small><{/if}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="news.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_NEWS}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item">
            <{if $cate.CateName}><a href="news.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
        </li>
    <{/if}>
    <li class="breadcrumb-item"><{$NewsInfo}></li>
    <{if $tags}>
        <li class="breadcrumb-item"><{$tags}></li>
    <{/if}>
</ol>

<{if $NewsContent}>
    <div id="list_new" style="background-color: #fefefe; line-height: 2; font-size:120%; ">
        <{$NewsContent}>
    </div>
<{/if}>

<{if $NewsContent==''}>
    <div class="my-border" style="font-size:2em;">
        <{$NewsUrlTxt}>
    </div>
<{else}>
    <{$NewsUrlTxt}>
<{/if}>

<{$NewsFiles}>

<div class="row" id="News_tool">
    <div class="col-md-6 text-left text-start d-grid gap-2"><a href="news.php?WebID=<{$WebID}>&NewsID=<{$prev_next.prev.NewsID}>" class="btn btn-secondary btn-block">&#xf053; <{$prev_next.prev.NewsTitle}></a></div>
    <div class="col-md-6 text-right text-end d-grid gap-2"><a href="news.php?WebID=<{$WebID}>&NewsID=<{$prev_next.next.NewsID}>" class="btn btn-secondary btn-block"><{$prev_next.next.NewsTitle}> &#xf054;</a></div>
</div>

<{$fb_comments}>

<div id="adm_bar" class="text-right text-end" style="margin: 30px 0px;">
    <{if $isMyWeb or $isCanEdit}>
        <a href="javascript:delete_news_func(<{$NewsID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
        <a href="news.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
        <a href="news.php?WebID=<{$WebID}>&op=edit_form&NewsID=<{$NewsID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
    <{/if}>

    <a class="btn btn-success print-preview"><i class="fa fa-print"></i> <{$smarty.const._MD_TCW_PRINT}></a>
</div>
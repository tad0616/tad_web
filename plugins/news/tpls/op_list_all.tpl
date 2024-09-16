<{if $WebID|default:false}>
    <div class="row">
        <div class="col-md-8">
            <{$cate_menu}>
        </div>
        <div class="col-md-4 text-right text-end">
            <{if $isMyWeb and $WebID}>
                <a href="cate.php?WebID=<{$WebID}>&ColName=news&table=tad_web_news" class="btn btn-warning <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-folder-open"></i> <{$smarty.const._MD_TCW_CATE_TOOLS}></a>
                <a href="news.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
            <{/if}>
        </div>
    </div>
<{/if}>

<{if $news_data|default:false}>
    <{include file="$xoops_rootpath/modules/tad_web/plugins/news/tpls/tad_web_common_news.tpl"}>
<{else}>
    <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$news.PluginTitle}></h2>
    <div class="alert alert-info"><{$smarty.const._MD_TCW_EMPTY}></div>
<{/if}>
<div class="clearfix"></div>
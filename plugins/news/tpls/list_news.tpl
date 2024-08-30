<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
    <{if $block.config.show_mode=="list"}>
        <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
        <ul class="list-group">
            <{foreach from=$bc.main_data key=i item=news}>
                <li class="list-group-item">
                    <{$news.Date}>
                    <{if isset($news.cate.CateID)}>
                        <span class="badge badge-info"><a href="news.php?WebID=<{$WebID}>&CateID=<{$news.cate.CateID}>" style="color: #FFFFFF;"><{$news.cate.CateName}></a></span>
                    <{/if}>
                    <a href="news.php?WebID=<{$news.WebID}>&NewsID=<{$news.NewsID}>"><{$news.NewsTitle}></a>
                    <{if $news.NewsEnable!=1}>[<{$smarty.const._MD_TCW_NEWS_DRAFT}>]<{/if}>

                    <{*if $news.isCanEdit*}>
                    <{if ($WebID && $isMyWeb) || $isAdmin || ($news.cate.CateID && $news.cate.CateID == $smarty.session.isAssistant.news)}>
                        <a href="javascript:delete_news_func(<{$news.NewsID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>
                        <a href="news.php?WebID=<{$news.WebID}>&op=edit_form&NewsID=<{$news.NewsID}>" class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                    <{/if}>
                </li>
            <{/foreach}>
        </ul>
    <{elseif $block.config.show_mode=="full"}>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#list_new img').css('height','').addClass('img-fluid');
            });
        </script>
        <{foreach from=$bc.main_data key=i item=news}>
            <{if $news.NewsContent}>
                <h3>
                    <{$news.Date}>
                    <a href="news.php?WebID=<{$WebID}>&NewsID=<{$news.NewsID}>"><{$news.NewsTitle}></a>
                    <{if $news.NewsEnable!=1}>[<{$smarty.const._MD_TCW_NEWS_DRAFT}>]<{/if}>

                    <{*if $news.isCanEdit*}>
                    <{if ($WebID && $isMyWeb) || $isAdmin || ($news.cate.CateID && $news.cate.CateID == $smarty.session.isAssistant.news)}>
                        <a href="javascript:delete_news_func(<{$news.NewsID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>
                        <a href="news.php?WebID=<{$WebID}>&op=edit_form&NewsID=<{$news.NewsID}>"class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                    <{/if}>
                </h3>
                <div class="my-border" id="list_new" style="min-height: 100px; overflow: auto; line-height: 1.8; ">
                    <{if isset($news.cate.CateID)}>
                        <span class="badge badge-info"><a href="news.php?WebID=<{$WebID}>&CateID=<{$news.cate.CateID}>" style="color: #FFFFFF;"><{$news.cate.CateName}></a></span>
                    <{/if}>
                    <{$news.NewsContent}>
                    <{if $news.more}>
                        <a href="news.php?WebID=<{$WebID}>&NewsID=<{$news.NewsID}>"><{$smarty.const._MD_TCW_READ_MORE}></a>
                    <{/if}>
                </div>
            <{else}>
                <div class="my-border" style="height: 100px; overflow: auto; line-height: 1.8; ">
                <h3>
                    <{$news.Date}>
                    <a href="news.php?WebID=<{$WebID}>&NewsID=<{$news.NewsID}>"><{$news.NewsTitle}></a>
                    <{if $news.NewsEnable!=1}>[<{$smarty.const._MD_TCW_NEWS_DRAFT}>]<{/if}>

                    <{*if $news.isCanEdit*}>
                    <{if ($WebID && $isMyWeb) || $isAdmin || ($news.cate.CateID && $news.cate.CateID == $smarty.session.isAssistant.news)}>
                        <a href="javascript:delete_news_func(<{$news.NewsID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>
                        <a href="news.php?WebID=<{$WebID}>&op=edit_form&NewsID=<{$news.NewsID}>" class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                    <{/if}>
                </h3>
                </div>
            <{/if}>
        <{/foreach}>
    <{else}>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#list_new img').css('height','').addClass('img-fluid');
            });
        </script>
        <{if $bc.main_data.0.NewsContent}>
            <h3>
                <{$bc.main_data.0.Date}>
                <a href="news.php?WebID=<{$WebID}>&NewsID=<{$bc.main_data.0.NewsID}>"><{$bc.main_data.0.NewsTitle}></a>
                <{if $bc.main_data.0.NewsEnable!=1}>[<{$smarty.const._MD_TCW_NEWS_DRAFT}>]<{/if}>

                <{*if $bc.main_data.0.isCanEdit*}>
                <{if ($WebID && $isMyWeb) || $isAdmin || ($bc.main_data.0.cate.CateID && $bc.main_data.0.cate.CateID == $smarty.session.isAssistant.news)}>
                    <a href="javascript:delete_news_func(<{$bc.main_data.0.NewsID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>
                    <a href="news.php?WebID=<{$WebID}>&op=edit_form&NewsID=<{$bc.main_data.0.NewsID}>"class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                <{/if}>
            </h3>
            <div class="my-border" id="list_new" style="min-height: 100px; overflow: auto; line-height: 1.8; ">
                <{if isset($bc.main_data.0.cate.CateID)}>
                    <span class="badge badge-info"><a href="news.php?WebID=<{$WebID}>&CateID=<{$bc.main_data.0.cate.CateID}>" style="color: #FFFFFF;"><{$bc.main_data.0.cate.CateName}></a></span>
                <{/if}>
                <{$bc.main_data.0.NewsContent}>
                <{if $bc.main_data.0.more}>
                    <a href="news.php?WebID=<{$WebID}>&NewsID=<{$bc.main_data.0.NewsID}>"><{$smarty.const._MD_TCW_READ_MORE}></a>
                <{/if}>
            </div>
        <{else}>
            <div class="my-border" style="height: 100px; overflow: auto; line-height: 1.8; ">
                <h3>
                    <{$bc.main_data.0.Date}>
                    <a href="news.php?WebID=<{$WebID}>&NewsID=<{$bc.main_data.0.NewsID}>"><{$bc.main_data.0.NewsTitle}></a>
                    <{if $bc.main_data.0.NewsEnable!=1}>[<{$smarty.const._MD_TCW_NEWS_DRAFT}>]<{/if}>

                    <{*if $bc.main_data.0.isCanEdit*}>
                    <{if ($WebID && $isMyWeb) || $isAdmin || ($bc.main_data.0.cate.CateID && $bc.main_data.0.cate.CateID == $smarty.session.isAssistant.news)}>
                        <a href="javascript:delete_news_func(<{$bc.main_data.0.NewsID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>
                        <a href="news.php?WebID=<{$WebID}>&op=edit_form&NewsID=<{$bc.main_data.0.NewsID}>" class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                    <{/if}>
                </h3>
            </div>
        <{/if}>

        <ul class="list-group">
            <{foreach from=$bc.main_data key=i item=news}>
                <{if $i > 0}>
                    <li class="list-group-item">
                        <{$news.Date}>
                        <{if isset($news.cate.CateID)}>
                            <span class="badge badge-info"><a href="news.php?WebID=<{$WebID}>&CateID=<{$news.cate.CateID}>" style="color: #FFFFFF;"><{$news.cate.CateName}></a></span>
                        <{/if}>
                        <a href="news.php?WebID=<{$news.WebID}>&NewsID=<{$news.NewsID}>"><{$news.NewsTitle}></a>
                        <{if $news.NewsEnable!=1}>[<{$smarty.const._MD_TCW_NEWS_DRAFT}>]<{/if}>

                        <{*if $news.isCanEdit*}>
                        <{if ($WebID && $isMyWeb) || $isAdmin || ($news.cate.CateID && $news.cate.CateID == $smarty.session.isAssistant.news)}>
                            <a href="javascript:delete_news_func(<{$news.NewsID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>
                            <a href="news.php?WebID=<{$news.WebID}>&op=edit_form&NewsID=<{$news.NewsID}>" class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                        <{/if}>
                    </li>
                <{/if}>
            <{/foreach}>
        </ul>
    <{/if}>
<{/if}>

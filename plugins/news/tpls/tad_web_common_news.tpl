<{if $news_data}>
    <{if $WebID}>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.list_new img').css('width','').css('height','').addClass('img-fluid');
            });
        </script>
        <{foreach from=$news_data key=i item=news}>
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
                <div class="my-border list_new" style="min-height: 100px; overflow: auto; line-height: 1.8; ">
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
        <{if $web_display_mode=='index' and $news_data}>
            <{if "$xoops_rootpath/uploads/tad_web/0/image/`$dirname`.png"|file_exists}>
                <a href="<{$xoops_url}>/modules/tad_web/<{$dirname}>.php"><img src="<{$xoops_url}>/uploads/tad_web/0/image/<{$dirname}>.png" alt="<{$news.PluginTitle}>"></a>
            <{else}>
                <h3><a href="<{$xoops_url}>/modules/tad_web/news.php"><{$news.PluginTitle}></a></h3>
            <{/if}>
        <{elseif $web_display_mode=='index_plugin'}>
            <h2><a href="<{$xoops_url}>/modules/tad_web/">&#xf015;</a> <{$news.PluginTitle}></h2>
        <{/if}>

        <table class="footable table common_table">
            <thead>
                <tr>
                    <th data-hide="phone" style="width:100px;text-align:center;">
                        <{$smarty.const._MD_TCW_NEWSDATE}>
                    </th>
                    <th data-class="expand">
                        <{$smarty.const._MD_TCW_NEWS}>
                    </th>
                    <th data-hide="phone" class="common_counter" style="text-align: center;">
                        <{$smarty.const._MD_TCW_NEWSCOUNTER}>
                    </th>
                    <{if $web_display_mode=="index" or $web_display_mode=="index_plugin"}>
                        <th data-hide="phone" class="common_team" style="text-align: center;">
                        <{$smarty.const._MD_TCW_TEAMID}>
                        </th>
                    <{/if}>
                </tr>
            </thead>
            <{foreach from=$news_data item=news}>
                <tr>
                    <td style="text-align:center;"><{$news.Date}></td>
                    <td>
                        <{if isset($news.cate.CateID)}>
                            <span class="badge badge-info"><a href="news.php?WebID=<{$news.WebID}>&CateID=<{$news.cate.CateID}>" style="color: #FFFFFF;"><{$news.cate.CateName}></a></span>
                        <{/if}>
                        <a href="news.php?WebID=<{$news.WebID}>&NewsID=<{$news.NewsID}>"><{$news.NewsTitle}></a>
                        <{if $news.NewsEnable!=1}>[<{$smarty.const._MD_TCW_NEWS_DRAFT}>]<{/if}>

                        <{*if $news.isCanEdit*}>
                        <{if ($WebID && $isMyWeb) || $isAdmin || ($news.cate.CateID && $news.cate.CateID == $smarty.session.isAssistant.news)}>
                            <a href="javascript:delete_news_func(<{$news.NewsID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>
                            <a href="news.php?WebID=<{$news.WebID}>&op=edit_form&NewsID=<{$news.NewsID}>" class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                        <{/if}>
                    </td>
                    <td style="text-align:center;">
                        <{$news.NewsCounter}>
                    </td>
                    <{if $web_display_mode=="index" or $web_display_mode=="index_plugin"}>
                        <td style="text-align:center;" class="common_team_content">
                            <{$news.WebTitle}>
                        </td>
                    <{/if}>
                </tr>
            <{/foreach}>
        </table>
    <{/if}>

    <{if $news_data}>
        <{if $web_display_mode=='index_plugin' or $web_display_mode=='home_plugin'}>
            <{$bar}>
        <{/if}>
    <{/if}>

    <div style="text-align:right; margin: 0px 0px 10px;">
        <{if $web_display_mode=='index'}>
            <a href="news.php" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
        <{elseif $web_display_mode=='home' or $NewsDefCateID}>
            <a href="news.php?WebID=<{$WebID}>" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
        <{/if}>

        <{if $isMyWeb and $WebID}>
            <a href="news.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
        <{/if}>
    </div>
<{/if}>

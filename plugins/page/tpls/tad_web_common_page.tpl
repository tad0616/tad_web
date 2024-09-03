<{if $web_display_mode=='index' and $page_data}>
    <{if "$xoops_rootpath/uploads/tad_web/0/image/`$dirname`.png"|file_exists}>
        <a href="<{$xoops_url}>/modules/tad_web/<{$dirname}>.php"><img src="<{$xoops_url}>/uploads/tad_web/0/image/<{$dirname}>.png" alt="<{$page.PluginTitle}>"></a>
    <{else}>
        <h3><a href="<{$xoops_url}>/modules/tad_web/page.php"><{$page.PluginTitle}></a></h3>
    <{/if}>
<{elseif $web_display_mode=='index_plugin'}>
    <h2><a href="<{$xoops_url}>/modules/tad_web/"><i class="fa fa-home"></i></a> <{$page.PluginTitle}></h2>
<{/if}>

<{if $isMyWeb}>
    <script type="text/javascript">
        $(document).ready(function(){
            <{foreach from=$cate_arr item=cate}>
                $('#page_sort<{$cate.CateID}>').sortable({ opacity: 0.6, cursor: 'move', update: function() {
                    var order = $(this).sortable('serialize');
                    $.post('<{$xoops_url}>/modules/tad_web/plugins/page/save_page_sort.php', order, function(theResponse){
                        $('#page_sort_save_msg<{$cate.CateID}>').html(theResponse);
                    });
                }
                });
            <{/foreach}>
        });
    </script>
<{/if}>

<{if $page_data}>

    <{foreach from=$cate_arr item=cate}>
        <{assign var="cid" value=$cate.CateID}>
        <{if $cate_data.$cid}>
            <h3>
                <a href="page.php?WebID=<{$WebID}>&CateID=<{$cid}>"><{$cate.CateName}></a>
                <{if $list_pages_title!='1'}>
                <small><{$smarty.const._MD_TCW_PAGE_SIZE1}><{$cate_size.$cid}><{$smarty.const._MD_TCW_PAGE_SIZE2}></small>
                <{/if}>
            </h3>
            <{if $list_pages_title=='1'}>
                <div id="page_sort_save_msg<{$cate.CateID}>"></div>
                <ul id="page_sort<{$cate.CateID}>" class="list-group">
                    <{foreach from=$cate_data.$cid item=page}>
                        <li id="li_<{$page.PageID}>" class="list-group-item">
                            <{if $isMyWeb}>
                                <i class="fa fa-arrows text-success" style="cursor: s-resize;" alt="<{$smarty.const._TAD_SORTABLE}>" title="<{$smarty.const._TAD_SORTABLE}> <{$page.PageSort}>"></i>
                            <{/if}>
                            <span class="badge badge-info"><{$page.PageCount}></span>
                            <a href='page.php?WebID=<{$page.WebID}>&PageID=<{$page.PageID}>'><{$page.PageTitle}></a>

                            <{*if $page.isCanEdit*}>
                            <{if ($WebID && $isMyWeb) || $isAdmin || ($page.cate.CateID && $page.cate.CateID == $smarty.session.isAssistant.page)}>
                                <a href="javascript:delete_page_func(<{$page.PageID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>
                                <a href="page.php?WebID=<{$WebID}>&op=edit_form&PageID=<{$page.PageID}>"  class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                            <{/if}>
                        </li>
                    <{/foreach}>
                </ul>
            <{/if}>
        <{/if}>
    <{/foreach}>

    <{if $cate_data.0}>
        <h3><a href="page.php?WebID=<{$WebID}>&CateID=0"><{$smarty.const._MD_TCW_PAGE_UNCATEGORY}></a></h3>

        <{if $list_pages_title=='1'}>
            <div id="page_sort_save_msg<{$cate.CateID}>"></div>
            <ul id="page_sort<{$cate.CateID}>" class="list-group">
                <{foreach from=$cate_data.0 item=page}>
                    <li id="li_<{$page.PageID}>" class="list-group-item">
                        <{if $isMyWeb}>
                            <i class="fa fa-arrows text-success" style="cursor: s-resize;" alt="<{$smarty.const._TAD_SORTABLE}>" title="<{$smarty.const._TAD_SORTABLE}> <{$page.PageSort}>"></i>
                        <{/if}>
                        <span class="badge badge-info"><{$page.PageCount}></span>
                        <a href='page.php?WebID=<{$page.WebID}>&PageID=<{$page.PageID}>'><{$page.PageTitle}></a>

                        <{*if $page.isCanEdit*}>
                        <{if ($WebID && $isMyWeb) || $isAdmin || ($page.cate.CateID && $page.cate.CateID == $smarty.session.isAssistant.page)}>
                            <a href="javascript:delete_page_func(<{$page.PageID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>
                            <a href="page.php?WebID=<{$WebID}>&op=edit_form&PageID=<{$page.PageID}>"  class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                        <{/if}>
                    </li>
                <{/foreach}>
            </ul>
        <{/if}>
    <{/if}>

    <div style="text-align:right; margin: 0px 0px 10px;">
        <{if $web_display_mode=='index'}>
            <a href="page.php" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_PAGE_SHORT}></a>
        <{elseif $web_display_mode=='home' or $PageDefCateID}>
            <a href="page.php?WebID=<{$WebID}>" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_PAGE_SHORT}></a>
        <{/if}>

        <{if $isMyWeb and $WebID}>
            <a href="page.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_PAGE_SHORT}></a>
        <{/if}>
    </div>
<{/if}>

<{assign var="bc" value=$block.BlockContent}>
<{if $isMyWeb|default:false}>
    <script type="text/javascript">
        $(document).ready(function(){
            <{foreach from=$bc.cate_arr item=cate}>
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
<h2 class="sr-only visually-hidden">Over View</h2>
<{if $bc.main_data|default:false}>
    <{foreach from=$bc.cate_arr item=cate}>
        <{assign var="cid" value=$cate.CateID}>
        <{if $bc.cate_data.$cid|default:false}>
            <h3><a href="page.php?WebID=<{$WebID|default:''}>&CateID=<{$cid|default:''}>"><{$cate.CateName}></a>
            <{if $bc.list_pages_title!='1'}>
                <small> (<{$bc.cate_size.$cid}>) </small>
            <{/if}></h3>
            <{if $bc.list_pages_title=='1'}>
                <div id="page_sort_save_msg<{$cate.CateID}>"></div>
                <ul id="page_sort<{$cate.CateID}>" class="list-group">
                    <{foreach from=$bc.cate_data.$cid item=page}>
                        <li id="li_<{$page.PageID}>" class="list-group-item">
                            <{if $isMyWeb|default:false}>
                                <i class="fa fa-arrows text-success" style="cursor: s-resize;" alt="<{$smarty.const._TAD_SORTABLE}>" title="<{$smarty.const._TAD_SORTABLE}>"></i>
                            <{/if}>
                            <{if $content.show_count|default:false=='1'}>
                                <span class="badge badge-info bg-info"><{$page.PageCount}></span>
                            <{/if}>
                            <a href='page.php?WebID=<{$page.WebID}>&PageID=<{$page.PageID}>'><{$page.PageTitle}></a>
                            <{*if $page.isCanEdit*}>
                            <{if ($WebID && $isMyWeb) || $smarty.session.tad_web_adm|default:false || (isset($page.cate.CateID) && isset($smarty.session.isAssistant.page) && $page.cate.CateID == $smarty.session.isAssistant.page)}>
                                <a href="javascript:delete_page_func(<{$page.PageID}>);" class="text-danger"><i class="fa fa-trash"></i><span class="sr-only visually-hidden">delete</span></a>
                                <a href="page.php?WebID=<{$WebID|default:''}>&op=edit_form&PageID=<{$page.PageID}>"  class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                            <{/if}>
                        </li>
                    <{/foreach}>
                </ul>
            <{/if}>
        <{/if}>
    <{/foreach}>

    <{if $PageDefCateID|default:false}>
        <div style="text-align:right; margin: 0px 0px 10px;">
            <{if $web_display_mode=='home' or $web_display_mode=='index' or $PageDefCateID}>
                <a href="page.php?WebID=<{$WebID|default:''}>" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_PAGE_SHORT}></a>
            <{/if}>
        </div>
    <{/if}>
<{/if}>

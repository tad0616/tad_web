<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <div id="accordion" role="tablist" aria-multiselectable="true">
        <{foreach from=$bc.page_list item=page}>
            <div class="card panel">
                <div class="card-header panel-heading" role="tab" id="heading<{$page.CateID}>" style="background: rgb(114, 185, 218);">
                    <div style="font-size: 1.5rem;">
                        <span class="badge badge-info bg-info pull-right float-right pull-end"><{$page.CateAmount}></span>
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<{$page.CateID}>" aria-expanded="true" aria-controls="collapse<{$page.CateID}>" style="color: #ffffff;">
                            <{$page.CateName}>
                        </a>
                    </div>
                </div>
                <div id="collapse<{$page.CateID}>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<{$page.CateID}>">
                    <ul class="list-group">
                        <{foreach from=$page.content item=content}>
                            <li class="list-group-item">
                                <{if $content.show_count=='1'}>
                                    <span class="badge badge-info bg-info"><{$content.PageCount}></span>
                                <{/if}>
                                <a href="page.php?WebID=<{$content.WebID}>&PageID=<{$content.PageID}>" style="color: #333333;"><{$content.PageTitle}></a>
                            </li>
                        <{/foreach}>
                    </ul>
                </div>
            </div>
        <{/foreach}>
    </div>
<{/if}>
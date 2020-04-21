<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
    <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <div id="accordion" role="tablist" aria-multiselectable="true">
        <{foreach from=$bc.page_list item=page}>
            <div class="card panel">
                <div class="card-header text-white bg-primary panel-heading" role="tab" id="heading<{$page.CateID}>">
                    <h4>
                        <span class="badge badge-info pull-right"><{$page.CateAmount}></span>
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<{$page.CateID}>" aria-expanded="true" aria-controls="collapse<{$page.CateID}>" style="color: #000000;">
                            <{$page.CateName}>
                        </a>
                    </h4>
                </div>
                <div id="collapse<{$page.CateID}>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<{$page.CateID}>">
                    <ul class="list-group">
                        <{foreach from=$page.content item=content}>
                            <li class="list-group-item">
                                <{if $content.show_count=='1'}>
                                    <span class="badge badge-info"><{$content.PageCount}></span>
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
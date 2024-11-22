<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data|default:false}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <{if $bc.page_list|default:false}>
        <div class="accordion" id="pageAccordion">
            <{foreach from=$bc.page_list item=page}>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading<{$page.CateID}>">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<{$page.CateID}>" aria-expanded="false" aria-controls="heading<{$page.CateID}>">
                            <span class="badge bg-info"><{$page.CateAmount}></span>
                            <{$page.CateName}>
                        </button>
                    </h2>

                    <div id="collapse<{$page.CateID}>" class="accordion-collapse collapse" aria-labelledby="heading<{$page.CateID}>" data-bs-parent="#pageAccordion">
                        <div class="accordion-body">
                            <{foreach from=$page.content item=content}>
                            <div>
                                <a href="page.php?WebID=<{$content.WebID}>&PageID=<{$content.PageID}>">
                                    <{if $content.show_count|default:false=='1'}>
                                        <span class="badge badge-info bg-info"><{$content.PageCount}></span>
                                    <{/if}><{$content.PageTitle}>
                                </a>
                            </div>
                            <{/foreach}>
                        </div>
                    </div>
                </div>
            <{/foreach}>
        </div>
    <{/if}>
<{/if}>
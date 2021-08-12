<{if $web_display_mode=="home"}>
    <{if !$center_block1 and !$center_block2 and !$center_block3 and !$center_block4 and !$center_block5 and !$center_block6}>
        <h3 class="sr-only">coming soon</h3>
        <div class="text-center">
            <img src="<{$xoops_url}>/modules/tad_web/images/empty.png" alt="coming soon" >
        </div>
    <{else}>
        <{if $center_block1}>
            <{foreach from=$center_block1 item=block}>
                <{includeq file="$xoops_rootpath/modules/tad_web/templates/sub_tad_web_block.tpl"}>
            <{/foreach}>
        <{/if}>

        <{if $center_block2 or $center_block3}>
            <div class="row">
                <{if $center_block2}>
                    <div class="col-md-6">
                        <{foreach from=$center_block2 item=block}>
                            <{includeq file="$xoops_rootpath/modules/tad_web/templates/sub_tad_web_block.tpl"}>
                        <{/foreach}>
                    </div>
                <{/if}>

                <{if $center_block3}>
                    <div class="col-md-6">
                        <{foreach from=$center_block3 item=block}>
                            <{includeq file="$xoops_rootpath/modules/tad_web/templates/sub_tad_web_block.tpl"}>
                        <{/foreach}>
                    </div>
                <{/if}>
            </div>
        <{/if}>

        <{if $center_block4}>
            <{foreach from=$center_block4 item=block}>
                <{includeq file="$xoops_rootpath/modules/tad_web/templates/sub_tad_web_block.tpl"}>
            <{/foreach}>
        <{/if}>

        <{if $center_block5 or $center_block6}>
            <div class="row">
                <{if $center_block5}>
                    <div class="col-md-6">
                        <{foreach from=$center_block5 item=block}>
                            <{includeq file="$xoops_rootpath/modules/tad_web/templates/sub_tad_web_block.tpl"}>
                        <{/foreach}>
                    </div>
                <{/if}>
                <{if $center_block6}>
                    <div class="col-md-6">
                        <{foreach from=$center_block6 item=block}>
                            <{includeq file="$xoops_rootpath/modules/tad_web/templates/sub_tad_web_block.tpl"}>
                        <{/foreach}>
                    </div>
                <{/if}>
            </div>
        <{/if}>
    <{/if}>
<{else}>
    <{$xoops_contents}>
<{/if}>
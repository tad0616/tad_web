<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <img src="https://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=<{$bc.main_data}>&chld=H|0" alt="QR Code" class="img-fluid img-responsive">
        </div>
        <div class="col-md-2"></div>
    </div>
<{/if}>

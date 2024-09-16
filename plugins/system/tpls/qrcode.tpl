<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data|default:false}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<{$bc.main_data}>" alt="QR Code"  class="img-fluid img-responsive">
        </div>
        <div class="col-md-2"></div>
    </div>
<{/if}>

<script language="JavaScript">
    $().ready(function(){
        $(".thumb").click(function(){
            var pic=$(this).attr("id");
            $("#head_bg").attr("src","<{$xoops_url}>/uploads/tad_web/<{$WebID|default:''}>/head/"+pic);
            $.post("config_ajax.php", {op: "save_head" , filename: pic, WebID: <{$WebID|default:''}>});
        });

        $(".def_thumb").click(function(){
            var pic=$(this).attr("id");
            $("#head_bg").attr("src","<{$xoops_url}>/modules/tad_web/images/head/"+pic);
            $.post("config_ajax.php", {op: "save_head" , filename: pic, WebID: <{$WebID|default:''}>});
        });
    });
</script>

<h3>
    <{$smarty.const._MD_TCW_HEAD_TOOLS}>
    <small>
        <{$smarty.const._MD_TCW_CLICK_TO_CHANG}>
    </small>
</h3>

<div class="alert alert-info">
    <{$bg_desc|default:''}>
</div>

<form action="config.php" method="post" enctype="multipart/form-data" class="form-horizontal myForm" role="form">
    <{$upform_head|default:''}>

    <{if $all_head|default:false}>
        <{foreach from=$all_head item=head}>
            <div style="width:100px; height:96px; display:inline-block; margin:4px;">
                <a href="#top" title="<{$head.file_name}>" class="thumb_link">
                    <label style="width: 100px; height: 70px; background: #000000 url('<{$head.tb_path}>') center center no-repeat; border: <{if $head.file_name == $web_head}>2px solid red<{else}>1px solid gray<{/if}>; background-size: contain;" id="<{$head.file_name}>" class="thumb">
                    </label>
                </a>
                <label class="del_img_box" style="font-size: 0.65rem; width:100%; overflow: hidden; line-height: 1rem;height: 1rem; white-space: nowrap;" id="del_img<{$head.files_sn}>">
                    <input type="checkbox" value="<{$head.files_sn}>" name="del_file[<{$head.files_sn}>]" title="<{$smarty.const._TAD_DEL}> <{$head.file_name}>"> <{$smarty.const._TAD_DEL}> <{$head.file_name}>
                </label>
            </div>
        <{/foreach}>
    <{/if}>
    <{foreach from=$all_default_head item=head}>
        <{if $head.file_name|default:false}>
            <div style="width:100px; height:96px; display:inline-block; margin:4px;">
                <a href="#top" title="<{$head.file_name}>" class="thumb_link">
                    <label style="width: 100px; height: 70px; background: #000000 url('<{$head.tb_path}>') center center no-repeat; border: <{if $head.file_name == $web_head}>2px solid red<{else}>1px solid gray<{/if}>; background-size: contain;" id="<{$head.file_name}>" class="def_thumb">
                    </label>
                </a>
                <label style="font-size: 0.65rem; width:100%; overflow: hidden; line-height: 1rem;height: 1rem; white-space: nowrap;">
                    <{$head.file_name}>
                </label>
            </div>
        <{/if}>
    <{/foreach}>

    <div class="text-center">
        <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
        <input type="hidden" name="op" value="upload_head">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
    </div>
</form>
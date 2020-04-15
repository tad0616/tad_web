<script language="JavaScript">
    $().ready(function(){
        $(".thumb").click(function(){
            var bg=$(this).attr("id");
            $("#head_bg").attr("src","<{$xoops_url}>/uploads/tad_web/<{$WebID}>/head/"+bg);
            $.post("config_ajax.php", {op: "save_head" , filename: bg, WebID: <{$WebID}>});
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
    <{$bg_desc}>
</div>

<form action="config.php" method="post" enctype="multipart/form-data" class="form-horizontal myForm" role="form">
    <{$upform_head}>

    <{if $all_head}>
        <{foreach from=$all_head item=head}>
            <div style="width:100px; height:96px; display:inline-block; margin:4px;">
                <a href="#top" title="<{$head.file_name}>" class="thumb_link">
                    <label style="width: 100px; height: 70px; background: #000000 url('<{$head.tb_path}>') center center no-repeat; border: 1px solid gray; background-size: contain;" id="<{$head.file_name}>" class="thumb">
                </label>
                </a>

                <label class="del_img_box" style="font-size: 80%;" id="del_img<{$head.files_sn}>">
                    <input type="checkbox" value="<{$head.files_sn}>" name="del_file[<{$head.files_sn}>]"> <{$smarty.const._TAD_DEL}>
                </label>
            </div>
        <{/foreach}>
    <{/if}>

    <div class="text-center">
        <input type="hidden" name="WebID" value="<{$WebID}>">
        <input type="hidden" name="op" value="upload_head">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
    </div>
</form>
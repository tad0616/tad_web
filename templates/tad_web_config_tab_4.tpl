<script language="JavaScript">
    $().ready(function(){
        $(".logo_thumb").click(function(){
            var logo=$(this).attr("id");
            $("#tad_web_logo").attr("src","<{$xoops_url}>/uploads/tad_web/<{$WebID}>/logo/"+logo);
            $.post("config_ajax.php", {op: "save_logo_pic" , filename: logo, WebID: <{$WebID}>});
        });
    });
</script>

<h3>
    <{$smarty.const._MD_TCW_LOGO_TOOLS}>
    <small>
        <{$smarty.const._MD_TCW_CLICK_TO_CHANG}>
    </small>
</h3>

<div class="alert alert-info">
    <{$logo_desc}>
</div>

<form action="config.php" method="post" enctype="multipart/form-data" class="form-horizontal myForm" role="form">

    <{$upform_logo}>

    <{if $all_logo}>
        <{foreach from=$all_logo item=logo}>
        <div style="width:150px; height:76px; display:inline-block; margin:4px;">
            <a href="#top">
                <label style="width: 150px; height: 50px; background: #000000 url('<{$logo.tb_path}>') center center no-repeat; border: 1px solid gray; background-size: contain;" id="<{$logo.file_name}>" class="logo_thumb">
                </label>
            </a>

            <label class="del_img_box" style="font-size: 80%;" id="del_img<{$logo.files_sn}>">
                <input type="checkbox" value="<{$logo.files_sn}>" name="del_file[<{$logo.files_sn}>]"> <{$smarty.const._TAD_DEL}>
            </label>
        </div>
        <{/foreach}>
    <{/if}>

    <div class="text-center">
        <input type="hidden" name="WebID" value="<{$WebID}>">
        <input type="hidden" name="op" value="upload_logo">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
    </div>
</form>
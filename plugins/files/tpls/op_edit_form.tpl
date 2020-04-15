<script type="text/javascript">
    $(document).ready(function(){
        <{if $fsn}>
            <{if $file_link==""}>
                $("#link_file").hide();
                $("#upload_file").show();
            <{else}>
                $("#link_file").show();
                $("#upload_file").hide();
            <{/if}>
        <{else}>
            $("#file_method").change(function(event) {
                var up_method=$("#file_method").val();
                if(up_method=="link_file"){
                $("#link_file").show();
                $("#upload_file").hide();
                }else{
                $("#link_file").hide();
                $("#upload_file").show();
                }
            });
        <{/if}>
    });
</script>

<h2><{$smarty.const._MD_TCW_FILES_ADD}></h2>

<div class="my-border">
    <form action="files.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">

        <!--分類-->
        <{$cate_menu_form}>

        <{if $fsn==""}>
            <div class="form-group row">
                <label class="col-md-2 col-form-label text-sm-right control-label">
                    <{$smarty.const._MD_TCW_FILES_METHOD}>
                </label>
                <div class="col-md-4">
                    <select name="file_method" id="file_method" class="form-control">
                        <option value="link_file"><{$smarty.const._MD_TCW_FILES_LINK}></option>
                        <option value="upload_file"><{$smarty.const._MD_TCW_FILES_UPLOAD}></option>
                    </select>
                </div>
            </div>
        <{else}>
            <{if $file_link==""}>
                <input type="hidden" name="file_method" value="upload_file">
            <{else}>
                <input type="hidden" name="file_method" value="link_file">
            <{/if}>
        <{/if}>

        <div id="link_file">
            <div class="form-group row">
                <label class="col-md-2 col-form-label text-sm-right control-label">
                    <{$smarty.const._MD_TCW_FILES_LINK}>
                </label>
                <div class="col-md-10">
                    <input type="text" name="file_link" class="form-control validate[required , custom[url]]" value="<{$file_link}>" placeholder="<{$smarty.const._MD_TCW_FILES_LINK_DESC}>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label text-sm-right control-label">
                    <{$smarty.const._MD_TCW_FILES_DESC}>
                </label>
                <div class="col-md-10">
                    <input type="text" name="file_description" class="form-control validate[required]" value="<{$file_description}>" placeholder="<{$smarty.const._MD_TCW_FILES_DESC}>">
                </div>
            </div>
        </div>

        <{$tags_form}>

        <div id="upload_file" style="display: none;">
            <div class="form-group row">
                <label class="col-md-2 col-form-label text-sm-right control-label">
                    <{$smarty.const._MD_TCW_FILES_UPLOAD}>
                </label>
                <div class="col-md-8">
                    <{$upform}>
                </div>
            </div>
            <{$list_del_file}>
        </div>

        <div class="text-center">
            <input type="hidden" name="WebID" value="<{$WebID}>">
            <!--檔案流水號-->
            <input type="hidden" name="fsn" value="<{$fsn}>">
            <input type="hidden" name="op" value="<{$next_op}>">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
</div>
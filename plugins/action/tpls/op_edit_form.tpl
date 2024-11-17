<script type="text/javascript">
    $(document).ready(function(){
        $('#gphoto_link_url').change(function(){
            var p=/^https:\/\/photos.app.goo.gl/gi;
            var url=$('#gphoto_link_url').val();
            if(p.test(url)){
                alert('<{$smarty.const._MD_TCW_ACTION_GPHOTO_URL_ALERT}>');
                $('#gphoto_link_url').val('');
            }
        });

        <{if $ActionID|default:false}>
            <{if $gphoto_link==""}>
                $("#gphoto_link").hide();
                $("#upload_photo").show();
            <{else}>
                $("#gphoto_link").show();
                $("#upload_photo").hide();
            <{/if}>
        <{else}>
            $("input[name='upload_method']").change(function(event) {
                var up_method = $("input[name='upload_method']:checked").val();
                if(up_method=="gphoto_link"){
                    $("#gphoto_link").show();
                    $("#upload_photo").hide();
                }else{
                    $("#gphoto_link").hide();
                    $("#upload_photo").show();
                }
            });
        <{/if}>
    });
</script>

<h2><{$smarty.const._MD_TCW_ACTION_ADD}></h2>
<div class="my-border">
    <form action="action.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">

        <!--分類-->
        <div class="form-group row mb-3">
            <div class="col-md-12">
                <{$cate_menu_form|default:''}>
            </div>
        </div>

        <!--活動名稱-->
        <div class="form-group row mb-3">
            <div class="col-md-12">
                <input type="text" name="ActionName" value="<{$ActionName|default:''}>" id="ActionName" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_ACTIONNAME}>">
            </div>
        </div>


        <!--活動說明-->
        <div class="form-group row mb-3">
            <div class="col-md-12">
                <textarea name="ActionDesc"  rows=4 id="ActionDesc"  class="form-control" placeholder="<{$smarty.const._MD_TCW_ACTIONDESC}>"><{$ActionDesc|default:''}></textarea>
            </div>
        </div>


        <!--活動日期-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_ACTIONDATE}>
            </label>
            <div class="col-md-4">
                <input type="text" name="ActionDate" class="form-control" value="<{$ActionDate|default:''}>" id="ActionDate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d'})">
            </div>
            <!--活動地點-->
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_ACTIONPLACE}>
            </label>
            <div class="col-md-4">
                <input type="text" name="ActionPlace" class="form-control" value="<{$ActionPlace|default:''}>" id="ActionPlace" >
            </div>
        </div>

        <{if !$ActionID|default:0}>
            <div class="form-group row mb-3">
                <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                    <{$smarty.const._MD_TCW_ACTION_UPLOAD_METHOD}>
                </label>
                <div class="col-md-4">
                    <div class="form-check-inline">
                        <label class="form-check-label" for="radio_upload_photo">
                            <input class="form-check-input validate[required]" type="radio" name="upload_method" id="radio_upload_photo" value="upload_photo" <{if $upload_method!='gphoto_link'}>checked<{/if}>>
                            <{$smarty.const._MD_TCW_ACTION_UPLOAD}>
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label" for="radio_gphoto_link">
                            <input class="form-check-input validate[required]" type="radio" name="upload_method" id="radio_gphoto_link" value="gphoto_link" <{if $upload_method=='gphoto_link'}>checked<{/if}>>
                            <{$smarty.const._MD_TCW_ACTION_GPHOTO}>
                        </label>
                    </div>
                </div>
            </div>
        <{else}>
            <{if $gphoto_link==""}>
                <input type="hidden" name="upload_method" value="upload_photo">
            <{else}>
                <input type="hidden" name="upload_method" value="gphoto_link">
            <{/if}>
        <{/if}>

        <!--上傳圖檔-->
        <div id="upload_photo">
            <div class="form-group row mb-3">
                <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                    <{$smarty.const._MD_TCW_ACTION_UPLOAD}>
                </label>
                <div class="col-md-8">
                    <{$upform|default:''}>
                </div>
            </div>
            <{$list_del_file|default:''}>
        </div>

        <div id="gphoto_link" style="display: none;">
            <div class="form-group row mb-3">
                <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                    <{$smarty.const._MD_TCW_ACTION_GPHOTO_URL}>
                </label>
                <div class="col-md-10">
                    <input type="text" name="gphoto_link" id="gphoto_link_url" class="form-control validate[required , custom[url]]" value="<{$gphoto_link|default:''}>" placeholder="<{$smarty.const._MD_TCW_ACTION_GPHOTO_URL_DEMO}>">
                </div>
            </div>

            <div class="alert alert-info">
                <{$smarty.const._MD_TCW_ACTION_GPHOTO_URL_HEPL}>
            </div>
        </div>


        <div class="form-group row mb-3">
            <div class="col-md-12">
                <{$power_form|default:''}>
            </div>
        </div>

        <div class="form-group row mb-3">
            <div class="col-md-12">
                <{$tags_form|default:''}>
            </div>
        </div>

        <div class="form-group row mb-3">
            <div class="col-md-12 text-center">
                <!--活動編號-->
                <input type="hidden" name="ActionID" value="<{$ActionID|default:''}>">
                <!--所屬團隊-->
                <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
                <input type="hidden" name="op" value="<{$next_op|default:''}>">
                <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>  <{$smarty.const._TAD_SAVE}></button>
            </div>
        </div>
    </form>
</div>
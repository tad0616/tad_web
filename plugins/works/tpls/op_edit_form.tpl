<script type="text/javascript">
    $(document).ready(function(){
        $('#WorksKind').change(function() {
            var WorksKind=$('#WorksKind').val();
            if(WorksKind!=""){
                $('#works_date_label').html('<{$smarty.const._MD_TCW_WORKS_END_DATE}>');
                $('#works_uploader').hide();
            }else{
                $('#works_date_label').html('<{$smarty.const._MD_TCW_WORKS_DATE}>');
                $('#works_uploader').show();
            }
        });
    });
</script>

<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
<h2><{$smarty.const._MD_TCW_WORKS_ADD}></h2>
<div class="my-border">
    <form action="works.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
        <!--分類-->
        <{$cate_menu_form|default:''}>

        <!--作品名稱-->
        <div class="form-group row mb-3">
            <div class="col-md-12">
                <input type="text" name="WorkName" value="<{$WorkName|default:''}>" id="WorkName" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_WORKS_NAME}>">
            </div>
        </div>

        <!--作品說明-->
        <div class="form-group row mb-3">
            <div class="col-md-12">
                <textarea name="WorkDesc"  rows=4 id="WorkDesc"  class="form-control" placeholder="<{$smarty.const._MD_TCW_WORKS_DESC}>"><{$WorkDesc|default:''}></textarea>
            </div>
        </div>


        <!--相關附檔-->
        <div class="form-group row mb-3" id="works_attachments">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_WORKS_UPLOAD}>
            </label>
            <div class="col-md-10">
                <{$attachments|default:''}>
            </div>
        </div>

        <!--上傳方式-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_WORKS_KIND}>
            </label>
            <div class="col-md-4">
                <select name="WorksKind" class="form-control" id="WorksKind">
                    <option value=""><{$smarty.const._MD_TCW_WORKS_KIND_DEFAULT}></option>
                    <option value="mem_now" <{if $WorksKind=="mem_now"}>selected<{/if}>><{$smarty.const._MD_TCW_WORKS_KIND_MEM_NOW}></option>
                    <option value="mem_after_end" <{if $WorksKind=="mem_after_end"}>selected<{/if}>><{$smarty.const._MD_TCW_WORKS_KIND_MEM_AFTER_END}></option>
                </select>
            </div>

            <!--是否啟用？-->
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_WORKS_ENABLE}>
            </label>
            <div class="col-md-4">
                <div class="form-check form-check-inline radio-inline">
                    <label class="form-check-label" for="WorksEnable_1">
                        <input class="form-check-input" type="radio" name="WorksEnable" id="WorksEnable_1" value="1" <{if $WorksEnable != "0"}>checked<{/if}>>
                        <{$smarty.const._YES}>
                    </label>
                </div>
                <div class="form-check form-check-inline radio-inline">
                    <label class="form-check-label" for="WorksEnable_0">
                        <input class="form-check-input" type="radio" name="WorksEnable" id="WorksEnable_0" value="0" <{if $WorksEnable == "0"}>checked<{/if}>>
                        <{$smarty.const._NO}>
                    </label>
                </div>
            </div>
        </div>

        <!--作品日期-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label" id="works_date_label">
                <{if $WorksKind!=""}>
                    <{$smarty.const._MD_TCW_WORKS_END_DATE}>
                <{else}>
                    <{$smarty.const._MD_TCW_WORKS_DATE}>
                <{/if}>
            </label>
            <div class="col-md-4">
                <input type="text" name="WorksDate" class="form-control" value="<{$WorksDate|default:''}>" id="WorksDate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
            </div>
        </div>

        <{$tags_form|default:''}>

        <!--上傳學生作品-->
        <{if $WorksKind==""}>
            <div class="form-group row mb-3" id="works_uploader">
                <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                    <{$smarty.const._MD_TCW_WORKS_ADD}>
                </label>
                <div class="col-md-10">
                    <{$upform|default:''}>
                </div>
            </div>
        <{/if}>
        <div class="text-center">
            <!--作品編號-->
            <input type="hidden" name="WorksID" value="<{$WorksID|default:''}>">
            <!--所屬團隊-->
            <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
            <input type="hidden" name="op" value="<{$next_op|default:''}>">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
</div>
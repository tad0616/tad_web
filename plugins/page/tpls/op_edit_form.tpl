<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>

<h2><{$smarty.const._MD_TCW_PAGE_ADD}></h2>
<div class="my-border">
    <form page="page.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
        <!--分類-->
        <{$cate_menu_form}>

        <!--頁面名稱-->
        <div class="form-group row mb-3">
            <div class="col-md-12">
                <input type="text" name="PageTitle" value="<{$PageTitle}>" id="PageTitle" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_PAGETITLE}>">
            </div>
        </div>

        <!--頁面說明-->
        <div class="form-group row mb-3">
            <div class="col-md-12">
                <{$PageContent_editor}>
            </div>
        </div>

        <{$tags_form}>

        <!--樣式設定-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right control-label">
                <{$smarty.const._MD_TCW_PAGECSS}>
            </label>
            <div class="col-md-10">
                <input type="text" name="PageCSS" value="<{$PageCSS}>" id="PageCSS" class="form-control" placeholder="">
                <span class="form-text text-muted"><{$smarty.const._MD_TCW_PAGECSS_DESC}></span>
            </div>
        </div>

        <!--上傳圖檔-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right control-label">
                <{$smarty.const._MD_TCW_PAGE_UPLOAD}>
            </label>
            <div class="col-md-10">
                <{$upform}>
            </div>
        </div>

        <div class="text-center">
            <!--頁面編號-->
            <input type="hidden" name="PageID" value="<{$PageID}>">
            <!--所屬團隊-->
            <input type="hidden" name="WebID" value="<{$WebID}>">
            <input type="hidden" name="uid" value="<{$uid}>">
            <input type="hidden" name="op" value="<{$next_op}>">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
</div>
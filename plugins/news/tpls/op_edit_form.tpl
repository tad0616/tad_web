<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
<h2><{$smarty.const._MD_TCW_NEWS_ADD}></h2>

<div class="my-border">
    <form action="news.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">

        <!--分類-->
        <{$cate_menu_form|default:''}>

        <!--標題-->
        <div class="form-group row mb-3">
            <div class="col-md-12">
                <input name="NewsTitle" id="NewsTitle" class="validate[required] form-control" type="text" value="<{$NewsTitle|default:''}>" placeholder="<{$smarty.const._MD_TCW_NEWSTITLE}>">
            </div>
        </div>


        <!--內容-->
        <div class="form-group row mb-3">
            <div class="col-md-12">
                <{$NewsContent_editor|default:''}>
            </div>
        </div>


        <!--相關連結-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_NEWSURL}>
            </label>
            <div class="col-md-10">
                <input type="text" name="NewsUrl" value="<{$NewsUrl|default:''}>" id="NewsUrl" class="form-control">
            </div>
        </div>


        <div class="form-group row mb-3">
            <!--發布時間-->
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_DISCUSSDATE}>
            </label>
            <div class="col-md-4">
                <input type="text" name="NewsDate" value="<{$NewsDate|default:''}>" id="NewsDate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm' , startDate:'%y-%M-%d %H:%m}'})" class="form-control">
            </div>

            <!--加到行事曆-->
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_TOCAL}>
            </label>
            <div class="col-md-4">
                <input type="text" name="toCal" value="<{$toCal|default:''}>" id="toCal" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d}'})" class="form-control" placeholder="<{$smarty.const._MD_TCW_NEWS_TO_CAL}>">
            </div>
        </div>

        <{$power_form|default:''}>

        <{$tags_form|default:''}>

        <!--相關附件-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_NEWS_FILES}>
            </label>
            <div class="col-md-4">
                <{$upform|default:''}>
            </div>

            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_NEWS_ENABLE}>
            </label>
            <div class="col-md-4">
                <div class="form-check form-check-inline radio-inline">
                    <label class="form-check-label" for="NewsEnable_1">
                        <input class="form-check-input" type="radio" name="NewsEnable" id="NewsEnable_1" value="1" <{if $NewsEnable !='0'}>checked<{/if}>>
                        <{$smarty.const._YES}>
                    </label>
                </div>
                <div class="form-check form-check-inline radio-inline">
                    <label class="form-check-label" for="NewsEnable_0">
                        <input class="form-check-input" type="radio" name="NewsEnable" id="NewsEnable_0" value="0" <{if $NewsEnable =='0'}>checked<{/if}>>
                        <{$smarty.const._NO}>
                    </label>
                </div>
            </div>
        </div>

        <div class="text-center">
            <!--編號-->
            <input type="hidden" name="NewsID" value="<{$NewsID|default:''}>">
            <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
            <input type="hidden" name="uid" value="<{$uid|default:''}>">
            <input type="hidden" name="op" value="<{$next_op|default:''}>">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
</div>
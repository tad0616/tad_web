<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>

<h2><{$smarty.const._MD_TCW_ACCOUNT_ADD}></h2>
<div class="my-border">
    <form action="account.php" method="post" id="myForm" enctype="multipart/form-data" role="form">

        <!--分類-->
        <{$cate_menu_form}>

        <!--帳目日期-->
        <div class="form-group row">
            <label class="col-md-2 col-form-label text-sm-right control-label">
                <{$smarty.const._MD_TCW_ACCOUNT_DATE}>
            </label>
            <div class="col-md-3">
                <input type="text" name="AccountDate" class="form-control" value="<{$AccountDate}>" id="AccountDate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d'})">
            </div>

            <!--帳目名稱-->
            <div class="col-md-7">
                <input type="text" name="AccountTitle" value="<{$AccountTitle}>" id="AccountTitle" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_ACCOUNT_TITLE}>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="form-group row">
                    <!--種類-->
                    <label class="col-md-5 col-form-label text-sm-right control-label">
                        <{$smarty.const._MD_TCW_ACCOUNT_MONEY}>
                    </label>
                    <div class="col-md-7">
                        <div class="form-check form-check-inline radio-inline">
                            <input class="form-check-input" type="radio" name="AccountKind" id="AccountIncome" value="AccountIncome" class="validate[required]" <{if $AccountIncome}>checked<{/if}>>
                            <label class="form-check-label" for="AccountIncome"><{$smarty.const._MD_TCW_ACCOUNT_INCOME}></label>
                        </div>
                        <div class="form-check form-check-inline radio-inline">
                            <input class="form-check-input" type="radio" name="AccountKind" id="AccountOutgoings" value="AccountOutgoings" class="validate[required]" <{if $AccountOutgoings}>checked<{/if}>>
                            <label class="form-check-label" for="AccountOutgoings"><{$smarty.const._MD_TCW_ACCOUNT_OUTGOINGS}></label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <!--金額-->
                    <label class="col-md-5 col-form-label text-sm-right control-label">
                    </label>
                    <div class="col-md-7">
                        <input type="text" name="AccountMoney" class="validate[required] form-control" value="<{$AccountMoney}>" id="AccountMoney" >
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <!--帳目說明-->
                <textarea name="AccountDesc"  rows=3 id="AccountDesc"  class="form-control" placeholder="<{$smarty.const._MD_TCW_ACCOUNT_DESC}>"><{$AccountDesc}></textarea>
            </div>
        </div>

        <!--上傳圖檔-->
        <div class="form-group row">
            <label class="col-md-2 col-form-label text-sm-right control-label">
                <{$smarty.const._MD_TCW_ACCOUNT_UPLOAD}>
            </label>
            <div class="col-md-10">
                <{$upform}>
            </div>
        </div>

        <{$power_form}>

        <div class="text-center">
            <!--帳目編號-->
            <input type="hidden" name="AccountID" value="<{$AccountID}>">
            <!--所屬團隊-->
            <input type="hidden" name="WebID" value="<{$WebID}>">
            <input type="hidden" name="op" value="<{$next_op}>">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
</div>
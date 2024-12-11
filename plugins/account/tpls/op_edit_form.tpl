<h2><{$smarty.const._MD_TCW_ACCOUNT_ADD}></h2>
<div class="my-border">
    <form action="account.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">

        <!--分類-->
        <{$cate_menu_form|default:''}>

        <!--帳目日期-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_ACCOUNT_DATE}>
            </label>
            <div class="col-md-3">
                <input type="text" name="AccountDate" class="form-control" value="<{$AccountDate|default:''}>" id="AccountDate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d'})">
            </div>

            <!--帳目名稱-->
            <div class="col-md-7">
                <input type="text" name="AccountTitle" value="<{$AccountTitle|default:''}>" id="AccountTitle" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_ACCOUNT_TITLE}>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="form-group row mb-3">
                    <!--種類-->
                    <label class="col-md-5 col-form-label text-sm-right text-sm-end control-label">
                        <{$smarty.const._MD_TCW_ACCOUNT_MONEY}>
                    </label>
                    <div class="col-md-7">
                        <div class="form-check form-check-inline radio-inline">
                            <label class="form-check-label" for="AccountIncome">
                                <input class="form-check-input validate[required]" type="radio" name="AccountKind" id="AccountIncome" value="AccountIncome" <{if $AccountIncome|default:false}>checked<{/if}>>
                                <{$smarty.const._MD_TCW_ACCOUNT_INCOME}>
                            </label>
                        </div>
                        <div class="form-check form-check-inline radio-inline">
                            <label class="form-check-label" for="AccountOutgoings">
                                <input class="form-check-input validate[required]" type="radio" name="AccountKind" id="AccountOutgoings" value="AccountOutgoings" <{if $AccountOutgoings|default:false}>checked<{/if}>>
                                <{$smarty.const._MD_TCW_ACCOUNT_OUTGOINGS}>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <!--金額-->
                    <label class="col-md-5 col-form-label text-sm-right text-sm-end control-label">
                    </label>
                    <div class="col-md-7">
                        <input type="text" name="AccountMoney" class="validate[required] form-control" value="<{$AccountMoney|default:''}>" id="AccountMoney" >
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <!--帳目說明-->
                <textarea name="AccountDesc"  rows=3 id="AccountDesc"  class="form-control" placeholder="<{$smarty.const._MD_TCW_ACCOUNT_DESC}>"><{$AccountDesc|default:''}></textarea>
            </div>
        </div>

        <!--上傳圖檔-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_ACCOUNT_UPLOAD}>
            </label>
            <div class="col-md-10">
                <{$upform|default:''}>
            </div>
        </div>

        <{$power_form|default:''}>

        <div class="text-center">
            <!--帳目編號-->
            <input type="hidden" name="AccountID" value="<{$AccountID|default:''}>">
            <!--所屬團隊-->
            <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
            <input type="hidden" name="op" value="<{$next_op|default:''}>">
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-disk" aria-hidden="true"></i>  <{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
</div>
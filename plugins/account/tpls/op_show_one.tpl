<h2><{$AccountTitle}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="account.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_ACCOUNT}></a>
    </li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item">
            <a href="account.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a>
        </li>
    <{/if}>
    <li class="breadcrumb-item"><{$AccountInfo}></li>
    <{if $tags}>
        <li class="breadcrumb-item"><{$tags}></li>
    <{/if}>
</ol>

<div class="row" style="margin:10px 0px;">
    <div class="col-md-2"><{$smarty.const._MD_TCW_ACCOUNT_DATE}></div>
    <div class="col-md-4"><{$AccountDate}></div>
</div>

<{if $AccountIncome}>
    <div class="row" style="margin:10px 0px;">
        <div class="col-md-2"><{$smarty.const._MD_TCW_ACCOUNT_INCOME}></div>
        <div class="col-md-4"><{$AccountIncome}></div>
    </div>
<{/if}>

<{if $AccountOutgoings}>
    <div class="row" style="margin:10px 0px;">
        <div class="col-md-2"><{$smarty.const._MD_TCW_ACCOUNT_OUTGOINGS}></div>
        <div class="col-md-4"><{$AccountOutgoings}></div>
    </div>
<{/if}>

<div class="row">
<{if $AccountDesc}>
    <div class="col-md-6">
        <div class="alert alert-info" style="line-height: 1.8; font-size: 120%;"><{$AccountDesc}></div>
    </div>
<{/if}>

<{if $pics}>
    <div class="col-md-6">
        <{$pics}>
    </div>
<{/if}>
</div>

<{$fb_comments}>

<{if $isMyWeb or $isCanEdit}>
    <div class="text-right" style="margin: 30px 0px;">
        <a href="javascript:delete_account_func(<{$AccountID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
        <a href="account.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
        <a href="account.php?WebID=<{$WebID}>&op=edit_form&AccountID=<{$AccountID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
    </div>
<{/if}>
<h2><{$AccountTitle|default:''}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="account.php?WebID=<{$WebID|default:''}>"><{$smarty.const._MD_TCW_ACCOUNT}></a>
    </li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item">
            <{if $cate.CateName|default:false}><a href="account.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
        </li>
    <{/if}>
    <li class="breadcrumb-item"><{$AccountInfo|default:''}></li>
    <{if $tags|default:false}>
        <li class="breadcrumb-item"><{$tags|default:''}></li>
    <{/if}>
</ol>

<div class="row" style="margin:10px 0px;">
    <div class="col-md-2"><{$smarty.const._MD_TCW_ACCOUNT_DATE}></div>
    <div class="col-md-4"><{$AccountDate|default:''}></div>
</div>

<{if $AccountIncome|default:false}>
    <div class="row" style="margin:10px 0px;">
        <div class="col-md-2"><{$smarty.const._MD_TCW_ACCOUNT_INCOME}></div>
        <div class="col-md-4"><{$AccountIncome|default:''}></div>
    </div>
<{/if}>

<{if $AccountOutgoings|default:false}>
    <div class="row" style="margin:10px 0px;">
        <div class="col-md-2"><{$smarty.const._MD_TCW_ACCOUNT_OUTGOINGS}></div>
        <div class="col-md-4"><{$AccountOutgoings|default:''}></div>
    </div>
<{/if}>

<div class="row">
<{if $AccountDesc|default:false}>
    <div class="col-md-6">
        <div class="alert alert-info" style="line-height: 1.8; font-size: 120%;"><{$AccountDesc|default:''}></div>
    </div>
<{/if}>

<{if $pics|default:false}>
    <div class="col-md-6">
        <{$pics|default:''}>
    </div>
<{/if}>
</div>



<{if $isMyWeb or $isCanEdit}>
    <div class="text-right text-end" style="margin: 30px 0px;">
        <a href="javascript:delete_account_func(<{$AccountID|default:''}>);" class="btn btn-danger"><i class="fa fa-trash"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
        <a href="account.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
        <a href="account.php?WebID=<{$WebID|default:''}>&op=edit_form&AccountID=<{$AccountID|default:''}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
    </div>
<{/if}>
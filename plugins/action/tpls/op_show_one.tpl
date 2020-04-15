<h2><{$ActionName}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="action.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_ACTION}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item"><a href="action.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a></li>
    <{/if}>
    <li class="breadcrumb-item"><{$ActionInfo}></li>
    <{if $tags}>
        <li class="breadcrumb-item"><{$tags}></li>
    <{/if}>
</ol>

<div class="row" style="margin:10px 0px;">
    <{if $ActionDate}>
        <div class="col-md-6"><{$smarty.const._MD_TCW_ACTIONDATE}><{$smarty.const._TAD_FOR}><{$ActionDate}></div>
    <{/if}>

    <{if $ActionPlace}>
        <div class="col-md-6"><{$smarty.const._MD_TCW_ACTIONPLACE}><{$smarty.const._TAD_FOR}><{$ActionPlace}></div>
    <{/if}>
</div>

<{$pics}>

<{if $ActionDesc}>
    <div class="my-border"><{$ActionDesc}></div>
<{/if}>

<{$fb_comments}>

<{if $isMyWeb or $isCanEdit}>
    <div class="text-right" style="margin: 30px 0px;">
        <a href="javascript:delete_action_func(<{$ActionID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
        <a href="action.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
        <a href="action.php?WebID=<{$WebID}>&op=edit_form&ActionID=<{$ActionID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
    </div>
<{/if}>
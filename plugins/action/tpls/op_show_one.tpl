<h2><{$ActionName|default:''}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="action.php?WebID=<{$WebID|default:''}>"><{$smarty.const._MD_TCW_ACTION}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item">
            <{if $cate.CateName|default:false}><a href="action.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
        </li>
    <{/if}>
    <li class="breadcrumb-item"><{$ActionInfo|default:''}></li>
    <{if $tags|default:false}>
        <li class="breadcrumb-item"><{$tags|default:''}></li>
    <{/if}>
</ol>

<div class="row" style="margin:10px 0px;">
    <{if $ActionDate|default:false}>
        <div class="col-md-6"><{$smarty.const._MD_TCW_ACTIONDATE}><{$smarty.const._TAD_FOR}><{$ActionDate|default:''}></div>
    <{/if}>

    <{if $ActionPlace|default:false}>
        <div class="col-md-6"><{$smarty.const._MD_TCW_ACTIONPLACE}><{$smarty.const._TAD_FOR}><{$ActionPlace|default:''}></div>
    <{/if}>
</div>

<{if $gphoto_link!=''}>
    <ul>
        <{foreach from=$pics item=pic}>
            <li style="width:120px;height:180px;float:left;list-style:none;margin-right:6px;">
            <a href="<{$pic.image_url}>?type=.jpg" class="thumbnail fancybox_ActionID" rel="fActionID" style="display:inline-block; width: 120px; height: 120px; overflow: hidden; background-color: #cfcfcf; background-size: cover;border-radius: 5px; background-image: url('<{$pic.image_url}>'); background-repeat: no-repeat; background-position: center center; margin-bottom: 4px;">&nbsp;</a>
            <a href="<{$pic.image_link}>" target="_blank"><{$smarty.const._MD_TCW_ACTION_VIEW_ORIGINAL_IMAGE}></a>
            </li>
        <{/foreach}>
    </ul>
    <div style="clear:both;"></div>
<{else}>
    <{$pics|default:''}>
<{/if}>

<{if $ActionDesc|default:false}>
    <div class="my-border"><{$ActionDesc|default:''}></div>
<{/if}>



<{if $isMyWeb or $isCanEdit}>
    <div class="text-right text-end" style="margin: 30px 0px;">
        <a href="javascript:delete_action_func(<{$ActionID|default:''}>);" class="btn btn-danger"><i class="fa fa-trash"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
        <a href="action.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
        <a href="action.php?WebID=<{$WebID|default:''}>&op=edit_form&ActionID=<{$ActionID|default:''}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
        <a href="action.php?WebID=<{$WebID|default:''}>&op=re_get&ActionID=<{$ActionID|default:''}>" class="btn btn-success"><i class="fa fa-refresh" aria-hidden="true"></i> <{$smarty.const._MD_TCW_ACTION_RE_GET}></a>
    </div>
<{/if}>
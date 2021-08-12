<{assign var="bc" value=$block.BlockContent}>

<{if $bc.main_data}>
    <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <div style="clear: both;"></div>
    <{foreach from=$bc.main_data item=act}>
        <div style="width: 156px; height: 240px; float:left; margin: 5px 2px; overflow: hidden;">
            <a href='action.php?WebID=<{$act.WebID}>&ActionID=<{$act.ActionID}>'>
                <div style="width: 150px; height: 160px; background-color: #F1F7FF ; border:1px dotted green; margin: 0px auto;">
                    <div style="width: 140px; height: 140px; background: #F1F7FF url('<{$act.ActionPic}>') center center no-repeat; border:8px solid #F1F7FF; margin: 0px auto;background-size:cover;"><span class="sr-only"><{$act.ActionID}></span>
                    </div>
                </div>
            </a>
            <div class="text-center" style="margin: 8px auto;">
                <a href='action.php?WebID=<{$act.WebID}>&ActionID=<{$act.ActionID}>'><{$act.ActionName}></a>
                <{*if $act.isCanEdit*}>
                <{if ($WebID && $isMyWeb) || $isAdmin || ($act.cate.CateID && $act.cate.CateID == $smarty.session.isAssistant.act)}>
                <a href="javascript:delete_action_func(<{$act.ActionID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only">delete</span></a>
                <a href="action.php?WebID=<{$WebID}>&op=edit_form&ActionID=<{$act.ActionID}>"  class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only">edit</span></a>
                <{/if}>
            </div>
        </div>
    <{/foreach}>
    <div style="clear: both;"></div>
<{/if}>

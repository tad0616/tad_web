<{assign var="bc" value=$block.BlockContent}>

<{if $bc.main_data|default:false}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <div style="clear: both;"></div>
    <{foreach from=$bc.main_data item=act}>
        <{assign var="ActionID" value=$act.ActionID}>
        <{assign var="read_power" value=$powers.ActionID.$ActionID}>
        <{if $read_power=='' || ($read_power=='users' && $xoops_isuser|default:false) || ($read_power=='web_users' && $LoginWebID==$WebID) || ($read_power=='web_admin' && $isMyWeb)}>
            <div style="width: 156px; height: 240px; float:left; margin: 5px 2px; overflow: hidden;">
                <a href='action.php?WebID=<{$act.WebID}>&ActionID=<{$act.ActionID}>'>
                    <div style="width: 150px; height: 160px; background-color: #F1F7FF ; border:1px dotted green; margin: 0px auto;">
                        <div style="width: 140px; height: 140px; background: #F1F7FF url('<{$act.ActionPic}>') center center no-repeat; border:8px solid #F1F7FF; margin: 0px auto;background-size:cover;"><span class="sr-only visually-hidden">Action of <{$act.ActionID}></span>
                        </div>
                    </div>
                </a>
                <div class="text-center" style="margin: 8px auto;">
                    <a href='action.php?WebID=<{$act.WebID}>&ActionID=<{$act.ActionID}>'><{$act.ActionName}></a>
                    <{*if $act.isCanEdit*}>
                    <{if ($WebID && $isMyWeb) || $smarty.session.tad_web_adm|default:false || (isset($act.cate.CateID) && isset($smarty.session.isAssistant.act) && $act.cate.CateID == $smarty.session.isAssistant.act)}>
                        <a href="javascript:delete_action_func(<{$act.ActionID}>);" class="text-danger"><i class="fa fa-trash"></i><span class="sr-only visually-hidden">delete</span></a>
                        <a href="action.php?WebID=<{$WebID|default:''}>&op=edit_form&ActionID=<{$act.ActionID}>"  class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                    <{/if}>
                </div>
            </div>
        <{/if}>
    <{/foreach}>
    <div style="clear: both;"></div>
<{/if}>

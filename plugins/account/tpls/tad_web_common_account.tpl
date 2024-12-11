<{if $web_display_mode=='home_plugin'}>
    <h2><a href="index.php?WebID=<{$WebID|default:''}>"><i class="fa fa-home"></i></a>
        <{if $cate.CateName|default:false}><a href="account.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
        <{$account.PluginTitle}></h2>
<{/if}>

<{if $account_data|default:false}>
    <table class="footable table common_table">
        <thead>
            <tr>
                <th style="width:100px;text-align:center;">
                    <{$smarty.const._MD_TCW_ACCOUNT_DATE}>
                </th>
                <th data-class="expand">
                    <{$smarty.const._MD_TCW_ACCOUNT_TITLE}>
                </th>
                <th data-hide="phone" style="text-align: right;">
                    <{$smarty.const._MD_TCW_ACCOUNT_MONEY}>
                </th>
            </tr>
        </thead>
        <{foreach from=$account_data item=account}>
            <tr>
                <td style="text-align:center;">
                    <{$account.AccountDate}>
                </td>
                <td>
                    <a href='account.php?WebID=<{$account.WebID}>&AccountID=<{$account.AccountID}>'><{$account.AccountTitle}></a>
                    <{if $account.hide|default:false}><span class="badge badge-danger bg-danger"><{$account.hide}></span><{/if}>
                    <{*if $account.isCanEdit*}>
                    <{if ($WebID && $isMyWeb) || $smarty.session.tad_web_adm|default:false || (isset($account.cate.CateID) && isset($smarty.session.isAssistant.account) && $account.cate.CateID == $smarty.session.isAssistant.account)}>
                        <a href="javascript:delete_account_func(<{$account.AccountID}>);" class="text-danger"><i class="fa fa-trash"></i><span class="sr-only visually-hidden">delete</span></a>
                        <a href="account.php?WebID=<{$WebID|default:''}>&op=edit_form&AccountID=<{$account.AccountID}>" class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit_form</span></a>
                    <{/if}>
                </td>
                <td style="text-align:right;">
                    <{$account.Money}>
                </td>
            </tr>
        <{/foreach}>
        <tr>
            <td style="text-align:center;">
                <{$smarty.const._MD_TCW_ACCOUNT_TOTAL}>
            </td>
            <td></td>
            <td style="text-align:right;">
                <{$AccountTotal|default:''}>
            </td>
        </tr>
    </table>

    <{if $account_data|default:false}>
        <{if $web_display_mode=='index_plugin' or $web_display_mode=='home_plugin'}>
            <{$bar|default:''}>
        <{/if}>
    <{/if}>

    <div style="text-align:right; margin: 0px 0px 10px;">
        <{if $web_display_mode=='index'}>
            <a href="account.php" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
        <{elseif $web_display_mode=='home' or $AccountDefCateID}>
            <a href="account.php?WebID=<{$WebID|default:''}>" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
        <{/if}>

        <{if $isMyWeb and $WebID}>
            <a href="account.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
        <{/if}>
    </div>
<{/if}>
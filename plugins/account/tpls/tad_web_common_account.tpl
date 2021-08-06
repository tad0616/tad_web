<{if $web_display_mode=='home_plugin'}>
    <h2><a href="index.php?WebID=<{$WebID}>">&#xf015;</a> <a href="account.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a> <{$account.PluginTitle}></h2>
<{/if}>

<{if $account_data}>
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
        <{foreach item=account from=$account_data}>
            <tr>
                <td style="text-align:center;">
                    <{$account.AccountDate}>
                </td>
                <td>
                    <a href='account.php?WebID=<{$account.WebID}>&AccountID=<{$account.AccountID}>'><{$account.AccountTitle}></a>
                    <{if $account.hide}><span class="badge badge-danger"><{$account.hide}></span><{/if}>
                    <{*if $account.isCanEdit*}>
                    <{if ($WebID && $isMyWeb) || $isAdmin || ($account.cate.CateID && $account.cate.CateID == $smarty.session.isAssistant.account)}>
                        <a href="javascript:delete_account_func(<{$account.AccountID}>);" class="text-danger"><i class="fa fa-trash-o"></i></a>
                        <a href="account.php?WebID=<{$WebID}>&op=edit_form&AccountID=<{$account.AccountID}>" class="text-warning"><i class="fa fa-pencil"></i></a>
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
                <{$AccountTotal}>
            </td>
        </tr>
    </table>

    <{if $account_data}>
        <{if $web_display_mode=='index_plugin' or $web_display_mode=='home_plugin'}>
            <{$bar}>
        <{/if}>
    <{/if}>

    <div style="text-align:right; margin: 0px 0px 10px;">
        <{if $web_display_mode=='index'}>
            <a href="account.php" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
        <{elseif $web_display_mode=='home' or $AccountDefCateID}>
            <a href="account.php?WebID=<{$WebID}>" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
        <{/if}>

        <{if $isMyWeb and $WebID}>
            <a href="account.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
        <{/if}>
    </div>
<{/if}>
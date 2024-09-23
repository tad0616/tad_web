<form action="config.php" method="post" enctype="multipart/form-data" class="form-horizontal myForm" role="form">
    <h3>
        <{$smarty.const._MD_TCW_FCNCTION_SETUP}>
    </h3>
    <div class="alert alert-info">
        <{$smarty.const._MD_TCW_ABOUT_PLUGIN_TOOLS}>
    </div>
    <div id="save_msg"><{$smarty.const._TAD_SORTABLE}></div>
    <table class="table">
        <tr>
            <th><{$smarty.const._MD_TCW_CATE_PLUGIN_ENABLE}></th>
            <th><{$smarty.const._MD_TCW_CATE_PLUGIN_TITLE}></th>
            <th><{$smarty.const._MD_TCW_CATE_PLUGIN_NEW_NAME}></th>
            <th><{$smarty.const._MD_TCW_SETUP}></th>
        </tr>
        <tbody id="sort">
            <{foreach from=$plugins item=plugin}>
                <{if $plugin.dirname=="system"}>
                    <input type="hidden" name="plugin_enable[<{$plugin.dirname}>]" value="1">
                    <input type="hidden" name="plugin_name[<{$plugin.dirname}>]" value="<{$smarty.const._MD_TCW_SYSTEM}>">
                <{else}>
                    <tr id="tr_<{$plugin.dirname}>">
                        <td <{if $plugin.db.PluginEnable=='0'}>style="background-color: #dfdfdf; color: #5f5f5f;"<{/if}>>
                            <div class="form-check form-check-inline checkbox-inline">
                                <label class="form-check-label" for="plugin_enable_<{$plugin.dirname}>">
                                    <input class="form-check-input" id="plugin_enable_<{$plugin.dirname}>" type="checkbox" name="plugin_enable[<{$plugin.dirname}>]" value="1" <{if $plugin.db.PluginEnable=='1'}>checked<{elseif $plugin.db.PluginEnable=='0'}><{else}>checked<{/if}>>
                                    <{$plugin.dirname}>
                                </label>
                            </div>
                        </td>
                        <td <{if $plugin.db.PluginEnable=='0'}>style="background-color: #dfdfdf; color: #5f5f5f;"<{/if}>>
                            <{$plugin.config.name}>
                        </td>
                        <td <{if $plugin.db.PluginEnable=='0'}>style="background-color: #dfdfdf; color: #5f5f5f;"<{/if}>>
                            <input type="text" name="plugin_name[<{$plugin.dirname}>]" value="<{if $plugin.db.PluginTitle|default:false}><{$plugin.db.PluginTitle}><{else}><{$plugin.config.name}><{/if}>" class="form-control" style="width: 120px;">
                        </td>
                        <td <{if $plugin.db.PluginEnable=='0'}>style="background-color: #dfdfdf; color: #5f5f5f;"<{/if}>>
                            <a href="setup.php?WebID=<{$WebID|default:''}>&plugin=<{$plugin.dirname}>" class="btn btn-success"><i class="fa fa-wrench"></i> <{$smarty.const._MD_TCW_SETUP}></a>
                        </td>
                    </tr>
                <{/if}>
            <{/foreach}>
        </tbody>
    </table>
    <div class="text-center">
        <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
        <input type="hidden" name="op" value="save_plugins">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
    </div>
</form>
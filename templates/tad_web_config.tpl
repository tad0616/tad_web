<{if $isMyWeb|default:false}>
    <{if $op=="delete_tad_web_chk"}>
        <h2><{$smarty.const._MD_TCW_WILL_DEL}></h2>
        <form action="config.php" method="post" class="myForm">
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <th><{$smarty.const._MD_TCW_CATE_PLUGIN_TITLE}></th>
                    <th><{$smarty.const._MD_TCW_PLUGIN_TOTAL}></th>
                </tr>
                <{foreach from=$plugins item=plugin}>
                    <tr>
                        <td>
                            <a href="<{$plugin.dirname}>.php?WebID=<{$WebID|default:''}>" target="_blank"><{$plugin.PluginTitle}></a>
                        </td>
                        <td style="text-align: center;">
                            <a href="<{$plugin.dirname}>.php?WebID=<{$WebID|default:''}>" target="_blank"><{$plugin.total}></a>
                        </td>
                    </tr>
                <{/foreach}>
            </table>
            <input type="hidden" name="op" value="delete_tad_web">
            <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
            <button type="submit" class="btn btn-danger"><{$smarty.const._MD_TCW_DELETE}></button>
        </form>
    <{else}>
        <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/jqueryCookie/jquery.cookie.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#tad_web_config_tabs").tabs({
                    active   : $.cookie('activetab'),
                    activate : function( event, ui ){
                        $.cookie( 'activetab', ui.newTab.index(),{
                            expires : 30
                        });
                    }
                });

                $('#sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
                    var order = $(this).sortable('serialize');
                    $.post('save_sort.php?WebID=<{$WebID|default:''}>', order, function(theResponse){
                        $('#save_msg').html(theResponse);
                    });
                }
                });
            });
        </script>




        <div id="ConfigTab">
            <ul class="resp-tabs-list vert">
                <li><{$smarty.const._MD_TCW_TOOLS}></li>
                <li><{$smarty.const._MD_TCW_PLUGIN_TOOLS}></li>
                <li><{$smarty.const._MD_TCW_HEAD_TOOLS}></li>
                <li><{$smarty.const._MD_TCW_LOGO_TOOLS}></li>
                <li><{$smarty.const._MD_TCW_BG_TOOLS}></li>
                <li><{$smarty.const._MD_TCW_COLOR_TOOLS}></li>
                <li><{$smarty.const._MD_TCW_ADMIN_SETUP}></li>
            </ul>
            <div class="resp-tabs-container vert">

                <div>
                    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_config_tab_1.tpl"}>
                </div>

                <div>
                    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_config_tab_2.tpl"}>
                </div>

                <div>
                    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_config_tab_3.tpl"}>
                </div>

                <div>
                    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_config_tab_4.tpl"}>
                </div>

                <div>
                    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_config_tab_5.tpl"}>
                </div>

                <div>
                    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_config_tab_6.tpl"}>
                </div>

                <div>
                    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_config_tab_7.tpl"}>
                </div>
            </div>
        </div>
    <{/if}>
<{/if}>
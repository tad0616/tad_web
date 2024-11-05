<{if $isMyWeb|default:false}>
    <script type="text/javascript">
        $(document).ready(function() {
            var plugin="<{$plugin|default:''}>";
            if(plugin!=''){
                $.post("<{$xoops_url}>/modules/tad_web/ajax.php", { op: 'get_cate_options', plugin: $('#plugin').val(), WebID: '<{$WebID|default:''}>'}, function(data){
                    $('#CateID').html(data);
                });

                $('#CateID').show();
            }

            $("#plugin").change(function(event) {
                $.post("<{$xoops_url}>/modules/tad_web/ajax.php", { op: 'get_cate_options', plugin: $('#plugin').val(), WebID: '<{$WebID|default:''}>'}, function(data){
                    $('#CateID').html(data);
                });

                $('#CateID').show();

                if($("#CateID").val()!=''){
                    $('#submit').show();
                }else{
                    $('#submit').hide();
                }
            });

            $("#CateID").change(function(event) {
                if($("#CateID").val()!=''){
                    $('#submit').show();
                }else{
                    $('#submit').hide();
                }
            });

        });
    </script>

    <h2><{$smarty.const._MD_TCW_CATE_SET_ASSISTANT}></h2>

    <form action="assistant.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    <select name="MemID" class="form-select" id="MemID" title="Mem ID">
                        <{foreach from=$AllMems item=mem}>
                            <option value="<{$mem.MemID}>">(<{$mem.MemNum}>) <{$mem.MemName}></option>
                        <{/foreach}>
                    </select>
                </td>
                <td>
                    <select name="plugin" class="form-select" id="plugin" title="plugin">
                        <option value=""><{$smarty.const._MD_TCW_CATE_PLUGIN_TITLE}></option>
                        <{foreach from=$plugin_menu_var key=plugin_name item=plugin_data}>
                            <{if $plugin_data.assistant=="1"}>
                                <option value="<{$plugin_name|default:''}>" <{if $plugin_name==$plugin}>selected<{/if}>><{$plugin_data.title}></option>
                            <{/if}>
                        <{/foreach}>
                    </select>
                </td>
                <td>
                    <select name="CateID" class="form-select" id="CateID" title="Cate ID" style="display:none;">
                    </select>
                </td>
                <td>
                    <input type="hidden" name="plugin" value="<{$plugin|default:''}>">
                    <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
                    <input type="hidden" name="op" value="save_assistant">
                    <button type="submit" id="submit" class="btn btn-primary" style="display:none;"><{$smarty.const._MD_TCW_CATE_SET_ASSISTANT}></button>
                </td>
            </tr>
            <{if $all_assistant|default:false}>
                <{foreach from=$all_assistant item=assistant}>
                    <tr>
                        <td <{if $assistant.ColName==$plugin}>style="background-color:#F0F7AF;"<{/if}>>
                            <a href="aboutus.php?WebID=<{$WebID|default:''}>&op=show_stu&CateID=<{$assistant.mem.CateID}>&MemID=<{$assistant.AssistantID}>">(<{$assistant.mem.MemNum}>) <{$assistant.mem.MemName}></a>
                        </td>
                        <td <{if $assistant.ColName==$plugin}>style="background-color:#F0F7AF;"<{/if}>>
                            <a href="<{$assistant.plugin.url}>"><{$assistant.plugin.title}></a>
                        </td>
                        <td <{if $assistant.ColName==$plugin}>style="background-color:#F0F7AF;"<{/if}>>
                            <{$assistant.CateName}>
                        </td>
                        <td <{if $assistant.ColName==$plugin}>style="background-color:#F0F7AF;"<{/if}>>
                            <a href="javascript:delete_assistant_func('<{$assistant.CateID}>');" class="btn btn-danger btn-sm btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}></a>
                        </td>
                    </tr>
                <{/foreach}>
            <{/if}>
        </table>
    </form>
<{/if}>
<{if $plugin_setup}>
    <script>
        function enableBlock(BlockID){
            var img_id="#"+BlockID+"_icon";
            var now_status=$(img_id).attr('title');
            if(now_status=='1'){
                status=0;
            }else{
                status=1;
            }
            $.post("save_block.php", {op:'save_enable', WebID: "<{$WebID}>", BlockEnable: status, BlockID: BlockID}, function(data) {
                $(img_id).attr("src","images/show"+status+".gif");
                $(img_id).attr("title",status);
            });
        }
    </script>

    <h2>
        <a href="<{$plugin_arr.PluginDirname}>.php?WebID=<{$WebID}>"><{$plugin_arr.PluginTitle}></a>
        <{$smarty.const._MD_TCW_PLUGIN_TOOLS}>
    </h2>

    <form action="setup.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
        <hr>
            <{foreach from=$plugin_setup item=setup}>
                <div class="form-group row mb-3">
                    <label class="col-md-3 col-form-label text-sm-right control-label">
                        <{$setup.text}>
                    </label>
                    <div class="col-md-4">
                        <{if $setup.type=="text"}>
                            <input type="text" name="<{$setup.name}>" value="<{$setup.value}>" class="form-control">
                        <{elseif $setup.type=="select"}>
                            <select name="<{$setup.name}>" id="<{$setup.name}>" class="form-control" title="Select">
                                <{foreach from=$setup.options key=title item=value}>
                                <option value="<{$value}>" <{if $setup.value==$value}>selected<{/if}>><{$title}> <{$value}></option>
                                <{/foreach}>
                            </select>
                        <{elseif $setup.type=="radio"}>
                            <{foreach from=$setup.options key=title item=value}>
                                <div class="form-check form-check-inline radio-inline">
                                    <label class="form-check-label" for="<{$setup.name}>">
                                        <input class="form-check-input" type="radio" name="<{$setup.name}>" id="<{$setup.name}>" value="<{$value}>" <{if $setup.value==$value}>checked<{/if}>>
                                        <{$title}>
                                    </label>
                                </div>
                            <{/foreach}>
                        <{elseif $setup.type=="checkbox"}>
                            <{foreach from=$setup.options key=title item=value}>
                                <div class="form-check form-check-inline checkbox-inline">
                                    <label class="form-check-label" for="<{$setup.name}>_<{$value}>">
                                        <input class="form-check-input" type="checkbox" name="<{$setup.name}>[]" id="<{$setup.name}>_<{$value}>" value="<{$value}>" <{if $value|in_array:$setup.value}>checked<{/if}>>
                                        <{$title}>
                                    </label>
                                </div>
                            <{/foreach}>
                        <{elseif $setup.type=="color"}>
                            <input type="text" name="<{$setup.name}>" id="<{$setup.name}>" value="<{$setup.value}>" class="form-control" data-hex="true" >
                        <{elseif $setup.type=="array"}>
                            <textarea name="<{$setup.name}>" class="form-control" rows=4 style="font-size:0.8em;"><{$setup.value}></textarea>
                        <{elseif $setup.type=="textarea"}>
                            <textarea name="<{$setup.name}>" class="form-control" rows=4 style="font-size:0.8em;"><{$setup.value}></textarea>
                        <{elseif $setup.type=="yesno"}>
                            <div class="form-check form-check-inline radio-inline">
                                <label class="form-check-label" for="<{$setup.name}>1">
                                    <input class="form-check-input" type="radio" name="<{$setup.name}>" id="<{$setup.name}>1" value="1" <{if $setup.value=='1'}>checked<{/if}> >
                                    <{$smarty.const._YES}>
                                </label>
                            </div>
                            <div class="form-check form-check-inline radio-inline">
                                <label class="form-check-label" for="<{$setup.name}>0">
                                    <input class="form-check-input" type="radio" name="<{$setup.name}>" id="<{$setup.name}>0" value="0" <{if $setup.value!='1'}>checked<{/if}> >
                                    <{$smarty.const._NO}>
                                </label>
                            </div>
                        <{elseif $setup.type=="file"}>
                            <{$setup.form}>
                        <{/if}>
                    </div>

                    <{if $setup.type=="file"}>
                        <div class="col-md-5">
                            <{if $setup.list}>
                                <div style="width:60px; height:86px; display:inline-block; margin:4px;">
                                    <label for="<{$setup.name}>0" style="width:60px; height:60px;border:1px dotted gray;" >
                                        <input type="radio" name="<{$setup.name}>" id="<{$setup.name}>0" value="" <{if $setup.value==""}>checked<{/if}>>
                                        <{$smarty.const._MA_TADTHEMES_NONE}>
                                    </label>
                                </div>

                                <div style="width:60px; height:86px; display:inline-block; margin:4px;">
                                    <label for="<{$setup.name}>" style="width:60px; height:60px; background:#000000 url(<{$setup.default}>);background-repeat:no-repeat;background-position:left center;border:1px solid gray;background-size: cover;" >
                                        <input type="radio" name="<{$setup.name}>" id="<{$setup.name}><{$file.files_sn}>" value="<{$setup.default}>"  <{if $setup.value==$setup.default}>checked<{/if}>>
                                    </label>
                                    <label class="checkbox" style="font-size: 68.75%;">
                                    <{$smarty.const._MA_TADTHEMES_DEFAULT}>
                                    </label>

                                </div>

                                <{foreach from=$setup.list item=file}>
                                    <div style="width:60px; height:86px; display:inline-block; margin:4px;">
                                        <label for="<{$setup.name}><{$file.files_sn}>" style="width:60px; height:60px; background:#000000 url(<{$file.tb_path}>);background-position:left center;border:1px solid gray;" >
                                        <input type="radio" name="<{$setup.name}>" id="<{$setup.name}><{$file.files_sn}>" value="<{$file.path}>" onChange="$('.del_<{$setup.name}>').show(); $('#del_<{$setup.name}><{$file.files_sn}>').hide();" <{if $setup.value==$file.path}>checked<{/if}>>
                                        </label>
                                        <label class="checkbox del_<{$setup.name}>" style="font-size: 68.75%;" id="del_<{$setup.name}><{$file.files_sn}>">
                                        <input type="checkbox" value="<{$file.files_sn}>" name="del_file[<{$file.files_sn}>]"> <{$smarty.const._TAD_DEL}>
                                        </label>
                                    </div>
                                <{/foreach}>
                                <div style="clear:both;"></div>
                            <{/if}>
                        </div>
                    <{else}>
                        <div class="col-md-5">
                            <div class="alert alert-info">
                                <{$setup.desc}>
                            </div>
                        </div>
                    <{/if}>
                </div>
            <{/foreach}>
        <hr>
        <div class="text-center">
            <input type="hidden" name="plugin" value="<{$plugin}>">
            <input type="hidden" name="WebID" value="<{$WebID}>">
            <input type="hidden" name="op" value="save_plugin_setup">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
<{/if}>

<h2>
    <a href="<{$plugin_arr.PluginDirname}>.php?WebID=<{$WebID}>"><{$plugin_arr.PluginTitle}></a>
    <{$smarty.const._MD_TCW_BLOCK_TOOLS}>
</h2>

<table class="table table-hover table-striped">
    <tr>
        <th style="text-align: center;">
            <{$smarty.const._MD_TCW_BLOCK_TITLE}>
        </th>
        <th style="text-align: center;">
            <{$smarty.const._MD_TCW_BLOCK_ENABLE}>
        </th>
        <th style="text-align: center;">
            <{$smarty.const._MD_TCW_BLOCK_POSITION}>
        </th>
        <th style="text-align: center;">
            <{$smarty.const._MD_TCW_BLOCK_DEMO}>
        </th>
        <th style="text-align: center;">
            <{$smarty.const._MD_TCW_TOOLS}>
        </th>
    </tr>
    <{foreach from=$web_install_blocks key=i item=block_arr}>
        <{assign var=Position value=$block_arr.BlockPosition}>
        <tr>
            <td style="text-align: center;">
                <a href="block.php?WebID=<{$WebID}>&op=demo&plugin=<{$plugin}>&BlockID=<{$block_arr.BlockID}>"data-fancybox-type="iframe" class="edit_block"><{$block_arr.BlockTitle}></a>
            </td>
            <td style="text-align: center;">
                <{if $block_arr.BlockEnable==1}>
                    <a href="javascript:enableBlock(<{$block_arr.BlockID}>);"><img src="images/show1.gif" alt="_TAD_ENABLE" id="<{$block_arr.BlockID}>_icon" title="1"></a>
                <{else}>
                    <a href="javascript:enableBlock(<{$block_arr.BlockID}>);"><img src="images/show0.gif" alt="_TAD_ENABLE" id="<{$block_arr.BlockID}>_icon" title="0"></a>
                <{/if}>
            </td>
            <td style="text-align: center;"><{$BlockPositionTitle.$Position}></td>
            <td style="text-align: center;">
                <a href="block.php?WebID=<{$WebID}>&op=demo&plugin=<{$plugin}>&BlockID=<{$block_arr.BlockID}>"data-fancybox-type="iframe" class="edit_block"><{$smarty.const._MD_TCW_BLOCK_DEMO}></a>
            </td>
            <td style="text-align: center;">
                <a href="block.php?WebID=<{$WebID}>&op=config&plugin=<{$plugin}>&BlockID=<{$block_arr.BlockID}>"><{$smarty.const._MD_TCW_TOOLS}></a>
            </td>
        </tr>
    <{/foreach}>
</table>
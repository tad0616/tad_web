<div class="container-fluid mb-5">
    <{if $op=="tad_web_form"}>
        <form action="main.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-sm-right control-label">
                    <{$smarty.const._MA_TCW_TEAMNAME}>
                </label>
                <div class="col-sm-10">
                    <input type="text" name="WebName" placeholder="<{$smarty.const.WebName}>" value="<{$WebName}>" id="WebName" class="form-control validate[required]">
                </div>
            </div>
            <{if $cate_menu}>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label text-sm-right control-label">
                        <{$smarty.const._MA_TADWEB_CATENAME}>
                    </label>
                    <div class="col-sm-10">
                        <{$cate_menu}>
                    </div>
                </div>
            <{/if}>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-sm-right control-label">
                    <{$smarty.const._MA_TCW_TEAMTITLE}>
                </label>
                <div class="col-sm-2">
                    <select name="year" class="form-control">
                        <option value=""></option>
                        <option value="<{$last_year}>"><{$last_year}></option>
                        <option value="<{$now_year}>"><{$now_year}></option>
                        <option value="<{$next_year}>"><{$next_year}></option>
                    </select>
                </div>
                <div class="col-sm-8">
                    <input type="text" name="WebTitle" value="<{$WebTitle}>" id="WebTitle" class="validate[required] form-control">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-sm-right control-label">
                    <{$smarty.const._MA_TCW_TEAMLEADER}>
                </label>
                <div class="col-sm-10">
                    <{$user_menu}>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label text-sm-right control-label">
                    <{$smarty.const._MA_TCW_TEAMENABLE}>
                </label>
                <div class="col-sm-10" style="padding-top: 8px;">
                    <div class="form-check-inline radio-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="WebEnable" value="1" <{if $WebEnable=='1'}>checked<{/if}>>
                            <{$smarty.const._TAD_ENABLE}>
                        </label>
                    </div>
                    <div class="form-check-inline radio-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="WebEnable" value="0" <{if $WebEnable=='0'}>checked<{/if}>>
                            <{$smarty.const._TAD_UNABLE}>
                        </label>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <input type="hidden" name="WebSort" size="20" value="<{$WebSort}>" id="WebSort">

                <!--編號-->
                <input type="hidden" name="WebID" value="<{$WebID}>">
                <input type="hidden" name="op" value="<{$next_op}>">
                <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
            </div>
        </form>
    <{elseif $op=="batch_creat_class"}>
        <form action="main.php" method="post" id="myForm" enctype="multipart/form-data">
        <{$content}>
        <input type="hidden" name="op" value="batch_add_class">
        <input type="submit" value="<{$smarty.const._TAD_SAVE}>">
        </form>
    <{elseif $op=="create_web"}>

        <a href="main.php?op=create_by_user" class="btn btn-large btn-info"><{$smarty.const._MA_TCW_CREATE_BY_USER}></a>
    <{elseif $op=="create_by_user"}>
        <script type="text/javascript" src="<{$xoops_url}>/modules/tad_web/class/tmt_core.js"></script>
        <script type="text/javascript" src="<{$xoops_url}>/modules/tad_web/class/tmt_spry_linkedselect.js"></script>
        <script type="text/javascript">
        function getOptions()
        {
            var values = [];
            var sel = document.getElementById('destination');
            for (var i=0, n=sel.options.length;i<n;i++) {
                if (sel.options[i].value) values.push(sel.options[i].value);
            }
            document.getElementById('WebOwnerUid').value=values.join(',');
        }
        </script>

        <form action="main.php" method="post" id="myForm" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-3">
                    <h3><{$smarty.const._MA_TCW_ALL_USER_NO}></h3>
                    <select name="repository" id="repository" size="12" multiple="multiple" tmt:linkedselect="true" class="form-control">
                        <{if $opt}>
                            <{foreach from=$opt key=uid item=name}>
                                <option value="<{$uid}>"><{$name}></option>
                            <{/foreach}>
                        <{/if}>
                    </select>
                </div>
                <div class="col-md-1 text-center" style="padding-top: 60px;">
                    <img src="<{$xoops_url}>/modules/tad_web/images/right.png" onclick="tmt.spry.linkedselect.util.moveOptions('repository', 'destination');getOptions();"><br>
                    <img src="<{$xoops_url}>/modules/tad_web/images/left.png" onclick="tmt.spry.linkedselect.util.moveOptions('destination' , 'repository');getOptions();"><br><br>
                    <img src="<{$xoops_url}>/modules/tad_web/images/up.png" onclick="tmt.spry.linkedselect.util.moveOptionUp('destination');getOptions();"><br>
                    <img src="<{$xoops_url}>/modules/tad_web/images/down.png" onclick="tmt.spry.linkedselect.util.moveOptionDown('destination');getOptions();"><br><br>

                    <input type="hidden" name="WebOwnerUid" id="WebOwnerUid" value="<{$WebOwnerUid}>">
                    <input type="hidden" name="op" value="batch_add_class_by_user">
                    <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
                </div>
                <div class="col-md-3">
                    <h3><{$smarty.const._MA_TCW_ALL_USER_YES}></h3>
                    <select id="destination" size="12" multiple="multiple" tmt:linkedselect="true" class="form-control">
                        <{if $opt2}>
                        <{foreach from=$opt2 key=uid item=name}>
                            <option value="<{$uid}>"><{$name}></option>
                        <{/foreach}>
                        <{/if}>
                    </select>
                </div>
            </div>
        </form>
    <{elseif $op=="delete_tad_web_chk"}>
        <h2><{$smarty.const._MA_TCW_WILL_DEL}></h2>
        <form action="main.php" method="post">
            <table class="table table-striped table-bordered table-hover">
                <tr><th><{$smarty.const._MA_TADWEB_PLUGIN_TITLE}></th><th><{$smarty.const._MA_TADWEB_PLUGIN_TOTAL}></th></tr>
                <{foreach from=$plugins item=plugin}>
                <tr>
                    <td><a href="../<{$plugin.dirname}>.php?WebID=<{$WebID}>" target="_blank"><{$plugin.PluginTitle}></a></td>
                    <td style="text-align: center;"><a href="../<{$plugin.dirname}>.php?WebID=<{$WebID}>" target="_blank"><{$plugin.total}></a></td>
                </tr>
                <{/foreach}>
            </table>
            <input type="hidden" name="op" value="delete_tad_web">
            <input type="hidden" name="WebID" value="<{$WebID}>">
            <input type="hidden" name="g2p" value="<{$g2p}>">

            <button type="submit" class="btn btn-danger"><{$smarty.const._MA_TCW_DELETE}></button>
        </form>
    <{elseif $op=="error"}>
        <{foreach from=$error key=title item=content}>
        <h2><{$title}></h2>
        <div class="alert alert-danger">
            <{$content}>
        </div>
        <{/foreach}>
    <{else}>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#sort").sortable({ opacity: 0.6, cursor: "move", update: function() {
                    var order = $(this).sortable("serialize");
                    $.post("save_sort.php", order, function(theResponse){
                        $("#save_msg").html(theResponse);
                    });
                }
                });
            });

            function delete_tad_web_func(WebID){
                var sure = window.confirm("<{$smarty.const._TAD_DEL_CONFIRM}>");
                if (!sure)  return;
                location.href="main.php?g2p=<{$g2p}>&WebID=" + WebID + "op=delete_tad_web_chk";
            }
        </script>


        <div>
            <a href="main.php?op=tad_web_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MA_TCW_TEAM}></a>
            <a href="main.php?op=create_by_user" class="btn btn-primary"><{$smarty.const._MA_TCW_CREATE_BY_USER}></a>
            <a href="main.php?op=order_by_teamtitle" class="btn btn-success"><{$smarty.const._MA_TCW_ORDER_BY_TEAMTITLE}></a>
        </div>

        <h2><{$smarty.const._MA_TCW_MAIN_TITLE}></h2>

        <{if $cate}>
            <div class="row">
                <div class="col-md-3">
                <select name="CateID" id="CateID" class="form-control" onchange="location.href='main.php?CateID='+this.value">
                    <option value="" <{if $CateID==""}>selected="selected"<{/if}>><{$smarty.const._MA_TCW_SELECT_CATE}></option>
                    <{foreach from=$cate item=cate}>
                    <option value="<{$cate.CateID}>" <{if $CateID==$cate.CateID}>selected="selected"<{/if}>><{$cate.CateName}></option>
                    <{/foreach}>
                </select>
                </div>
            </div>
        <{/if}>

        <{$bar}>
        <form action="main.php" method="post" id="myForm" enctype="multipart/form-data">
            <div id="save_msg"></div>
            <table class="table table-striped table-hover">
                <tr>
                <th><{$smarty.const._TAD_DEL}></th>
                <th><{$smarty.const._MA_TCW_TEAMNAME}></th>
                <th><{$smarty.const._MA_TCW_TEAMTITLE}></th>
                <th><{$smarty.const._MA_TCW_TEAMLEADER}></th>
                <th><{$smarty.const._MA_TCW_CO_ADMIN}></th>
                <th><{$smarty.const._MA_TCW_MEM_AMOUNT}></th>
                <th><{$smarty.const._MA_TCW_TEAMCOUNTER}></th>
                <th><{$smarty.const._MA_TCW_LAST_ACCESSED}></th>
                <th><{$smarty.const._TAD_FUNCTION}></th>
                </tr>
                <tbody id="sort">
                <{foreach from=$data item=class}>
                <tr id="tr_<{$class.WebID}>">
                    <td>
                    <a href="main.php?WebID=<{$class.WebID}>&op=delete_tad_web_chk&g2p=<{$g2p}>" class="btn btn-sm btn-danger"><{$smarty.const._TAD_DEL}></a>
                    </td>
                    <td>

                    <{if $class.WebEnable=='1'}>
                    <a href="main.php?WebID=<{$class.WebID}>&op=save_webs_able&able=0"><img src="../images/show1.gif" alt="<{$smarty.const._TAD_ENABLE}>"></a>
                    <{else}>
                    <a href="main.php?WebID=<{$class.WebID}>&op=save_webs_able&able=1"><img src="../images/show0.gif" alt="<{$smarty.const._TAD_UNABLE}>"></a>
                    <{/if}>

                    <a href="../index.php?WebID=<{$class.WebID}>" target="_blank"><{$class.WebName}></a>
                    </td>
                    <td style="width: 300px;">
                    <input type="text" name="webTitle[<{$class.WebID}>]" value="<{$class.WebTitle}>" class="form-control">
                    <input type="hidden" name="old_webTitle[<{$class.WebID}>]" value="<{$class.WebTitle}>">
                    </td>
                    <td>
                    <{$class.WebOwner}> (<{$class.uname}>)
                    </td>
                    <td>
                    <{if $class.admin_arr}>
                        <{foreach from=$class.admin_arr item=admin}>
                        <{$admin.name}> (<{$admin.uname}>),
                        <{/foreach}>
                    <{/if}>
                    </td>
                    <td>
                    <{$class.memAmount}>
                    </td>
                    <td>
                    <{$class.WebCounter}>
                    </td>
                    <td>
                    <{$class.last_accessed}>
                    </td>
                    <td>
                    <a href="main.php?WebID=<{$class.WebID}>&op=tad_web_form" class="btn btn-sm btn-info"><{$smarty.const._TAD_EDIT}></a>

                    </td>
                </tr>
                <{/foreach}>
                </tbody>
            </table>
            <div class="text-center">
                <input type="hidden" name="op" value="save_webs_title">
                <input type="hidden" name="g2p" value="<{$g2p}>">
                <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
            </div>
        </form>
        <{$bar}>
    <{/if}>
</div>
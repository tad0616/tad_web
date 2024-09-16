<div class="container-fluid">
    <!--顯示表單-->
    <{if $now_op=="tad_web_cate_form"}>
        <{$delete_tad_web_cate_func}>

        <form action="<{$action}>" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
            <!--分類名稱-->
            <div class="form-group row mb-3">
                <label class="col-md-1 col-form-label text-sm-right text-sm-end control-label">
                    <{$smarty.const._MA_TADWEB_CATENAME}>
                </label>
                <div class="col-md-3">
                    <input type="text" name="CateName" id="CateName" class="form-control validate[required]" value="<{$CateName}>" placeholder="<{$smarty.const._MA_TADWEB_CATENAME}>">
                </div>
                <div class="col-md-8">
                    <input type='hidden' name="CateID" value="<{$CateID}>">
                    <input type="hidden" name="ColName" id="ColName" value="<{$ColName}>">
                    <input type="hidden" name="ColSN" id="ColSN" value="<{$ColSN}>">
                    <input type="hidden" name="CateSort" id="CateSort" value="<{$CateSort}>">
                    <input type="hidden" name="CateEnable" id="CateEnable" value="<{$CateEnable}>">
                    <input type="hidden" name="op" value="<{$next_op}>">
                    <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
                </div>
            </div>
        </form>

        <script type="text/javascript" src="<{$xoops_url}>/modules/tad_web/class/tmt_core.js"></script>
        <script type="text/javascript" src="<{$xoops_url}>/modules/tad_web/class/tmt_spry_linkedselect.js"></script>
        <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/jqueryCookie/jquery.cookie.js"></script>

        <script type="text/javascript">
            $(function() {
                $("#tad_web_cate_tabs").tabs({
                    active   : $.cookie('activetab'),
                    activate : function( event, ui ){
                        $.cookie( 'activetab', ui.newTab.index(),{
                            expires : 30
                        });
                    }
                });
            });

            function getOptions(tabName)
            {
                var values = [];
                var sel = document.getElementById('destination' + tabName);
                for (var i=0, n=sel.options.length;i<n;i++) {
                    if (sel.options[i].value) values.push(sel.options[i].value);
                }
                var hide_id='web_cate_arr_' + tabName;
                document.getElementById(hide_id).value=values.join(',');

                var values2 = [];
                var sel2 = document.getElementById('repository' + tabName);
                for (var i=0, n=sel2.options.length;i<n;i++) {
                    if (sel2.options[i].value) values2.push(sel2.options[i].value);
                }
                var hide_id2='web_cate_blank_arr_' + tabName;
                document.getElementById(hide_id2).value=values2.join(',');
            }
        </script>

        <div id="tad_web_cate_tabs">
            <ul>
                <{foreach from=$all_content key=i item=cate}>
                    <li><a href="#tabs-<{$i}>"><{$cate.CateName}></a></li>
                <{/foreach}>
            </ul>
            <{foreach from=$all_content key=i item=cate}>
                <div id="tabs-<{$i}>">
                    <{$cate.CateName}>
                    <a href="javascript:delete_tad_web_cate_func(<{$cate.CateID}>);" class="btn btn-sm btn-danger"><{$smarty.const._TAD_DEL}></a>
                    <a href="<{$xoops_url}>/modules/tad_web/admin/cate.php?op=tad_web_cate_form&CateID=<{$cate.CateID}>" class="btn btn-sm btn-warning"><{$smarty.const._TAD_EDIT}></a>

                    <form action="cate.php" method="post" id="myForm<{$i}>" role="form" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-5">
                                <select id="repository<{$i}>" size="12" multiple="multiple" tmt:linkedselect="true" class="form-control" style="height: 200px;">
                                    <{foreach from=$cate.repository item=opt}>
                                    <{if $opt.WebID|default:false}>
                                        <option value="<{$opt.WebID}>"><{$opt.WebOwner}> (<{$opt.WebTitle}>)</option>
                                    <{/if}>
                                    <{/foreach}>
                                </select>
                            </div>
                            <div class="col-md-1 text-center">
                                <img src="<{$xoops_url}>/modules/tad_web/images/right.png" onclick="tmt.spry.linkedselect.util.moveOptions('repository<{$i}>', 'destination<{$i}>'); getOptions(<{$i}>);" alt="right"><br>
                                <img src="<{$xoops_url}>/modules/tad_web/images/left.png" onclick="tmt.spry.linkedselect.util.moveOptions('destination<{$i}>' , 'repository<{$i}>'); getOptions(<{$i}>);" alt="left"><br><br>

                                <img src="<{$xoops_url}>/modules/tad_web/images/up.png" onclick="tmt.spry.linkedselect.util.moveOptionUp('destination<{$i}>'); getOptions(<{$i}>);" alt="up"><br>
                                <img src="<{$xoops_url}>/modules/tad_web/images/down.png" onclick="tmt.spry.linkedselect.util.moveOptionDown('destination<{$i}>'); getOptions(<{$i}>);" alt="down"><br><br>

                                <input type='hidden' name='web_cate_arr' id='web_cate_arr_<{$i}>' value='<{$cate.web_cate_arr_str}>'>
                                <input type='hidden' name='web_cate_blank_arr' id='web_cate_blank_arr_<{$i}>' value='<{$cate.web_cate_blank_arr}>'>
                                <input type='hidden' name="CateID" value="<{$cate.CateID}>">
                                <input type="hidden" name="op" value="update_tad_web_cate_arr">
                                <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
                            </div>
                            <div class="col-md-5">
                                <select id="destination<{$i}>" size="12" multiple="multiple" tmt:linkedselect="true" class="form-control" style="height: 200px;">
                                    <{foreach from=$cate.destination item=opt}>
                                    <{if $opt.WebID|default:false}>
                                        <option value="<{$opt.WebID}>"><{$opt.WebOwner}> (<{$opt.WebTitle}>)</option>
                                    <{/if}>
                                    <{/foreach}>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            <{/foreach}>
        </div>
    <{/if}>
</div>
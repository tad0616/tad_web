<{if $op=="new_class" or $now_cate}>
    <form action="aboutus.php?WebID=<{$WebID}>" method="post" enctype="multipart/form-data" role="form" class="form-horizontal">
        <h3><{$class_setup}></h3>
        <div class="row">
            <div class="col-md-4">
                <{if $class_pic_thumb}>
                    <div class="my-border" style="background-image:url('<{$class_pic_thumb}>');background-repeat:no-repeat;background-size: contain;background-position:center; height:<{if $isMyWeb}>220px<{else}>150px<{/if}>;"></div>
                <{else}>
                    <div class="my-border" style="height: 150px; line-height:  100px; text-align: center;"><{$no_class_photo}></div>
                <{/if}>
            </div>
            <div class="col-md-8">
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-sm-right control-label">
                        <{$edit_class_title}>
                    </label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-append input-group-btn ">
                                <select name="year" class="form-control" style="width:140px;">
                                    <option value="" <{if $op!="new_class"}>selected<{/if}>></option>
                                    <option value="<{$last_year}>"><{$last_year}></option>
                                    <option value="<{$now_year}>" <{if $op=="new_class"}>selected<{/if}>><{$now_year}></option>
                                    <option value="<{$next_year}>"><{$next_year}></option>
                                </select>
                            </div>
                            <input type="text" name="newCateName" value="<{$now_cate.CateName}>" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-sm-right control-label">
                        <{$class_pic}>
                    </label>
                    <div class="col-md-9">
                        <{$upform_class}>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-sm-right control-label">
                        <{$smarty.const._MD_TCW_ABOUTUS_DEFAULT_CLASS}>
                    </label>
                    <div class="col-md-9">
                        <div class="form-check form-check-inline checkbox-inline">
                            <label class="form-check-label" for="default_class">
                                <input class="form-check-input" id="default_class" type="checkbox" name="default_class" value='1' <{if $default_class==$CateID}>checked<{/if}>>
                                <{$smarty.const._MD_TCW_ABOUTUS_DEFAULT_CLASS_DESC}>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-sm-right control-label">
                        <{$smarty.const._MD_TCW_ABOUTUS_HIDE_CLASS}>
                    </label>
                    <div class="col-md-9">
                        <div class="form-check form-check-inline checkbox-inline">
                            <label class="form-check-label" for="hide_class">
                                <input class="form-check-input" id="hide_class" type="checkbox" name="hide_class" value='1' <{if $hide_class==$CateID}>checked<{/if}>>
                                <{$smarty.const._MD_TCW_ABOUTUS_HIDE_CLASS_DESC}>
                            </label>
                        </div>
                    </div>
                </div>

                <{if $op=="new_class" and $old_cate}>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-sm-right control-label">
                            <{$setup_stud}>
                        </label>
                        <div class="col-md-9">
                            <select name="form_CateID" class="form-control">
                                <option value=""><{$smarty.const._MD_TCW_STUDENT_NO_COPY}></option>
                                <{foreach from=$old_cate item=cate}>
                                <option value="<{$cate.CateID}>"><{$cate.CateNameMem}></option>
                                <{/foreach}>
                            </select>
                        </div>
                    </div>
                <{/if}>
                <div class="text-center">
                    <input type="hidden" name="op" value="<{$next_op}>">
                    <input type="hidden" name="WebID" value="<{$WebID}>">
                    <input type="hidden" name="CateID" value="<{$CateID}>">
                    <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
                </div>
                <div class="alert alert-warning"><{$smarty.const._MD_TCW_STUDENT_NOTE}></div>
            </div>
        </div>
    </form>
<{/if}>

<{if $old_cate}>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
                var order = $(this).sortable('serialize');
                $.post('<{$xoops_url}>/modules/tad_web/plugins/aboutus/save_sort.php', order, function(theResponse){
                    $('#save_msg').html(theResponse);
                });
            }
            });
        });
    </script>
    <h3><{$class_list}></h3>
    <div id="save_msg"></div>
    <table class="table table-bordered">
        <tr>
            <th style="text-align: center;"><{$class_title}></th>
            <th style="text-align: center;"><{$student_amount}></th>
            <th style="text-align: center;"><{$smarty.const._TAD_FUNCTION}></th>
        </tr>
        <tbody id="sort">
            <{foreach from=$old_cate item=cate}>
                <tr id="CateID_<{$cate.CateID}>" <{if $cate.CateEnable!=1}>style="background: #cfcfcf;"<{/if}>>
                    <td>
                        <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;margin:0px 4px;" alt="<{$smarty.const._TAD_SORTABLE}>" title="<{$smarty.const._TAD_SORTABLE}>">
                        <a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a>
                        <{if $cate.CateEnable!=1}>
                        <a href="javascript:del_class(<{$cate.CateID}>)" class="btn btn-sm btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                        <{/if}>
                    </td>
                    <td style="text-align: center;">
                        <{$cate.CateMemCount}>
                    </td>
                    <td style="text-align: center;">
                        <{if $cate.CateEnable!=1}>
                            <a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&op=class_enable" class="btn btn-sm btn-xs btn-success"><{$smarty.const._MD_TCW_ENABLE}></a>
                        <{else}>
                            <a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&op=class_unable" class="btn btn-sm btn-xs btn-secondary"><{$smarty.const._MD_TCW_UNABLE}></a>
                        <{/if}>
                        <a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&op=edit_form" class="btn btn-sm btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
                        <a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&op=edit_position" class="btn btn-sm btn-xs btn-success"><{$smarty.const._MD_TCW_STUDENT_POSITION}></a>

                        <a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&op=edit_class_stu" class="btn btn-sm btn-xs btn-info"><{$edit_student}></a>
                    </td>
                </tr>
            <{/foreach}>
        </tbody>
    </table>
<{/if}>

<div class="text-right">
    <a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&op=new_class" class="btn btn-primary"><{$add_class}></a>
</div>
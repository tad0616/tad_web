<h3>
    <{if $cate.CateName|default:false}><a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
    <{$setup_stud|default:''}>
</h3>

<div class="row">
    <div class="col-md-8">
        <form action="aboutus.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
            <!--學生相片-->
            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label text-sm-right text-sm-end control-label">
                    <{$show_files|default:''}>
                </label>
                <div class="col-md-8">
                    <h2><{$MemName|default:''}></h2>
                    <input type="file" name="upfile[]"  maxlength="1" accept="gif|jpg|png|GIF|JPG|PNG">
                </div>
            </div>

            <{if $LoginMemID|default:false}>
                <input type="hidden" name="MemName" value="<{$MemName|default:''}>">
                <input type="hidden" name="MemSex" value="<{$MemSex|default:''}>">
                <input type="hidden" name="MemEnable" value="<{$MemEnable|default:''}>">
            <{else}>
                <div class="alert alert-info">
                    <!--學生姓名-->
                    <div class="form-group row mb-3">
                        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                            <{$smarty.const._MD_TCW_MEM_NAME}>
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="MemName" value="<{$MemName|default:''}>" id="MemName" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_MEM_NAME}>">
                        </div>
                    </div>

                    <!--性別-->
                    <div class="form-group row mb-3">
                        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                            <{$smarty.const._MD_TCW_MEM_SEX}>
                        </label>
                        <div class="col-md-9">
                            <select name="MemSex" class="form-control form-select">
                                <option value="1" <{if $MemSex!='0'}>selected<{/if}>><{$smarty.const._MD_TCW_BOY}></option>
                                <option value="0" <{if $MemSex=='0'}>selected<{/if}>><{$smarty.const._MD_TCW_GIRL}></option>
                            </select>
                        </div>
                    </div>

                    <!--是否還在班上-->
                    <div class="form-group row mb-3">
                        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                            <{$smarty.const._MD_TCW_MEM_STATUS}>
                        </label>
                        <div class="col-md-9">
                            <select name="MemEnable" id="MemEnable" class="form-control form-select">
                                <option value="1" <{if $MemEnable!='0'}>selected<{/if}>><{$smarty.const._MD_TCW_MEM_ENABLE}></option>
                                <option value="0" <{if $MemEnable=='0'}>selected<{/if}>><{$smarty.const._MD_TCW_MEM_UNABLE}></option>
                            </select>
                        </div>
                    </div>
                </div>
            <{/if}>

            <!--座號-->
            <{if 'MemNum'|in_array:$mem_column}>
                <div class="form-group row mb-3">
                    <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                        <{$smarty.const._MD_TCW_MEM_NUM}>
                    </label>
                    <div class="col-md-9">
                        <{if $LoginMemID|default:false}>
                            <input type="hidden" name="MemNum" value="<{$MemNum|default:''}>"><{$MemNum|default:''}>
                        <{else}>
                            <input type="text" name="MemNum" value="<{$MemNum|default:''}>" id="MemNum" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_MEM_NUM}>">
                        <{/if}>
                    </div>
                </div>
            <{/if}>

            <!--學號-->
            <{if 'MemUnicode'|in_array:$mem_column}>
                <div class="form-group row mb-3">
                    <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                        <{$smarty.const._MD_TCW_MEM_UNICODE}>
                    </label>
                    <div class="col-md-9">
                        <{if $LoginMemID|default:false}>
                            <input type="hidden" name="MemUnicode" value="<{$MemUnicode|default:''}>"><{$MemUnicode|default:''}>
                        <{else}>
                            <input type="text" name="MemUnicode" value="<{$MemUnicode|default:''}>" id="MemUnicode" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_MEM_UNICODE}>">
                        <{/if}>
                    </div>
                </div>
            <{/if}>

            <!--生日-->
            <{if 'MemBirthday'|in_array:$mem_column}>
                <div class="form-group row mb-3">
                    <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                        <{$smarty.const._MD_TCW_MEM_BIRTHDAY}>
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="MemBirthday" value="<{$MemBirthday|default:''}>" id="MemBirthday" class="form-control" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d'})" placeholder="<{$smarty.const._MD_TCW_MEM_BIRTHDAY}>">
                    </div>
                </div>
            <{/if}>

            <!--學生暱稱-->
            <{if 'MemNickName'|in_array:$mem_column}>
                <div class="form-group row mb-3">
                    <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                        <{$smarty.const._MD_TCW_MEM_NICKNAME}>
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="MemNickName" value="<{$MemNickName|default:''}>" id="MemNickName" class="form-control" placeholder="<{$smarty.const._MD_TCW_MEM_NICKNAME}>">
                    </div>
                </div>
            <{/if}>

            <!--專長-->
            <{if 'MemExpertises'|in_array:$mem_column}>
                <div class="form-group row mb-3">
                    <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                        <{$smarty.const._MD_TCW_MEM_EXPERTISES}>
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="MemExpertises" value="<{$MemExpertises|default:''}>" id="MemExpertises" class="form-control" placeholder="<{$smarty.const._MD_TCW_MEM_EXPERTISES}>">
                    </div>
                </div>
            <{/if}>

            <!--職稱-->
            <{if 'MemClassOrgan'|in_array:$mem_column}>
                <div class="form-group row mb-3">
                    <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                        <{$smarty.const._MD_TCW_MEM_CLASSORGAN}>
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="MemClassOrgan" value="<{$MemClassOrgan|default:''}>" id="MemClassOrgan" class="form-control" placeholder="<{$smarty.const._MD_TCW_MEM_CLASSORGAN}>">
                    </div>
                </div>
            <{/if}>

            <!--自我介紹-->
            <{if 'AboutMem'|in_array:$mem_column}>
                <div class="form-group row mb-3">
                    <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                        <{$smarty.const._MD_TCW_MEM_ABOUTME}>
                    </label>
                    <div class="col-md-9">
                        <textarea name="AboutMem" id="AboutMem" class="form-control" placeholder="<{$smarty.const._MD_TCW_MEM_ABOUTME}>" style="height:300px;"><{$AboutMem|default:''}></textarea>
                    </div>
                </div>
            <{/if}>

            <div class="alert alert-warning">
                <!--帳號-->
                <div class="form-group row mb-3" >
                    <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                        <{$smarty.const._MD_TCW_MEM_UNAME}>
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="MemUname" value="<{$MemUname|default:''}>" title="<{$smarty.const._MD_TCW_MEM_UNAME}>" id="MemUname" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_MEM_UNAME}>">
                    </div>
                </div>

                <!--密碼-->
                <div class="form-group row mb-3">
                    <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                        <{$smarty.const._MD_TCW_MEM_PASSWD}>
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="MemPasswd" value="<{$MemPasswd|default:''}>" title="<{$smarty.const._MD_TCW_MEM_PASSWD}>" id="MemPasswd" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_MEM_PASSWD}>">
                    </div>
                </div>
            </div>

            <div class="text-center">
                <{$del_btn|default:''}>
                <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
                <input type="hidden" name="MemID" value="<{$MemID|default:''}>">
                <input type="hidden" name="CateID" value="<{$cate.CateID}>">
                <input type="hidden" name="op" value="<{$next_op|default:''}>">
                <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-disk" aria-hidden="true"></i>  <{$smarty.const._TAD_SAVE}></button>
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <{if $LoginMemID|default:false}>
            <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/mem_toolbar.tpl"}>
        <{else}>
            <table class="table table-striped table-bordered table-hover table-sm">
                <tr>
                    <{if 'MemNum'|in_array:$mem_column}>
                        <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_NUM}></th>
                    <{/if}>
                    <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_NAME}></th>
                    <{if 'MemUnicode'|in_array:$mem_column}>
                        <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_UNICODE}></th>
                    <{/if}>
                    <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_SEX}></th>
                </tr>
                <{foreach from=$students item=stud}>
                    <tr>
                        <{if 'MemNum'|in_array:$mem_column}>
                            <td style="text-align: center;<{if $stud.MemID==$MemID}>background: yellow;<{/if}>"><a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>&MemID=<{$stud.MemID}>&op=edit_stu"><{$stud.MemNum}></a></td>
                        <{/if}>
                        <td style="text-align: center;<{if $stud.MemID==$MemID}>background: yellow;<{/if}>"><a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>&MemID=<{$stud.MemID}>&op=edit_stu"><{$stud.MemName}></a></td>
                        <{if 'MemUnicode'|in_array:$mem_column}>
                            <td style="text-align: center;<{if $stud.MemID==$MemID}>background: yellow;<{/if}>"><a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>&MemID=<{$stud.MemID}>&op=edit_stu"><{$stud.MemUnicode}></a></td>
                        <{/if}>
                        <td style="text-align: center; color: <{$stud.color}>;<{if $stud.MemID==$MemID}>background: yellow;<{/if}>"><{$stud.MemSex}></td>
                    </tr>
                <{/foreach}>
            </table>

            <div class="text-center">
                <a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>&op=edit_stu" class="btn btn-primary"><{$add_stud|default:''}></a>
            </div>
        <{/if}>
    </div>
</div>

<h2>
    <{if $cate.CateName|default:false}><a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
</h2>

<div style="font-size: 2em; margin: 30px auto;">
    <{if 'MemNum'|in_array:$mem_column}>
        <label class="badge badge-primary bg-primary"><{$class_mem.MemNum}></label>
    <{/if}>
    <{$mem.MemName}>
    <{if 'MemUnicode'|in_array:$mem_column}>
        (<{$mem.MemUnicode}>)
    <{/if}>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-8">
                <!--性別-->
                <div class="row">
                    <label class="col-sm-3"><{$smarty.const._MD_TCW_MEM_SEX}></label>
                    <div class="col-sm-9">
                        <{$mem.MemSex}>
                    </div>
                </div>

                <!--是否還在班上-->
                <div class="row">
                    <label class="col-sm-3"><{$smarty.const._MD_TCW_MEM_STATUS}></label>
                    <div class="col-sm-9">
                        <{$class_mem.MemEnable}>
                    </div>
                </div>

                <!--生日-->
                <{if 'MemBirthday'|in_array:$mem_column}>
                    <div class="row">
                        <label class="col-sm-3"><{$smarty.const._MD_TCW_MEM_BIRTHDAY}></label>
                        <div class="col-sm-9">
                            <{$mem.MemBirthday}>
                        </div>
                    </div>
                <{/if}>

                <!--學生暱稱-->
                <{if 'MemNickName'|in_array:$mem_column}>
                    <div class="row">
                        <label class="col-sm-3"><{$smarty.const._MD_TCW_MEM_NICKNAME}></label>
                        <div class="col-sm-9">
                            <{$mem.MemNickName}>
                        </div>
                    </div>
                <{/if}>

                <!--專長-->
                <{if 'MemExpertises'|in_array:$mem_column}>
                    <div class="row">
                        <label class="col-sm-3"><{$smarty.const._MD_TCW_MEM_EXPERTISES}></label>
                        <div class="col-sm-9">
                            <{$mem.MemExpertises}>
                        </div>
                    </div>
                <{/if}>

                <!--職稱-->
                <{if 'MemClassOrgan'|in_array:$mem_column}>
                    <div class="row">
                        <label class="col-sm-3"><{$smarty.const._MD_TCW_MEM_CLASSORGAN}></label>
                        <div class="col-sm-9">
                            <{$class_mem.MemClassOrgan}>
                        </div>
                    </div>
                <{/if}>
            </div>

            <div class="col-md-4 text-center">
                <img src="<{$pic}>" alt="<{$mem.MemName}>" class="img-fluid rounded">
            </div>
        </div>

        <!--自我介紹-->
        <{if 'AboutMem'|in_array:$mem_column}>
            <div class="row">
                <label class="col-md-2"><{$smarty.const._MD_TCW_MEM_ABOUTME}></label>
                <div class="col-md-10">
                    <{$class_mem.AboutMem}>
                </div>
            </div>
        <{/if}>

        <{if $isMyWeb|default:false}>
            <div class="text-center" style="margin: 30px auto;">
                <a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&MemID=<{$mem.MemID}>&op=edit_stu" class="btn btn-warning"><{$smarty.const._TAD_EDIT}></a>
            </div>
        <{/if}>

    </div>
    <div class="col-md-4">
        <{if $im_student|default:false}>
            <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/mem_toolbar.tpl"}>
        <{elseif $students}>
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
                        <td style="text-align: center;<{if $stud.MemID==$mem.MemID}>background: yellow;<{/if}>"><a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&MemID=<{$stud.MemID}>&op=show_stu"><{$stud.MemNum}></a></td>
                    <{/if}>
                    <td style="text-align: center;<{if $stud.MemID==$mem.MemID}>background: yellow;<{/if}>"><a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&MemID=<{$stud.MemID}>&op=show_stu"><{$stud.MemName}></a></td>
                    <{if 'MemUnicode'|in_array:$mem_column}>
                        <td style="text-align: center;<{if $stud.MemID==$mem.MemID}>background: yellow;<{/if}>"><a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&MemID=<{$stud.MemID}>&op=show_stu"><{$stud.MemUnicode}></a></td>
                    <{/if}>
                    <td style="text-align: center; color: <{$stud.color}>;<{if $stud.MemID==$mem.MemID}>background: yellow;<{/if}>"><{$stud.MemSex}></td>
                    </tr>
                <{/foreach}>
            </table>
        <{/if}>
    </div>
</div>

<{if $stud_works.main_data|default:false}>
    <h2><{$smarty.const._MD_TCW_ABOUTUS_UPLOAD_WORKS}></h2>
    <{foreach from=$stud_works.main_data item=work}>
        <div class="my-border">
            <div class="row">
                <div class="col-md-8">
                    <span style="font-size: 2em;">
                        <a href="works.php?WebID=<{$WebID}>&WorksID=<{$work.WorksID}>" target="_blank"><{$work.WorkName}></a>
                    </span>
                </div>
                <div class="col-md-4">
                    <div class="d-grid gap-2">
                        <{if $work.mem_upload_content.UploadDate!=""}>
                            <a href="works.php?WebID=<{$WebID}>&WorksID=<{$work.WorksID}>" target="_blank"class="btn btn-primary btn-block"><{$work.mem_upload_content.mem_upload_date}></a>
                        <{else}>
                            <a href="works.php?WebID=<{$WebID}>&WorksID=<{$work.WorksID}>" target="_blank" class="btn btn-success btn-block"><{$smarty.const._MD_TCW_ABOUTUS_UPLOAD_NOW}></a>
                        <{/if}>
                    </div>
                </div>
            </div>

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><{$smarty.const._MD_TCW_WORKS_END_DATE}>: <{$work.WorksDate}></li>
            </ol>

            <{if $work.WorkDesc|default:false}>
                <{$work.WorkDesc}>
            <{/if}>
        </div>
    <{/foreach}>
<{/if}>

<{if $stud_scores.main_data|default:false}>
    <h2><{$smarty.const._MD_TCW_ABOUTUS_UPLOADED_WORKS}></h2>
    <table class="table">
        <tr>
            <th><{$smarty.const._MD_TCW_WORKS_NAME}></th>
            <th><{$smarty.const._MD_TCW_WORKS_WORKS_SCORE}></th>
            <th><{$smarty.const._MD_TCW_WORKS_WORKS_JUDGMENT}></th>
        </tr>
        <{foreach from=$stud_scores.main_data item=work}>
            <tr>
                <td><a href="works.php?WebID=<{$WebID}>&WorksID=<{$work.WorksID}>" target="_blank"><{$work.WorkName}></a></td>
                <td><{$work.mem_upload_content.WorkScore}></td>
                <td><{$work.mem_upload_content.WorkJudgment}></td>
            </tr>
        <{/foreach}>
    </table>
<{/if}>
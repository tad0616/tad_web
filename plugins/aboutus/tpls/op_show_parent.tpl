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
    <{$smarty.const._MD_TCW_ABOUTUS_S}><{$parent.Reationship}>
</div>

<form action="aboutus.php?WebID=<{$WebID}>" method="post" enctype="multipart/form-data" role="form" class="form-horizontal">
    <div class="row">
        <div class="col-md-3">
            <img src="<{$pic}>" alt="<{$mem.MemName}><{$smarty.const._MD_TCW_ABOUTUS_S}><{$parent.Reationship}>" class="img-fluid img-rounded">
            <br>
            <input type="file" name="upfile[]"  maxlength="1" accept="gif|jpg|png|GIF|JPG|PNG">
        </div>
        <div class="col-md-6">
            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label text-sm-right text-sm-end control-label">
                    <{$smarty.const._MD_TCW_ABOUTUS_PARENT_EMAIL}>
                </label>
                <div class="col-md-8">
                    <input type="text" name="ParentEmail"  class="form-control" value="<{$parent.ParentEmail}>">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label text-sm-right text-sm-end control-label">
                    <{$smarty.const._MD_TCW_ABOUTUS_YOUR_ARE}><{$mem.MemName}><{$smarty.const._MD_TCW_ABOUTUS_S}>
                </label>
                <div class="col-md-8">
                    <input type="text" name="Reationship"  class="form-control" value="<{$parent.Reationship}>">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label text-sm-right text-sm-end control-label">
                    <{$smarty.const._MD_TCW_ABOUTUS_PARENT_MODIFY_PASSWD}>
                </label>
                <div class="col-md-8">
                    <input type="text" name="ParentPasswd"  class="form-control" title="<{$smarty.const._MD_TCW_ABOUTUS_PARENT_MODIFY_PASSWD_DESC}>" placeholder="<{$smarty.const._MD_TCW_ABOUTUS_PARENT_MODIFY_PASSWD_DESC}>">
                </div>
            </div>

            <div class="text-center">
                <input type="hidden" name="ParentID" value="<{$ParentID}>">
                <input type="hidden" name="op" value="save_parent">
                <input type="hidden" name="WebID" value="<{$WebID}>">
                <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
            </div>
        </div>
        <div class="col-md-3">
            <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/parent_toolbar.tpl"}>
        </div>
    </div>
</form>

<{if $stud_scores.main_data|default:false}>
    <h2><{$mem.MemName}><{$smarty.const._MD_TCW_ABOUTUS_UPLOADED_WORKS}></h2>
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
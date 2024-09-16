<h2><{$WorkName}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="works.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_WORKS}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item">
            <{if $cate.CateName|default:false}><a href="works.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
        </li>
    <{/if}>
    <li class="breadcrumb-item"><{$WorksInfo}></li>
    <{if $tags|default:false}><li class="breadcrumb-item"><{$tags}></li><{/if}>
    <{if $hide|default:false}><li class="breadcrumb-item"><span class="badge badge-danger bg-danger"><{$hide}></span></li><{/if}>
</ol>

<{if $WorkDesc|default:false}>
    <div class="my-border"><{$WorkDesc}></div>
<{/if}>

<{$pics}>

<{if $show_mem_upload_form|default:false}>
    <form action="works.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
        <!--上傳檔案-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_WORKS_ADD}>
            </label>
            <div class="col-md-10">
                <{$upform}>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_WORKS_DESCRIPTION}>
            </label>
            <div class="col-md-10">
                <textarea name="WorkDesc" id="WorkDesc" cols="30" rows="3" class="form-control"></textarea>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_WORKS_UPLOADED_WORKS}>
            </label>
            <div class="col-md-10">
                <{$mem_upload_content.list_del_file}>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_WORKS_AUTHOR}>
            </label>
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$LoginMemName}>
            </label>
        </div>

        <div class="text-center">
            <!--作品編號-->
            <input type="hidden" name="LoginMemName" value="<{$LoginMemName}>">
            <input type="hidden" name="LoginMemID" value="<{$LoginMemID}>">
            <input type="hidden" name="WorksID" value="<{$WorksID}>">
            <input type="hidden" name="WebID" value="<{$WebID}>">
            <input type="hidden" name="op" value="mem_upload">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
<{/if}>


<{if $isMyWeb|default:false}>
    <div class="text-right text-end" style="margin: 30px 0px;">
        <a href="javascript:delete_works_func(<{$WorksID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_WORKS_SHORT}></a>
        <a href="works.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_WORKS_SHORT}></a>
        <a href="works.php?WebID=<{$WebID}>&op=edit_form&WorksID=<{$WorksID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_WORKS_SHORT}></a>
        <{if $WorksKind!==''}>
            <a href="works.php?WebID=<{$WebID}>&op=score_form&WorksID=<{$WorksID}>" class="btn btn-primary"><i class="fa fa-star-half-o"></i> <{$smarty.const._MD_TCW_WORKS_WORKS_SCORE_FORM}></a>
        <{/if}>
    </div>
<{/if}>

<h2><a href="works.php?WebID=<{$WebID|default:''}>&WorksID=<{$WorksID|default:''}>"><{$WorkName|default:''}></a> <{$smarty.const._MD_TCW_WORKS_WORKS_SCORE_FORM}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="works.php?WebID=<{$WebID|default:''}>"><{$smarty.const._MD_TCW_WORKS}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item">
            <{if $cate.CateName|default:false}><a href="works.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
        </li>
        <{/if}>
    <li class="breadcrumb-item"><{$WorksInfo|default:''}></li>
    <{if $hide|default:false}>
        <li class="breadcrumb-item"><span class="badge badge-danger bg-danger"><{$hide|default:''}></span></li>
    <{/if}>
</ol>

<form action="works.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
    <{foreach from=$all_upload_content item=work}>
        <div class="row">
            <div class="col-md-6">
                <div style="font-size: 2em;"><{$work.MemName}> @ <{$work.UploadDate}></div>
            </div>
            <div class="col-md-6">
                <div class="form-group row mb-3">
                    <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
                        <{$work.MemName}><{$smarty.const._MD_TCW_WORKS_WORKS_SCORE}>
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="WorkScore[<{$work.MemID}>]" class="form-control" value="<{$work.WorkScore}>" placeholder="<{$smarty.const._MD_TCW_WORKS_WORKS_SCORE}>">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <{if $work.WorkDesc|default:false}><div class="alert alert-info"><{$work.WorkDesc}></div><{/if}>
                <{$work.list_del_file}>
            </div>
            <div class="col-md-6">
                <textarea type="text" name="WorkJudgment[<{$work.MemID}>]" class="form-control" placeholder="<{$smarty.const._MD_TCW_WORKS_WORKS_SCORE}>" style="height:150px;"><{$work.WorkJudgment}></textarea>
            </div>
        </div>
        <hr>
    <{/foreach}>

    <div class="text-center">
        <!--作品編號-->
        <input type="hidden" name="WorksID" value="<{$WorksID|default:''}>">
        <!--所屬團隊-->
        <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
        <input type="hidden" name="op" value="save_score">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
    </div>
</form>
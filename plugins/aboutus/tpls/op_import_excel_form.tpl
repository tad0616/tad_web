<h3>
    <{if $cate.CateName|default:false}><a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
    <{$edit_student|default:''}></h3>
<div class="my-border">
    <form action="aboutus.php?WebID=<{$WebID|default:''}>" method="post" enctype="multipart/form-data">
        <div class="row">
            <label class="col-md-3"><{$import_excel|default:''}></label>
            <div class="col-md-5">
                <input type="file" name="importfile">
            </div>
            <input type="hidden" name="op" value="import_excel">
            <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
            <input type="hidden" name="CateID" value="<{$cate.CateID}>">
            <button type="submit" class="btn btn-primary"><{$smarty.const._MD_TCW_IMPORT}></button>
        </div>
    </form>
</div>
<div class="alert alert-info">
    <{$smarty.const._MD_TCW_IMPORT_LINK}>
</div>
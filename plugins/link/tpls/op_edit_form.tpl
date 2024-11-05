<script type="text/javascript">
    $(document).ready(function(){
        $('#LinkUrl').change(function() {
        $('#LinkTitle').val($('#LinkUrl').val());
        $.post("link_ajax.php", { url: $('#LinkUrl').val()},
            function(data) {
                var obj = $.parseJSON(data);
                $('#LinkTitle').val(obj.title);
                $('#LinkDesc').val(obj.description);
            });
        });


        $('#LinkGet').click(function() {
        $.post("link_ajax.php", { url: $('#LinkUrl').val()},
            function(data) {
                var obj = $.parseJSON(data);
                $('#LinkTitle').val(obj.title);
                $('#LinkDesc').val(obj.description);
            });
        });
    });
</script>

<h2><{$smarty.const._MD_TCW_LINK}></h2>
<div class="my-border">
    <form action="link.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">

        <!--分類-->
        <{$cate_menu_form|default:''}>

        <!--網站連結-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_LINKURL}>
            </label>
            <div class="col-md-8">
                <input type="text" name="LinkUrl" value="<{$LinkUrl|default:''}>" id="LinkUrl" class="form-control validate[required]" placeholder="<{$smarty.const._MD_TCW_LINKURL}>">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-success" id="LinkGet"><{$smarty.const._MD_TCW_LINK_AUTO_GET}></button>
            </div>
        </div>

        <!--網站名稱-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_LINKTITLE}>
            </label>
            <div class="col-md-10">
                <input type="text" name="LinkTitle" value="<{$LinkTitle|default:''}>" id="LinkTitle" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_LINKTITLE}>">
            </div>
        </div>

        <!--說明-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_LINKDESC}>
            </label>
            <div class="col-md-10">
                <textarea name="LinkDesc" class="form-control" rows=3 id="LinkDesc" placehold="<{$smarty.const._MD_TCW_LINKDESC}>"><{$LinkDesc|default:''}></textarea>
            </div>
        </div>

        <{$tags_form|default:''}>

        <div class="text-center">
            <!--排序-->
            <input type="hidden" name="LinkSort" value="<{$LinkSort|default:''}>">
            <!--所屬團隊-->
            <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
            <!--編號-->
            <input type="hidden" name="LinkID" value="<{$LinkID|default:''}>">
            <input type="hidden" name="op" value="<{$next_op|default:''}>">
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>  <{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
</div>
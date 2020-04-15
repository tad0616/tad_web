<script type="text/javascript">
    $(document).ready(function(){
        $('#Youtube').change(function() {
        $('#VideoName').val($('#Youtube').val());
        $.post("link_ajax.php", { url: $('#Youtube').val()},
            function(data) {
            var obj = $.parseJSON(data);
            $('#VideoName').val(obj.title);
            $('#VideoDesc').val(obj.description);
            });
        });


        $('#LinkGet').click(function() {
        $.post("link_ajax.php", { url: $('#Youtube').val()},
            function(data) {
            var obj = $.parseJSON(data);
            $('#VideoName').val(obj.title);
            $('#VideoDesc').val(obj.description);
            });
        });
    });
</script>


<h2><{$smarty.const._MD_TCW_VIDEO_ADD}></h2>
<div class="my-border">
    <form action="video.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
        <!--分類-->
        <{$cate_menu_form}>

        <!--影片網址-->
        <div class="form-group row">
            <div class="col-md-10">
                <input type="text" name="Youtube" value="<{$Youtube}>" id="Youtube" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_VIDEOYOUTUBE}>">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn" id="LinkGet"><{$smarty.const._MD_TCW_LINK_AUTO_GET}></button>
            </div>
        </div>


        <!--影片名稱-->
        <input type="text" name="VideoName" value="<{$VideoName}>" id="VideoName" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_VIDEONAME}>">


        <!--影片說明-->
        <textarea name="VideoDesc" rows=4 id="VideoDesc"  class="form-control" placeholder="<{$smarty.const._MD_TCW_VIDEODESC}>"><{$VideoDesc}></textarea>

        <{$tags_form}>

        <div class="text-center">
            <!--影片編號-->
            <input type="hidden" name="VideoID" value="<{$VideoID}>">
            <!--所屬團隊-->
            <input type="hidden" name="WebID" value="<{$WebID}>">
            <input type="hidden" name="op" value="<{$next_op}>">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
</div>
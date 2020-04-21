<{if $LoginMemID=="" and $LoginParentID=="" and !$isMyWeb}>
    <{$smarty.const._MD_TCW_DISCUSS_LOGIN_FIRST}>
<{else}>
    <script type="text/javascript">
        function typeInTextarea(Field, newText) {
            var el=document.getElementById(Field);
            var start = el.selectionStart
            var end = el.selectionEnd
            var text = el.value
            var before = text.substring(0, start)
            var after  = text.substring(end, text.length)
            el.value = (before + newText + after)
            el.selectionStart = el.selectionEnd = start + newText.length
            el.focus()
        }
    </script>

    <h2><{$smarty.const._MD_TCW_DISCUSS_ADD}></h2>
    <div class="my-border">
        <form action="discuss.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">

            <!--分類-->
            <{$cate_menu_form}>

            <!--標題-->
            <div class="form-group row">
                <div class="col-sm-12">
                    <input type="text" name="DiscussTitle" value="<{$DiscussTitle}>" id="DiscussTitle" class="form-control validate[required]" placeholder="<{$smarty.const._MD_TCW_DISCUSSTITLE}>">
                </div>
            </div>

            <!--內容-->
            <div class="form-group row">
                <div class="col-sm-12">
                    <textarea name="DiscussContent" class="form-control" rows=15 id="DiscussContent" placehold="<{$smarty.const._MD_TCW_DISCUSSCONTENT}>"><{$DiscussContent}></textarea>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <{foreach from=$smile_pics item=file}>
                        <img src="<{$xoops_url}>/modules/tad_web/plugins/discuss/smiles/<{$file}>" alt="<{$file}>" onClick="typeInTextarea('DiscussContent','[<{$file}>]');" style="margin:1px;">
                    <{/foreach}>
                </div>
            </div>

            <{$tags_form}>

            <!--相關附件-->
            <div class="form-group row">
                <label class="col-md-2 col-form-label text-sm-right control-label">
                    <{$smarty.const._MD_TCW_DISCUSS_FILES}>
                </label>
                <div class="col-md-10">
                    <{$upform}>
                </div>
            </div>

            <div class="text-center">
                <input type="hidden" name="WebID" value="<{$WebID}>">
                <input type="hidden" name="DiscussID" value="<{$DiscussID}>">
                <input type="hidden" name="ReDiscussID" value="<{$ReDiscussID}>">
                <input type="hidden" name="LoginWebID" value="<{$LoginWebID}>">
                <input type="hidden" name="op" value="<{$next_op}>">
                <button type="submit" class="btn btn-primary"><{$smarty.const._MD_TCW_DISCUSS_SUBMIT}></button>
            </div>
        </form>
    </div>
<{/if}>
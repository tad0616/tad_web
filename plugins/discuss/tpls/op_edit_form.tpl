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
            <{$cate_menu_form|default:''}>

            <!--標題-->
            <div class="form-group row mb-3">
                <div class="col-sm-12">
                    <input type="text" name="DiscussTitle" value="<{$DiscussTitle|default:''}>" id="DiscussTitle" class="form-control validate[required]" placeholder="<{$smarty.const._MD_TCW_DISCUSSTITLE}>">
                </div>
            </div>

            <!--內容-->
            <div class="form-group row mb-3">
                <div class="col-sm-12">
                    <textarea name="DiscussContent" class="form-control" rows=15 id="DiscussContent" placehold="<{$smarty.const._MD_TCW_DISCUSSCONTENT}>"><{$DiscussContent|default:''}></textarea>
                </div>
            </div>

            <div class="form-group row mb-3">
                <div class="col-sm-12">
                    <{foreach from=$smile_pics item=file}>
                        <img src="<{$xoops_url}>/modules/tad_web/plugins/discuss/smiles/<{$file|default:''}>" alt="<{$file|default:''}>" onClick="typeInTextarea('DiscussContent','[<{$file|default:''}>]');" style="margin:1px;">
                    <{/foreach}>
                </div>
            </div>

            <{$tags_form|default:''}>

            <!--相關附件-->
            <div class="form-group row mb-3">
                <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                    <{$smarty.const._MD_TCW_DISCUSS_FILES}>
                </label>
                <div class="col-md-10">
                    <{$upform|default:''}>
                </div>
            </div>

            <div class="text-center">
                <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
                <input type="hidden" name="DiscussID" value="<{$DiscussID|default:''}>">
                <input type="hidden" name="ReDiscussID" value="<{$ReDiscussID|default:''}>">
                <input type="hidden" name="LoginWebID" value="<{$LoginWebID|default:''}>">
                <input type="hidden" name="op" value="<{$next_op|default:''}>">
                <button type="submit" class="btn btn-primary"><{$smarty.const._MD_TCW_DISCUSS_SUBMIT}></button>
            </div>
        </form>
    </div>
<{/if}>
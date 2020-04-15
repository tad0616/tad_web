<link href="bubble.css" rel="stylesheet" type="text/css">

<{if $isMyWeb}>
    <{$sweet_delete_discuss_func_code}>
<{/if}>

<h2><{$DiscussTitle}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="discuss.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_DISCUSS}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item"><a href="discuss.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a></li>
    <{/if}>
    <li class="breadcrumb-item"><{$DiscussInfo}></li>
    <{if $tags}>
        <li class="breadcrumb-item"><{$tags}></li>
    <{/if}>
</ol>

<{if $DiscussContent}>

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

    <div class="row">
        <div class="col-md-3 col-lg-2  text-center">
            <img src="<{$pic}>" alt="<{$MemName}>" style="max-width: 100%;" class="rounded img-polaroid">
            <div style="line-height:1.5em;">
                <div><{$MemName}></div>
                <div style="font-size: 62.5%; background: #1d649b; color: #fff; border-radius: 3px;"><{$DiscussDate}></div>
            </div>

        </div>
        <div class="col-md-9 col-lg-10">
            <{$DiscussContent}>
            <div style="float: right;">
                <{if $isMineDiscuss}>
                    <a href="javascript:delete_discuss_func(<{$DiscussID}>);" class="btn btn-sm btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                    <a href="discuss.php?WebID=<{$WebID}>&op=edit_form&DiscussID=<{$DiscussID}>" class="btn btn-sm btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
                <{/if}>
            </div>
        </div>
    </div>
<{/if}>

<{$re}>

<{if $LoginMemID or $LoginParentID or $isMineDiscuss}>
    <div style="clear: both;"></div>
    <h3><{$smarty.const._MD_TCW_DISCUSS_REPLY}></h3>
    <form action="discuss.php" method="post" id="myForm" enctype="multipart/form-data" role="form" style="margin-top:16px;" class="form-horizontal">
        <textarea name="DiscussContent" class="form-control" rows=8 id="DiscussContent" class="validate[required , length[10,9999]]"></textarea>

        <{foreach from=$smile_pics item=file}>
            <img src="<{$xoops_url}>/modules/tad_web/plugins/discuss/smiles/<{$file}>" alt="<{$file}>" onClick="typeInTextarea('DiscussContent','[<{$file}>]');" style="margin:1px;">
        <{/foreach}>

        <!--相關附件-->
        <div class="form-group row">
            <label class="col-md-2 col-form-label text-sm-right control-label">
                <{$smarty.const._MD_TCW_DISCUSS_FILES}>
            </label>
            <div class="col-md-8">
                <{$upform}>
            </div>
            <div class="col-md-2">
                <input type="hidden" name="WebID" value="<{$WebID}>">

                <!--回覆編號-->
                <input type="hidden" name="ReDiscussID" value="<{$DiscussID}>">
                <input type="hidden" name="DiscussTitle" value="Re:<{$DiscussTitle}>" id="DiscussTitle">
                <input type="hidden" name="op" value="insert">
                <button type="submit" class="btn btn-primary"><{$smarty.const._MD_TCW_DISCUSS_TO_REPLY}></button>
            </div>
        </div>
    </form>
<{/if}>
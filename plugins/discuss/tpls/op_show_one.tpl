<h2><{$DiscussTitle|default:''}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="discuss.php?WebID=<{$WebID|default:''}>"><{$smarty.const._MD_TCW_DISCUSS}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item">
            <{if $cate.CateName|default:false}><a href="discuss.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
        </li>
    <{/if}>
    <li class="breadcrumb-item"><{$DiscussInfo|default:''}></li>
    <{if $tags|default:false}>
        <li class="breadcrumb-item"><{$tags|default:''}></li>
    <{/if}>
</ol>

<{if $DiscussContent|default:false}>

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
            <img src="<{$pic|default:''}>" alt="<{$MemName|default:''}>" style="max-width: 100%;" class="rounded img-polaroid">
            <div style="line-height:1.5em;">
                <div><{$MemName|default:''}></div>
                <div style="font-size: 62.5%; background: #1d649b; color: #fff; border-radius: 3px;"><{$DiscussDate|default:''}></div>
            </div>

        </div>
        <div class="col-md-9 col-lg-10">
            <{$DiscussContent|default:''}>
            <div style="float: right;">
                <{if ($WebID && $isMyWeb) || $smarty.session.tad_web_adm|default:false || ($smarty.session.LoginMemID && $MemID == $smarty.session.LoginMemID) || ($smarty.session.LoginParentID && $ParentID == $smarty.session.LoginParentID)}>
                    <a href="javascript:delete_discuss_func(<{$DiscussID|default:''}>);" class="btn btn-sm btn-xs btn-danger"><i class="fa fa-trash" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}></a>
                    <a href="discuss.php?WebID=<{$WebID|default:''}>&op=edit_form&DiscussID=<{$DiscussID|default:''}>" class="btn btn-sm btn-xs btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i>  <{$smarty.const._TAD_EDIT}></a>
                <{/if}>
            </div>
        </div>
    </div>
<{/if}>

<{$re|default:''}>

<{if $LoginMemID or $LoginParentID or $isMineDiscuss}>
    <div style="clear: both;"></div>
    <h3><{$smarty.const._MD_TCW_DISCUSS_REPLY}></h3>
    <form action="discuss.php" method="post" id="myForm" enctype="multipart/form-data" role="form" style="margin-top:16px;" class="form-horizontal">
        <textarea name="DiscussContent" class="form-control" rows=8 id="DiscussContent" class="validate[required , length[10,9999]]"></textarea>

        <{foreach from=$smile_pics item=file}>
            <img src="<{$xoops_url}>/modules/tad_web/plugins/discuss/smiles/<{$file|default:''}>" alt="<{$file|default:''}>" onClick="typeInTextarea('DiscussContent','[<{$file|default:''}>]');" style="margin:1px;">
        <{/foreach}>

        <!--相關附件-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_DISCUSS_FILES}>
            </label>
            <div class="col-md-8">
                <{$upform|default:''}>
            </div>
            <div class="col-md-2">
                <input type="hidden" name="WebID" value="<{$WebID|default:''}>">

                <!--回覆編號-->
                <input type="hidden" name="ReDiscussID" value="<{$DiscussID|default:''}>">
                <input type="hidden" name="DiscussTitle" value="Re:<{$DiscussTitle|default:''}>" id="DiscussTitle">
                <input type="hidden" name="op" value="insert">
                <button type="submit" class="btn btn-primary"><{$smarty.const._MD_TCW_DISCUSS_TO_REPLY}></button>
            </div>
        </div>
    </form>
<{/if}>
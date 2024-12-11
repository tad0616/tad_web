<h2><{$VideoName|default:''}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="video.php?WebID=<{$WebID|default:''}>"><{$smarty.const._MD_TCW_VIDEO}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item">
            <{if $cate.CateName|default:false}><a href="video.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
        </li>
    <{/if}>
    <li class="breadcrumb-item"><{$VideoInfo|default:''}></li>
    <{if $tags|default:false}>
        <li class="breadcrumb-item"><{$tags|default:''}></li>
    <{/if}>
</ol>

<div class="embed-responsive embed-responsive-16by9 ratio ratio-4x3"><iframe title="show_one_video" class="embed-responsive-item" src="https://www.youtube.com/embed/<{$VideoPlace|default:''}>?feature=oembed" frameborder="0" allowfullscreen></iframe></div>

<div style="line-height: 1.8; margin: 10px auto;">
    <{$VideoDesc|default:''}>
</div>



<{if $isMyWeb or $isAssistant}>
    <div class="text-right text-end" style="margin: 30px 0px;">
        <a href="javascript:delete_video_func(<{$VideoID|default:''}>);" class="btn btn-danger"><i class="fa fa-trash"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_VIDEO_SHORT}></a>
        <a href="video.php?WebID=<{$WebID|default:''}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_VIDEO_SHORT}></a>
        <a href="video.php?WebID=<{$WebID|default:''}>&op=edit_form&VideoID=<{$VideoID|default:''}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_VIDEO_SHORT}></a>
    </div>
<{/if}>
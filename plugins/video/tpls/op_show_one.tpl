<h2><{$VideoName}></h2>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="video.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_VIDEO}></a></li>
    <{if isset($cate.CateID)}>
        <li class="breadcrumb-item">
            <{if $cate.CateName}><a href="video.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
        </li>
    <{/if}>
    <li class="breadcrumb-item"><{$VideoInfo}></li>
    <{if $tags}>
        <li class="breadcrumb-item"><{$tags}></li>
    <{/if}>
</ol>

<div class="embed-responsive embed-responsive-16by9 ratio ratio-4x3"><iframe title="show_one_video" class="embed-responsive-item" src="https://www.youtube.com/embed/<{$VideoPlace}>?feature=oembed" frameborder="0" allowfullscreen></iframe></div>

<div style="line-height: 1.8; margin: 10px auto;">
    <{$VideoDesc}>
</div>



<{if $isMyWeb or $isAssistant}>
    <div class="text-right text-end" style="margin: 30px 0px;">
        <a href="javascript:delete_video_func(<{$VideoID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_VIDEO_SHORT}></a>
        <a href="video.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_VIDEO_SHORT}></a>
        <a href="video.php?WebID=<{$WebID}>&op=edit_form&VideoID=<{$VideoID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_VIDEO_SHORT}></a>
    </div>
<{/if}>
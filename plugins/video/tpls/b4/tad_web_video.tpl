<{if $op=="edit_form"}>
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
  <div class="card card-body bg-light m-1">
    <form action="video.php" method="post" id="myForm" enctype="multipart/form-data" role="form">
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
      <div class="form-group row">
        <div class="col-md-12">
          <input type="text" name="VideoName" value="<{$VideoName}>" id="VideoName" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_VIDEONAME}>">
        </div>
      </div>


      <!--影片說明-->
      <div class="form-group row">
        <div class="col-md-12">
          <textarea name="VideoDesc" rows=4 id="VideoDesc"  class="form-control" placeholder="<{$smarty.const._MD_TCW_VIDEODESC}>"><{$VideoDesc}></textarea>
        </div>
      </div>
      <{$tags_form}>

      <div class="form-group row">
        <div class="col-md-12 text-center">
          <!--影片編號-->
          <input type="hidden" name="VideoID" value="<{$VideoID}>">

          <!--所屬團隊-->
          <input type="hidden" name="WebID" value="<{$WebID}>">
          <input type="hidden" name="op" value="<{$next_op}>">
          <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
      </div>
    </form>
  </div>
<{elseif $op=="show_one"}>

  <{if $isMyWeb}>
    <{$sweet_delete_video_func_code}>
  <{/if}>

  <h2><{$VideoName}></h2>

  <ol class="breadcrumb">
    <li><a href="video.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_VIDEO}></a></li>
    <{if isset($cate.CateID)}><li><a href="video.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a></li><{/if}>
    <li><{$VideoInfo}></li>
    <{if $tags}><li><{$tags}></li><{/if}>
  </ol>

  <div class="embed-responsive embed-responsive-4by3"><iframe title="show_one_video" class="embed-responsive-item" src="https://www.youtube.com/embed/<{$VideoPlace}>?feature=oembed" frameborder="0" allowfullscreen></iframe></div>

  <div style="line-height: 1.8; margin: 10px auto;">
    <{$VideoDesc}>
  </div>

  <{$fb_comments}>


  <{if $isMyWeb or $isAssistant}>
    <div class="text-right" style="margin: 30px 0px;">
    <a href="javascript:delete_video_func(<{$VideoID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_VIDEO_SHORT}></a>
    <a href="video.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_VIDEO_SHORT}></a>
    <a href="video.php?WebID=<{$WebID}>&op=edit_form&VideoID=<{$VideoID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_VIDEO_SHORT}></a>
    </div>
  <{/if}>
<{elseif $op=="list_all"}>
  <{if $WebID}>
    <div class="row">
      <div class="col-md-8">
        <{$cate_menu}>
      </div>
      <div class="col-md-4 text-right">
        <{if $isMyWeb and $WebID}>
          <a href="cate.php?WebID=<{$WebID}>&ColName=video&table=tad_web_video" class="btn btn-warning <{if $web_display_mode=='index'}>btn-sm<{/if}>"><i class="fa fa-folder-open"></i> <{$smarty.const._MD_TCW_CATE_TOOLS}></a>
          <a href="video.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_VIDEO_SHORT}></a>
        <{/if}>
      </div>
    </div>
  <{/if}>
  <{if $video_data}>

    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/video/tpls/b4/tad_web_common_video.tpl"}>
  <{else}>
    <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$video.PluginTitle}></h2>
    <div class="alert alert-info"><{$smarty.const._MD_TCW_EMPTY}></div>
  <{/if}>
<{elseif $op=="setup"}>

  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
    <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$video.PluginTitle}></h2>
    <{if $isMyWeb or $isAssistant}>
      <a href="video.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_VIDEO_SHORT}></a>
    <{else}>
      <div class="text-center">
        <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
      </div>
    <{/if}>
<{/if}>
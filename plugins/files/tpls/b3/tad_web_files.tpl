<{if $op=="edit_form"}>
  <script type="text/javascript">
    $(document).ready(function(){
      <{if $fsn}>
        <{if $file_link==""}>
          $("#link_file").hide();
          $("#upload_file").show();
        <{else}>
          $("#link_file").show();
          $("#upload_file").hide();
        <{/if}>
      <{else}>
        $("#file_method").change(function(event) {
          var up_method=$("#file_method").val();
          if(up_method=="link_file"){
            $("#link_file").show();
            $("#upload_file").hide();
          }else{
            $("#link_file").hide();
            $("#upload_file").show();
          }
        });
      <{/if}>
    });
  </script>
  <h2><{$smarty.const._MD_TCW_FILES_ADD}></h2>
  <div class="my-border">
    <form action="files.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">

      <!--分類-->
      <{$cate_menu_form}>

      <{if $fsn==""}>
        <div class="form-group">
          <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TCW_FILES_METHOD}>
          </label>
          <div class="col-sm-4">
            <select name="file_method" id="file_method" class="form-control">
              <option value="link_file"><{$smarty.const._MD_TCW_FILES_LINK}></option>
              <option value="upload_file"><{$smarty.const._MD_TCW_FILES_UPLOAD}></option>
            </select>
          </div>
        </div>
      <{else}>
        <{if $file_link==""}>
          <input type="hidden" name="file_method" value="upload_file">
        <{else}>
          <input type="hidden" name="file_method" value="link_file">
        <{/if}>

      <{/if}>

      <div id="link_file">
        <div class="form-group">
          <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TCW_FILES_LINK}>
          </label>
          <div class="col-sm-10">
            <input type="text" name="file_link" class="form-control validate[required , custom[url]]" value="<{$file_link}>" placeholder="<{$smarty.const._MD_TCW_FILES_LINK_DESC}>">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TCW_FILES_DESC}>
          </label>
          <div class="col-sm-10">
            <input type="text" name="file_description" class="form-control validate[required]" value="<{$file_description}>" placeholder="<{$smarty.const._MD_TCW_FILES_DESC}>">
          </div>
        </div>
      </div>

      <{$tags_form}>

      <div id="upload_file" style="display: none;">
        <div class="form-group">
          <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TCW_FILES_UPLOAD}>
          </label>
          <div class="col-sm-8">
            <{$upform}>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-12">
            <{$list_del_file}>
          </div>
        </div>
      </div>

      <div class="text-center">
        <input type="hidden" name="WebID" value="<{$WebID}>">
        <!--檔案流水號-->
        <input type="hidden" name="fsn" value="<{$fsn}>">
        <input type="hidden" name="op" value="<{$next_op}>">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
      </div>
    </form>
  </div>
<{elseif $op=="list_all"}>
  <{if $WebID}>
    <div class="row">
      <div class="col-sm-8">
        <{$cate_menu}>
      </div>
      <div class="col-sm-4 text-right">
        <{if $isMyWeb and $WebID}>
          <a href="cate.php?WebID=<{$WebID}>&ColName=files&table=tad_web_files" class="btn btn-warning <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-folder-open"></i> <{$smarty.const._MD_TCW_CATE_TOOLS}></a>
          <a href="files.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_FILES_SHORT}></a>
        <{/if}>
      </div>
    </div>
  <{/if}>
  <{if $file_data}>

    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/files/tpls/b3/tad_web_common_files.tpl"}>
  <{else}>
    <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$files.PluginTitle}></h2>
    <div class="alert alert-info"><{$smarty.const._MD_TCW_EMPTY}></div>
  <{/if}>
<{elseif $op=="setup"}>
  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
    <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$files.PluginTitle}></h2>
    <{if $isMyWeb or $isAssistant}>
      <a href="files.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_FILES_SHORT}></a>
    <{else}>
      <div class="text-center">
        <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
      </div>
    <{/if}>
<{/if}>

<{if $op=="edit_form"}>

  <{$formValidator_code}>


  <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>

  <h2><{$smarty.const._MD_TCW_PAGE_ADD}></h2>
  <div class="well">
    <form page="page.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">

      <!--分類-->
      <{$cate_menu_form}>

      <!--頁面名稱-->
      <div class="form-group">
        <div class="col-sm-12">
          <input type="text" name="PageTitle" value="<{$PageTitle}>" id="PageTitle" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_PAGETITLE}>">
        </div>
      </div>


      <!--頁面說明-->
      <div class="form-group">
        <div class="col-sm-12">
          <{$PageContent_editor}>
        </div>
      </div>

      <{$tags_form}>

      <!--樣式設定-->
      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_PAGECSS}>
        </label>
        <div class="col-sm-10">
          <input type="text" name="PageCSS" value="<{$PageCSS}>" id="PageCSS" class="form-control" placeholder="">
          <span class="help-block"><{$smarty.const._MD_TCW_PAGECSS_DESC}></span>
        </div>
      </div>


      <!--上傳圖檔-->
      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_PAGE_UPLOAD}>
        </label>
        <div class="col-sm-10">
          <{$upform}>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-12 text-center">

          <!--頁面編號-->
          <input type="hidden" name="PageID" value="<{$PageID}>">
          <!--所屬團隊-->
          <input type="hidden" name="WebID" value="<{$WebID}>">
          <input type="hidden" name="uid" value="<{$uid}>">
          <input type="hidden" name="op" value="<{$next_op}>">
          <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
      </div>
    </form>
  </div>
<{elseif $op=="show_one"}>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#list_page img').css('width','').css('height','').addClass('img-responsive');
    });
  </script>

  <h2><{$PageTitle}></h2>

  <ol class="breadcrumb">
    <li><a href="page.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_PAGE}></a></li>
    <{if isset($cate.CateID)}><li><a href="page.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a></li><{/if}>
    <li><{$PageInfo}></li>
    <{if $tags}><li><{$tags}></li><{/if}>
  </ol>

  <{if $PageContent}>
    <div id="list_page" style="min-height: 100px; overflow: auto; border-radius: 5px; margin:10px auto; padding: 20px;<{if $PageCSS}><{$PageCSS}><{else}>line-height: 2; font-size:120%; background: #FFFFFF;<{/if}>"><{$PageContent}></div>
  <{/if}>

  <div class="row">
    <{$files}>
  </div>

  <div class="row" id="page_tool">
    <div class="col-sm-6 text-left"><a href="page.php?WebID=<{$WebID}>&PageID=<{$prev_next.prev.PageID}>" class="btn btn-default btn-block"><i class="fa fa-chevron-left"></i> <{$prev_next.prev.PageTitle}></a></div>
    <div class="col-sm-6 text-right"><a href="page.php?WebID=<{$WebID}>&PageID=<{$prev_next.next.PageID}>" class="btn btn-default btn-block"><{$prev_next.next.PageTitle}> <i class="fa fa-chevron-right"></i></a></div>
  </div>

  <{$fb_comments}>

  <div id="adm_bar" class="text-right" style="margin: 30px 0px;">
    <{if $isMyWeb or $isCanEdit}>
      <a href="javascript:delete_page_func(<{$PageID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_PAGE_SHORT}></a>
      <a href="page.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_PAGE_SHORT}></a>
      <a href="page.php?WebID=<{$WebID}>&op=edit_form&PageID=<{$PageID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_PAGE_SHORT}></a>
    <{/if}>
    <a class="btn btn-success print-preview"><i class="fa fa-print"></i> <{$smarty.const._MD_TCW_PRINT}></a>
  </div>

<{elseif $page_data}>
  <div class="row">
    <div class="col-sm-12">
      <{includeq file="$xoops_rootpath/modules/tad_web/plugins/page/tpls/tad_web_common_page.tpl"}>
    </div>
  </div>
<{elseif $op=="setup"}>

  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
  <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$page.PluginTitle}></h2>
  <{if $isMyWeb or $isCanEdit}>
    <a href="page.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_PAGE_SHORT}></a>
  <{else}>
    <div class="text-center">
      <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
    </div>
  <{/if}>
<{/if}>
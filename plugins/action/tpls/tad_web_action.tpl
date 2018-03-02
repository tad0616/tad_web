<{if $op=="edit_form"}>

  <{$formValidator_code}>


  <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>

  <h1><{$smarty.const._MD_TCW_ACTION_ADD}></h1>
  <div class="well">
    <form action="action.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">

      <!--分類-->
      <{$cate_menu_form}>

      <!--活動名稱-->
      <div class="form-group">
        <div class="col-sm-12">
          <input type="text" name="ActionName" value="<{$ActionName}>" id="ActionName" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_ACTIONNAME}>">
        </div>
      </div>


      <!--活動說明-->
      <div class="form-group">
        <div class="col-sm-12">
          <textarea name="ActionDesc"  rows=4 id="ActionDesc"  class="form-control" placeholder="<{$smarty.const._MD_TCW_ACTIONDESC}>"><{$ActionDesc}></textarea>
        </div>
      </div>

      <!--活動日期-->
      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_ACTIONDATE}>
        </label>
        <div class="col-sm-4">
          <input type="text" name="ActionDate" class="form-control" value="<{$ActionDate}>" id="ActionDate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d'})">
        </div>
        <!--活動地點-->
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_ACTIONPLACE}>
        </label>
        <div class="col-sm-4">
          <input type="text" name="ActionPlace" class="form-control" value="<{$ActionPlace}>" id="ActionPlace" >
        </div>
      </div>

      <{$power_form}>
      <{$tags_form}>

      <!--上傳圖檔-->
      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_ACTION_UPLOAD}>
        </label>
        <div class="col-sm-10">
          <{$upform}>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-12 text-center">

          <!--活動編號-->
          <input type="hidden" name="ActionID" value="<{$ActionID}>">
          <!--所屬團隊-->
          <input type="hidden" name="WebID" value="<{$WebID}>">
          <input type="hidden" name="op" value="<{$next_op}>">
          <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
      </div>
    </form>
  </div>
<{elseif $op=="show_one"}>
  <h1><{$ActionName}></h1>

  <ol class="breadcrumb">
    <li><a href="action.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_ACTION}></a></li>
    <{if isset($cate.CateID)}><li><a href="action.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a></li><{/if}>
    <li><{$ActionInfo}></li>
    <{if $tags}><li><{$tags}></li><{/if}>
  </ol>

  <div class="row" style="margin:10px 0px;">
    <{if $ActionDate}>
      <div class="col-sm-6"><{$smarty.const._MD_TCW_ACTIONDATE}><{$smarty.const._TAD_FOR}><{$ActionDate}></div>
    <{/if}>

    <{if $ActionPlace}>
      <div class="col-sm-6"><{$smarty.const._MD_TCW_ACTIONPLACE}><{$smarty.const._TAD_FOR}><{$ActionPlace}></div>
    <{/if}>
  </div>


  <div class="row">
    <{$pics}>
  </div>

  <{if $ActionDesc}>
    <hr>
    <div class="row">
      <div class="col-sm-12">
        <div style="line-height: 1.8; font-size: 120%;"><{$ActionDesc}></div>
      </div>
    </div>
    <hr>
  <{/if}>

  <{$fb_comments}>

  <{if $isMyWeb or $isCanEdit}>
    <div class="text-right" style="margin: 30px 0px;">
      <a href="javascript:delete_action_func(<{$ActionID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
      <a href="action.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
      <a href="action.php?WebID=<{$WebID}>&op=edit_form&ActionID=<{$ActionID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
    </div>
  <{/if}>
<{elseif $op=="list_all"}>
  <{if $WebID}>
    <div class="row">
      <div class="col-sm-8">
        <{$cate_menu}>
      </div>
      <div class="col-sm-4 text-right">
        <{if $isMyWeb and $WebID}>
          <a href="cate.php?WebID=<{$WebID}>&ColName=action&table=tad_web_action" class="btn btn-warning <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-folder-open"></i> <{$smarty.const._MD_TCW_CATE_TOOLS}></a>
          <a href="action.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
        <{/if}>
      </div>
    </div>
  <{/if}>
  <{if $action_data}>
    <{$FooTableJS}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/action/tpls/tad_web_common_action.tpl"}>
  <{else}>
    <h1><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$action.PluginTitle}></h1>
    <div class="alert alert-info"><{$smarty.const._MD_TCW_EMPTY}></div>
  <{/if}>
<{elseif $op=="setup"}>

  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
  <h1><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$action.PluginTitle}></h1>
  <{if $isMyWeb or $isCanEdit}>
    <a href="action.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
  <{else}>
    <div class="text-center">
      <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
    </div>
  <{/if}>
<{/if}>
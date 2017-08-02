<{if $op=="edit_form"}>

  <{$formValidator_code}>
  <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
  <h1><{$smarty.const._MD_TCW_NEWS_ADD}></h1>

  <div class="well">
    <form action="news.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">

      <!--分類-->
      <{$cate_menu_form}>

      <!--標題-->
      <div class="form-group">
        <div class="col-sm-12">
          <input name="NewsTitle" id="NewsTitle" class="validate[required] form-control" type="text" value="<{$NewsTitle}>" placeholder="<{$smarty.const._MD_TCW_NEWSTITLE}>">
        </div>
      </div>

      <!--內容-->
      <div class="form-group">
        <div class="col-sm-12">
           <{$NewsContent_editor}>
        </div>
      </div>

      <!--相關連結-->
      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_NEWSURL}>
        </label>
        <div class="col-sm-10">
          <input type="text" name="NewsUrl" value="<{$NewsUrl}>" id="NewsUrl" class="form-control">
        </div>
      </div>


      <div class="form-group">
        <!--發布時間-->
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_DISCUSSDATE}>
        </label>
        <div class="col-sm-4">
          <input type="text" name="NewsDate" value="<{$NewsDate}>" id="NewsDate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm' , startDate:'%y-%M-%d %H:%m}'})" class="form-control">
        </div>

        <!--加到行事曆-->
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_TOCAL}>
        </label>
        <div class="col-sm-4">
          <input type="text" name="toCal" value="<{$toCal}>" id="toCal" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d}'})" class="form-control" placeholder="<{$smarty.const._MD_TCW_NEWS_TO_CAL}>">
        </div>
      </div>

      <{$power_form}>
      <{$tags_form}>

      <!--相關附件-->
      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_NEWS_FILES}>
        </label>
        <div class="col-sm-4">
          <{$upform}>
        </div>
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_NEWS_ENABLE}>
        </label>
        <div class="col-sm-4">
          <label class="radio-inline">
            <input type="radio" name="NewsEnable" value="1" <{if $NewsEnable!='0'}>checked<{/if}>><{$smarty.const._YES}>
          </label>
          <label class="radio-inline">
            <input type="radio" name="NewsEnable" value="0" <{if $NewsEnable=='0'}>checked<{/if}>><{$smarty.const._NO}>
          </label>
        </div>
      </div>


      <div class="text-center">
        <!--編號-->
        <input type="hidden" name="NewsID" value="<{$NewsID}>">
        <input type="hidden" name="WebID" value="<{$WebID}>">
        <input type="hidden" name="op" value="<{$next_op}>">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
      </div>
    </form>
  </div>
<{elseif $op=="show_one"}>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#list_new img').css('width','').css('height','').addClass('img-responsive');
    });
  </script>

  <h1><{$NewsTitle}><{if $NewsEnable!=1}><small>[<{$smarty.const._MD_TCW_NEWS_DRAFT}>]</small><{/if}></h1>

  <ol class="breadcrumb">
    <li><a href="news.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_NEWS}></a></li>
    <{if isset($cate.CateID)}><li><a href="news.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a></li><{/if}>
    <li><{$NewsInfo}></li>
    <{if $tags}><li><{$tags}></li><{/if}>
  </ol>

  <{if $NewsContent}>
    <div id="list_new" style="background-color: #fefefe; line-height: 2; font-size:120%; ">
     <{$NewsContent}>
    </div>
  <{/if}>

  <{if $NewsContent==''}>
    <div class="well" style="font-size:2em;">
     <{$NewsUrlTxt}>
    </div>
  <{else}>
    <{$NewsUrlTxt}>
  <{/if}>

  <{$NewsFiles}>

  <div class="row" id="News_tool">
    <div class="col-sm-6 text-left"><a href="news.php?WebID=<{$WebID}>&NewsID=<{$prev_next.prev.NewsID}>" class="btn btn-default btn-block"><i class="fa fa-chevron-left"></i> <{$prev_next.prev.NewsTitle}></a></div>
    <div class="col-sm-6 text-right"><a href="news.php?WebID=<{$WebID}>&NewsID=<{$prev_next.next.NewsID}>" class="btn btn-default btn-block"><{$prev_next.next.NewsTitle}> <i class="fa fa-chevron-right"></i></a></div>
  </div>

  <{$fb_comments}>

  <div id="adm_bar" class="text-right" style="margin: 30px 0px;">
    <{if $isMyWeb or $isAssistant}>
      <a href="javascript:delete_news_func(<{$NewsID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
      <a href="news.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
      <a href="news.php?WebID=<{$WebID}>&op=edit_form&NewsID=<{$NewsID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
    <{/if}>

    <a class="btn btn-success print-preview"><i class="fa fa-print"></i> <{$smarty.const._MD_TCW_PRINT}></a>
  </div>
<{elseif $op=="list_all"}>
  <{if $WebID}>
    <div class="row">
      <div class="col-sm-8">
        <{$cate_menu}>
      </div>
      <div class="col-sm-4 text-right">
        <{if $isMyWeb and $WebID}>
          <a href="cate.php?WebID=<{$WebID}>&ColName=news&table=tad_web_news" class="btn btn-warning <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-folder-open"></i> <{$smarty.const._MD_TCW_CATE_TOOLS}></a>
          <a href="news.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
        <{/if}>
      </div>
    </div>
  <{/if}>

  <{if $news_data}>
    <{$FooTableJS}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/news/tpls/tad_web_common_news.tpl"}>
  <{else}>
    <h1><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$news.PluginTitle}></h1>
    <div class="alert alert-info"><{$smarty.const._MD_TCW_EMPTY}></div>
  <{/if}>
  <div class="clearfix"></div>
<{elseif $op=="setup"}>

  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
    <h1><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$news.PluginTitle}></h1>
    <{if $isMyWeb or $isAssistant}>
      <a href="news.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_NEWS_SHORT}></a>
    <{else}>
      <div class="text-center">
        <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
      </div>
    <{/if}>
<{/if}>
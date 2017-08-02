<{if $op=="edit_form"}>

  <{$formValidator_code}>
  <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
  <script type="text/javascript">
    function chang_title(){
      var new_date = $('#toCal').val();
      var new_title="<{$WebTitle}> " + new_date + " <{$smarty.const._MD_TCW_HOMEWORK_SHORT}>";
      $('#HomeworkTitle').val(new_title);
    };
  </script>

  <h1><{$smarty.const._MD_TCW_HOMEWORK_ADD}></h1>

  <div class="well">
    <form action="homework.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
      <!--分類-->
      <{$cate_menu_form}>


      <div class="form-group">
        <!--加到行事曆-->
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_HOMEWORK_CAL_DATE}>
        </label>
        <div class="col-sm-4">
          <input type="text" name="toCal" value="<{$toCal}>" id="toCal" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d}', onpicked:function(){chang_title();} })" class="form-control" placeholder="<{$smarty.const._HOMEWORK_TOCAL_DESC}>">
        </div>

        <!--發布時間-->
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_HOMEWORK_POST_DATE}>
        </label>
        <div class="col-sm-4">
          <select name="HomeworkPostDate" id="HomeworkPostDate" class="form-control">
            <option value="<{$HomeworkPostDate}>"><{$smarty.const._MD_TCW_HOMEWORK_POST_NOW}></option>
            <option value="8" <{if $HomeworkPostDate==8}>selected<{/if}>><{$smarty.const._MD_TCW_HOMEWORK_POST_8}></option>
            <option value="12" <{if $HomeworkPostDate==12}>selected<{/if}>><{$smarty.const._MD_TCW_HOMEWORK_POST_12}></option>
            <option value="16" <{if $HomeworkPostDate==16}>selected<{/if}>><{$smarty.const._MD_TCW_HOMEWORK_POST_16}></option>
          </select>
        </div>

      </div>

      <!--標題-->
      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_HOMEWORK_TITLE}>
        </label>
        <div class="col-sm-10">
          <input name="HomeworkTitle" id="HomeworkTitle" class="validate[required] form-control" type="text" value="<{$HomeworkTitle}>" placeholder="<{$smarty.const._MD_TCW_HOMEWORKTITLE}>">
        </div>
      </div>

      <{if $HomeworkContent_editor}>
        <!--內容-->
        <div class="form-group">
          <div class="col-sm-12">
             <{$HomeworkContent_editor}>
          </div>
        </div>
      <{else}>
        <!--今日作業-->
        <div class="form-group">
        <label class="col-sm-3 control-label">
          <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TODAY_WORK}>" src="<{$xoops_url}>/modules/tad_web/images/today_homework.png" class="img-responsive">
        </label>
          <div class="col-sm-9">
             <{$HomeworkContent_editor1}>
          </div>
        </div>

        <!--攜帶物品-->
        <div class="form-group">
        <label class="col-sm-3 control-label">
          <img alt="<{$smarty.const._MD_TCW_HOMEWORK_BRING}>" src="<{$xoops_url}>/modules/tad_web/images/bring.png" class="img-responsive">
        </label>
          <div class="col-sm-9">
             <{$HomeworkContent_editor2}>
          </div>
        </div>

        <!--叮嚀-->
        <div class="form-group">
        <label class="col-sm-3 control-label">
          <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TEACHER_SAY}>" src="<{$xoops_url}>/modules/tad_web/images/teacher_say.png" class="img-responsive">
        </label>
          <div class="col-sm-9">
             <{$HomeworkContent_editor3}>
          </div>
        </div>

        <!--其他-->
        <div class="form-group">
        <label class="col-sm-3 control-label">
          <{$smarty.const._MD_TCW_HOMEWORK_OTHER}>
        </label>
          <div class="col-sm-9">
             <{$HomeworkContent_editor4}>
          </div>
        </div>
      <{/if}>


      <!--相關附件-->
      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_HOMEWORK_FILES}>
        </label>
        <div class="col-sm-10">
          <{$upform}>
        </div>
      </div>


      <div class="text-center">
        <!--編號-->
        <input type="hidden" name="HomeworkID" value="<{$HomeworkID}>">
        <input type="hidden" name="WebID" value="<{$WebID}>">

        <input type="hidden" name="op" value="<{$next_op}>">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
      </div>
    </form>
  </div>
<{elseif $op=="show_one"}>

  <{if $isMyWeb}>
    <{$sweet_delete_homework_func_code}>
  <{/if}>

  <h1><{$HomeworkTitle}></h1>
  <ol class="breadcrumb">
    <li><a href="homework.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_HOMEWORK}></a></li>
    <{if isset($cate.CateID)}><li><a href="homework.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a></li><{/if}>
    <li><{$HomeworkInfo}></li>
  </ol>

  <div style="min-height: 100px; overflow: hidden; line-height: 1.8; background-color: #FFFFFF; border: 2px solid #99C454; border-radius: 5px; margin:10px auto;">
    <{if $HomeworkContent}>
       <{$HomeworkContent}>
    <{else}>
      <div class="row">
        <{if $today_homework}>
          <div class="col-sm-<{$ColWidth}>">
            <div style="border-bottom: 1px solid #cfcfcf;">
              <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TODAY_WORK}>" src="<{$xoops_url}>/modules/tad_web/images/today_homework.png" class="img-responsive" style="margin:6px auto;">
            </div>
            <{$today_homework}>
          </div>
        <{/if}>
        <{if $bring}>
          <div class="col-sm-<{$ColWidth}>">
            <div style="border-bottom: 1px solid #cfcfcf;">
              <img alt="<{$smarty.const._MD_TCW_HOMEWORK_BRING}>" src="<{$xoops_url}>/modules/tad_web/images/bring.png" class="img-responsive" style="margin:6px auto;">
            </div>
            <{$bring}>
          </div>
        <{/if}>
        <{if $teacher_say}>
          <div class="col-sm-<{$ColWidth}>">
            <div style="border-bottom: 1px solid #cfcfcf;">
              <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TEACHER_SAY}>" src="<{$xoops_url}>/modules/tad_web/images/teacher_say.png" class="img-responsive" style="margin:6px auto;">
            </div>
            <{$teacher_say}>
          </div>
        <{/if}>
      </div>
      <{if $other}>
        <div class="alert alert-info"><{$other}></div>
      <{/if}>
    <{/if}>
  </div>

  <{if $HomeworkFiles}>
    <{$HomeworkFiles}>
  <{/if}>

  <{$fb_comments}>

  <{if $isMyWeb or $isAssistant}>
    <div class="text-right" style="margin: 30px 0px;">
      <a href="javascript:delete_homework_func(<{$HomeworkID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
      <a href="homework.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
      <a href="homework.php?WebID=<{$WebID}>&op=edit_form&HomeworkID=<{$HomeworkID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
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
          <a href="cate.php?WebID=<{$WebID}>&ColName=homework&table=tad_web_homework" class="btn btn-warning <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-folder-open"></i> <{$smarty.const._MD_TCW_CATE_TOOLS}></a>
          <a href="homework.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
        <{/if}>
      </div>
    </div>
  <{/if}>
  <{if $homework_data or $yet_data}>
    <{$FooTableJS}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/homework/tpls/tad_web_common_homework.tpl"}>
  <{else}>
    <h1><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$homework.PluginTitle}></h1>
    <div class="alert alert-info"><{$smarty.const._MD_TCW_EMPTY}></div>
  <{/if}>
  <div class="clearfix"></div>
  <{includeq file="$xoops_rootpath/modules/tad_web/plugins/calendar/tpls/tad_web_common_calendar.tpl"}>
<{elseif $op=="setup"}>

  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
    <h1><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$homework.PluginTitle}></h1>
    <{if $isMyWeb or $isAssistant}>
      <a href="homework.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
    <{else}>
      <div class="text-center">
        <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
      </div>
    <{/if}>
<{/if}>
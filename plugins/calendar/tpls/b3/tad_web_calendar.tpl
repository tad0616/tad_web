<{if $op=="edit_form"}>

  <{$formValidator_code}>


  <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>

  <h2><{$smarty.const._MD_TCW_CALENDAR_ADD}></h2>
  <div class="well">
    <form action="calendar.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">

      <!--活動日期-->
      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_CALENDARDATE}>
        </label>
        <div class="col-sm-3">
          <input type="text" name="CalendarDate" class="form-control" value="<{$CalendarDate}>" id="CalendarDate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d'})">
        </div>

        <!--活動名稱-->
        <div class="col-sm-7">
          <input type="text" name="CalendarName" value="<{$CalendarName}>" id="CalendarName" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_CALENDARNAME}>">
        </div>
      </div>

      <!--活動說明-->
      <div class="form-group">
        <div class="col-sm-12">
          <textarea name="CalendarDesc"  rows=4 id="CalendarDesc"  class="form-control" placeholder="<{$smarty.const._MD_TCW_CALENDARDESC}>"><{$CalendarDesc}></textarea>
        </div>
      </div>

      <!--全校活動-->
      <div class="form-group">
        <div class="col-sm-10">
          <label class="checkbox-inline">
            <input type="checkbox" name="CalendarType" id="CalendarType" value="all"><{$smarty.const._MD_TCW_CALENDAR_TYPE_GLOBAL}>
          </label>
        </div>

        <div class="col-sm-2">
          <input type="hidden" name="CalendarID" value="<{$CalendarID}>">
          <input type="hidden" name="WebID" value="<{$WebID}>">
          <input type="hidden" name="op" value="<{$next_op}>">
          <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
      </div>

    </form>
  </div>
<{elseif $op=="show_one"}>

  <{if $isMyWeb}>
    <{$sweet_delete_calendar_func_code}>
  <{/if}>

  <h2><{$CalendarDate}><{$CalendarName}></h2>

  <ol class="breadcrumb">
    <li><a href="calendar.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_CALENDAR}></a></li>
    <{if isset($cate.CateID)}><li><a href="calendar.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a></li><{/if}>
    <li><{$CalendarInfo}></li>
  </ol>

  <{if $CalendarDesc}>
    <div class="alert" style="background-color: #F5F9DB;"><{$CalendarDesc}></div>
  <{/if}>

  <{$fb_comments}>

  <{if $isMyWeb}>
    <div class="text-right" style="margin: 30px 0px;">
      <a href="javascript:delete_calendar_func(<{$CalendarID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
      <a href="setup.php?WebID=<{$WebID}>&plugin=calendar" class="btn btn-success"><i class="fa fa-wrench"></i> <{$smarty.const._MD_TCW_SETUP}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
      <a href="calendar.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
      <a href="calendar.php?WebID=<{$WebID}>&op=edit_form&CalendarID=<{$CalendarID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
    </div>
  <{/if}>
<{elseif $calendar_data}>
  <{if $WebID}>
    <{$cate_menu}>
  <{/if}>
  <div class="row">
    <div class="col-sm-12">
      <{includeq file="$xoops_rootpath/modules/tad_web/plugins/calendar/tpls/b3/tad_web_common_calendar.tpl"}>
    </div>
  </div>
<{elseif $op=="setup"}>

  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
  <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$calendar.PluginTitle}></h2>
  <{if $isMyWeb and $WebID}>
    <a href="setup.php?WebID=<{$WebID}>&plugin=calendar" class="btn btn-success"><i class="fa fa-wrench"></i> <{$smarty.const._MD_TCW_SETUP}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
    <a href="calendar.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_CALENDAR_SHORT}></a>
  <{else}>
    <div class="text-center">
      <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
    </div>
  <{/if}>
<{/if}>
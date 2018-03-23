<link href="<{$xoops_url}>/modules/tad_web/plugins/schedule/schedule.css" rel="stylesheet">
<{if $op=="edit_form"}>

  <{$formValidator_code}>


  <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
  <script>
  $(function() {
    //$( ".draggable" ).draggable({ revert: "valid" });

    $( "#catalog li" ).draggable({
      appendTo: "body",
      helper: "clone"
    });

    $( ".droppable" ).droppable({
      hoverClass: "hover",
      drop: function( event, ui ) {

        if(ui.draggable.text()=='<{$smarty.const._MD_TCW_SCHEDULE_BLANK}>'){
          $.post("<{$xoops_url}>/modules/tad_web/plugins/schedule/save_schedule.php", {op:'delete', WebID: "<{$WebID}>", ScheduleID: "<{$ScheduleID}>", tag: $( this ).attr('id')});
          $( this )
          .removeClass( "dropped" )
          .css( "color", ui.draggable.css("color"))
          .css( "background-color", ui.draggable.css("background-color"))
          .find( "div" )
          .html('<{$smarty.const._MD_TCW_SCHEDULE_BLANK}>').appendTo( this );;
        }else{
          $.post("<{$xoops_url}>/modules/tad_web/plugins/schedule/save_schedule.php", {op:'save', WebID: "<{$WebID}>", ScheduleID: "<{$ScheduleID}>", tag: $( this ).attr('id'), Subject: ui.draggable.text()});
          $( this )
            .addClass( "dropped" )
            .css( "color", ui.draggable.css("color"))
            .css( "background-color", ui.draggable.css("background-color"))
            .find( "div" )
            .html( ui.draggable.text() ).appendTo( this );
        }
      }
    });
  });
  </script>

  <h2><{$smarty.const._MD_TCW_SCHEDULE_ADD}></h2>
  <div class="well">
    <form schedule="schedule.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">

      <!--分類-->
      <{$cate_menu_form}>

      <!--課表名稱-->
      <div class="form-group">
        <label class="col-sm-2 control-label"><{$smarty.const._MD_TCW_SCHEDULENAME}></label>
        <div class="col-sm-10">
          <input type="text" name="ScheduleName" value="<{$ScheduleName}>" id="ScheduleName" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_SCHEDULENAME}>">
        </div>
      </div>

      <{if $ScheduleID}>
        <div class="form-group">
          <div class="col-sm-12">
            <div class="alert alert-warning">
              <ul id="catalog">
                <{foreach from=$schedule_subjects item=subject}>
                <li style="cursor: move; text-align: center; color: <{$subject.color}>; background-color: <{$subject.bg_color}>;"><{$subject.Subject}></li>
                <{/foreach}>
                <li style="cursor: move; color: #CDCDCD; background-color: #FFFFFF;"><{$smarty.const._MD_TCW_SCHEDULE_BLANK}></li>
              </ul>
              <div style="clear: both;" class="text-right"><a href="schedule.php?WebID=<{$WebID}>&op=setup_subject&ScheduleID=<{$ScheduleID}>" class="btn btn-xs btn-info"><{$smarty.const._MD_TCW_SCHEDULE_SETUP_SUBJECT}></a></div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-12">
            <{$schedule_template}>
          </div>
        </div>
      <{/if}>


      <!--顯示設定-->
      <div class="form-group">
        <label class="sr-only"></label>
        <div class="col-sm-12">
          <label class="checkbox-inline">
            <input type="checkbox" name="ScheduleDisplay" value="1" id="ScheduleDisplay" <{if $ScheduleDisplay=='1'}>checked<{/if}>>
            <{$smarty.const._MD_TCW_SCHEDULE_SET_DEFAULT}><span class="text-danger"><{$smarty.const._MD_TCW_SCHEDULE_SET_DEFAULT_DESC}></span>
          </label>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-12 text-center">

          <!--活動編號-->
          <input type="hidden" name="ScheduleID" value="<{$ScheduleID}>">
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
    <{$sweet_delete_schedule_func_code}>
  <{/if}>

  <h2><{$ScheduleName}></h2>

  <ol class="breadcrumb">
    <li><a href="schedule.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_SCHEDULE}></a></li>
    <{if isset($cate.CateID)}><li><a href="schedule.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a></li><{/if}>
    <li><{$ScheduleInfo}></li>
  </ol>

  <{$schedule_template}>

  <div class="text-right" style="margin: 30px 0px;">
    <{if $isMyWeb or $isCanEdit}>
      <a href="javascript:delete_schedule_func(<{$ScheduleID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_SCHEDULE_SHORT}></a>
      <a href="schedule.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_SCHEDULE_SHORT}></a>
      <a href="schedule.php?WebID=<{$WebID}>&op=edit_form&ScheduleID=<{$ScheduleID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_SCHEDULE_SHORT}></a>
    <{/if}>
    <a href="<{$xoops_url}>/modules/tad_web/plugins/schedule/pdf.php?WebID=<{$WebID}>&ScheduleID=<{$ScheduleID}>" class="btn btn-primary"><{$smarty.const._MD_TCW_SCHEDULE_PDF}></a>
  </div>
<{elseif $op=="setup_subject"}>
  <h2><{$smarty.const._MD_TCW_SCHEDULE_SETUP_SUBJECT}></h2>
  <script language="JavaScript">
    function change_color(selector,css_name,css_val){
      $(selector).css(css_name,css_val);
    }

    $(document).ready(function(){

      var item_form_index=<{$item_form_index_start}>;

      $("#add_item").click(function(){

        //複製一份表單
        var new_content=item_template(item_form_index);
        $("#new_item_form").append(new_content);

        $('.color'+item_form_index).mColorPicker({
            imageFolder: '<{$xoops_url}>/modules/tadtools/mColorPicker/images/'
        });

        item_form_index++;
      });
    });

    function item_template(item_form_index){
      var content='<tr id="item_form'+item_form_index+'"><td><div id="remove'+item_form_index+'" style="cursor: pointer;" onClick="$(\'#item_form'+item_form_index+'\').remove();"><i class="fa fa-times text-danger"></i></div></td><td id="demo_'+item_form_index+'" style="text-align: center; vertical-align: middle; color: #000000; background-color: #FFFFFF;"><div id="Subject_demo_'+item_form_index+'"></div><div id="Teacher_demo_'+item_form_index+'" style="font-size:12px;"></div><input type="hidden" name="old_Subject['+item_form_index+']" value=""></td><td><input type="text" class="form-control" name="Subject['+item_form_index+']" value="" onChange="$(\'#Subject_demo_'+item_form_index+'\').html(this.value);"></td><td><input type="text" class="form-control" name="Teacher['+item_form_index+']" value="" placeholder="<{$smarty.const._MD_TCW_SCHEDULE_MAIN_TEACHER}>" onChange="$(\'#Teacher_demo_'+item_form_index+'\').html(this.value);"></td><td><input type="text" class="form-control color'+item_form_index+'" name="color['+item_form_index+']" value="#000000" data-text="true" data-hex="true" style="width:90px; display: inline-block;" onChange="change_color(\'#demo_'+item_form_index+'\',\'color\',this.value);"></td><td><input type="text" class="form-control color'+item_form_index+'" name="bg_color['+item_form_index+']" value="#FFFFFF" data-text="true" data-hex="true" style="width:90px; display: inline-block;" onChange="change_color(\'#demo_'+item_form_index+'\',\'background-color\',this.value);"></td></tr>';
      return content;
    }
  </script>

  <{$mColorPicker_code}>
  <form schedule="schedule.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
    <table class="table">
      <tr>
        <th colspan=2 style="text-align:center;"><{$smarty.const._MD_TCW_SCHEDULE_DEMO}></th>
        <th><{$smarty.const._MD_TCW_SCHEDULE_SUBJECT_TITLE}></th>
        <th><{$smarty.const._MD_TCW_SCHEDULE_TEACHER_NAME}></th>
        <th><{$smarty.const._MD_TCW_SCHEDULE_COLOR}></th>
        <th><{$smarty.const._MD_TCW_SCHEDULE_BGCOLOR}></th>
      </tr>
      <tbody id="new_item_form">
        <{foreach from=$schedule_subjects_arr key=i item=subject}>
          <tr id="item_form<{$i}>">
            <td><div id="remove<{$i}>" style="cursor: pointer;" onClick="$('#item_form<{$i}>').remove();"><i class="fa fa-times text-danger"></i></div></td>
            <td id="demo_<{$i}>" style="text-align: center; vertical-align: middle; color: <{$subject.color}>; background-color: <{$subject.bg_color}>;">
              <div id="Subject_demo_<{$i}>"><{$subject.Subject}></div>
              <div id="Teacher_demo_<{$i}>" style="font-size:12px;"><{$subject.Teacher}></div>
              <input type="hidden" name="old_Subject[<{$i}>]" value="<{$subject.Subject}>">
            </td>
            <td><input type="text" class="form-control" name="Subject[<{$i}>]" value="<{$subject.Subject}>" onChange="$('#Subject_demo_<{$i}>').html(this.value);"></td>
            <td><input type="text" class="form-control" name="Teacher[<{$i}>]" value="<{$subject.Teacher}>" placeholder="<{$smarty.const._MD_TCW_SCHEDULE_MAIN_TEACHER}>" onChange="$('#Teacher_demo_<{$i}>').html(this.value);"></td>
            <td><input type="text" class="form-control color" name="color[<{$i}>]" value="<{$subject.color}>" data-text="true" data-hex="true" style="width:90px; display: inline-block;" onChange="change_color('#demo_<{$i}>','color',this.value);"></td>
            <td><input type="text" class="form-control color" name="bg_color[<{$i}>]" value="<{$subject.bg_color}>" data-text="true" data-hex="true" style="width:90px; display: inline-block;" onChange="change_color('#demo_<{$i}>','background-color',this.value);"></td>
          </tr>
        <{/foreach}>
      </tbody>
    </table>


    <div class="form-group">
      <div class="col-sm-12 text-center">
        <input type="hidden" name="ScheduleID" value="<{$ScheduleID}>">
        <input type="hidden" name="WebID" value="<{$WebID}>">
        <input type="hidden" name="op" value="save_subject">

        <a id="add_item" class="btn btn-success"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_SCHEDULE_ADD_ITEM}></a>
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
      </div>
    </div>
  </form>
<{elseif $schedule_data}>
  <{if $WebID}>
    <{$cate_menu}>
  <{/if}>
  <{$FooTableJS}>

  <{includeq file="$xoops_rootpath/modules/tad_web/plugins/schedule/tpls/tad_web_common_schedule.tpl"}>

<{elseif $op=="setup"}>

  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
  <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$schedule.PluginTitle}></h2>
  <{if $isMyWeb  or $isCanEdit}>
    <a href="schedule.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_SCHEDULE_SHORT}></a>
  <{else}>
    <div class="text-center">
      <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
    </div>
  <{/if}>
<{/if}>
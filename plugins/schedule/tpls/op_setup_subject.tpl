  <h2><{$smarty.const._MD_TCW_SCHEDULE_SETUP_SUBJECT}></h2>
  <script language="JavaScript">
    function change_color(selector,css_name,css_val){
      $(selector).css(css_name,css_val);
    }

    $(document).ready(function(){

      var item_form_index=<{$item_form_index_start|default:''}>;

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
      var content='<tr id="item_form'+item_form_index+'"><td><div id="remove'+item_form_index+'" style="cursor: pointer;" onClick="$(\'#item_form'+item_form_index+'\').remove();"><i class="fa fa-times text-danger"></i></div></td><td id="demo_'+item_form_index+'" style="text-align: center; vertical-align: middle; color: #000000; background-color: #FFFFFF;"><div id="Subject_demo_'+item_form_index+'"></div><div id="Teacher_demo_'+item_form_index+'" style="font-size: 80%;"></div><input type="hidden" name="old_Subject['+item_form_index+']" value=""></td><td><input type="text" class="form-control" name="Subject['+item_form_index+']" value="" onChange="$(\'#Subject_demo_'+item_form_index+'\').html(this.value);"></td><td><input type="text" class="form-control" name="Teacher['+item_form_index+']" value="" placeholder="<{$smarty.const._MD_TCW_SCHEDULE_MAIN_TEACHER}>" onChange="$(\'#Teacher_demo_'+item_form_index+'\').html(this.value);"></td><td><input type="text" class="form-control color'+item_form_index+'" name="color['+item_form_index+']" value="#000000" data-text="true" data-hex="true" style="width:90px; display: inline-block;" onChange="change_color(\'#demo_'+item_form_index+'\',\'color\',this.value);"></td><td><input type="text" class="form-control color'+item_form_index+'" name="bg_color['+item_form_index+']" value="#FFFFFF" data-text="true" data-hex="true" style="width:90px; display: inline-block;" onChange="change_color(\'#demo_'+item_form_index+'\',\'background-color\',this.value);"></td></tr>';
      return content;
    }
  </script>


  <form schedule="schedule.php" method="post" id="myForm" enctype="multipart/form-data" role="form">
    <table class="table">
      <tr>
        <th colspan=2 style="text-align:center;"><{$smarty.const._MD_TCW_SCHEDULE_DEMO}></th>
        <th><{$smarty.const._MD_TCW_SCHEDULE_SUBJECT_TITLE}></th>
        <th><{$smarty.const._MD_TCW_SCHEDULE_TEACHER_NAME}></th>
        <th><{$smarty.const._MD_TCW_SCHEDULE_LINK}></th>
        <th><{$smarty.const._MD_TCW_SCHEDULE_COLOR}></th>
        <th><{$smarty.const._MD_TCW_SCHEDULE_BGCOLOR}></th>
      </tr>
      <tbody id="new_item_form">
        <{foreach from=$schedule_subjects_arr key=i item=subject}>
          <tr id="item_form<{$i|default:''}>">
            <td><div id="remove<{$i|default:''}>" style="cursor: pointer;" onClick="$('#item_form<{$i|default:''}>').remove();"><i class="fa fa-times text-danger"></i></div></td>
            <td id="demo_<{$i|default:''}>" style="text-align: center; vertical-align: middle; color: <{$subject.color}>; background-color: <{$subject.bg_color}>;">
              <div id="Subject_demo_<{$i|default:''}>"><{$subject.Subject}></div>
              <div id="Teacher_demo_<{$i|default:''}>" style="font-size: 80%;"><{$subject.Teacher}></div>
              <input type="hidden" name="old_Subject[<{$i|default:''}>]" value="<{$subject.Subject}>">
            </td>
            <td><input type="text" class="form-control" name="Subject[<{$i|default:''}>]" value="<{$subject.Subject}>" onChange="$('#Subject_demo_<{$i|default:''}>').html(this.value);"></td>
            <td><input type="text" class="form-control" name="Teacher[<{$i|default:''}>]" value="<{$subject.Teacher}>" placeholder="<{$smarty.const._MD_TCW_SCHEDULE_MAIN_TEACHER}>" onChange="$('#Teacher_demo_<{$i|default:''}>').html(this.value);"></td>
            <td><input type="text" class="form-control" name="Link[<{$i|default:''}>]" value="<{$subject.Link}>" placeholder="<{$smarty.const._MD_TCW_SCHEDULE_LINK_DESC}>"></td>
            <td><input type="text" class="form-control color" name="color[<{$i|default:''}>]" value="<{$subject.color}>" data-text="true" data-hex="true" style="width:90px; display: inline-block;" onChange="change_color('#demo_<{$i|default:''}>','color',this.value);"></td>
            <td><input type="text" class="form-control color" name="bg_color[<{$i|default:''}>]" value="<{$subject.bg_color}>" data-text="true" data-hex="true" style="width:90px; display: inline-block;" onChange="change_color('#demo_<{$i|default:''}>','background-color',this.value);"></td>
          </tr>
        <{/foreach}>
      </tbody>
    </table>


    <div class="form-group row mb-3">
      <div class="col-md-12 text-center">
        <input type="hidden" name="ScheduleID" value="<{$ScheduleID|default:''}>">
        <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
        <input type="hidden" name="op" value="save_subject">

        <a id="add_item" class="btn btn-success"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_SCHEDULE_ADD_ITEM}></a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>  <{$smarty.const._TAD_SAVE}></button>
      </div>
    </div>
  </form>
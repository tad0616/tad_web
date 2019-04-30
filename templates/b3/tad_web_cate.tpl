
<script type="text/javascript">
  $(document).ready(function(){
    $('#sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
        var order = $(this).sortable('serialize');
        $.post('save_cate_sort.php', order, function(theResponse){
            $('#save_msg').html(theResponse);
        });
    }
    });


    $(".act").change(function() {
      var this_op=$(this).val();
      var cate_id=$(this).attr("id");
      if(this_op=="rename"){
        $("#new_name_col"+cate_id).show();
        $("#move2"+cate_id).hide();
        $("#del_alert"+cate_id).hide();
        $("#set_assistant"+cate_id).hide();
        $("#set_power"+cate_id).hide();
      }else if(this_op=="delete" || this_op=="move"){
        $("#new_name_col"+cate_id).hide();
        $("#move2"+cate_id).show();
        $("#del_alert"+cate_id).hide();
        $("#set_assistant"+cate_id).hide();
        $("#set_power"+cate_id).hide();
      }else if(this_op=="del_all"){
        $("#new_name_col"+cate_id).hide();
        $("#move2"+cate_id).hide();
        $("#del_alert"+cate_id).show();
        $("#set_assistant"+cate_id).hide();
        $("#set_power"+cate_id).hide();
      }else if(this_op=="set_assistant"){
        $("#new_name_col"+cate_id).hide();
        $("#move2"+cate_id).hide();
        $("#del_alert"+cate_id).hide();
        $("#set_assistant"+cate_id).show();
        $("#set_power"+cate_id).hide();
      }else if(this_op=="power"){
        $("#new_name_col"+cate_id).hide();
        $("#move2"+cate_id).hide();
        $("#del_alert"+cate_id).hide();
        $("#set_assistant"+cate_id).hide();
        $("#set_power"+cate_id).show();
      }else{
        $("#new_name_col"+cate_id).hide();
        $("#move2"+cate_id).hide();
        $("#del_alert"+cate_id).hide();
        $("#set_assistant"+cate_id).hide();
        $("#set_power"+cate_id).hide();
      }
    });
  });
</script>




<h2><a href="<{$plugin.url}>"><{$plugin.title}></a><{$smarty.const._MD_TCW_CATE_TOOLS}></h2>

<{if $cate_arr}>
  <form action="cate.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
    <{$cate_menu_form}>
    <hr>

    <{if $isMyWeb and $WebID}>
      <div class="form-group">
        <label class="col-sm-3 control-label">
          <{$plugin.title}><{$smarty.const._MD_TCW_NEW_CATE}>
        </label>
        <div class="col-sm-4">
          <input type="text" name="newCateName" id="newCateName" class="form-control">
        </div>
      </div>
    <{/if}>
    <{if $cate_opt_arr}>
      <div id="save_msg"></div>
      <div id="sort">
        <{foreach from=$cate_opt_arr key=i item=cate}>
          <!--<{$cate.CateName}>-->
          <div class="form-group" id="CateID_<{$cate.CateID}>">
            <label class="col-sm-3 control-label">
              <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;margin:0px 4px;" alt="<{$smarty.const._TAD_SORTABLE}>" title="<{$smarty.const._TAD_SORTABLE}>">
              <span class="fa-stack fa-1x text-warning pull-left">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-inverse fa-stack-1x"><{$cate.CateSort}></i>
              </span>
              <{if $cate.CateEnable=='1'}>
                <span class="label label-info"><{$smarty.const._MD_TCW_CATE_ENABLED}></span>
              <{else}>
                <span class="label label-danger"><{$smarty.const._MD_TCW_CATE_UNABLED}></span>
              <{/if}>
              <{if $cate.assistant}>
                <i class="fa fa-male" alt="<{$cate.assistant.MemName}>" title="<{$cate.assistant.MemName}>"></i>
              <{/if}>

              <a href="<{$ColName}>.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a>
            </label>
            <div class="col-sm-4">
              <select name="act[<{$cate.CateID}>]" id="<{$cate.CateID}>" class="form-control act">
                <option value=""><{$smarty.const._MD_TCW_CATE_NONE_OPT}><{$smarty.const._MD_TCW_CATE_DATA_AMOUNT1}><{$cate.counter}><{$smarty.const._MD_TCW_CATE_DATA_AMOUNT2}></option>
                <option value="move"><{$smarty.const._MD_TCW_CATE_MOVE}></option>
                <option value="rename"><{$smarty.const._MD_TCW_CATE_MODIFY}></option>
                <option value="delete"><{$smarty.const._MD_TCW_DEL_CATE_MOVE_TO}></option>
                <option value="del_all"><{$smarty.const._MD_TCW_DEL_CATE_ALL}></option>
                <{if $cate.CateEnable=='1'}>
                  <option value="unable"><{$smarty.const._MD_TCW_UNABLE_CATE}></option>
                <{else}>
                  <option value="enable"><{$smarty.const._MD_TCW_ENABLE_CATE}></option>
                <{/if}>
                <{if $plugin.assistant=='1'}>
                  <option value="set_assistant"><{$smarty.const._MD_TCW_CATE_SET_ASSISTANT}></option>
                <{/if}>
                <option value="power"><{$smarty.const._MD_TCW_CATE_POWER}><{if $cate.power}>[<{$cate.power}>]<{/if}></option>
              </select>
            </div>
            <div class="col-sm-5">
              <input type="text" name="newName[<{$cate.CateID}>]" value="<{$cate.CateName}>" id="new_name_col<{$cate.CateID}>" class="validate[required] form-control new_name_col" style="display: none;">
              <select name="move2[<{$cate.CateID}>]" id="move2<{$cate.CateID}>" class="form-control move2" style="display: none;">
                <{foreach from=$cate_arr item=other}>
                  <{if $other.CateID!=$cate.CateID}>
                    <option value="<{$other.CateID}>"><{$other.CateName}></option>
                  <{/if}>
                <{/foreach}>
              </select>
              <div id="del_alert<{$cate.CateID}>" class="del_alert" style="color: red; display: none;">
                <label class="checkbox">
                  <input type="checkbox" name="alert[<{$cate.CateID}>]" value="yes">
                  <{$smarty.const._MD_TCW_DEL_CATE_ALL_ALERT}>
                </label>
              </div>
              <select name="MemID[<{$cate.CateID}>]" id="set_assistant<{$cate.CateID}>" class="form-control set_assistant" style="display: none;">
                <{foreach from=$students item=mem}>
                  <option value="<{$mem.MemID}>" <{if $cate.assistant.MemID==$mem.MemID}>selected<{/if}>><{$mem.CateName}>(<{$mem.MemSort}>)<{$mem.MemName}></option>
                <{/foreach}>
              </select>
              <select name="power[<{$cate.CateID}>]" id="set_power<{$cate.CateID}>" class="form-control" style="display: none;">
                <option value="" <{if $cate.power==''}>selected<{/if}>><{$smarty.const._MD_TCW_POWER_FOR_ALL}></option>
                <option value="users" <{if $cate.power=='users'}>selected<{/if}>><{$smarty.const._MD_TCW_POWER_FOR_USERS}></option>
                <option value="web_users" <{if $cate.power=='web_users'}>selected<{/if}>><{$smarty.const._MD_TCW_POWER_FOR_WEB_USERS}></option>
                <option value="web_admin" <{if $cate.power=='web_admin'}>selected<{/if}>><{$smarty.const._MD_TCW_POWER_FOR_WEB_ADMIN}></option>
              </select>
            </div>
          </div>
        <{/foreach}>
      </div>
    <{/if}>

    <div class="form-group">
      <div class="col-sm-12 text-center">
        <input type="hidden" name="ColName" value="<{$ColName}>">
        <input type="hidden" name="WebID" value="<{$WebID}>">
        <input type="hidden" name="op" value="save_cate">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
      </div>
    </div>
  </form>
<{else}>
  <div class="jumbotron">
    <h2><a href="<{$plugin.url}>"><{$plugin.title}></a><{$smarty.const._MD_TCW_CATE_NONE}></h2>
      <{if $isMyWeb and $WebID}>
        <form action="cate.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
          <div class="form-group">
            <label class="col-sm-2 control-label">
              <{$smarty.const._MD_TCW_NEW_CATE}>
            </label>
            <div class="col-sm-6">
              <input type="text" name="newCateName" id="newCateName" class="validate[required] form-control">
            </div>
            <div class="col-sm-4">
              <input type="hidden" name="ColName" value="<{$ColName}>">
              <input type="hidden" name="WebID" value="<{$WebID}>">
              <input type="hidden" name="op" value="save_cate">
              <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
            </div>
          </div>
        </form>
      <{/if}>
  </div>
<{/if}>
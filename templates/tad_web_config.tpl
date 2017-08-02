<{if $isMyWeb}>
  <{if $op=="delete_tad_web_chk"}>
    <h2><{$smarty.const._MD_TCW_WILL_DEL}></h2>
    <form action="config.php" method="post" class="myForm">
      <table class="table table-striped table-bordered table-hover" style="width:auto;">
        <tr><th><{$smarty.const._MD_TCW_CATE_PLUGIN_TITLE}></th><th><{$smarty.const._MD_TCW_PLUGIN_TOTAL}></th></tr>
        <{foreach from=$plugins item=plugin}>
          <tr>
            <td><a href="<{$plugin.dirname}>.php?WebID=<{$WebID}>" target="_blank"><{$plugin.PluginTitle}></a></td>
            <td style="text-align: center;"><a href="<{$plugin.dirname}>.php?WebID=<{$WebID}>" target="_blank"><{$plugin.total}></a></td>
          </tr>
        <{/foreach}>
      </table>
      <input type="hidden" name="op" value="delete_tad_web">
      <input type="hidden" name="WebID" value="<{$WebID}>">
      <button type="submit" class="btn btn-danger"><{$smarty.const._MD_TCW_DELETE}></button>
    </form>
  <{else}>
    <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/jqueryCookie/jquery.cookie.js"></script>
    <script type="text/javascript" src="<{$xoops_url}>/modules/tad_web/class/tmt_core.js"></script>
    <script type="text/javascript" src="<{$xoops_url}>/modules/tad_web/class/tmt_spry_linkedselect.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $("#tad_web_config_tabs").tabs({
          active   : $.cookie('activetab'),
          activate : function( event, ui ){
              $.cookie( 'activetab', ui.newTab.index(),{
                  expires : 30
              });
          }
        });

        $('#sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
          var order = $(this).sortable('serialize');
          $.post('save_sort.php?WebID=<{$WebID}>', order, function(theResponse){
              $('#save_msg').html(theResponse);
          });
        }
        });
      });

      function getOptions(destination,val_col)
        {

        var values = [];
        var sel = document.getElementById(destination);
        for (var i=0, n=sel.options.length;i<n;i++) {
          if (sel.options[i].value) values.push(sel.options[i].value);
        }
        document.getElementById(val_col).value=values.join(',');
      }
    </script>

    <div id="tad_web_config_tabs">
      <ul>
        <li><a href='#tad_web_config_tab-1'><{$smarty.const._MD_TCW_TOOLS}></a></li>
        <li><a href='#tad_web_config_tab-2'><{$smarty.const._MD_TCW_PLUGIN_TOOLS}></a></li>
        <li><a href='#tad_web_config_tab-3'><{$smarty.const._MD_TCW_HEAD_TOOLS}></a></li>
        <li><a href='#tad_web_config_tab-4'><{$smarty.const._MD_TCW_LOGO_TOOLS}></a></li>
        <li><a href='#tad_web_config_tab-5'><{$smarty.const._MD_TCW_BG_TOOLS}></a></li>
        <li><a href='#tad_web_config_tab-6'><{$smarty.const._MD_TCW_COLOR_TOOLS}></a></li>
        <li><a href='#tad_web_config_tab-7'><{$smarty.const._MD_TCW_ADMIN_SETUP}></a></li>
      </ul>

      <div id='tad_web_config_tab-1'>
        <form action="config.php" method="post" enctype="multipart/form-data" class="form-horizontal myForm" role="form">
          <div class="row">
            <div class="col-sm-7">
              <h3><{$smarty.const._MD_TCW_WEB_TOOLS}></h3>
              <{$cate_menu}>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_WEB_NAME_SETUP}>
                </label>
                <div class="col-sm-9">
                  <input type="text" name="WebName" value="<{$WebName}>" id="WebName" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_CLASS_WEB_NAME}>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_UPDATE_MY_NAME}>
                </label>
                <div class="col-sm-9">
                  <input type="text" name="WebOwner" value="<{$WebOwner}>" id="WebOwner" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_UPDATE_MY_NAME}>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_UPLOAD_MY_PHOTO}>
                </label>
                <div class="col-sm-9">
                  <{$upform_teacher}>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_OTHER_CLASS_SETUP}>
                </label>
                <div class="col-sm-9">
                  <input type="text" name="other_web_url" value="<{$other_web_url}>" id="other_web_url" class="form-control" placeholder="http://">
                </div>
              </div>
            </div>
            <div class="col-sm-5">
              <{if $teacher_thumb_pic}>
                <img src="<{$teacher_thumb_pic}>" alt="photo" class="img-rounded img-polaroid img-responsive img-thumbnail">
              <{/if}>
            </div>
          </div>

          <h3><{$smarty.const._MD_TCW_THEME_TOOLS}></h3>
          <div class="row">
            <label class="col-sm-2 control-label">
              <{$smarty.const._MD_TCW_THEME_TOOLS_DEFAULT_THEME}>
            </label>
            <div class="col-sm-4">
              <select name="defalut_theme" id="defalut_theme" class="form-control">
                <option value="for_tad_web_theme" <{if $defalut_theme=="for_tad_web_theme"}>selected<{/if}>>for_tad_web_theme</option>
                <option value="for_tad_web_theme_2" <{if $defalut_theme=="for_tad_web_theme_2"}>selected<{/if}>>for_tad_web_theme_2</option>
              </select>
            </div><label class="col-sm-2 control-label">
              <{$smarty.const._MD_TCW_USER_SIMPLE_MENU}>
            </label>
            <div class="col-sm-4">
              <label class="radio-inline">
                <input type="radio" name="use_simple_menu" value="1" <{if $use_simple_menu=='1'}>checked<{/if}>>
                <{$smarty.const._YES}>
              </label>
              <label class="radio-inline">
                <input type="radio" name="use_simple_menu" value="0" <{if $use_simple_menu!='1'}>checked<{/if}>>
                <{$smarty.const._NO}>
              </label>
            </div>
          </div>

          <div class="row">
            <label class="col-sm-2 control-label">
              <{$smarty.const._MD_TCW_THEME_TOOLS_THEME_SIDE}>
            </label>
            <div class="col-sm-4">
              <label class="radio-inline">
                <input type="radio" name="theme_side" value="left" <{if $theme_side=="left"}>checked<{/if}>> <{$smarty.const._MD_TCW_THEME_TOOLS_THEME_SIDE_LEFT}>
              </label>
              <label class="radio-inline">
                <input type="radio" name="theme_side" value="none" <{if $theme_side=="none"}>checked<{/if}>> <{$smarty.const._MD_TCW_THEME_TOOLS_THEME_SIDE_NONE}>
              </label>
              <label class="radio-inline">
                <input type="radio" name="theme_side" value="right" <{if $theme_side=="right"}>checked<{/if}>> <{$smarty.const._MD_TCW_THEME_TOOLS_THEME_SIDE_RIGHT}>
              </label>
            </div>

            <label class="col-sm-2 control-label">
              <{$smarty.const._MD_TCW_THEME_TOOLS_FONT_SIZE}>
            </label>
            <div class="col-sm-ˋ">
              <div class="input-group">
                <input type="text" name="menu_font_size" value="<{$menu_font_size}>" class="form-control">
                <span class="input-group-addon">px</span>
              </div>
            </div>
          </div>

          <{if $login_method}>
            <{if $login_config==''}>
              <{assign var="login_config" value=$login_defval}>
            <{/if}>
            <h3><{$smarty.const._MD_TCW_WEB_OPENID_SETUP}></h3>
              <{assign var="i" value=0}>
              <{assign var="total" value=1}>
              <{foreach from=$login_method key=title item=openid}>
                <{if $i==0}>
                  <div class="row">
                <{/if}>
                <div class="col-sm-4">
                  <label class="checkbox">
                    <input type="checkbox" name="login_method[]" value="<{$openid}>" <{if $openid|in_array:$login_config}>checked<{/if}>><{$title}><{$openid}>
                  </label>
                </div>
                <{assign var="i" value=$i+1}>
                <{if $i == 3 || $total==$login_method_count}>
                  </div>
                  <{assign var="i" value=0}>
                <{/if}>
                <{assign var="total" value=$total+1}>
              <{/foreach}>
          <{/if}>


          <div class="text-center">
            <input type="hidden" name="WebID" value="<{$WebID}>">
            <input type="hidden" name="op" value="save_config">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
          </div>
        </form>
        <hr>

        <{if $Web.WebEnable=='1'}>
          <h3><{$smarty.const._MD_TCW_CLOSE_WEB}></h3>
          <div class="alert alert-warning">
            <p><{$smarty.const._MD_TCW_CLOSE_WEB_DESC}></p>
            <a href="config.php?WebID=<{$WebID}>&op=unable_my_web" class="btn btn-warning"><{$smarty.const._MD_TCW_CLOSE_WEB}></a>
          </div>
          <hr>
        <{else}>
          <h3><{$smarty.const._MD_TCW_OPEN_WEB}></h3>
          <div class="alert alert-warning">
            <p><{$smarty.const._MD_TCW_OPEN_WEB_DESC}></p>
            <a href="config.php?WebID=<{$WebID}>&op=enable_my_web" class="btn btn-success"><{$smarty.const._MD_TCW_OPEN_WEB}></a>
          </div>
          <hr>
        <{/if}>


        <{if $Web.WebEnable!='1'}>
          <h3><{$smarty.const._MD_TCW_DEL_WEB}></h3>
          <div class="alert alert-danger">
            <p><{$smarty.const._MD_TCW_DEL_WEB_DESC}></p>
            <a href="javascript:delete_my_web('<{$WebID}>')" class="btn btn-danger"><{$smarty.const._MD_TCW_DEL_WEB}></a>
          </div>
        <{/if}>
      </div>

      <div id='tad_web_config_tab-2'>
        <form action="config.php" method="post" enctype="multipart/form-data" class="form-horizontal myForm" role="form">
          <h3>
            <{$smarty.const._MD_TCW_FCNCTION_SETUP}>
          </h3>
          <div class="alert alert-info">
            <{$smarty.const._MD_TCW_ABOUT_PLUGIN_TOOLS}>
          </div>
          <div id="save_msg"><{$smarty.const._TAD_SORTABLE}></div>
          <table class="table">
            <tr>
              <th><{$smarty.const._MD_TCW_CATE_PLUGIN_ENABLE}></th>
              <th><{$smarty.const._MD_TCW_CATE_PLUGIN_TITLE}></th>
              <th><{$smarty.const._MD_TCW_CATE_PLUGIN_NEW_NAME}></th>
              <th><{$smarty.const._MD_TCW_SETUP}></th>
            </tr>
            <tbody id="sort">
              <{foreach from=$plugins item=plugin}>
                <{if $plugin.dirname=="system"}>
                  <input type="hidden" name="plugin_enable[<{$plugin.dirname}>]" value="1">
                  <input type="hidden" name="plugin_name[<{$plugin.dirname}>]" value="<{$smarty.const._MD_TCW_SYSTEM}>">
                <{else}>
                  <tr id="tr_<{$plugin.dirname}>">
                    <td <{if $plugin.db.PluginEnable=='0'}>style="background-color: #dfdfdf; color: #5f5f5f;"<{/if}>>
                      <label class="checkbox-inline"><input type="checkbox" name="plugin_enable[<{$plugin.dirname}>]" value="1" <{if $plugin.db.PluginEnable=='1'}>checked="checked"<{elseif $plugin.db.PluginEnable=='0'}><{else}>checked="checked"<{/if}>><{$plugin.dirname}></label>
                    </td>
                    <td <{if $plugin.db.PluginEnable=='0'}>style="background-color: #dfdfdf; color: #5f5f5f;"<{/if}>><{$plugin.config.name}></td>
                    <td <{if $plugin.db.PluginEnable=='0'}>style="background-color: #dfdfdf; color: #5f5f5f;"<{/if}>><input type="text" name="plugin_name[<{$plugin.dirname}>]" value="<{if $plugin.db.PluginTitle}><{$plugin.db.PluginTitle}><{else}><{$plugin.config.name}><{/if}>" class="form-control" style="width: 120px;"></td>
                    <td <{if $plugin.db.PluginEnable=='0'}>style="background-color: #dfdfdf; color: #5f5f5f;"<{/if}>>
                      <a href="setup.php?WebID=<{$WebID}>&plugin=<{$plugin.dirname}>" class="btn btn-success"><i class="fa fa-wrench"></i> <{$smarty.const._MD_TCW_SETUP}></a>
                    </td>
                  </tr>
                <{/if}>
              <{/foreach}>
            </tbody>
          </table>
          <div class="form-group">
            <div class="col-sm-12 text-center">
              <input type="hidden" name="WebID" value="<{$WebID}>">
              <input type="hidden" name="op" value="save_plugins">
              <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
            </div>
          </div>
        </form>
      </div>

      <div id='tad_web_config_tab-3'>

        <div class="row">
          <div class="col-sm-12">
            <script language="JavaScript">
              $().ready(function(){
                $(".thumb").click(function(){
                  var bg=$(this).attr("id");
                  $("#head_bg").attr("src","<{$xoops_url}>/uploads/tad_web/<{$WebID}>/head/"+bg);
                  $.post("config_ajax.php", {op: "save_head" , filename: bg, WebID: <{$WebID}>});
                });
              });
            </script>

            <h3>
              <{$smarty.const._MD_TCW_HEAD_TOOLS}>
              <small>
                <{$smarty.const._MD_TCW_CLICK_TO_CHANG}>
              </small>
            </h3>
            <div class="alert alert-info">
              <{$bg_desc}>
            </div>

            <form action="config.php" method="post" enctype="multipart/form-data" class="form-horizontal myForm" role="form">
              <div class="form-group">
                <div class="col-sm-12">
                  <{$upform_head}>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-12">
                  <{if $all_head}>
                    <{foreach from=$all_head item=head}>
                      <div style="width:100px; height:96px; display:inline-block; margin:4px;">
                        <a href="#top" title="<{$head.file_name}>" class="thumb_link">
                          <label style="width: 100px; height: 70px; background: #000000 url('<{$head.tb_path}>') center center no-repeat; border: 1px solid gray; background-size: contain;" id="<{$head.file_name}>" class="thumb">
                          </label>
                        </a>

                        <label class="del_img_box" style="font-size:12px;" id="del_img<{$head.files_sn}>">
                          <input type="checkbox" value="<{$head.files_sn}>" name="del_file[<{$head.files_sn}>]"> <{$smarty.const._TAD_DEL}>
                        </label>
                      </div>
                    <{/foreach}>
                  <{/if}>
                </div>
              </div>

              <div class="text-center">
                <input type="hidden" name="WebID" value="<{$WebID}>">
                <input type="hidden" name="op" value="upload_head">
                <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div id='tad_web_config_tab-4'>

        <div class="row">
          <div class="col-sm-12">
            <script language="JavaScript">
              $().ready(function(){
                $(".logo_thumb").click(function(){
                  var logo=$(this).attr("id");
                  $("#tad_web_logo").attr("src","<{$xoops_url}>/uploads/tad_web/<{$WebID}>/logo/"+logo);
                  $.post("config_ajax.php", {op: "save_logo_pic" , filename: logo, WebID: <{$WebID}>});
                });
              });
            </script>

            <h3>
              <{$smarty.const._MD_TCW_LOGO_TOOLS}>
              <small>
                <{$smarty.const._MD_TCW_CLICK_TO_CHANG}>
              </small>
            </h3>
            <div class="alert alert-info">
              <{$logo_desc}>
            </div>

            <form action="config.php" method="post" enctype="multipart/form-data" class="form-horizontal myForm" role="form">
              <div class="form-group">
                <div class="col-sm-12">
                  <{$upform_logo}>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-12">
                  <{if $all_logo}>
                    <{foreach from=$all_logo item=logo}>
                      <div style="width:150px; height:76px; display:inline-block; margin:4px;">
                        <a href="#top">
                          <label style="width: 150px; height: 50px; background: #000000 url('<{$logo.tb_path}>') center center no-repeat; border: 1px solid gray; background-size: contain;" id="<{$logo.file_name}>" class="logo_thumb">
                          </label>
                        </a>

                        <label class="del_img_box" style="font-size:12px;" id="del_img<{$logo.files_sn}>">
                          <input type="checkbox" value="<{$logo.files_sn}>" name="del_file[<{$logo.files_sn}>]"> <{$smarty.const._TAD_DEL}>
                        </label>
                      </div>
                    <{/foreach}>
                  <{/if}>
                </div>
              </div>

              <div class="text-center">
                <input type="hidden" name="WebID" value="<{$WebID}>">
                <input type="hidden" name="op" value="upload_logo">
                <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div id='tad_web_config_tab-5'>

        <div class="row">
          <div class="col-sm-12">
            <script language="JavaScript">
              $().ready(function(){
                $(".bg_thumb").click(function(){
                  var bg=$(this).attr("id");
                  if(bg=="none"){
                    $("body").css("background-image","none");
                    $.post("config_ajax.php", {op: "save_bg" , filename: '', WebID: <{$WebID}>});
                  }else{
                    $("body").css("background-image","url('<{$xoops_url}>/uploads/tad_web/<{$WebID}>/bg/"+bg+"')");
                    $(this).css("border","2px solid blue");
                    $.post("config_ajax.php", {op: "save_bg" , filename: bg, WebID: <{$WebID}>});
                  }
                });
              });
            </script>

            <h3>
              <{$smarty.const._MD_TCW_BG_TOOLS}>
              <small>
                <{$smarty.const._MD_TCW_CLICK_TO_CHANG}>
              </small>
            </h3>
            <form action="config.php" method="post" enctype="multipart/form-data" class="form-horizontal myForm" role="form">
              <div class="form-group">
                <div class="col-sm-12">
                  <{$upform_bg}>
                </div>
              </div>
              <hr>
              <div class="form-group">
                <label class="col-sm-2 control-label">
                  <{$smarty.const._MD_TCW_BG_REPEAT}>
                </label>
                <div class="col-sm-4">
                  <select name="bg_repeat" id="bg_repeat" class="form-control">
                    <option value="" <{if $bg_repeat==""}>selected<{/if}>><{$smarty.const._MD_TCW_BG_REPEAT_NORMAL}></option>
                    <option value="repeat-x" <{if $bg_repeat=="repeat-x"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_REPEAT_X}></option>
                    <option value="repeat-y" <{if $bg_repeat=="repeat-y"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_REPEAT_Y}></option>
                    <option value="no-repeat" <{if $bg_repeat=="no-repeat"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_NO_REPEAT}></option>
                  </select>
                </div>
                <label class="col-sm-2 control-label">
                  <{$smarty.const._MD_TCW_BG_ATTACHMENT}>
                </label>
                <div class="col-sm-4">
                  <select name="bg_attachment" id="bg_attachment" class="form-control">
                    <option value="" <{if $bg_attachment==""}>selected<{/if}>><{$smarty.const._MD_TCW_BG_ATTACHMENT_SCROLL}></option>
                    <option value="fixed" <{if $bg_attachment=="fixed"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_ATTACHMENT_FIXED}></option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">
                  <{$smarty.const._MD_TCW_BG_POSITION}>
                </label>
                <div class="col-sm-4">
                  <select name="bg_postiton" id="bg_postiton" class="form-control">
                    <option value="left top" <{if $bg_postiton=="left top"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_POSITION_LT}></option>
                    <option value="right top" <{if $bg_postiton=="right top"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_POSITION_RT}></option>
                    <option value="left bottom" <{if $bg_postiton=="left bottom"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_POSITION_LB}></option>
                    <option value="right bottom" <{if $bg_postiton=="right bottom"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_POSITION_RB}></option>
                    <option value="center center" <{if $bg_postiton=="center center"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_POSITION_CC}></option>
                    <option value="center top" <{if $bg_postiton=="center top"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_POSITION_CT}></option>
                    <option value="center bottom" <{if $bg_postiton=="center bottom"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_POSITION_CB}></option>
                  </select>
                </div>
                <label class="col-sm-2 control-label">
                  <{$smarty.const._MD_TCW_BG_SIZE}>
                </label>
                <div class="col-sm-4">
                  <select name="bg_size" id="bg_size" class="form-control">
                    <option value="" <{if $bg_size==""}>selected<{/if}>><{$smarty.const._MD_TCW_BG_SIZE_NONE}></option>
                    <option value="cover" <{if $bg_size=="cover"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_SIZE_COVER}></option>
                    <option value="contain" <{if $bg_size=="contain"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_SIZE_CONTAIN}></option>
                  </select>
                </div>
              </div>
              <hr>
              <div class="form-group">
                <div class="col-sm-12">
                  <div style="width:100px; height:96px; display:inline-block; margin:4px;">
                    <label style="width: 80px; height: 80px; background: <{$bg_color}>; border: 1px solid gray; background-size: contain;" id="none" class="bg_thumb">
                    </label>

                    <label class="del_img_box" style="font-size:12px;" id="del_img<{$bg.files_sn}>">
                      <{$smarty.const._MD_TCW_CONFIG_NONE_BG}>
                    </label>
                  </div>
                  <{if $all_bg}>
                    <{foreach from=$all_bg item=bg}>
                      <div style="width:100px; height:96px; display:inline-block; margin:4px;">
                        <label style="width: 80px; height: 80px; background: #000000 url('<{$bg.tb_path}>') center center no-repeat; border: <{if $bg.file_name == $web_bg}>2px solid red<{else}>1px solid gray<{/if}>; background-size: contain;" id="<{$bg.file_name}>" class="bg_thumb">
                        </label>

                        <label class="del_img_box" style="font-size:12px;" id="del_img<{$bg.files_sn}>">
                          <input type="checkbox" value="<{$bg.files_sn}>" name="del_file[<{$bg.files_sn}>]"> <{$smarty.const._TAD_DEL}>
                        </label>
                      </div>
                    <{/foreach}>
                  <{/if}>
                </div>
              </div>

              <div class="text-center">
                <input type="hidden" name="WebID" value="<{$WebID}>">
                <input type="hidden" name="op" value="upload_bg">
                <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div id='tad_web_config_tab-6'>
        <{$mColorPicker_code}>
        <div class="row">
          <div class="col-sm-12">
            <script language="JavaScript">
              function change_color(selector,css_name,css_val){
                $(selector).css(css_name,css_val);
              }
              function save_color(col){
                  $.post("config_ajax.php", {op: "save_color" , col_name: $(col).attr('id'), col_val: $(col).val(), WebID: <{$WebID}>});
              }

              $(document).ready(function () {
                $('#container_bg_color').bind('colorpicked', function () {
                  save_color(this);
                });
                $('#bg_color').bind('colorpicked', function () {
                  save_color(this);
                });
                $('#navbar_bg_top').bind('colorpicked', function () {
                  save_color(this);
                });
                $('#navbar_color').bind('colorpicked', function () {
                  save_color(this);
                });
                $('#navbar_hover').bind('colorpicked', function () {
                  save_color(this);
                });
                $('#navbar_color_hover').bind('colorpicked', function () {
                  save_color(this);
                });

              });
            </script>

            <h3>
              <{$smarty.const._MD_TCW_COLOR_TOOLS}>
            </h3>
            <form action="config.php" method="post" enctype="multipart/form-data" class="form-horizontal myForm" role="form">
              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_MAIN_NAV_TOP_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[navbar_bg_top]" class="form-control color" value="<{$navbar_bg_top}>" id="navbar_bg_top" data-text="true" data-hex="true" onChange="change_color('#tad_web_nav,.sf-menu li','background-color',this.value);" style="width:120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$navbar_bg_top}>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_MAIN_NAV_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[navbar_color]" class="form-control color" value="<{$navbar_color}>" id="navbar_color" data-text="true" data-hex="true" onChange="change_color('.sf-menu a','color',this.value);" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$navbar_color}>
                </div>
              </div>

              <hr>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_MAIN_NAV_HOVER_BG_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[navbar_hover]" class="form-control color" value="<{$navbar_hover}>" id="navbar_hover" data-text="true" data-hex="true" onChange="change_color('.sf-menu li:hover,.sf-menu li.sfHover','background-color',this.value);" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$navbar_hover}>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_MAIN_NAV_HOVER_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[navbar_color_hover]" class="form-control color" value="<{$navbar_color_hover}>" id="navbar_color_hover" data-text="true" data-hex="true" onChange="change_color('.sf-menu a:hover','color',this.value);" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$navbar_color_hover}>
                </div>
              </div>

              <hr>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_MAIN_BG_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[bg_color]" class="form-control color" value="<{$bg_color}>" id="bg_color" data-text="true" data-hex="true" onChange="change_color('body','background-color',this.value);" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$bg_color}>
                </div>
              </div>

              <hr>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_CONTAINER_BG_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[container_bg_color]" class="form-control color" value="<{$container_bg_color}>" id="container_bg_color" data-text="true" data-hex="true" onChange="change_color('#container','background-color',this.value);" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$container_bg_color}>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_CENTER_TEXT_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[center_text_color]" class="form-control color" value="<{$center_text_color}>" id="center_text_color" data-text="true" data-hex="true" onChange="change_color('#web_center_block','color',this.value);" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$center_text_color}>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_CENTER_LINK_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[center_link_color]" class="form-control color" value="<{$center_link_color}>" id="center_link_color" data-text="true" data-hex="true" onChange="change_color('#web_center_block a','color',this.value);" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$center_link_color}>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_CENTER_HOVER_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[center_hover_color]" class="form-control color" value="<{$center_hover_color}>" id="center_hover_color" data-text="true" data-hex="true" onChange="change_color('#web_center_block a','color',this.value);" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$center_hover_color}>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_CENTER_HEADER_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[center_header_color]" class="form-control color" value="<{$center_header_color}>" id="center_header_color" data-text="true" data-hex="true" onChange="change_color('#web_center_block h3','color',this.value);" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$center_header_color}>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_CENTER_BORDER_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[center_border_color]" class="form-control color" value="<{$center_border_color}>" id="center_border_color" data-text="true" data-hex="true" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$center_border_color}>
                </div>
              </div>

              <hr>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_SIDE_BG_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[side_bg_color]" class="form-control color" value="<{$side_bg_color}>" id="side_bg_color" data-text="true" data-hex="true" onChange="change_color('#web_side_block','background-color',this.value);" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$side_bg_color}>
                </div>
              </div>


              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_SIDE_TEXT_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[side_text_color]" class="form-control color" value="<{$side_text_color}>" id="side_text_color" data-text="true" data-hex="true" onChange="change_color('#web_side_block','color',this.value);" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$side_text_color}>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_SIDE_LINK_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[side_link_color]" class="form-control color" value="<{$side_link_color}>" id="side_link_color" data-text="true" data-hex="true" onChange="change_color('#web_side_block a','color',this.value);" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$side_link_color}>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_SIDE_HOVER_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[side_hover_color]" class="form-control color" value="<{$side_hover_color}>" id="side_hover_color" data-text="true" data-hex="true" onChange="change_color('#web_side_block a','color',this.value);" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$side_hover_color}>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_SIDE_HEADER_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[side_header_color]" class="form-control color" value="<{$side_header_color}>" id="side_header_color" data-text="true" data-hex="true" onChange="change_color('#web_center_block h3','color',this.value);" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$side_header_color}>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">
                  <{$smarty.const._MD_TCW_SIDE_BORDER_COLOR}>
                </label>
                <div class="col-sm-4">
                  <input type="text" name="color_setup[side_border_color]" class="form-control color" value="<{$side_border_color}>" id="side_border_color" data-text="true" data-hex="true" style="width: 120px; display: inline-block;">
                </div>
                <div class="col-sm-5">
                  <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$side_border_color}>
                </div>
              </div>

              <hr>

              <div class="text-center">
                <input type="hidden" name="WebID" value="<{$WebID}>">
                <input type="hidden" name="op" value="save_all_color">
                <a href="config.php?WebID=<{$WebID}>&op=default_color" class="btn btn-warning"><{$smarty.const._MD_TCW_DEFAULT_COLOR}></a>
                <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <script type="text/javascript">
        $(document).ready(function() {
          $('#keyman').change(function(event) {
            $.post("config_ajax.php", {op: "keyman" , keyman: $('#keyman').val(), WebID: <{$WebID}>}, function(theResponse){
                $('#adm_repository').html(theResponse);
            });
          });
        });
      </script>

      <div id="tad_web_config_tab-7">
        <{$smarty.const._MD_TCW_DEFAULT_ADMIN}><{$Web.WebOwnerUid}> <{$Web.WebOwner}>
        <div class="row">
          <div class="col-sm-5 text-center">
            <h3><{$smarty.const._MD_TCW_USER_LIST}></h3>
          </div>
          <div class="col-sm-2"></div>
          <div class="col-sm-5 text-center">
            <h3><{$smarty.const._MD_TCW_USER_SELECTED}></h3>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <form action="config.php" method="post" class="form-horizontal myForm" role="form">
            <div class="row">
              <div class="col-sm-5">

                <div class="input-group">
                  <input type="text" name="keyman" id="keyman" placeholder="<{$smarty.const._MD_TCW_KEYWORD_TO_SELECT_USER}>" class="form-control">
                  <span class="input-group-btn">
                    <a href="#" class="btn btn-success"><{$smarty.const._MD_TCW_SELETC_USER}></a>
                  </span>
                </div>



                <select name="adm_repository" id="adm_repository" size="10" multiple="multiple" tmt:linkedselect="true" class="form-control">
                <{$user_yet}>
                </select>
              </div>
              <div class="col-sm-2 text-center">
                <img src="<{$xoops_url}>/modules/tad_web/images/right.png" onclick="tmt.spry.linkedselect.util.moveOptions('adm_repository', 'adm_destination');getOptions('adm_destination','web_admins');"><br>
                <img src="<{$xoops_url}>/modules/tad_web/images/left.png" onclick="tmt.spry.linkedselect.util.moveOptions('adm_destination' , 'adm_repository');getOptions('adm_destination','web_admins');"><br><br>

                <img src="<{$xoops_url}>/modules/tad_web/images/up.png" onclick="tmt.spry.linkedselect.util.moveOptionUp('adm_destination');getOptions('adm_destination','web_admins');"><br>
                <img src="<{$xoops_url}>/modules/tad_web/images/down.png" onclick="tmt.spry.linkedselect.util.moveOptionDown('adm_destination');getOptions('adm_destination','web_admins');">
                <div class="text-center" style="margin-top: 30px;">
                  <input type="hidden" name="web_admins" id="web_admins" value="<{$web_admins}>">
                  <input type="hidden" name="op" value="save_adm">
                  <input type="hidden" name="WebID" value="<{$WebID}>">
                  <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
                </div>
              </div>
              <div class="col-sm-5">
                <select id="adm_destination" size="12" multiple="multiple" tmt:linkedselect="true" class="form-control">
                <{$user_ok}>
                </select>
              </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <{/if}>
<{/if}>
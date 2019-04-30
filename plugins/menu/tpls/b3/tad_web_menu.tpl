<{if $op=="edit_form"}>


  <link rel="stylesheet" href="class/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css">

  <h2><{$smarty.const._MD_TCW_MENU_ADD}></h2>
  <div class="well">
    <form action="menu.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">

      <!--分類-->
      <{$cate_menu_form}>


      <!--外部連結-->
      <div class="form-group">
        <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TCW_MENU_LINK}>
        </label>
        <div class="col-sm-2">
          <select name="menu_type" id="menu_type" class="form-control" >
            <option value="">請選擇</option>
            <option value="Keyin" <{if $MenuID and $Link!=''}>selected<{/if}>>自行輸入</option>
            <option value="Plugin" <{if $MenuID and $Link==''}>selected<{/if}>>本站單元</option>
          </select>
        </div>
        <div id="PluginDiv" <{if ($MenuID and $Link!='') or $MenuID==''}>style="display: none;"<{/if}>>
          <div class="col-sm-2">
            <select name="Plugin" id="Plugin" class="form-control">
              <option value="">選擇功能</option>
              <{foreach from=$plugin_menu_var key=plugin item=p}>
                <option value="<{$plugin}>" <{if $plugin==$Plugin}>selected<{/if}>><{$p.title}></option>
              <{/foreach}>
            </select>
          </div>
          <div class="col-sm-3">
            <select name="PluginCate" id="PluginCate" class="form-control" <{if ($MenuID and $Link!='') or $MenuID==''}>style="display: none;"<{/if}>>
              <option value="">該功能首頁</option>
              <optgroup id="PluginCateOpt"></optgroup>
            </select>
          </div>
          <div class="col-sm-3">
            <select name="PluginContent" id="PluginContent" class="form-control" <{if ($MenuID and $Link!='') or $MenuID==''}>style="display: none;"<{/if}>>
              <option value="">該分類首頁</option>
              <optgroup id="PluginContentOpt"></optgroup>
            </select>
          </div>
        </div>
        <div id="KeyinDiv" <{if ($MenuID and $Link=='') or $MenuID==''}>style="display: none;"<{/if}>>
          <div class="col-sm-8">
            <input type="text" name="Link" value="<{$Link}>" id="Link" class="form-control" placeholder="<{$smarty.const._MD_TCW_MENU_LINK}>">
          </div>
        </div>
      </div>

      <!--選項名稱-->
      <div class="form-group">
        <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TCW_MENU_TITLE}>
        </label>
        <div class="col-sm-7">
          <input type="text" name="MenuTitle" value="<{$MenuTitle}>" id="MenuTitle" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_MENU_TITLE}>">
        </div>
        <div class="col-sm-3">
          <label class="checkbox-inline">
            <input type="checkbox" name="Target" value="_balnk">
            <{$smarty.const._MD_TCW_MENU_TARGET_BLANK}>
          </label>
        </div>
      </div>


      <!--圖示-->
      <div class="form-group">
        <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TCW_MENU_ICON}>
        </label>
        <div class="col-sm-2">
          <input type="text" name="Icon" data-dnp-widget="fapicker" value="<{$Icon}>" id="Icon" class="form-control" placeholder="<{$smarty.const._MD_TCW_MENU_ICON}>">
        </div>

      <!--顏色-->
        <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TCW_MENU_COLOR}>
        </label>
        <div class="col-sm-2">
          <input type="text" name="Color" value="<{$Color}>" id="Color" class="form-control color" placeholder="<{$smarty.const._MD_TCW_MENU_COLOR}>" data-hex="true" >
        </div>

      <!--底色-->
        <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TCW_MENU_BGCOLOR}>
        </label>
        <div class="col-sm-2">
          <input type="text" name="BgColor" value="<{$BgColor}>" id="BgColor" class="form-control color" placeholder="<{$smarty.const._MD_TCW_MENU_BGCOLOR}>" data-hex="true">
        </div>
      </div>

      <{$power_form}>


      <div class="form-group">
        <div class="col-sm-12 text-center">

          <!--選項編號-->
          <input type="hidden" name="MenuID" value="<{$MenuID}>">
          <!--所屬團隊-->
          <input type="hidden" name="WebID" value="<{$WebID}>">
          <input type="hidden" name="op" value="<{$next_op}>">
          <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
      </div>
    </form>
  </div>

  <script src="class/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js"></script>
  <script type="text/javascript">
    $(function() {
      $('#Icon').iconpicker();
      $('#menu_type').change(function(){
        if($('#menu_type').val()=="Keyin"){
          $('#PluginDiv').hide();
          $('#KeyinDiv').show();
        }else if($('#menu_type').val()=="Plugin"){
          $('#KeyinDiv').hide();
          $('#PluginDiv').show();
        }
      });

      $('#Plugin').change(function(){
        $('#PluginCate').show();
        $.post("<{$xoops_url}>/modules/tad_web/plugins/menu/get_plugin_data.php", { ColName: $('#Plugin').val(), WebID: '<{$WebID}>'},
        function(data) {
          $('#PluginCateOpt').html(data);
        });
        $('#MenuTitle').val($('#Plugin option:selected').text());
      });

      $('#PluginCate').change(function(){
        $('#PluginContent').show();
        $.post("<{$xoops_url}>/modules/tad_web/plugins/menu/get_plugin_data.php", {op: 'PluginContent', dirname:$('#Plugin').val(), CateID: $('#PluginCate').val(), WebID: '<{$WebID}>'},
        function(data) {
          $('#PluginContentOpt').html(data);
        });
        $('#MenuTitle').val($('#Plugin option:selected').text()+'-'+$('#PluginCate option:selected').text());
      });

      $('#PluginContent').change(function(){
          $('#MenuTitle').val($('#Plugin option:selected').text()+'-'+$('#PluginCate option:selected').text()+'-'+$('#PluginContent option:selected').text());
      });
    });
  </script>

<{elseif $menu_data}>
  <{if $WebID}>
    <{$cate_menu}>
  <{/if}>

  <div class="row">
    <div class="col-sm-12">
      <{includeq file="$xoops_rootpath/modules/tad_web/plugins/menu/tpls/b3/tad_web_common_menu.tpl"}>
    </div>
  </div>

<{elseif $op=="setup"}>

  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
  <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$menu.PluginTitle}></h2>
  <{if $isMyWeb}>
    <a href="menu.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_MENU_SHORT}></a>
  <{else}>
    <div class="text-center">
      <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
    </div>
  <{/if}>
<{/if}>
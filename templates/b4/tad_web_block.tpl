<{if $op=="config" or $op=="add_block"}>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#content_type').change(function(event) {
        var content_type=$('#content_type').val();
        if(content_type=="js"){
          $('#html_editor').hide();
          $('#js_editor').show();
          $('#iframe_editor').hide();
        }else if(content_type=="iframe"){
          $('#html_editor').hide();
          $('#js_editor').hide();
          $('#iframe_editor').show();
        }else{
          $('#html_editor').show();
          $('#js_editor').hide();
          $('#iframe_editor').hide();
        }
      });
    });
  </script>

  <div class="row">
    <div class="col-md-12">
      <h1 class="text-center"><{$block.BlockTitle}> <small><{$block.BlockName}></small></h2>
      <form action="block.php" method="post" enctype="multipart/form-data" role="form">
        <div class="form-group row">
          <label class="col-md-3 col-form-label text-sm-right">
            <{$smarty.const._MD_TCW_BLOCK_TITLE}>
          </label>
          <div class="col-md-9">
            <input type="text" name="BlockTitle" value="<{$block.BlockTitle}>" class="form-control">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-form-label text-sm-right">
            <{$smarty.const._MD_TCW_BLOCK_SHOW_TITLE}>
          </label>
          <div class="col-md-9">
            <div class="form-check form-check-inline">
              <input class="form-check-input" id="show_title1" type="radio" name="config[show_title]" value="1" <{if $block.config.show_title!='0'}>checked<{/if}>>
              <label class="form-check-label" for="show_title1"><{$smarty.const._YES}></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" id="show_title0" type="radio" name="config[show_title]" value="0" <{if $block.config.show_title=='0'}>checked<{/if}>>
              <label class="form-check-label" for="show_title0"><{$smarty.const._NO}></label>
            </div>
          </div>
        </div>


        <div class="form-group row">
          <label class="col-md-3 col-form-label text-sm-right">
            <{$smarty.const._MD_TCW_BLOCK_ENABLE}>
          </label>
          <div class="col-md-9">
            <div class="form-check form-check-inline">
              <input class="form-check-input" id="BlockEnable1" type="radio" name="BlockEnable" value="1" <{if $block.BlockEnable!='0'}>checked<{/if}>>
              <label class="form-check-label" for="BlockEnable1"><{$smarty.const._YES}></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" id="BlockEnable0" type="radio" name="BlockEnable" value="0" <{if $block.BlockEnable=='0'}>checked<{/if}>>
              <label class="form-check-label" for="BlockEnable0"><{$smarty.const._NO}></label>
            </div>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-form-label text-sm-right">
            <{$smarty.const._MD_TCW_BLOCK_POSITION}>
          </label>
          <div class="col-md-9">
            <select name="BlockPosition" id="BlockPosition" class="form-control">
              <option value="block1" <{if $block.BlockPosition=="block1"}>selected<{/if}>><{$smarty.const._MD_TCW_TOP_CENTER_BLOCK}></option>
              <option value="block2" <{if $block.BlockPosition=="block2"}>selected<{/if}>><{$smarty.const._MD_TCW_TOP_LEFT_BLOCK}></option>
              <option value="block3" <{if $block.BlockPosition=="block3"}>selected<{/if}>><{$smarty.const._MD_TCW_TOP_RIGHT_BLOCK}></option>
              <option value="block4" <{if $block.BlockPosition=="block4"}>selected<{/if}>><{$smarty.const._MD_TCW_BOTTOM_CENTER_BLOCK}></option>
              <option value="block5" <{if $block.BlockPosition=="block5"}>selected<{/if}>><{$smarty.const._MD_TCW_BOTTOM_LEFT_BLOCK}></option>
              <option value="block6" <{if $block.BlockPosition=="block6"}>selected<{/if}>><{$smarty.const._MD_TCW_BOTTOM_RIGHT_BLOCK}></option>
              <option value="side" <{if $block.BlockPosition=="side"}>selected<{/if}>><{$smarty.const._MD_TCW_SIDE_BLOCK}></option>
            </select>
          </div>
        </div>

        <{$power_form}>
        <{if $form}>
          <hr>
          <{$form}>
          <hr>
        <{/if}>

        <{if $block.plugin=="custom" or $op=="add_block"}>
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-sm-right">
              <{$smarty.const._MD_TCW_BLOCK_CONTENT}>
              <{$block.config.content_type}>
            </label>
            <div class="col-md-9">
              <select name="config[content_type]" id="content_type" class="form-control">
                <option value="html" <{if $block.config.content_type=="html"}>selected<{/if}>><{$smarty.const._MD_TCW_BLOCK_HTML}></option>
                <option value="js" <{if $block.config.content_type=="js"}>selected<{/if}>><{$smarty.const._MD_TCW_BLOCK_JS}></option>
                <option value="iframe" <{if $block.config.content_type=="iframe"}>selected<{/if}>><{$smarty.const._MD_TCW_BLOCK_IFRAME}></option>
              </select>
            </div>
          </div>

          <div id="html_editor" <{if $block.config.content_type!="html" and $block.config.content_type!=""}>style="display:none;"<{/if}>>
            <{$editor}>
          </div>

          <div id="js_editor" <{if $block.config.content_type!="js"}>style="display:none;"<{/if}>>
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-sm-right">
                <{$smarty.const._MD_TCW_BLOCK_JS_DESC}>
              </label>
              <div class="col-md-9">
                <textarea name="BlockContent[js]"  rows="10" class="form-control" style="background: #11190E; color: #90E86A; "><{$block.BlockContent}></textarea>
              </div>
            </div>
          </div>

          <div id="iframe_editor" <{if $block.config.content_type!="iframe"}>style="display:none;"<{/if}>>
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-sm-right">
                <{$smarty.const._MD_TCW_BLOCK_IFRAME_DESC}>
              </label>
              <div class="col-md-9">
                <input type="text" name="BlockContent[iframe]"  class="form-control" placeholder="http://" value="<{$iframeContent}>">
              </div>
            </div>
          </div>

          <{if $block.ShareFrom > 0 or $_IS_EZCLASS=='1'}>
            <input type="hidden" name="BlockShare" value="0">
          <{else}>
            <div class="form-group row">
              <label class="col-md-3 col-form-label text-sm-right">
                <{$smarty.const._MD_TCW_BLOCK_SHARE}>
              </label>
              <div class="col-md-9">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" id="BlockShare_1" type="radio" name="BlockShare" value="1" <{if $shareBlockID > 0}>checked<{/if}>>
                  <label class="form-check-label" for="BlockShare_1"><{$smarty.const._YES}></label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" id="BlockShare_0" type="radio" name="BlockShare" value="0" <{if $shareBlockID == 0 or $block.BlockID==""}>checked<{/if}>>
                  <label class="form-check-label" for="BlockShare_0"><{$smarty.const._NO}></label>
                </div>
              </div>
            </div>
            <input type="hidden" name="shareBlockID" value="<{$shareBlockID}>">
          <{/if}>
        <{/if}>


        <div class="text-center" stye="margin-top: 30px;">
          <input type="hidden" name="WebID" value="<{$WebID}>">
          <input type="hidden" name="ShareFrom" value="<{$block.ShareFrom}>">
          <input type="hidden" name="op" value="save_block_config">
          <input type="hidden" name="BlockName" value="<{$block.BlockName}>">
          <input type="hidden" name="BlockID" value="<{$block.BlockID}>">
          <{if $block.plugin=="custom" or $block.BlockCopy!="0"}>
            <a href="javascript:delete_block_func('<{$block.BlockID}>');" class="btn btn-danger"><{$smarty.const._TAD_DEL}></a>
          <{/if}>
          <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
      </form>

      <{if $use_share_web}>
        <h3><{$shareBlockCount}></h3>
        <div class="alert alert-info">
          <ul>
            <{foreach from=$use_share_web item=web}>
              <li><a href="index.php?WebID=<{$web.WebID}>" target="_blank"><{$web.WebTitle}></a></li>
            <{/foreach}>
          </ul>
        </div>
      <{/if}>
    </div>
  </div>
<{else}>
  <style>
    #sort_uninstall, #sort_block1, #sort_block2, #sort_block3, #sort_block4, #sort_block5, #sort_block6, #sort_side {
      border: 1px dotted rgba(0, 0, 0, 0.3);
      min-height: 40px;
      list-style-type: none;
      margin: 0;
      padding: 5px 0 0 0;
      margin-right: 10px;
    }

    #sort_uninstall li, #sort_block1 li, #sort_block2 li, #sort_block3 li, #sort_block4 li, #sort_block5 li, #sort_block6 li, #sort_side li {
      margin: 0 auto 5px auto;
      padding: 5px;
      font-size: 1.2em;
      max-width: 220px;
    }
    .custom_block{
      background: #FCEFFC url('<{$xoops_url}>/modules/tad_web/images/custom_block.png') right bottom no-repeat;
      border: 1px dashed #CE75CE;
    }
    .share_block{
      background: #FFFF00 url('<{$xoops_url}>/modules/tad_web/images/share_block.png') right bottom no-repeat;
      border: 1px dashed #CE75CE;
    }
  </style>
  <script>
    $(function() {
      $( "#sort_block1, #sort_block2, #sort_block3, #sort_block4, #sort_block5, #sort_block6, #sort_side,#sort_uninstall" ).sortable({
        connectWith: ".connectedSortable",
        placeholder: "ui-state-highlight",
        forcePlaceholderSize: true,
        cursor: "move",
        cursorAt: { left: 5 },
        opacity: 0.5,
        receive: function( event, ui ) {
          var position=$(this).parent().attr("id");
          var order = $(this).sortable('toArray', {attribute: 'id'});
          var block_id = ui.item.attr("id");
          var new_block_id = ui.item.attr("id");
          var chang_id=false;

          $.post("save_block.php", {op:'save_position', WebID: "<{$WebID}>", PositionName: position, BlockID: block_id, plugin: ui.item.attr("title"), order_arr: order}, function(data) {
              if(!isNaN(data)){
                // location.reload();
                var new_block_id =data;
                var new_block_plugin='custom';
                var chang_id=true;
              }else{
                var new_block_plugin=ui.item.attr("title");
                var new_block_id = ui.item.attr("id");
              }

              if(position!="uninstall"){
                $("#"+block_id).css('display','');
                $("#"+block_id).append('<span id="blktool_'+block_id+'"><a href="block.php?WebID=<{$WebID}>&op=config&plugin='+new_block_plugin+'&BlockID='+new_block_id+'" class="pull-right text-danger"><i class="fa fa-pencil"></i></a></span>');
                $("#"+block_id+"_icon").attr('src','images/show1.gif');

                if(chang_id){
                  $("#"+block_id).attr('title','custom');
                  $("#"+block_id).attr('id',new_block_id);
                  $("#"+block_id+"_icon").attr('onclick',"enableBlock('"+new_block_id+"')");
                  $("#"+block_id+"_icon").attr('id',new_block_id+"_icon");
                  $("#blktool_"+block_id).attr('id',"#blktool_"+new_block_id);
                }
              }else{
                $("#"+block_id).css('display','inline-block');
                $("#blktool_"+block_id).hide();
                $("#blktool_"+block_id).remove();
              }
          });
        },
        update: function( event, ui ) {
          var position=$(this).parent().attr("id");
          var order = $(this).sortable('toArray', {attribute: 'id'});
          var block_id = ui.item.attr("id");

          $.post("save_block.php", {op:'save_sort', WebID: "<{$WebID}>", PositionName: position, BlockID: block_id, plugin: ui.item.attr("title"), order_arr: order}, function(data) {
              $("#msg").html(data);
          });
        }
      }).disableSelection();
    });

    function enableBlock(BlockID){
      var img_id="#"+BlockID+"_icon";
      var now_status=$(img_id).attr('title');
      if(now_status=='1'){
        status=0;
      }else{
        status=1;
      }
      $.post("save_block.php", {op:'save_enable', WebID: "<{$WebID}>", BlockEnable: status, BlockID: BlockID}, function(data) {
              $("#msg").html(data);
              $(img_id).attr("src","images/show"+status+".gif");
              $(img_id).attr("title",status);
          });
    }
  </script>
  <div class="row">
    <div class="col-md-6">
      <h2><{$smarty.const._MD_TCW_BLOCK_TOOLS}></h2>
    </div>
    <div class="col-md-6 text-right">
      <a href="block.php?WebID=<{$WebID}>&op=add_block" class="btn btn-primary"><{$smarty.const._MD_TCW_BLOCK_ADD}></a>
    </div>
  </div>


  <{if $uninstall}>
    <div id="uninstall" class="alert alert-danger">
      <div class="text-danger text-center" style="font-size: 2em; opacity: 0.4; filter: alpha(opacity=40); margin-top: -10px;"><{$smarty.const._MD_TCW_UNINSTALL_BLOCK}></div>
      <ul id="sort_uninstall" class="connectedSortable">
          <{foreach from=$uninstall item=block}>
            <li id="<{$block.BlockID}>" title="<{$block.plugin}>" class="ui-state-highlight <{if $block.BlockShare=="1"}>share_block<{elseif $block.plugin=="custom"}>custom_block<{/if}>" style="display: inline-block;">
              <{$block.icon}>
              <{if $block.BlockTitle}>
                <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockTitle}></a>
              <{else}>
                <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockID}></a>
              <{/if}>
            </li>
          <{/foreach}>
      </ul>
    </div>
  <{/if}>

  <div id="msg"></div>
  <div class="row">
    <{if $theme_side=="left"}>
      <div class="col-md-4">
          <div id="side" class="alert alert-success" style="min-height: 300px;">
            <div class="text-success text-center" style="font-size: 2em; opacity: 0.4; filter: alpha(opacity=40); margin-top: -10px;"><{$smarty.const._MD_TCW_SIDE_BLOCK}></div>
            <ul id="sort_side" class="connectedSortable">
              <{if $side}>
                <{foreach from=$side item=block}>
                  <li id="<{$block.BlockID}>" title="<{$block.plugin}>" class="ui-state-highlight <{if $block.BlockShare=="1"}>share_block<{elseif $block.plugin=="custom"}>custom_block<{/if}>">
                    <span id="blktool_<{$block.BlockID}>" class="pull-right"><{if $block.plugin!="custom" and $block.plugin!="share" and $block.BlockCopy==0}><a href="block.php?WebID=<{$WebID}>&op=copy&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-green"><i class="fa fa-files-o"></i></a><{/if}> <a href="block.php?WebID=<{$WebID}>&op=config&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-danger"><i class="fa fa-pencil"></i></a></span>
                    <{$block.icon}>
                    <{if $block.BlockTitle}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockTitle}></a>
                    <{else}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockID}></a>
                    <{/if}>
                  </li>
                <{/foreach}>
              <{/if}>
            </ul>
          </div>
        </div>
    <{/if}>
    <div class="col-md-8">
      <div class="row">
        <div class="col-md-12">
          <div id="block1" class="alert alert-info" style="min-height: 100px;">
            <div class="text-info text-center" style="font-size: 2em; opacity: 0.4; filter: alpha(opacity=40); margin-top: -10px;"><{$smarty.const._MD_TCW_TOP_CENTER_BLOCK}></div>
            <ul id="sort_block1" class="connectedSortable">
              <{if $block1}>
                <{foreach from=$block1 item=block}>
                  <li id="<{$block.BlockID}>" title="<{$block.plugin}>" class="ui-state-highlight <{if $block.BlockShare=="1"}>share_block<{elseif $block.plugin=="custom"}>custom_block<{/if}>">
                    <span id="blktool_<{$block.BlockID}>" class="pull-right"><{if $block.plugin!="custom" and $block.plugin!="share" and $block.BlockCopy==0}><a href="block.php?WebID=<{$WebID}>&op=copy&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-green"><i class="fa fa-files-o"></i></a><{/if}> <a href="block.php?WebID=<{$WebID}>&op=config&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-danger"><i class="fa fa-pencil"></i></a></span>
                    <{$block.icon}>

                    <{if $block.BlockTitle}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockTitle}></a>
                    <{else}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockID}></a>
                    <{/if}>
                  </li>
                <{/foreach}>
              <{/if}>
            </ul>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div id="block2" class="alert alert-info" style="min-height: 100px;">
            <div class="text-info text-center" style="font-size: 2em; opacity: 0.4; filter: alpha(opacity=40); margin-top: -10px;"><{$smarty.const._MD_TCW_TOP_LEFT_BLOCK}></div>
            <ul id="sort_block2" class="connectedSortable">
              <{if $block2}>
                <{foreach from=$block2 item=block}>
                  <li id="<{$block.BlockID}>" title="<{$block.plugin}>" class="ui-state-highlight <{if $block.BlockShare=="1"}>share_block<{elseif $block.plugin=="custom"}>custom_block<{/if}>">
                    <span id="blktool_<{$block.BlockID}>" class="pull-right"><{if $block.plugin!="custom" and $block.plugin!="share" and $block.BlockCopy==0}><a href="block.php?WebID=<{$WebID}>&op=copy&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-green"><i class="fa fa-files-o"></i></a><{/if}> <a href="block.php?WebID=<{$WebID}>&op=config&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-danger"><i class="fa fa-pencil"></i></a></span>
                    <{$block.icon}>

                    <{if $block.BlockTitle}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockTitle}></a>
                    <{else}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockID}></a>
                    <{/if}>
                  </li>
                <{/foreach}>
              <{/if}>
            </ul>
          </div>
        </div>
        <div class="col-md-6">
          <div id="block3" class="alert alert-info" style="min-height: 100px;">
            <div class="text-info text-center" style="font-size: 2em; opacity: 0.4; filter: alpha(opacity=40); margin-top: -10px;"><{$smarty.const._MD_TCW_TOP_RIGHT_BLOCK}></div>
            <ul id="sort_block3" class="connectedSortable">
              <{if $block3}>
                <{foreach from=$block3 item=block}>
                  <li id="<{$block.BlockID}>" title="<{$block.plugin}>" class="ui-state-highlight <{if $block.BlockShare=="1"}>share_block<{elseif $block.plugin=="custom"}>custom_block<{/if}>">
                    <span id="blktool_<{$block.BlockID}>" class="pull-right"><{if $block.plugin!="custom" and $block.plugin!="share" and $block.BlockCopy==0}><a href="block.php?WebID=<{$WebID}>&op=copy&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-green"><i class="fa fa-files-o"></i></a><{/if}> <a href="block.php?WebID=<{$WebID}>&op=config&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-danger"><i class="fa fa-pencil"></i></a></span>
                    <{$block.icon}>

                    <{if $block.BlockTitle}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockTitle}></a>
                    <{else}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockID}></a>
                    <{/if}>
                  </li>
                <{/foreach}>
              <{/if}>
            </ul>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div id="block4" class="alert alert-info" style="min-height: 100px;">
            <div class="text-info text-center" style="font-size: 2em; opacity: 0.4; filter: alpha(opacity=40); margin-top: -10px;"><{$smarty.const._MD_TCW_BOTTOM_CENTER_BLOCK}></div>
            <ul id="sort_block4" class="connectedSortable">
              <{if $block4}>
                <{foreach from=$block4 item=block}>
                  <li id="<{$block.BlockID}>" title="<{$block.plugin}>" class="ui-state-highlight <{if $block.BlockShare=="1"}>share_block<{elseif $block.plugin=="custom"}>custom_block<{/if}>">
                    <span id="blktool_<{$block.BlockID}>" class="pull-right"><{if $block.plugin!="custom" and $block.plugin!="share" and $block.BlockCopy==0}><a href="block.php?WebID=<{$WebID}>&op=copy&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-green"><i class="fa fa-files-o"></i></a><{/if}> <a href="block.php?WebID=<{$WebID}>&op=config&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-danger"><i class="fa fa-pencil"></i></a></span>
                    <{$block.icon}>

                    <{if $block.BlockTitle}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockTitle}></a>
                    <{else}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockID}></a>
                    <{/if}>
                  </li>
                <{/foreach}>
              <{/if}>
            </ul>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div id="block5" class="alert alert-info" style="min-height: 100px;">
            <div class="text-info text-center" style="font-size: 2em; opacity: 0.4; filter: alpha(opacity=40); margin-top: -10px;"><{$smarty.const._MD_TCW_BOTTOM_LEFT_BLOCK}></div>
            <ul id="sort_block5" class="connectedSortable">
              <{if $block5}>
                <{foreach from=$block5 item=block}>
                  <li id="<{$block.BlockID}>" title="<{$block.plugin}>" class="ui-state-highlight <{if $block.BlockShare=="1"}>share_block<{elseif $block.plugin=="custom"}>custom_block<{/if}>">
                    <span id="blktool_<{$block.BlockID}>" class="pull-right"><{if $block.plugin!="custom" and $block.plugin!="share" and $block.BlockCopy==0}><a href="block.php?WebID=<{$WebID}>&op=copy&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-green"><i class="fa fa-files-o"></i></a><{/if}> <a href="block.php?WebID=<{$WebID}>&op=config&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-danger"><i class="fa fa-pencil"></i></a></span>
                    <{$block.icon}>

                    <{if $block.BlockTitle}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockTitle}></a>
                    <{else}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockID}></a>
                    <{/if}>
                  </li>
                <{/foreach}>
              <{/if}>
            </ul>
          </div>
        </div>
        <div class="col-md-6">
          <div id="block6" class="alert alert-info" style="min-height: 100px;">
            <div class="text-info text-center" style="font-size: 2em; opacity: 0.4; filter: alpha(opacity=40); margin-top: -10px;"><{$smarty.const._MD_TCW_BOTTOM_RIGHT_BLOCK}></div>
            <ul id="sort_block6" class="connectedSortable">
              <{if $block6}>
                <{foreach from=$block6 item=block}>
                  <li id="<{$block.BlockID}>" title="<{$block.plugin}>" class="ui-state-highlight <{if $block.BlockShare=="1"}>share_block<{elseif $block.plugin=="custom"}>custom_block<{/if}>">
                    <span id="blktool_<{$block.BlockID}>" class="pull-right"><{if $block.plugin!="custom" and $block.plugin!="share" and $block.BlockCopy==0}><a href="block.php?WebID=<{$WebID}>&op=copy&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-green"><i class="fa fa-files-o"></i></a><{/if}> <a href="block.php?WebID=<{$WebID}>&op=config&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-danger"><i class="fa fa-pencil"></i></a></span>
                    <{$block.icon}>

                    <{if $block.BlockTitle}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockTitle}></a>
                    <{else}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockID}></a>
                    <{/if}>
                  </li>
                <{/foreach}>
              <{/if}>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <{if $theme_side!="left"}>
      <div class="col-md-4">
        <div id="side" class="alert alert-success" style="min-height: 300px;">
          <div class="text-success text-center" style="font-size: 2em; opacity: 0.4; filter: alpha(opacity=40); margin-top: -10px;"><{$smarty.const._MD_TCW_SIDE_BLOCK}></div>
          <ul id="sort_side" class="connectedSortable">
            <{if $side}>
              <{foreach from=$side item=block}>
                <li id="<{$block.BlockID}>" title="<{$block.plugin}>" class="ui-state-highlight <{if $block.BlockShare=="1"}>share_block<{elseif $block.plugin=="custom"}>custom_block<{/if}>">
                    <span id="blktool_<{$block.BlockID}>" class="pull-right"><{if $block.plugin!="custom" and $block.plugin!="share" and $block.BlockCopy==0}><a href="block.php?WebID=<{$WebID}>&op=copy&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-green"><i class="fa fa-files-o"></i></a><{/if}> <a href="block.php?WebID=<{$WebID}>&op=config&plugin=<{$block.plugin}>&BlockID=<{$block.BlockID}>" class="text-danger"><i class="fa fa-pencil"></i></a></span>
                  <{$block.icon}>

                    <{if $block.BlockTitle}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockTitle}></a>
                    <{else}>
                      <a href="block.php?WebID=<{$WebID}>&op=demo&BlockID=<{$block.BlockID}>" class="edit_block" data-fancybox-type="iframe"><{$block.BlockID}></a>
                    <{/if}>
                </li>
              <{/foreach}>
            <{/if}>
          </ul>
        </div>
      </div>
    <{/if}>
  </div>

  <hr>
  <h2><{$smarty.const._MD_TCW_BLOCK_TITLE_PIC}></h2>
  <{$mColorPicker_code}>
  <form action="block.php" method="post" enctype="multipart/form-data" role="form">
    <div class="form-group row">
      <label class="col-md-2 col-form-label text-sm-right">
        <{$smarty.const._MD_TCW_BLOCK_PIC_COLOR}>
      </label>
      <div class="col-md-3">
        <input type="text" name="block_pic[block_pic_text_color]" value="<{$block_pic_text_color}>" class="form-control color" value="<{$block_pic_text_color}>" id="block_pic_text_color" data-text="true" data-hex="true" style="width:120px; display: inline-block;">
      </div>
      <label class="col-md-2 col-form-label text-sm-right">
        <{$smarty.const._MD_TCW_BLOCK_PIC_BORDER_COLOR}>
      </label>
      <div class="col-md-3">
        <input type="text" name="block_pic[block_pic_border_color]" value="<{$block_pic_border_color}>" class="form-control color" value="<{$block_pic_border_color}>" id="block_pic_border_color" data-text="true" data-hex="true" style="width:120px; display: inline-block;">
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-2 col-form-label text-sm-right">
        <{$smarty.const._MD_TCW_BLOCK_PIC_SIZE}>
      </label>
      <div class="col-md-3">
        <input type="text" name="block_pic[block_pic_text_size]" value="<{$block_pic_text_size}>" class="form-control">
      </div>
      <label class="col-md-2 col-form-label text-sm-right">
        <{$smarty.const._MD_TCW_BLOCK_PIC_FONT}>
      </label>
      <div class="col-md-3">
        <select name="block_pic[block_pic_font]" class="form-control">
        <option value="font.ttf" <{if $block_pic_font!='font.ttf'}>selected<{/if}>><{$smarty.const._MD_TCW_BLOCK_PIC_FONT1}></option>
        <option value="DroidSansFallback.ttf" <{if $block_pic_font!='font.ttf'}>selected<{/if}>>DroidSansFallback<{$smarty.const._MD_TCW_BLOCK_PIC_FONT2}></option>
        </select>
      </div>
    </div>

    <div class="alert alert-success">
      <div class="form-check form-check-inline">
        <input class="form-check-input" id="use_block_pic" type="checkbox" name="use_block_pic" value="1" <{if $use_block_pic=="1"}>checked<{/if}>>
        <label class="form-check-label" for="use_block_pic"><{$smarty.const._MD_TCW_BLOCK_TITLE_USE_PIC}></label>
      </div>
    </div>

    <div class="text-center" stye="margin-top: 30px;">
      <input type="hidden" name="WebID" value="<{$WebID}>">
      <input type="hidden" name="op" value="mk_block_pic">
      <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
    </div>
  </form>

<{/if}>
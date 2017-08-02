<{if $op=="edit_form"}>
  <{if $LoginMemID=="" and $LoginParentID=="" and !$isMyWeb}>
    請先登入
  <{else}>

    <{$formValidator_code}>
    <script type="text/javascript">

      function typeInTextarea(Field, newText) {
        var el=document.getElementById(Field);
        var start = el.selectionStart
        var end = el.selectionEnd
        var text = el.value
        var before = text.substring(0, start)
        var after  = text.substring(end, text.length)
        el.value = (before + newText + after)
        el.selectionStart = el.selectionEnd = start + newText.length
        el.focus()
      }
    </script>

    <h1><{$smarty.const._MD_TCW_DISCUSS_ADD}></h1>
    <div class="well">
      <form action="discuss.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">

      <!--分類-->
      <{$cate_menu_form}>

      <!--標題-->
      <div class="form-group">
        <div class="col-sm-12">
          <input type="text" name="DiscussTitle" value="<{$DiscussTitle}>" id="DiscussTitle" class="form-control validate[required]" placeholder="<{$smarty.const._MD_TCW_DISCUSSTITLE}>">
        </div>
      </div>

      <!--內容-->
      <div class="form-group">
        <div class="col-sm-12">
          <textarea name="DiscussContent" class="form-control" rows=15 id="DiscussContent" placehold="<{$smarty.const._MD_TCW_DISCUSSCONTENT}>"><{$DiscussContent}></textarea>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-12">
          <{foreach from=$smile_pics item=file}>
            <img src="<{$xoops_url}>/modules/tad_web/plugins/discuss/smiles/<{$file}>" alt="<{$file}>" onClick="typeInTextarea('DiscussContent','[<{$file}>]');" style="margin:1px;">
          <{/foreach}>
        </div>
      </div>

      <{$tags_form}>

      <!--相關附件-->
      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_DISCUSS_FILES}>
        </label>
        <div class="col-sm-10">
          <{$upform}>
        </div>
      </div>


      <div class="form-group">
        <div class="col-sm-12 text-center">
          <input type="hidden" name="WebID" value="<{$WebID}>">
          <input type="hidden" name="DiscussID" value="<{$DiscussID}>">
          <input type="hidden" name="ReDiscussID" value="<{$ReDiscussID}>">
          <input type="hidden" name="LoginWebID" value="<{$LoginWebID}>">

          <input type="hidden" name="op" value="<{$next_op}>">
          <button type="submit" class="btn btn-primary"><{$smarty.const._MD_TCW_DISCUSS_SUBMIT}></button>
        </div>
      </div>


      </form>
    </div>
  <{/if}>
<{elseif $op=="show_one"}>

  <link href="bubble.css" rel="stylesheet" type="text/css">

  <{if $isMyWeb}>
      <{$sweet_delete_discuss_func_code}>
  <{/if}>

  <h1><{$DiscussTitle}></h1>

  <ol class="breadcrumb">
    <li><a href="discuss.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_DISCUSS}></a></li>
    <{if isset($cate.CateID)}><li><a href="discuss.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a></li><{/if}>
    <li><{$DiscussInfo}></li>
    <{if $tags}><li><{$tags}></li><{/if}>
  </ol>

  <{if $DiscussContent}>

    <script type="text/javascript">

      function typeInTextarea(Field, newText) {
        var el=document.getElementById(Field);
        var start = el.selectionStart
        var end = el.selectionEnd
        var text = el.value
        var before = text.substring(0, start)
        var after  = text.substring(end, text.length)
        el.value = (before + newText + after)
        el.selectionStart = el.selectionEnd = start + newText.length
        el.focus()
      }
    </script>


    <div class="row">
        <div class="col-sm-3 col-md-2  text-center">
            <img src="<{$pic}>" alt="<{$MemName}>" style="max-width: 100%;" class="img-rounded img-polaroid">
            <div style="line-height:1.5em;">
              <div><{$MemName}></div><div style="font-size:10px; background: #1d649b; color: #fff; border-radius: 3px;"><{$DiscussDate}></div>
            </div>

        </div>
        <div class="col-sm-9 col-md-10">
            <{$DiscussContent}>
            <div style="float: right;">
              <{if $isMineDiscuss}>
                <a href="javascript:delete_discuss_func(<{$DiscussID}>);" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                <a href="discuss.php?WebID=<{$WebID}>&op=edit_form&DiscussID=<{$DiscussID}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
              <{/if}>
            </div>
        </div>
    </div>

  <{/if}>

  <{$re}>

  <{if $LoginMemID or $LoginParentID or $isMineDiscuss}>
    <div style="clear: both;"></div>
    <h3><{$smarty.const._MD_TCW_DISCUSS_REPLY}></h3>
    <form action="discuss.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form" style="margin-top:16px;">
      <div class="form-group">
        <div class="col-sm-12">
          <textarea name="DiscussContent" class="form-control" rows=8 id="DiscussContent" class="validate[required , length[10,9999]]"></textarea>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-12">
          <{foreach from=$smile_pics item=file}>
            <img src="<{$xoops_url}>/modules/tad_web/plugins/discuss/smiles/<{$file}>" alt="<{$file}>" onClick="typeInTextarea('DiscussContent','[<{$file}>]');" style="margin:1px;">
          <{/foreach}>
        </div>
      </div>

      <!--相關附件-->
      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MD_TCW_DISCUSS_FILES}>
        </label>
        <div class="col-sm-8">
          <{$upform}>
        </div>
        <div class="col-sm-2">
          <input type="hidden" name="WebID" value="<{$WebID}>">

          <!--回覆編號-->
          <input type="hidden" name="ReDiscussID" value="<{$DiscussID}>">
          <input type="hidden" name="DiscussTitle" value="Re:<{$DiscussTitle}>" id="DiscussTitle">
          <input type="hidden" name="op" value="insert">
          <button type="submit" class="btn btn-primary"><{$smarty.const._MD_TCW_DISCUSS_TO_REPLY}></button>
        </div>
      </div>
    </form>
  <{/if}>
<{elseif $op=="list_all"}>
  <{if $WebID}>
    <div class="row">
      <div class="col-sm-8">
        <{$cate_menu}>
      </div>
      <div class="col-sm-4 text-right">
        <{if $isMyWeb and $WebID}>
          <a href="cate.php?WebID=<{$WebID}>&ColName=discuss&table=tad_web_discuss" class="btn btn-warning <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-folder-open"></i> <{$smarty.const._MD_TCW_CATE_TOOLS}></a>
        <{/if}>

        <{if $isMyWeb or $LoginMemID or $LoginParentID}>
          <a href="discuss.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_DISCUSS_ADD}></a>
        <{/if}>
      </div>
    </div>
  <{/if}>
  <{if $discuss_data}>
    <{$FooTableJS}>
    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/discuss/tpls/tad_web_common_discuss.tpl"}>
  <{else}>
    <h1><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$discuss.PluginTitle}></h1>
    <div class="alert alert-info"><{$smarty.const._MD_TCW_EMPTY}></div>
  <{/if}>
<{elseif $op=="setup"}>

  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{else}>
  <h1><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$discuss.PluginTitle}></h1>
  <{if $isMyWeb and $WebID}>
    <a href="discuss.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_DISCUSS_SHORT}></a>
  <{else}>
    <div class="text-center">
      <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
    </div>
  <{/if}>
<{/if}>
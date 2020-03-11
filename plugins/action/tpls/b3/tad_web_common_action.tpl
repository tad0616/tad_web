<{if $web_display_mode=='index' and $action_data}>
  <{if "$xoops_rootpath/uploads/tad_web/0/image/`$dirname`.png"|file_exists}>
    <a href="<{$xoops_url}>/modules/tad_web/<{$dirname}>.php"><img src="<{$xoops_url}>/uploads/tad_web/0/image/<{$dirname}>.png" alt="<{$action.PluginTitle}>"></a>
  <{else}>
    <h3><a href="<{$xoops_url}>/modules/tad_web/action.php"><{$action.PluginTitle}></a></h3>
  <{/if}>
<{elseif $web_display_mode=='index_plugin'}>
  <h2><a href="<{$xoops_url}>/modules/tad_web/"><i class="fa fa-home"></i></a> <{$action.PluginTitle}></h2>
<{elseif $web_display_mode=='home_plugin'}>
  <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$action.PluginTitle}></h2>
<{/if}>

<{if $action_data}>
  <div class="row">
    <div class="col-sm-12">
      <{foreach item=act from=$action_data}>
        <div style="width: 156px; height: 260px; float:left; margin: 5px 2px; overflow: hidden;">
          <a href='action.php?WebID=<{$act.WebID}>&ActionID=<{$act.ActionID}>'>
            <div style="width: 150px; height: 160px; background-color: #F1F7FF ; border:1px dotted green; margin: 0px auto;">
            <div style="width: 140px; height: 140px; background: #F1F7FF url('<{$act.ActionPic}>') center center no-repeat; border:8px solid #F1F7FF; margin: 0px auto;background-size:cover;">
            </div>
            </div>
          </a>
          <div class="text-center" style="margin: 8px auto;">
            <a href='action.php?WebID=<{$act.WebID}>&ActionID=<{$act.ActionID}>'><{$act.ActionName}></a>
            <{if $act.isCanEdit}>
              <a href="javascript:delete_action_func(<{$act.ActionID}>);" class="text-danger"><i class="fa fa-trash-o"></i></a>
              <a href="action.php?WebID=<{$WebID}>&op=edit_form&ActionID=<{$act.ActionID}>"  class="text-warning"><i class="fa fa-pencil"></i></a>
            <{/if}>
          </div>
          <{if $web_display_mode=="index" or $web_display_mode=="index_plugin"}>
            <div class="text-center" style="font-size: 75%;">
              <{$act.WebTitle}>
            </div>
          <{/if}>
        </div>
      <{/foreach}>
    </div>
  </div>

  <div style="clear: both;"></div>

  <{if $action_data}>
    <{if $web_display_mode=='index_plugin' or $web_display_mode=='home_plugin'}>
      <{$bar}>
    <{/if}>
  <{/if}>

  <div style="text-align:right; margin: 0px 0px 10px;">
    <{if $web_display_mode=='index'}>
      <a href="action.php" class="btn btn-primary <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
    <{elseif $web_display_mode=='home' or $ActionDefCateID}>
      <a href="action.php?WebID=<{$WebID}>" class="btn btn-primary <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
    <{/if}>

    <{if $isMyWeb and $WebID}>
      <a href="action.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACTION_SHORT}></a>
    <{/if}>
  </div>
<{/if}>

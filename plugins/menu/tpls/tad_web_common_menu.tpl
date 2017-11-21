<{if $web_display_mode=='index' and $menu_data}>
  <{if "$xoops_rootpath/uploads/tad_web/0/image/`$dirname`.png"|file_exists}>
    <a href="<{$xoops_url}>/modules/tad_web/<{$dirname}>.php"><img src="<{$xoops_url}>/uploads/tad_web/0/image/<{$dirname}>.png" alt="<{$menu.PluginTitle}>"></a>
  <{else}>
    <h3><a href="<{$xoops_url}>/modules/tad_web/menu.php"><{$menu.PluginTitle}></a></h3>
  <{/if}>
<{elseif $web_display_mode=='index_plugin'}>
  <h1><a href="<{$xoops_url}>/modules/tad_web/"><i class="fa fa-home"></i></a> <{$menu.PluginTitle}></h1>
<{elseif $web_display_mode=='home_plugin'}>
  <h1><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$menu.PluginTitle}></h1>
<{/if}>

<{foreach from=$cate_arr item=cate}>
  <{assign var="cid" value=$cate.CateID}>

  <div class="panel panel-primary">
    <div class="panel-heading">
        <{$cate.CateName}>
    </div>

    <{if $cate_data.$cid}>
      <ul  class="list-group">
        <{foreach from=$cate_data.$cid item=m}>
          <li class="list-group-item">
            <a href="<{$m.Link}>" target="<{$m.Target}>">
              <{$m.MenuTitle}>
            </a>
            <{if $m.isMyWeb}>
              <a href="javascript:delete_menu_func(<{$m.MenuID}>);" class="text-danger"><i class="fa fa-trash-o"></i></a>
              <a href="menu.php?WebID=<{$WebID}>&op=edit_form&MenuID=<{$m.MenuID}>"  class="text-warning"><i class="fa fa-pencil"></i></a>
            <{/if}>
          </li>
        <{/foreach}>
      </ul>
    <{/if}>
  </div>

<{/foreach}>

<div style="clear: both;"></div>

<{if $menu_data}>
  <{if $web_display_mode=='index_plugin' or $web_display_mode=='home_plugin'}>
    <{$bar}>
  <{/if}>
<{/if}>

<div style="text-align:right; margin: 0px 0px 10px;">
  <{if $web_display_mode=='index'}>
    <a href="menu.php" class="btn btn-primary <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_MENU_SHORT}></a>
  <{elseif $web_display_mode=='home' or $MenuDefCateID}>
    <a href="menu.php?WebID=<{$WebID}>" class="btn btn-primary <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_MENU_SHORT}></a>
  <{/if}>

  <{if $isMyWeb and $WebID}>
    <a href="menu.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_MENU_SHORT}></a>
  <{/if}>
</div>


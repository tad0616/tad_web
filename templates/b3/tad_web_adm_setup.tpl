
<div class="container-fluid">
  <script type="text/javascript">
    $(document).ready(function() {
      $('#sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
        var order = $(this).sortable('serialize');
        $.post('save_sort.php?op=plugin', order, function(theResponse){
            $('#save_msg').html(theResponse);
        });
      }
      });
    });
  </script>

  <form action="setup.php" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
    <h3>
      <{$smarty.const._MD_TCW_FCNCTION_SETUP}>
    </h3>

    <div id="save_msg"><{$smarty.const._TAD_SORTABLE}></div>

    <table class="table">
      <tr>
        <th><{$smarty.const._MD_TCW_CATE_PLUGIN_ENABLE}></th>
        <th><{$smarty.const._MD_TCW_CATE_PLUGIN_TITLE}></th>
        <th><{$smarty.const._MD_TCW_CATE_PLUGIN_NEW_NAME}></th>
        <th colspan="2"><{$smarty.const._MD_TCW_CATE_PLUGIN_IN_FRONTPAGE}></th>

      </tr>
      <tbody id="sort">
        <{foreach from=$plugins item=plugin}>
          <tr id="tr_<{$plugin.dirname}>">
            <td>
              <{$plugin.dirname}>
            </td>
            <td><{$plugin.config.name}></td>
            <td><input type="text" name="plugin_name[<{$plugin.dirname}>]" value="<{if $plugin.db.PluginTitle}><{$plugin.db.PluginTitle}><{else}><{$plugin.config.name}><{/if}>" class="form-control" style="width: 120px;"></td>
            <td>
              <{if $plugin.config.common==1}>
                <label class="checkbox"><input type="checkbox" name="plugin_display[<{$plugin.dirname}>]" value="1" <{if $web_plugin_display_arr=="" or $plugin.dirname|in_array:$web_plugin_display_arr}>checked="checked"<{/if}>></label>
              <{else}>
                <input type="hidden" name="plugin_display[<{$plugin.dirname}>]" value="0">
              <{/if}>
            </td>
            <td>
              <{if $plugin.config.limit!=''}>
                <input type="text" name="plugin_limit[<{$plugin.dirname}>]" value="<{if $plugin.limit}><{$plugin.limit}><{else}><{$plugin.config.limit}><{/if}>" class="form-control" style="width: 50px;">
              <{else}>
                <input type="hidden" name="plugin_limit[<{$plugin.dirname}>]" value="none">
              <{/if}>
            </td>
          </tr>
        <{/foreach}>
      </tbody>
    </table>

    <div class="form-group">
      <div class="col-sm-12 text-center">
        <input type="hidden" name="op" value="save_plugins">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
      </div>
    </div>

  </form>
</div>
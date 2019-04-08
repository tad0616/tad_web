<h2>匯出設定</h2>
<form action="aboutus.php" method="post" class="form-horizontal" role="form">
  <div class="form-group">
    <label class="col-sm-2 control-label">
      選擇匯出班級
    </label>
    <div class="col-sm-10">
      <{foreach from=$aboutus.cates item=cate}>
        <label class="radio">
          <input type="radio" name="aboutus['CateID']" value="<{$cate.CateID}>"><{$cate.CateName}>
        </label>
      <{/foreach}>
    </div>
  </div>




  <{foreach from=$config_plugin_arr item=plugin}>
    <div class="form-group">
      <label class="col-sm-2 control-label">
        <{$plugin.title}>
      </label>
      <div class="col-sm-10">
        <{foreach from=$plugin.cates item=cate}>
          <label class="checkbox">
            <input type="checkbox" name="<{$plugin.dirname}>['CateID'][]" value="<{$cate.CateID}>"><{$cate.CateName}>
          </label>
          <div>
            <{foreach from=$plugin.content item=content}>
              <label class="checkbox">
                <input type="checkbox" name="<{$content.ID}>['CateID'][]" value="<{$content.ID}>"><{$content.title}>
              </label>
            <{/foreach}>
          </div>
        <{/foreach}>
      </div>
    </div>
  <{/foreach}>
</form>
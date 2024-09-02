<h2><{$smarty.const._MD_TCW_DISCUSS_EXPORT_SETTINGS}></h2>
<form action="aboutus.php" method="post" class="form-horizontal" role="form">
    <div class="form-group row mb-3">
        <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_DISCUSS_SELECT_EXPORT_CLASSES}>
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
        <div class="form-group row mb-3">
            <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label">
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
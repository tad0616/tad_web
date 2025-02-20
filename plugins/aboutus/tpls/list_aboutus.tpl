<{if $WebID=="" and $data}>
    <{if $tad_web_cate|default:false}>
        <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/jqueryCookie/jquery.cookie.js"></script>

        <script type="text/javascript">
            $(function() {
                $("#tad_web_cate_tabs").tabs({
                    active   : $.cookie('activetab'),
                    activate : function( event, ui ){
                        $.cookie( 'activetab', ui.newTab.index(),{
                            expires : 30
                        });
                    }
                });
            });
        </script>

        <div id="tad_web_cate_tabs" class="mb-3">
            <ul>
                <{foreach from=$tad_web_cate key=i item=cate}>
                    <li><a href="<{$xoops_url}>/modules/tad_web/plugins/aboutus/get_webs.php?CateID=<{$cate.CateID}>"><{$cate.CateName}></a></li>
                <{/foreach}>
            </ul>
        </div>
    <{else}>
        <script type="text/javascript">
            $(function() {
                $.get("<{$xoops_url}>/modules/tad_web/plugins/aboutus/get_webs.php", { CateID: ""},
                function(data){
                    $('#list_all_web').html(data);
                });
            });
        </script>
        <h2><{$module_title|default:''}></h2>
        <div id="list_all_web"></div>
    <{/if}>
    <div class="clearfix"></div>
<{elseif $isMyWeb}>
    <h2><{$module_title|default:''}></h2>
    <div class="alert alert-danger">
        <a href="<{$xoops_url}>/modules/tad_web/admin/main.php?op=create_by_user"><{$smarty.const._MD_TCW_NO_WEB_ADMIN}></a>
    </div>
<{else}>
    <h2><{$module_title|default:''}></h2>

    <div class="alert alert-info">
        <{$smarty.const._MD_TCW_NO_WEB}>
    </div>
<{/if}>
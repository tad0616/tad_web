<{if $web_display_mode=='index' and $data}>
    <{if "$xoops_rootpath/uploads/tad_web/0/image/`$dirname`.png"|file_exists}>
        <a href="<{$xoops_url}>/modules/tad_web/<{$dirname|default:''}>.php"><img src="<{$xoops_url}>/uploads/tad_web/0/image/<{$dirname|default:''}>.png" alt="<{$aboutus.PluginTitle}>"></a>
    <{else}>
        <h3><a href="<{$xoops_url}>/modules/tad_web/aboutus.php"><{$aboutus.PluginTitle}></a></h3>
    <{/if}>
<{elseif $web_display_mode=='index_plugin'}>
    <h2><a href="<{$xoops_url}>/modules/tad_web/"><i class="fa fa-home"></i></a> <{$aboutus.PluginTitle}></h2>
<{elseif $web_display_mode=='home_plugin'}>
    <h2><a href="index.php?WebID=<{$WebID|default:''}>"><i class="fa fa-home"></i></a> <{$aboutus.PluginTitle}></h2>
<{/if}>

<{if $WebID=="" and $data}>
    <{if $_IS_EZCLASS and $web_version=="all"}>
        <{include file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/tad_web_common_aboutus_all.tpl"}>
    <{else}>
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
            <div id="list_all_web"></div>
        <{/if}>
        <div class="clearfix"></div>
    <{/if}>

<{elseif $isMyWeb}>
    <div class="alert alert-danger">
        <a href="<{$xoops_url}>/modules/tad_web/admin/main.php?op=create_by_user"><{$smarty.const._MD_TCW_NO_WEB_ADMIN}></a>
    </div>
<{else}>
    <div class="alert alert-info">
        <{$smarty.const._MD_TCW_NO_WEB}>
    </div>
<{/if}>
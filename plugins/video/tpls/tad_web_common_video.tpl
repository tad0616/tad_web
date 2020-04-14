<{if $web_display_mode=='index' and $video_data}>
    <{if "$xoops_rootpath/uploads/tad_web/0/image/`$dirname`.png"|file_exists}>
        <a href="<{$xoops_url}>/modules/tad_web/<{$dirname}>.php"><img src="<{$xoops_url}>/uploads/tad_web/0/image/<{$dirname}>.png" alt="<{$video.PluginTitle}>"></a>
    <{else}>
        <h3><a href="<{$xoops_url}>/modules/tad_web/video.php"><{$video.PluginTitle}></a></h3>
    <{/if}>
<{elseif $web_display_mode=='index_plugin'}>
    <h2><a href="<{$xoops_url}>/modules/tad_web/"><i class="fa fa-home"></i></a> <{$video.PluginTitle}></h2>
<{elseif $web_display_mode=='home_plugin'}>
    <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$video.PluginTitle}></h2>
<{/if}>



<{if $video_data}>
    <{if $isMyWeb}>
        <{$sweet_delete_video_func_code}>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
                    var order = $(this).sortable('serialize');
                    $.post('<{$xoops_url}>/modules/tad_web/plugins/video/save_sort.php', order, function(theResponse){
                        $('#save_msg').html(theResponse);
                    });
                }
                });
            });
        </script>
    <{/if}>

    <div id="save_msg"></div>
    <table class="footable table common_table">
        <thead>
            <tr>
                <th data-hide="phone" style="width:100px;text-align:center;"><{$smarty.const._MD_TCW_VIDEO_SHORT}></th>
                <th data-class="expand"><{$smarty.const._MD_TCW_VIDEONAME}></th>
            </tr>
        </thead>
        <tbody id="sort">
            <{foreach item=video from=$video_data name=v}>
                <tr id="VideoID_<{$video.VideoID}>">
                    <td style="width: 200px;">
                        <a href="video.php?WebID=<{$video.WebID}>&VideoID=<{$video.VideoID}>">
                            <img src="https://i3.ytimg.com/vi/<{$video.VideoPlace}>/0.jpg" class="img-fluid rounded" style="width: 100%;" alt="<{$video.VideoName}>" title="<{$video.VideoName}>">
                        </a>

                        <{if $web_display_mode=="index" or $web_display_mode=="index_plugin"}>
                            <div style="text-align:center; font-size: 75%; margin-top:6px;">
                                <{$video.WebTitle}>
                            </div>
                        <{/if}>
                    </td>

                    <td>
                        <p>
                            <span class="badge badge-warning"><{$smarty.foreach.v.iteration}></span>
                            <{$video.VideoDate}>
                            <{if isset($video.cate.CateID)}>
                                <span class="badge badge-info"><a href="video.php?WebID=<{$video.WebID}>&CateID=<{$video.cate.CateID}>" style="color: #FFFFFF;"><{$video.cate.CateName}></a></span>
                            <{/if}>
                            <a href="video.php?WebID=<{$video.WebID}>&VideoID=<{$video.VideoID}>" style="font-size: 120%; margin-bottom: 10px;"><{$video.VideoName}></a>
                            <{if $video.isMyWeb or $video.isAssistant}>
                                <a href="javascript:delete_video_func(<{$video.VideoID}>);" class="text-danger"><i class="fa fa-trash-o"></i></a>
                                <a href="video.php?WebID=<{$video.WebID}>&op=edit_form&VideoID=<{$video.VideoID}>" class="text-warning"><i class="fa fa-pencil"></i></a>
                            <{/if}>
                        </p>
                        <p style="line-height: 1.6;"><{$video.VideoDesc}></p>
                    </td>
                </tr>
            <{/foreach}>
        </tbody>
    </table>

    <{if $video_data}>
        <{if $web_display_mode=='index_plugin' or $web_display_mode=='home_plugin'}>
            <{$bar}>
        <{/if}>
    <{/if}>

    <div style="text-align:right; margin: 0px 0px 10px;">
        <{if $web_display_mode=='index'}>
            <a href="video.php" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_VIDEO_SHORT}></a>
        <{elseif $web_display_mode=='home' or $VideoDefCateID}>
            <a href="video.php?WebID=<{$WebID}>" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_VIDEO_SHORT}></a>
        <{/if}>

        <{if $isMyWeb and $WebID}>
            <a href="video.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm btn-xs<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_VIDEO_SHORT}></a>
        <{/if}>
    </div>
<{/if}>
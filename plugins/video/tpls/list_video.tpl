<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
  <{if $isMyWeb}>
    <{$sweet_delete_video_func_code}>
  <{/if}>
  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>

  <{if $bc.display=="thumb"}>
    <{foreach item=video from=$bc.main_data}>
      <div style="width: 156px; height: 240px; float:left; margin: 5px 2px; overflow: hidden;">
        <a href='video.php?WebID=<{$video.WebID}>&VideoID=<{$video.VideoID}>'>
          <div style="width: 150px; height: 160px; background-color: #F1F7FF ; border:1px dotted green; margin: 0px auto;">
          <div style="width: 140px; height: 140px; background: #F1F7FF url('http://i3.ytimg.com/vi/<{$video.VideoPlace}>/0.jpg') center center no-repeat; border:8px solid #F1F7FF; margin: 0px auto;background-size:cover;">
          </div>
          </div>
        </a>
        <div class="text-center" style="margin: 8px auto;">
          <a href='video.php?WebID=<{$video.WebID}>&VideoID=<{$video.VideoID}>'><{$video.VideoName}></a>
          <{if $video.isMyWeb or $video.isAssistant}>
            <a href="javascript:delete_video_func(<{$video.VideoID}>);" class="text-danger"><i class="fa fa-trash-o"></i></a>
            <a href="video.php?WebID=<{$WebID}>&op=edit_form&VideoID=<{$video.VideoID}>"  class="text-warning"><i class="fa fa-pencil"></i></a>
          <{/if}>
        </div>
      </div>
    <{/foreach}>
  <{else}>
    <table class="footable table common_table">
      <thead>
        <tr>
          <th data-class="expand" style="text-align:center;">
            <{$smarty.const._MD_TCW_VIDEODATE}>
          </th>
          <th data-hide="phone">
            <{$smarty.const._MD_TCW_VIDEONAME}>
          </th>
        </tr>
      </thead>
      <{foreach item=video from=$bc.main_data}>
        <tr>
          <td style="width: 200px;">
            <a href="video.php?WebID=<{$video.WebID}>&VideoID=<{$video.VideoID}>">
              <img src="http://i3.ytimg.com/vi/<{$video.VideoPlace}>/0.jpg" class="img-responsive img-rounded" style="width: 100%;" alt="<{$video.VideoName}>" title="<{$video.VideoName}>">
            </a>
          </td>

          <td>
            <p>
              <{$video.VideoDate}>
              <{if isset($video.cate.CateID)}>
                <span class="label label-info"><a href="video.php?WebID=<{$video.WebID}>&CateID=<{$video.cate.CateID}>" style="color: #FFFFFF;"><{$video.cate.CateName}></a></span>
              <{/if}>
              <a href="video.php?WebID=<{$video.WebID}>&VideoID=<{$video.VideoID}>" style="font-size: 120%; margin-bottom: 10px;"><{$video.VideoName}></a>
              <{if $video.isMyWeb or $video.isAssistant}>
                <a href="javascript:delete_video_func(<{$video.VideoID}>);" class="text-danger"><i class="fa fa-trash-o"></i></a>
                <a href="video.php?WebID=<{$video.WebID}>&op=edit_form&VideoID=<{$video.VideoID}>" class="text-warning"><i class="fa fa-pencil"></i></a>
              <{/if}>
            </p>
            <p style="line-height: 1.6;">
              <{$video.VideoDesc}>
            </p>
         </td>
        </tr>
      <{/foreach}>
    </table>
  <{/if}>
<{/if}>
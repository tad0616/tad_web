<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
  <div style="margin-bottom:15px;"></div>
  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
  <!-- start feedwind code -->
  <script type="text/javascript">
    document.write('\x3Cscript type="text/javascript" src="' + ('https:' == document.location.protocol ? 'https://' : 'http://') + 'feed.mikle.com/js/rssmikle.js">\x3C/script>');
  </script>
  <script type="text/javascript">
    (function() {
      var params = {
        <{if $bc.config.feed_url!=""}>
        rssmikle_url: "<{$bc.config.feed_url}>",
        <{else}>
        rssmikle_url: "<{$xoops_url}>/modules/tad_web/rss.php?WebID=<{$bc.WebID}>",
        <{/if}>
        rssmikle_frame_width: "300",
        rssmikle_frame_height: "<{$bc.config.height}>",
        frame_height_by_article: "0",
        rssmikle_target: "_blank",
        rssmikle_font: "Arial, Helvetica, sans-serif",
        rssmikle_font_size: "12",
        rssmikle_border: "off",
        responsive: "on",
        rssmikle_css_url: "",
        text_align: "left",
        text_align2: "left",
        corner: "off",
        scrollbar: "on",
        autoscroll: "on",
        scrolldirection: "up",
        scrollstep: "5",
        mcspeed: "20",
        sort: "Off",
        rssmikle_title: "on",
        rssmikle_title_sentence: "",
        rssmikle_title_link: "",
        rssmikle_title_bgcolor: "#0066FF",
        rssmikle_title_color: "#FFFFFF",
        rssmikle_title_bgimage: "",
        rssmikle_item_bgcolor: "#FFFFFF",
        rssmikle_item_bgimage: "",
        rssmikle_item_title_length: "90",
        rssmikle_item_title_color: "#0066FF",
        rssmikle_item_border_bottom: "on",
        rssmikle_item_description: "<{$bc.config.display_rss}>",
        item_link: "on",
        rssmikle_item_description_length: "150",
        rssmikle_item_description_color: "#666666",
        rssmikle_item_date: "gl1",
        rssmikle_timezone: "Etc/GMT-8",
        datetime_format: "%b %e, %Y %l:%M %p",
        item_description_style: "text+tn",
        item_thumbnail: "full",
        item_thumbnail_selection: "auto",
        article_num: "15",
        rssmikle_item_podcast: "off",
        keyword_inc: "",
        keyword_exc: ""
      };
      feedwind_show_widget_iframe(params);
    })();
  </script>
  <div class="row">
    <div class="col-md-7">
      <a href="<{if $bc.config.feed_url!=""}><{$bc.config.feed_url}><{else}><{$xoops_url}>/modules/tad_web/rss.php?WebID=<{$bc.WebID}><{/if}>" target="_blank" style="font-size: 68.75%; "><img src="<{$xoops_url}>/modules/tad_web/plugins/news/images/rss.png" alt="rss" style="float:left; margin-right:3px;"><{$smarty.const._MD_TCW_NEWS_BLOCK_RSS_LINK}></a>
    </div>
    <div class="col-md-5 text-right">
      <a href="http://feed.mikle.com/" target="_blank" style="color:#CCCCCC;font-size: 62.5%;">RSS Feed Widget</a>
    </div>
    <!--Please display the above link in your web page according to Terms of Service.-->
  </div>
  <!-- end feedwind code --><!--  end  feedwind code -->
<{/if}>

<{assign var="bc" value=$block.BlockContent}>
<{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>

<link href="<{$xoops_url}>/modules/tad_web/class/Countdown/timeTo.css" rel="stylesheet" />
<script src="<{$xoops_url}>/modules/tad_web/class/Countdown/jquery.time-to.js"></script>

<div class="text-center">
  <div style="font-size: 1.5em; margin-bottom:10px;"><{$bc.config.countdown_title}></div>
  <div id="countdown_<{$bc.randStr}>"></div>
</div>

<script type="text/javascript">
  $(function () {
    $('#countdown_<{$bc.randStr}>').timeTo({
        timeTo: new Date(new Date('<{$bc.config.countdown_date}>')),
        displayDays: 3,
        theme: "black",
        displayCaptions: true,
        fontSize: 22,
        captionSize: 12,
        lang:"zh"
    });

  });
</script>
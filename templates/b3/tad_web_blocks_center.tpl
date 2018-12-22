<{if $web_display_mode=="home"}>
  <{if $plugin_data_total==0 and !$center_block1 and !$center_block2 and !$center_block3 and !$center_block4 and !$center_block5 and !$center_block6}>
    <div class="row">
      <div class="col-sm-12">
        <div class="text-center">
          <img src="<{$xoops_url}>/modules/tad_web/images/empty.png" alt="coming soon" >
        </div>
      </div>
    </div>
  <{else}>
    <{if $center_block1}>
      <div class="row">
        <div class="col-sm-12">
          <{foreach from=$center_block1 item=block}>
            <div class="tad_web_block">
              <!-- <{$block.BlockTitle}> -->
              <{if $block.plugin=="xoops"}>
                <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
                <{block id=$block.BlockName}>
              <{elseif $block.plugin=="custom"}>
                <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_custom.tpl"}>
              <{else}>
                <{if "$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/b3/`$block.tpl`"|file_exists}>
                  <{includeq file="$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/b3/`$block.tpl`"}>
                <{/if}>
              <{/if}>
              <{if $isMyWeb and $WebID and isset($block.BlockContent.main_data) and $block.BlockContent.main_data}>
                <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_tool.tpl"}>
              <{/if}>
            </div>
          <{/foreach}>
        </div>
      </div>
    <{/if}>

    <{if $center_block2 or $center_block3}>
      <div class="row">
        <{if $center_block2}>
          <div class="col-sm-6">
            <{foreach from=$center_block2 item=block}>
              <div class="tad_web_block">
                <!-- <{$block.BlockTitle}> -->
                <{if $block.plugin=="xoops"}>
                  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
                  <{block id=$block.BlockName}>
                <{elseif $block.plugin=="custom"}>
                  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_custom.tpl"}>
                <{else}>
                  <{if "$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/b3/`$block.tpl`"|file_exists}>
                    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/b3/`$block.tpl`"}>
                  <{/if}>
                <{/if}>
                <{if $isMyWeb and $WebID and isset($block.BlockContent.main_data) and $block.BlockContent.main_data}>
                  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_tool.tpl"}>
                <{/if}>
              </div>
            <{/foreach}>
          </div>
        <{/if}>

        <{if $center_block3}>
          <div class="col-sm-6">
            <{foreach from=$center_block3 item=block}>
              <div class="tad_web_block">
                <!-- <{$block.BlockTitle}> -->
                <{if $block.plugin=="xoops"}>
                  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
                  <{block id=$block.BlockName}>
                <{elseif $block.plugin=="custom"}>
                  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_custom.tpl"}>
                <{else}>
                  <{if "$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/b3/`$block.tpl`"|file_exists}>
                    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/b3/`$block.tpl`"}>
                  <{/if}>
                <{/if}>
                <{if $isMyWeb and $WebID and isset($block.BlockContent.main_data) and $block.BlockContent.main_data}>
                  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_tool.tpl"}>
                <{/if}>
              </div>
            <{/foreach}>
          </div>
        <{/if}>
      </div>
    <{/if}>

    <{if $center_block4}>
      <div class="row">
        <div class="col-sm-12">
          <{foreach from=$center_block4 item=block}>
            <div class="tad_web_block">
              <!-- <{$block.BlockTitle}> -->
              <{if $block.plugin=="xoops"}>
                <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
                <{block id=$block.BlockName}>
              <{elseif $block.plugin=="custom"}>
                <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_custom.tpl"}>
              <{else}>
                <{if "$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/b3/`$block.tpl`"|file_exists}>
                  <{includeq file="$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/b3/`$block.tpl`"}>
                <{/if}>
              <{/if}>
              <{if $isMyWeb and $WebID and isset($block.BlockContent.main_data) and $block.BlockContent.main_data}>
                <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_tool.tpl"}>
              <{/if}>
            </div>
          <{/foreach}>
        </div>
      </div>
    <{/if}>

    <{if $center_block5 or $center_block6}>
      <div class="row">
        <{if $center_block5}>
          <div class="col-sm-6">
            <{foreach from=$center_block5 item=block}>
              <div class="tad_web_block">
                <!-- <{$block.BlockTitle}> -->
                <{if $block.plugin=="xoops"}>
                  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
                  <{block id=$block.BlockName}>
                <{elseif $block.plugin=="custom"}>
                  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_custom.tpl"}>
                <{else}>
                  <{if "$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/b3/`$block.tpl`"|file_exists}>
                    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/b3/`$block.tpl`"}>
                  <{/if}>
                <{/if}>
                <{if $isMyWeb and $WebID and isset($block.BlockContent.main_data) and $block.BlockContent.main_data}>
                  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_tool.tpl"}>
                <{/if}>
              </div>
            <{/foreach}>
          </div>
        <{/if}>
        <{if $center_block6}>
          <div class="col-sm-6">
            <{foreach from=$center_block6 item=block}>
              <div class="tad_web_block">
                <!-- <{$block.BlockTitle}> -->
                <{if $block.plugin=="xoops"}>
                  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
                  <{block id=$block.BlockName}>
                <{elseif $block.plugin=="custom"}>
                  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_custom.tpl"}>
                <{else}>
                  <{if "$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/b3/`$block.tpl`"|file_exists}>
                    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/`$block.plugin`/tpls/b3/`$block.tpl`"}>
                  <{/if}>
                <{/if}>
                <{if $isMyWeb and $WebID and isset($block.BlockContent.main_data) and $block.BlockContent.main_data}>
                  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_tool.tpl"}>
                <{/if}>
              </div>
            <{/foreach}>
          </div>
        <{/if}>
      </div>
    <{/if}>

  <{/if}>
<{else}>
  <{$xoops_contents}>

<{/if}>
<{if $block.op=="login"}>
  <{if $LoginMemNickName==""}>
    <script type="text/javascript">
      $(document).ready(function() {
        $("#tad_web_login").hide();
        $("#stu_login").click(function() {
          var x = document.getElementById("stu_login").checked;
          if(x){
            $("#tad_web_login").show();
            $("#xoops_login").hide();
            $("#open_login").hide();
          }else{
            $("#tad_web_login").hide();
            $("#xoops_login").show();
            $("#open_login").show();
          }
        });
      });
    </script>

    <{if $block.DefWebID}>
      <h3><{$smarty.const._MB_TCW_LOGIN}></h3>
    <{/if}>

    <div class="card card-body bg-light m-1">
      <form action="<{$xoops_url}>/user.php" method="post" role="form" id="xoops_login">
        <div class="form-group row">
          <label class="col-md-4 col-form-label text-sm-right" for="uname">
            <{$smarty.const._MB_TCW_ID}>
          </label>
          <div class="col-md-8">
            <input type="text" name="uname"  id="uname" placeholder="<{$smarty.const.TF_USER_ID}>"  class="form-control">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label text-sm-right" for="pass">
            <{$smarty.const._MB_TCW_PASSWD}>
          </label>
          <div class="col-md-8">
          <input type="password" name="pass" id="pass" placeholder="<{$smarty.const.TF_USER_PASS}>" class="form-control">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label text-sm-right">
          </label>
          <div class="col-md-8">
            <input type="hidden" name="xoops_redirect" value="<{$xoops_requesturi}>">
            <input type="hidden" name="rememberme" value="On">
            <input type="hidden" name="op" value="login">
            <button type="submit" class="btn btn-primary btn-block"><{$smarty.const.TF_USER_ENTER}></button>
          </div>
        </div>
      </form>

      <form action="aboutus.php" method="post" enctype="multipart/form-data" role="form" id="tad_web_login">
        <div class="form-group row">
          <label class="col-md-4 col-form-label text-sm-right">
            <{$smarty.const._MB_TCW_MEM_UNAME}>
          </label>
          <div class="col-md-8 controls">
            <input class="form-control" type="text" name="MemUname" placeholder="<{$smarty.const._MB_TCW_PLEASE_INPUT}><{$smarty.const._MB_TCW_MEM_UNAME}>">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-4 col-form-label text-sm-right">
            <{$smarty.const._MB_TCW_MEM_PASSWD}>
          </label>
          <div class="col-md-8 controls">
            <input class="form-control" type="password" name="MemPasswd" placeholder="<{$smarty.const._MB_TCW_PLEASE_INPUT}><{$smarty.const._MB_TCW_MEM_PASSWD}>">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label text-sm-right">
          </label>
          <div class="col-md-8">
            <input type="hidden" name="WebID" value="<{$WebID}>">
            <input type="hidden" name="op" value="mem_login">
            <button type="submit" class="btn btn-success btn-block"><{$smarty.const._MB_TCW_LOGIN}></button>
          </div>
        </div>
      </form>

      <div class="text-center">
        <label class="checkbox" for="stu_login">
          <input type="checkbox" name="stu_login" id="stu_login" value="stu_login">
          <{$smarty.const._MB_TCW_IM_MEM}>
        </label>
      </div>
      <div id="open_login">
        <{foreach from=$block.tlogin item=login}>
          <a href="<{$login.link}>" style="padding: 5px; margin: 5px; display: inline-block;">
            <img src="<{$login.img}>" alt="<{$login.text}>" title="<{$login.text}>">
          </a>
        <{/foreach}>
      </div>
    </div>
  <{/if}>
<{elseif $block.op=="mem"}>
  <div class="row">
    <div class="col-md-12">
      <div class="card card-body bg-light m-1 card card-body bg-light m-1-small">

        <{$LoginMemName}>
        <{if $LoginMemNickName!=""}> (<{$LoginMemNickName}>) <{/if}>
        <{$smarty.const._MD_TCW_HELLO}>

        <div class="row">
          <div class="col-md-6">
            <a href="<{$xoops_url}>/modules/tad_web/aboutus.php?op=mem_logout&WebID=<{$WebID}>" class="btn btn-<{$mini}> btn-warning btn-block"><{$smarty.const._MD_TCW_EXIT}></a>
          </div>
          <div class="col-md-6">
            <a href="<{$xoops_url}>/modules/tad_web/discuss.php?WebID=<{$LoginWebID}>&op=edit_form" class="btn btn-<{$mini}> btn-info btn-block"><{$smarty.const._MD_TCW_DISCUSS_ADD}></a>
          </div>
        </div>

      </div>
    </div>
  </div>
<{else}>
  <script type="text/javascript" src="<{$xoops_url}>/modules/tad_web/class/bootstrap-progressbar/bootstrap-progressbar.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('.progress .progress-bar').progressbar({display_text: 'fill'});
    });
  </script>
  <{if $block.DefWebID}>
    <h3><{$block.WebTitle}><small><{$smarty.const._MB_TCW_MENU}></small></h3>
  <{/if}>
  <{if $block.defaltWebID}>
    <div class="row">
      <div class="col-md-12">
        <{if $block.web_num > 1}>
          <select class="span12 form-control" onChange="location.href=this.value">
            <{foreach from=$block.webs item=web}>
              <option value="<{$web.url}>" <{if $web.WebID==$WebID}>selected<{/if}>><{$web.title}> (<{$web.name}>)</option>
            <{/foreach}>
          </select>
        <{/if}>
        <div style="margin:10px 0px;">
          <a href="<{$xoops_url}>/modules/tad_web/index.php?WebID=<{$block.defaltWebID}>">
            <i class="fa fa-home"></i>
            <{$block.back_home}>
          </a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 text-center">
        <div class="btn-group">
          <a href="<{$xoops_url}>/modules/tad_web/config.php?WebID=<{$block.defaltWebID}>" class="btn btn-success">
            <i class="fa fa-check-square-o"></i>
            <{$smarty.const._MB_TCW_WEB_CONFIG}>
          </a>
          <a href="<{$xoops_url}>/modules/tad_web/block.php?WebID=<{$block.defaltWebID}>" class="btn btn-info">
            <i class="fa fa-check-square-o"></i>
            <{$smarty.const._MB_TCW_WEB_BLOCK_CONFIG}>
          </a>
          <a href="<{$xoops_url}>/modules/tad_web/block.php?WebID=<{$block.defaltWebID}>&op=add_block" class="btn btn-info" title="<{$smarty.const._MB_TCW_BLOCK_ADD}>">
            <i class="fa fa-plus"></i>
          </a>
        </div>
      </div>
    </div>

    <table class="table">
      <{foreach from=$block.plugins item=plugin}>
        <{if $plugin.menu=='1'}>
          <{if $plugin.enable=='1'}>
            <tr>
              <td>
                <a href="<{$xoops_url}>/modules/tad_web/<{$plugin.url}>">
                  <i class="fa <{$plugin.icon}>"></i>
                  <{$plugin.title}>
                </a>
              </td>


              <td>
                <{if $plugin.setup=='1'}>
                  <a href="<{$xoops_url}>/modules/tad_web/setup.php?WebID=<{$plugin.WebID}>&plugin=<{$plugin.dirname}>&op=plugin_setup" title="<{$smarty.const._MB_TCW_SETUP}><{$plugin.short}>">
                    <i class="fa fa-wrench"></i>
                  </a>
                <{/if}>
              </td>

              <td>
                <{if $plugin.add=='1'}>
                  <a href="<{$xoops_url}>/modules/tad_web/<{$plugin.url}>&op=edit_form" title="<{$smarty.const._MB_TCW_ADD}><{$plugin.short}>">
                    <i class="fa fa-plus"></i>
                  </a>
                <{/if}>
              </td>
              <td>
                <{if $plugin.cate=='1'}>
                  <a href="<{$xoops_url}>/modules/tad_web/cate.php?WebID=<{$plugin.WebID}>&ColName=<{$plugin.dirname}>&table=<{$plugin.cate_table}>"  title="<{$plugin.short}><{$smarty.const._MB_TCW_CATE_TOOLS}>">
                    <i class="fa fa-folder-open"></i>
                  </a>
                <{/if}>

              </td>
            </tr>
          <{else}>
            <tr style="background-color: #dfdfdf;">
              <td style="background-color: #dfdfdf; color:#5f5f5f;">
                  <i class="fa <{$plugin.icon}>"></i>
                  <{$plugin.title}>
              </td>
              <td colspan=3 style="background-color: #dfdfdf;">
                <a href="<{$xoops_url}>/modules/tad_web/config.php?WebID=<{$plugin.WebID}>&dirname=<{$plugin.dirname}>&op=enabe_plugin" class="btn btn-primary btn-sm"><{$smarty.const._MB_TCW_ENABLE_PLUGIN}></a>
              </td>
            </tr>
          <{/if}>
        <{/if}>
      <{/foreach}>
    </table>

    <div class="progress progress-striped">
      <div class="progress-bar progress-bar-<{$block.progress_color}>" role="progressbar" data-transitiongoal="<{$block.percentage}>"></div>
    </div>
    已使用空間：<{$block.size}>MB/<{$block.quota}>MB (<{$block.percentage}>%)

  <{/if}>

  <{if $block.closed_webs}>
    <div class="row">
      <div class="col-md-12">
        <{foreach from=$block.closed_webs item=web}>
          <a href="<{$web.url}>" class="btn btn-secondary btn-block"><{$smarty.const._MB_TCW_ENABLE}> <{$web.name}></a>
        <{/foreach}>
      </div>
    </div>
  <{/if}>
  <div class="row">
    <div class="col-md-12">
      <a href="<{$xoops_url}>/modules/tad_web/aboutus.php?op=mem_logout&WebID=<{$WebID}>" class="btn btn-danger btn-block"><i class="fa fa-sign-out"></i> <{$smarty.const.TF_USER_EXIT}></a>
    </div>
  </div>

<{/if}>
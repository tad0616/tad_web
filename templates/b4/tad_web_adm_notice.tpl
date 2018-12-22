<link href="<{$xoops_url}>/modules/tadtools/css/font-awesome/css/font-awesome.css" rel="stylesheet">
<div class="container-fluid">
  <h2><{$smarty.const._MA_TCW_WEB_NOTICE}></h2>
<!--顯示表單-->
<{if $now_op=="tad_web_notice_form"}>


  <div class="container">
    <!--套用formValidator驗證機制-->
    <{$formValidator_code}>
    <form action="<{$action}>" method="post" id="myForm" enctype="multipart/form-data" role="form">


      <!--通知標題-->
      <div class="form-group row">
        <label class="col-md-2 col-form-label text-sm-right">
          <{$smarty.const._MA_TADWEB_NOTICETITLE}>
        </label>
        <div class="col-md-6">
          <input type="text" name="NoticeTitle" id="NoticeTitle" class="form-control validate[required]" value="<{$NoticeTitle}>" placeholder="<{$smarty.const._MA_TADWEB_NOTICETITLE}>">
        </div>
      </div>

      <!--通知內容-->
      <div class="form-group row">
        <label class="col-md-2 col-form-label text-sm-right">
          <{$smarty.const._MA_TADWEB_NOTICECONTENT}>
        </label>
        <div class="col-md-6">
          <{$NoticeContent_editor}>
        </div>
      </div>

      <!--通知網站-->
      <!--div class="form-group row">
        <label class="col-md-2 col-form-label text-sm-right">
          <{$smarty.const._MA_TADWEB_NOTICEWEB}>
        </label>
        <div class="col-md-6">
          <textarea name="NoticeWeb" rows=8 id="NoticeWeb" class="form-control " placeholder="<{$smarty.const._MA_TADWEB_NOTICEWEB}>"><{$NoticeWeb}></textarea>
        </div>
      </div-->

      <!--通知對象-->
      <div class="form-group row">
        <label class="col-md-2 col-form-label text-sm-right">
          <{$smarty.const._MA_TADWEB_NOTICEWHO}>
        </label>
        <div class="col-md-10">

        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="NoticeWho[]" id="NoticeWho_def0" value="master" <{if 'master'|in_array:$NoticeWho}>checked="checked"<{/if}>>
          <label class="form-check-label" for="NoticeWho_def0"><{$smarty.const._MA_TADWEB_NOTICEWHO_DEF0}></label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="NoticeWho[]" id="NoticeWho_def1" value="mem" <{if 'mem'|in_array:$NoticeWho}>checked="checked"<{/if}>>
          <label class="form-check-label" for="NoticeWho_def1"><{$smarty.const._MA_TADWEB_NOTICEWHO_DEF1}></label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="NoticeWho[]" id="NoticeWho_def2" value="parent" <{if 'parent'|in_array:$NoticeWho}>checked="checked"<{/if}>>
          <label class="form-check-label" for="NoticeWho_def2"><{$smarty.const._MA_TADWEB_NOTICEWHO_DEF2}></label>
        </div>
        </div>
      </div>

      <div class="text-center">

      <!--通知編號-->
      <input type='hidden' name="NoticeID" value="<{$NoticeID}>">

        <{$token_form}>

        <input type="hidden" name="op" value="<{$next_op}>">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
      </div>
    </form>
  </div>
<{/if}>


<!--顯示某一筆資料-->
<{if $now_op=="show_one_tad_web_notice"}>
  <{if $isAdmin}>
    <{$delete_tad_web_notice_func}>
  <{/if}>
  <h2 class="text-center"><{$NoticeTitle}></h2>


  <!--通知內容-->
  <div class="row">
    <label class="col-md-3 text-right">
      <{$smarty.const._MA_TADWEB_NOTICECONTENT}>
    </label>
    <div class="col-md-9">

      <div class="card card-body bg-light m-1">
        <{$NoticeContent}>
      </div>
    </div>
  </div>

  <!--通知網站-->
  <!--div class="row">
    <label class="col-md-3 text-right">
      <{$smarty.const._MA_TADWEB_NOTICEWEB}>
    </label>
    <div class="col-md-9">

      <div class="card card-body bg-light m-1">
        <{$NoticeWeb}>
      </div>
    </div>
  </div-->

  <!--通知對象-->
  <div class="row">
    <label class="col-md-3 text-right">
      <{$smarty.const._MA_TADWEB_NOTICEWHO}>
    </label>
    <div class="col-md-9">
      <{$NoticeWho}>
    </div>
  </div>

  <!--通知日期-->
  <div class="row">
    <label class="col-md-3 text-right">
      <{$smarty.const._MA_TADWEB_NOTICEDATE}>
    </label>
    <div class="col-md-9">
      <{$NoticeDate}>
    </div>
  </div>

  <div class="text-right">
    <{if $isAdmin}>
      <a href="javascript:delete_tad_web_notice_func(<{$NoticeID}>);" class="btn btn-danger"><{$smarty.const._TAD_DEL}></a>
      <a href="<{$xoops_url}>/modules/tad_web/admin/notice.php?op=tad_web_notice_form&NoticeID=<{$NoticeID}>" class="btn btn-warning"><{$smarty.const._TAD_EDIT}></a>
      <a href="<{$xoops_url}>/modules/tad_web/admin/notice.php?op=tad_web_notice_form" class="btn btn-primary"><{$smarty.const._TAD_ADD}></a>
    <{/if}>
    <a href="<{$action}>" class="btn btn-success"><{$smarty.const._TAD_HOME}></a>
  </div>
<{/if}>

<!--列出所有資料-->

<{if $now_op=="list_tad_web_notice"}>
  <{if $all_content}>
    <{if $isAdmin}>
      <{$delete_tad_web_notice_func}>

    <{/if}>

    <div id="tad_web_notice_save_msg"></div>

    <table class="table table-striped table-hover">
      <thead>
        <tr>

          <th>
            <!--通知標題-->
            <{$smarty.const._MA_TADWEB_NOTICETITLE}>
          </th>
          <th>
            <!--通知對象-->
            <{$smarty.const._MA_TADWEB_NOTICEWHO}>
          </th>
          <th>
            <!--通知日期-->
            <{$smarty.const._MA_TADWEB_NOTICEDATE}>
          </th>
          <{if $isAdmin}>
            <th><{$smarty.const._TAD_FUNCTION}></th>
          <{/if}>
        </tr>
      </thead>

      <tbody id="tad_web_notice_sort">
        <{foreach from=$all_content item=data}>
          <tr id="tr_<{$data.NoticeID}>">

            <td>
              <!--通知標題-->
              <a href="<{$action}>?NoticeID=<{$data.NoticeID}>"><{$data.NoticeTitle}></a>
            </td>

            <td>
              <!--通知對象-->
              <{$data.NoticeWho}>
            </td>

            <td>
              <!--通知日期-->
              <{$data.NoticeDate}>
            </td>

            <{if $isAdmin}>
              <td>
                <a href="javascript:delete_tad_web_notice_func(<{$data.NoticeID}>);" class="btn btn-sm btn-danger"><{$smarty.const._TAD_DEL}></a>
                <a href="<{$xoops_url}>/modules/tad_web/admin/notice.php?op=tad_web_notice_form&NoticeID=<{$data.NoticeID}>" class="btn btn-sm btn-warning"><{$smarty.const._TAD_EDIT}></a>
                <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;margin:0px 4px;" alt="<{$smarty.const._TAD_SORTABLE}>" title="<{$smarty.const._TAD_SORTABLE}>">
              </td>
            <{/if}>
          </tr>
        <{/foreach}>
      </tbody>
    </table>


    <{if $isAdmin}>
      <div class="text-right">
        <a href="<{$xoops_url}>/modules/tad_web/admin/notice.php?op=tad_web_notice_form" class="btn btn-info"><{$smarty.const._TAD_ADD}></a>
      </div>
    <{/if}>

    <{$bar}>
  <{else}>
    <{if $isAdmin}>
      <div class="jumbotron text-center">
        <a href="<{$xoops_url}>/modules/tad_web/admin/notice.php?op=tad_web_notice_form" class="btn btn-info"><{$smarty.const._TAD_ADD}></a>
      </div>
    <{/if}>
  <{/if}>
<{/if}>
</div>
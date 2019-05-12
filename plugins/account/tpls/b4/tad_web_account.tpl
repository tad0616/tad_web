<{if $op=="edit_form"}>




  <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>

  <h2><{$smarty.const._MD_TCW_ACCOUNT_ADD}></h2>
  <div class="card card-body bg-light m-1">
    <form action="account.php" method="post" id="myForm" enctype="multipart/form-data" role="form">

      <!--分類-->
      <{$cate_menu_form}>

      <!--帳目日期-->
      <div class="form-group row">

        <label class="col-md-2 col-form-label text-sm-right">
          <{$smarty.const._MD_TCW_ACCOUNT_DATE}>
        </label>
        <div class="col-md-3">
          <input type="text" name="AccountDate" class="form-control" value="<{$AccountDate}>" id="AccountDate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d'})">
        </div>

      <!--帳目名稱-->
        <div class="col-md-7">
          <input type="text" name="AccountTitle" value="<{$AccountTitle}>" id="AccountTitle" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_ACCOUNT_TITLE}>">
        </div>
      </div>

      <div class="row">
        <div class="col-md-5">

          <div class="form-group row">
            <!--種類-->
            <label class="col-md-5 col-form-label text-sm-right">
              <{$smarty.const._MD_TCW_ACCOUNT_MONEY}>
            </label>
            <div class="col-md-7">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="AccountKind" id="AccountIncome" value="AccountIncome" class="validate[required]" <{if $AccountIncome}>checked<{/if}>>
                <label class="form-check-label" for="AccountIncome"><{$smarty.const._MD_TCW_ACCOUNT_INCOME}></label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="AccountKind" id="AccountOutgoings" value="AccountOutgoings" class="validate[required]" <{if $AccountOutgoings}>checked<{/if}>>
                <label class="form-check-label" for="AccountOutgoings"><{$smarty.const._MD_TCW_ACCOUNT_OUTGOINGS}></label>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <!--金額-->
            <label class="col-md-5 col-form-label text-sm-right">
            </label>
            <div class="col-md-7">
              <input type="text" name="AccountMoney" class="validate[required] form-control" value="<{$AccountMoney}>" id="AccountMoney" >
            </div>
          </div>


        </div>
        <div class="col-md-7">
          <!--帳目說明-->
          <div class="form-group row">
            <div class="col-md-12">
              <textarea name="AccountDesc"  rows=3 id="AccountDesc"  class="form-control" placeholder="<{$smarty.const._MD_TCW_ACCOUNT_DESC}>"><{$AccountDesc}></textarea>
            </div>
          </div>

        </div>
      </div>


      <!--上傳圖檔-->
      <div class="form-group row">
        <label class="col-md-2 col-form-label text-sm-right">
          <{$smarty.const._MD_TCW_ACCOUNT_UPLOAD}>
        </label>
        <div class="col-md-10">
          <{$upform}>
        </div>
      </div>

      <{$power_form}>



      <div class="form-group row">
        <div class="col-md-12 text-center">

          <!--帳目編號-->
          <input type="hidden" name="AccountID" value="<{$AccountID}>">
          <!--所屬團隊-->
          <input type="hidden" name="WebID" value="<{$WebID}>">
          <input type="hidden" name="op" value="<{$next_op}>">
          <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
      </div>
    </form>
  </div>
<{elseif $op=="show_one"}>
  <h2><{$AccountTitle}></h2>

  <ol class="breadcrumb">
    <li><a href="account.php?WebID=<{$WebID}>"><{$smarty.const._MD_TCW_ACCOUNT}></a></li>
    <{if isset($cate.CateID)}><li><a href="account.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a></li><{/if}>
    <li><{$AccountInfo}></li>
    <{if $tags}><li><{$tags}></li><{/if}>
  </ol>

  <div class="row" style="margin:10px 0px;">
    <div class="col-md-2"><{$smarty.const._MD_TCW_ACCOUNT_DATE}></div>
    <div class="col-md-4"><{$AccountDate}></div>
  </div>

  <{if $AccountIncome}>
    <div class="row" style="margin:10px 0px;">
      <div class="col-md-2"><{$smarty.const._MD_TCW_ACCOUNT_INCOME}></div>
      <div class="col-md-4"><{$AccountIncome}></div>
    </div>
  <{/if}>

  <{if $AccountOutgoings}>
    <div class="row" style="margin:10px 0px;">
      <div class="col-md-2"><{$smarty.const._MD_TCW_ACCOUNT_OUTGOINGS}></div>
      <div class="col-md-4"><{$AccountOutgoings}></div>
    </div>
  <{/if}>


  <div class="row">
    <{if $AccountDesc}>
      <div class="col-md-6">
        <div class="alert alert-info" style="line-height: 1.8; font-size: 120%;"><{$AccountDesc}></div>
      </div>
    <{/if}>
    <{if $pics}>
    <div class="col-md-6">
      <{$pics}>
    </div>
    <{/if}>
  </div>

  <{$fb_comments}>

  <{if $isMyWeb or $isCanEdit}>
    <div class="text-right" style="margin: 30px 0px;">
      <a href="javascript:delete_account_func(<{$AccountID}>);" class="btn btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
      <a href="account.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
      <a href="account.php?WebID=<{$WebID}>&op=edit_form&AccountID=<{$AccountID}>" class="btn btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
    </div>
  <{/if}>
<{elseif $op=="setup"}>

  <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_plugin_setup.tpl"}>
<{elseif $op=="list_all"}>
  <{if $WebID}>
    <div class="row">
      <div class="col-md-8">
        <{$cate_menu}>
      </div>
      <div class="col-md-4 text-right">
        <{if $isMyWeb and $WebID}>
          <a href="cate.php?WebID=<{$WebID}>&ColName=account&table=tad_web_account" class="btn btn-warning <{if $web_display_mode=='index'}>btn-sm<{/if}>"><i class="fa fa-folder-open"></i> <{$smarty.const._MD_TCW_CATE_TOOLS}></a>
          <a href="account.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
        <{/if}>
      </div>
    </div>
  <{/if}>
  <{if $account_data}>

    <{includeq file="$xoops_rootpath/modules/tad_web/plugins/account/tpls/b4/tad_web_common_account.tpl"}>
  <{else}>
    <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$account.PluginTitle}></h2>
    <div class="alert alert-info"><{$smarty.const._MD_TCW_EMPTY}></div>
  <{/if}>

<{else}>
  <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$account.PluginTitle}></h2>
  <{if $isMyWeb or $isCanEdit}>
    <a href="account.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_ACCOUNT_SHORT}></a>
  <{else}>
    <div class="text-center">
      <img src="images/empty.png" alt="<{$smarty.const._MD_TCW_EMPTY}>" title="<{$smarty.const._MD_TCW_EMPTY}>">
    </div>
  <{/if}>
<{/if}>
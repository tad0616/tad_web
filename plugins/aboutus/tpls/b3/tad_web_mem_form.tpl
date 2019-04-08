<{$formValidator_code}>

<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>

<h3><a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a> <{$setup_stud}></h3>


<div class="row">

  <div class="col-sm-8">
    <form action="aboutus.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
      <!--學生相片-->
      <div class="form-group">
        <label class="col-sm-4 control-label">
        <{$show_files}>
        </label>
        <div class="col-sm-8">
          <h2><{$MemName}></h2>
          <input type="file" name="upfile[]"  maxlength="1" accept="gif|jpg|png|GIF|JPG|PNG">
        </div>
      </div>

      <{if $LoginMemID}>
        <input type="hidden" name="MemName" value="<{$MemName}>">
        <input type="hidden" name="MemSex" value="<{$MemSex}>">
        <input type="hidden" name="MemEnable" value="<{$MemEnable}>">
      <{else}>

        <div class="alert alert-info">
          <!--學生姓名-->
          <div class="form-group">
            <label class="col-sm-3 control-label"><{$smarty.const._MD_TCW_MEM_NAME}></label>
            <div class="col-sm-9">
              <input type="text" name="MemName" value="<{$MemName}>" id="MemName" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_MEM_NAME}>">
            </div>
          </div>


          <!--性別-->
          <div class="form-group">
            <label class="col-sm-3 control-label"><{$smarty.const._MD_TCW_MEM_SEX}></label>
            <div class="col-sm-9">
              <select name="MemSex" class="form-control">
                <option value="1" <{if $MemSex!='0'}>selected<{/if}>><{$smarty.const._MD_TCW_BOY}></option>
                <option value="0" <{if $MemSex=='0'}>selected<{/if}>><{$smarty.const._MD_TCW_GIRL}></option>
              </select>
            </div>
          </div>

          <!--是否還在班上-->
          <div class="form-group">
            <label class="col-sm-3 control-label"><{$smarty.const._MD_TCW_MEM_STATUS}></label>
            <div class="col-sm-9">
              <select name="MemEnable" id="MemEnable" class="form-control">
                <option value="1" <{if $MemEnable!='0'}>selected<{/if}>><{$smarty.const._MD_TCW_MEM_ENABLE}></option>
                <option value="0" <{if $MemEnable=='0'}>selected<{/if}>><{$smarty.const._MD_TCW_MEM_UNABLE}></option>
              </select>
            </div>
          </div>
        </div>
      <{/if}>

      <!--座號-->
      <{if 'MemNum'|in_array:$mem_column}>

        <div class="form-group">
          <label class="col-sm-3 control-label"><{$smarty.const._MD_TCW_MEM_NUM}></label>
          <div class="col-sm-9">
            <{if $LoginMemID}>
              <input type="hidden" name="MemNum" value="<{$MemNum}>"><{$MemNum}>
            <{else}>
              <input type="text" name="MemNum" value="<{$MemNum}>" id="MemNum" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_MEM_NUM}>">
            <{/if}>
          </div>
        </div>
      <{/if}>

      <!--學號-->
      <{if 'MemUnicode'|in_array:$mem_column}>
        <div class="form-group">
          <label class="col-sm-3 control-label"><{$smarty.const._MD_TCW_MEM_UNICODE}></label>
          <div class="col-sm-9">
            <{if $LoginMemID}>
              <input type="hidden" name="MemUnicode" value="<{$MemUnicode}>"><{$MemUnicode}>
            <{else}>
              <input type="text" name="MemUnicode" value="<{$MemUnicode}>" id="MemUnicode" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_MEM_UNICODE}>">
            <{/if}>
          </div>
        </div>
      <{/if}>

      <!--生日-->
      <{if 'MemBirthday'|in_array:$mem_column}>
        <div class="form-group">
          <label class="col-sm-3 control-label"><{$smarty.const._MD_TCW_MEM_BIRTHDAY}></label>
          <div class="col-sm-9">
            <input type="text" name="MemBirthday" value="<{$MemBirthday}>" id="MemBirthday" class="form-control" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d'})" placeholder="<{$smarty.const._MD_TCW_MEM_BIRTHDAY}>">
          </div>
        </div>
      <{/if}>

      <!--學生暱稱-->
      <{if 'MemNickName'|in_array:$mem_column}>
        <div class="form-group">
          <label class="col-sm-3 control-label"><{$smarty.const._MD_TCW_MEM_NICKNAME}></label>
          <div class="col-sm-9">
            <input type="text" name="MemNickName" value="<{$MemNickName}>" id="MemNickName" class="form-control" placeholder="<{$smarty.const._MD_TCW_MEM_NICKNAME}>">
          </div>
        </div>
      <{/if}>

      <!--專長-->
      <{if 'MemExpertises'|in_array:$mem_column}>
        <div class="form-group">
          <label class="col-sm-3 control-label"><{$smarty.const._MD_TCW_MEM_EXPERTISES}></label>
          <div class="col-sm-9">
            <input type="text" name="MemExpertises" value="<{$MemExpertises}>" id="MemExpertises" class="form-control" placeholder="<{$smarty.const._MD_TCW_MEM_EXPERTISES}>">
          </div>
        </div>
      <{/if}>

      <!--職稱-->
      <{if 'MemClassOrgan'|in_array:$mem_column}>
        <div class="form-group">
          <label class="col-sm-3 control-label"><{$smarty.const._MD_TCW_MEM_CLASSORGAN}></label>
          <div class="col-sm-9">
            <input type="text" name="MemClassOrgan" value="<{$MemClassOrgan}>" id="MemClassOrgan" class="form-control" placeholder="<{$smarty.const._MD_TCW_MEM_CLASSORGAN}>">
          </div>
        </div>
      <{/if}>

      <!--自我介紹-->
      <{if 'AboutMem'|in_array:$mem_column}>
        <div class="form-group">
          <label class="col-sm-3 control-label"><{$smarty.const._MD_TCW_MEM_ABOUTME}></label>
          <div class="col-sm-9">
            <textarea name="AboutMem" id="AboutMem" class="form-control" placeholder="<{$smarty.const._MD_TCW_MEM_ABOUTME}>" style="height:300px;"><{$AboutMem}></textarea>
          </div>
        </div>
      <{/if}>

      <div class="alert alert-warning">
        <!--帳號-->
        <div class="form-group" >
          <label class="col-sm-3 control-label"><{$smarty.const._MD_TCW_MEM_UNAME}></label>
          <div class="col-sm-9">
            <input type="text" name="MemUname" value="<{$MemUname}>" id="MemUname" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_MEM_UNAME}>">
          </div>
        </div>

        <!--密碼-->
        <div class="form-group">
          <label class="col-sm-3 control-label"><{$smarty.const._MD_TCW_MEM_PASSWD}></label>
          <div class="col-sm-9">
            <input type="text" name="MemPasswd" value="<{$MemPasswd}>" id="MemPasswd" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_MEM_PASSWD}>">
          </div>
        </div>
      </div>

      <div class="text-center">
        <{$del_btn}>
        <input type="hidden" name="WebID" value="<{$WebID}>">
        <input type="hidden" name="MemID" value="<{$MemID}>">
        <input type="hidden" name="CateID" value="<{$cate.CateID}>">
        <input type="hidden" name="op" value="<{$next_op}>">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
      </div>
    </form>
  </div>

  <div class="col-sm-4">
    <{if $LoginMemID}>
       <{includeq file="$xoops_rootpath/modules/tad_web/plugins/aboutus/tpls/b3/mem_toolbar.tpl"}>
    <{else}>
      <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
        <{if 'MemNum'|in_array:$mem_column}>
          <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_NUM}></th>
        <{/if}>
          <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_NAME}></th>
        <{if 'MemUnicode'|in_array:$mem_column}>
          <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_UNICODE}></th>
        <{/if}>
          <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_SEX}></th>
        </tr>
        <{foreach from=$students item=stud}>
        <tr>
        <{if 'MemNum'|in_array:$mem_column}>
          <td style="text-align: center;<{if $stud.MemID==$MemID}>background: yellow;<{/if}>"><a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&MemID=<{$stud.MemID}>&op=edit_stu"><{$stud.MemNum}></a></td>
        <{/if}>
          <td style="text-align: center;<{if $stud.MemID==$MemID}>background: yellow;<{/if}>"><a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&MemID=<{$stud.MemID}>&op=edit_stu"><{$stud.MemName}></a></td>
        <{if 'MemUnicode'|in_array:$mem_column}>
          <td style="text-align: center;<{if $stud.MemID==$MemID}>background: yellow;<{/if}>"><a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&MemID=<{$stud.MemID}>&op=edit_stu"><{$stud.MemUnicode}></a></td>
        <{/if}>
          <td style="text-align: center; color: <{$stud.color}>;<{if $stud.MemID==$MemID}>background: yellow;<{/if}>"><{$stud.MemSex}></td>
        </tr>
        <{/foreach}>
      </table>

      <div class="text-center">
        <a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&op=edit_stu" class="btn btn-primary"><{$add_stud}></a>
      </div>
    <{/if}>
  </div>
</div>

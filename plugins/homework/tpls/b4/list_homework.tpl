<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
  <{if $isMyWeb}>
    <{$sweet_delete_homework_func_code}>
  <{/if}>

      <ul class="list-group">
        <{foreach from=$bc.main_data key=i item=homework}>
          <{if $homework.toCal > $bc.today }>
            <li class="list-group-item">

              <span class="badge badge-info"><{$homework.HomeworkCounter}></span>
              <a href="homework.php?WebID=<{$homework.WebID}>&HomeworkID=<{$homework.HomeworkID}>"><{$homework.toCal}> (<{$homework.Week}>) <{$smarty.const._MD_TCW_HOMEWORK}></a>

              <{if $homework.isCanEdit}>
                <a href="javascript:delete_homework_func(<{$homework.HomeworkID}>);" class="text-danger"><i class="fa fa-trash-o"></i></a>
                <a href="homework.php?WebID=<{$homework.WebID}>&op=edit_form&HomeworkID=<{$homework.HomeworkID}>" class="text-warning"><i class="fa fa-pencil"></i></a>
              <{/if}>
            </li>
          <{/if}>
        <{/foreach}>
      </ul>

  <{foreach from=$bc.main_data key=i item=homework}>
    <{if $homework.toCal ==$bc.today}>

          <h3>
            <a href="homework.php?WebID=<{$WebID}>&HomeworkID=<{$homework.HomeworkID}>"><{$homework.toCal}> (<{$homework.Week}>) <{$smarty.const._MD_TCW_HOMEWORK}></a>

            <{if $homework.isCanEdit}>
              <a href="javascript:delete_homework_func(<{$homework.HomeworkID}>);" class="text-danger"><i class="fa fa-trash-o"></i></a>

              <a href="homework.php?WebID=<{$WebID}>&op=edit_form&HomeworkID=<{$homework.HomeworkID}>" class="text-warning"><i class="fa fa-pencil"></i></a>
            <{/if}>
          </h3>
          <div style="min-height: 100px; overflow: hidden; line-height: 1.8; background: #FFFFFF ; border: 2px solid #99C454; border-radius: 5px; margin:10px auto;">
            <{if $homework.HomeworkContent}>
              <{$homework.HomeworkContent}>
            <{else}>
              <div class="row">
                <{if $homework.today_homework}>
                  <div class="col-md-<{$homework.ColWidth}>">
                    <div style="border-bottom: 1px solid #cfcfcf;">
                      <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TODAY_WORK}>" src="<{$xoops_url}>/modules/tad_web/images/today_homework.png" class="img-fluid" style="margin:6px auto;">
                    </div>
                    <{$homework.today_homework}>
                  </div>
                <{/if}>
                <{if $homework.bring}>
                  <div class="col-md-<{$homework.ColWidth}>">
                    <div style="border-bottom: 1px solid #cfcfcf;">
                      <img alt="<{$smarty.const._MD_TCW_HOMEWORK_BRING}>" src="<{$xoops_url}>/modules/tad_web/images/bring.png" class="img-fluid" style="margin:6px auto;">
                    </div>
                    <{$homework.bring}>
                  </div>
                <{/if}>
                <{if $homework.teacher_say}>
                  <div class="col-md-<{$homework.ColWidth}>">
                    <div style="border-bottom: 1px solid #cfcfcf;">
                      <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TEACHER_SAY}>" src="<{$xoops_url}>/modules/tad_web/images/teacher_say.png" class="img-fluid" style="margin:6px auto;">
                    </div>
                    <{$homework.teacher_say}>
                  </div>
                <{/if}>
              </div>
              <{if $homework.other}>
               <div class="alert alert-info"><{$homework.other}></div>
              <{/if}>
            <{/if}>
          </div>

    <{/if}>
  <{/foreach}>

      <ul class="list-group">
        <{foreach from=$bc.main_data key=i item=homework}>
          <{if $homework.toCal < $bc.today}>
            <li class="list-group-item">
              <span class="badge badge-info"><{$homework.HomeworkCounter}></span>
              <a href="homework.php?WebID=<{$homework.WebID}>&HomeworkID=<{$homework.HomeworkID}>"><{$homework.toCal}> (<{$homework.Week}>) <{$smarty.const._MD_TCW_HOMEWORK}></a>

              <{if $homework.isCanEdit}>
                <a href="javascript:delete_homework_func(<{$homework.HomeworkID}>);" class="text-danger"><i class="fa fa-trash-o"></i></a>
                <a href="homework.php?WebID=<{$homework.WebID}>&op=edit_form&HomeworkID=<{$homework.HomeworkID}>" class="text-warning"><i class="fa fa-pencil"></i></a>
              <{/if}>
            </li>
          <{/if}>
        <{/foreach}>
      </ul>

<{/if}>

<{if $bc.yet_data and $isMyWeb}>

      <ul class="list-group">
        <{foreach from=$bc.yet_data key=i item=homework}>
          <li class="list-group-item">
            <span class="badge badge-info"><{$homework.HomeworkCounter}></span>

            <a href="homework.php?WebID=<{$homework.WebID}>&HomeworkID=<{$homework.HomeworkID}>" style="color: gray;"><{$homework.toCal}> (<{$homework.Week}>) <{$smarty.const._MD_TCW_HOMEWORK}></a>

            <{if $homework.isCanEdit}>
              <a href="javascript:delete_homework_func(<{$homework.HomeworkID}>);" class="text-danger"><i class="fa fa-trash-o"></i></a>
              <a href="homework.php?WebID=<{$homework.WebID}>&op=edit_form&HomeworkID=<{$homework.HomeworkID}>" class="text-warning"><i class="fa fa-pencil"></i></a>
            <{/if}>

            <span style="color: #840707;"><{$homework.display_at}></span>
          </li>
        <{/foreach}>
      </ul>
<{/if}>
<{if $homework_data}>
    <{if $isMyWeb}>
        <{$sweet_delete_homework_func_code}>
    <{/if}>

    <{if $WebID}>

            <ul class="list-group">
            <{foreach from=$homework_data key=i item=homework}>
                <{if $homework.toCal > $today}>
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


        <{foreach from=$homework_data key=i item=homework}>
        <{if $homework.toCal ==$today}>

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
            <{foreach from=$homework_data key=i item=homework}>
                <{if $homework.toCal < $today}>
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

    <{else}>
        <{if $web_display_mode=='index' and $homework_data}>
        <{if "$xoops_rootpath/uploads/tad_web/0/image/`$dirname`.png"|file_exists}>
            <a href="<{$xoops_url}>/modules/tad_web/<{$dirname}>.php"><img src="<{$xoops_url}>/uploads/tad_web/0/image/<{$dirname}>.png" alt="<{$homework.PluginTitle}>"></a>
        <{else}>
            <h3><a href="<{$xoops_url}>/modules/tad_web/homework.php"><{$homework.PluginTitle}></a></h3>
        <{/if}>
        <{elseif $web_display_mode=='index_plugin'}>
        <h2><a href="<{$xoops_url}>/modules/tad_web/"><i class="fa fa-home"></i></a> <{$homework.PluginTitle}></h2>
        <{elseif $web_display_mode=='home_plugin'}>
        <h2><a href="index.php?WebID=<{$WebID}>"><i class="fa fa-home"></i></a> <{$homework.PluginTitle}></h2>
        <{/if}>

        <table class="footable table common_table">
        <thead>
            <tr>
            <th data-class="expand">
                <{$smarty.const._MD_TCW_HOMEWORK}>
            </th>
            <th data-hide="phone" class="common_counter" style="text-align: center;">
                <{$smarty.const._MD_TCW_HOMEWORKCOUNTER}>
            </th>
            <{if $web_display_mode=="index" or $web_display_mode=="index_plugin"}>
                <th data-hide="phone" class="common_team" style="text-align: center;">
                <{$smarty.const._MD_TCW_TEAMID}>
                </th>
            <{/if}>
            </tr>
        </thead>
        <{foreach  from=$homework_data item=homework}>
            <tr>
            <td>
                <a href="homework.php?WebID=<{$homework.WebID}>&HomeworkID=<{$homework.HomeworkID}>"><{$homework.WebName}> <{$homework.toCal}> (<{$homework.Week}>) <{$smarty.const._MD_TCW_HOMEWORK}></a>
                <{if $homework.isCanEdit}>
                <a href="javascript:delete_homework_func(<{$homework.HomeworkID}>);" class="text-danger"><i class="fa fa-trash-o"></i></a>
                <a href="homework.php?WebID=<{$homework.WebID}>&op=edit_form&HomeworkID=<{$homework.HomeworkID}>" class="text-warning"><i class="fa fa-pencil"></i></a>
                <{/if}>
            </td>
            <td style="text-align:center;">
                <span class="badge badge-info"><{$homework.HomeworkCounter}></span>
            </td>
            <{if $web_display_mode=="index" or $web_display_mode=="index_plugin"}>
                <td style="text-align:center;" class="common_team_content">
                <{$homework.WebTitle}>
                </td>
            <{/if}>
            </tr>
        <{/foreach}>
        </table>

        <{if $web_display_mode=='index_plugin' or $web_display_mode=='home_plugin'}>
        <{$bar}>
        <{/if}>
    <{/if}>

    <div style="text-align:right; margin: 0px 0px 10px;">
        <{if $web_display_mode=='index'}>
        <a href="homework.php" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
        <{elseif $web_display_mode=='home' or $HomeworkDefCateID}>
        <a href="homework.php?WebID=<{$WebID}>" class="btn btn-primary <{if $web_display_mode=='index'}>btn-sm<{/if}>"><i class="fa fa-info-circle"></i> <{$smarty.const._MD_TCW_MORE}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
        <{/if}>

        <{if $isMyWeb and $WebID}>
        <a href="homework.php?WebID=<{$WebID}>&op=edit_form" class="btn btn-info <{if $web_display_mode=='index'}>btn-sm<{/if}>"><i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$smarty.const._MD_TCW_HOMEWORK_SHORT}></a>
        <{/if}>
    </div>
<{/if}>

<{if $yet_data and $isMyWeb}>

    <ul class="list-group">
        <{foreach from=$yet_data key=i item=homework}>
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
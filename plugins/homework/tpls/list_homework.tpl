<{assign var="bc" value=$block.BlockContent}>

<{if $bc.main_data|default:false}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <ul class="list-group">
        <{foreach from=$bc.main_data key=i item=homework}>
            <{if $homework.HomeworkPostDateTS > $nowTS}>
                <li class="list-group-item">
                    <{if ($WebID && $isMyWeb) || $isAdmin || (isset($homework.cate.CateID) && isset($smarty.session.isAssistant.homework) && $homework.cate.CateID == $smarty.session.isAssistant.homework)}>
                        <a href="homework.php?WebID=<{$homework.WebID}>&HomeworkID=<{$homework.HomeworkID}>" style="color: gray;"><{$homework.toCal}> (<{$homework.Week}>) <{$smarty.const._MD_TCW_HOMEWORK}></a>
                    <{else}>
                        <{$homework.toCal}> (<{$homework.Week}>) <{$smarty.const._MD_TCW_HOMEWORK}>
                    <{/if}>

                    <{*if $homework.isCanEdit*}>
                    <{if ($WebID && $isMyWeb) || $isAdmin || (isset($homework.cate.CateID) && isset($smarty.session.isAssistant.homework) && $homework.cate.CateID == $smarty.session.isAssistant.homework)}>
                        <a href="javascript:delete_homework_func(<{$homework.HomeworkID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>
                        <a href="homework.php?WebID=<{$homework.WebID}>&op=edit_form&HomeworkID=<{$homework.HomeworkID}>" class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                    <{/if}>
                    <span style="color: #840707;"><{$homework.display_at}></span>
                </li>
            <{elseif $homework.toCal == $today && $homework.HomeworkPostDateTS < $nowTS}>
                <li class="list-group-item">
                    <h3>
                        <a href="homework.php?WebID=<{$WebID|default:''}>&HomeworkID=<{$homework.HomeworkID}>"><{$homework.toCal}> (<{$homework.Week}>) <{$smarty.const._MD_TCW_HOMEWORK}></a>

                        <{*if $homework.isCanEdit*}>
                        <{if ($WebID && $isMyWeb) || $isAdmin || (isset($homework.cate.CateID) && isset($smarty.session.isAssistant.homework) && $homework.cate.CateID == $smarty.session.isAssistant.homework)}>
                            <a href="javascript:delete_homework_func(<{$homework.HomeworkID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>

                            <a href="homework.php?WebID=<{$WebID|default:''}>&op=edit_form&HomeworkID=<{$homework.HomeworkID}>" class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                        <{/if}>
                    </h3>

                    <div style="min-height: 100px; overflow: hidden; line-height: 1.8; background: #FFFFFF ; border: 2px solid #99C454; border-radius: 5px; margin:10px auto;">
                        <{if $homework.HomeworkContent|default:false}>
                            <{$homework.HomeworkContent}>
                        <{else}>
                            <div class="row">
                                <{if $homework.today_homework|default:false}>
                                    <div class="col-md-<{$homework.ColWidth}>">
                                        <div style="border-bottom: 1px solid #cfcfcf;">
                                            <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TODAY_WORK}>" src="<{$xoops_url}>/modules/tad_web/images/today_homework.png" class="img-fluid img-responsive" style="margin:6px auto;">
                                        </div>
                                        <{$homework.today_homework}>
                                    </div>
                                <{/if}>

                                <{if $homework.bring|default:false}>
                                    <div class="col-md-<{$homework.ColWidth}>">
                                        <div style="border-bottom: 1px solid #cfcfcf;">
                                            <img alt="<{$smarty.const._MD_TCW_HOMEWORK_BRING}>" src="<{$xoops_url}>/modules/tad_web/images/bring.png" class="img-fluid img-responsive" style="margin:6px auto;">
                                        </div>
                                        <{$homework.bring}>
                                    </div>
                                <{/if}>

                                <{if $homework.teacher_say|default:false}>
                                    <div class="col-md-<{$homework.ColWidth}>">
                                        <div style="border-bottom: 1px solid #cfcfcf;">
                                            <img alt="<{$smarty.const._MD_TCW_HOMEWORK_TEACHER_SAY}>" src="<{$xoops_url}>/modules/tad_web/images/teacher_say.png" class="img-fluid img-responsive" style="margin:6px auto;">
                                        </div>
                                        <{$homework.teacher_say}>
                                    </div>
                                <{/if}>
                            </div>

                            <{if $homework.other|default:false}>
                                <div class="alert alert-info"><{$homework.other}></div>
                            <{/if}>
                        <{/if}>
                    </div>
                </li>

            <{else}>
                <li class="list-group-item">
                    <a href="homework.php?WebID=<{$homework.WebID}>&HomeworkID=<{$homework.HomeworkID}>"><{$homework.toCal}> (<{$homework.Week}>) <{$smarty.const._MD_TCW_HOMEWORK}></a>

                    <{*if $homework.isCanEdit*}>
                    <{if ($WebID && $isMyWeb) || $isAdmin || (isset($homework.cate.CateID) && isset($smarty.session.isAssistant.homework) && $homework.cate.CateID == $smarty.session.isAssistant.homework)}>
                        <a href="javascript:delete_homework_func(<{$homework.HomeworkID}>);" class="text-danger"><i class="fa fa-trash-o"></i><span class="sr-only visually-hidden">delete</span></a>
                        <a href="homework.php?WebID=<{$homework.WebID}>&op=edit_form&HomeworkID=<{$homework.HomeworkID}>" class="text-warning"><i class="fa fa-pencil"></i><span class="sr-only visually-hidden">edit</span></a>
                    <{/if}>
                </li>
            <{/if}>
        <{/foreach}>
    </ul>
<{/if}>

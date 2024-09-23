<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
<script>
    $(function() {
        $( "#catalog li" ).draggable({
            appendTo: "body",
            helper: "clone"
        });

        $( ".droppable" ).droppable({
            hoverClass: "hover",
            drop: function( event, ui ) {

            if(ui.draggable.text()=='<{$smarty.const._MD_TCW_SCHEDULE_BLANK}>'){
                $.post("<{$xoops_url}>/modules/tad_web/plugins/schedule/save_schedule.php", {op:'delete', WebID: "<{$WebID|default:''}>", ScheduleID: "<{$ScheduleID|default:''}>", tag: $( this ).attr('id')});
                $( this )
                .removeClass( "dropped" )
                .css( "color", ui.draggable.css("color"))
                .css( "background-color", ui.draggable.css("background-color"))
                .find( "div" )
                .html('<{$smarty.const._MD_TCW_SCHEDULE_BLANK}>').appendTo( this );;
            }else{
                $.post("<{$xoops_url}>/modules/tad_web/plugins/schedule/save_schedule.php", {op:'save', WebID: "<{$WebID|default:''}>", ScheduleID: "<{$ScheduleID|default:''}>", tag: $( this ).attr('id'), Subject: ui.draggable.text()});
                $( this )
                .addClass( "dropped" )
                .css( "color", ui.draggable.css("color"))
                .css( "background-color", ui.draggable.css("background-color"))
                .find( "div" )
                .html( ui.draggable.text() ).appendTo( this );
            }
            }
        });
    });
</script>

<h2><{$smarty.const._MD_TCW_SCHEDULE_ADD}></h2>
<div class="my-border">
    <form schedule="schedule.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
        <!--分類-->
        <{$cate_menu_form|default:''}>

        <!--課表名稱-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label"><{$smarty.const._MD_TCW_SCHEDULENAME}></label>
            <div class="col-md-10">
                <input type="text" name="ScheduleName" value="<{$ScheduleName|default:''}>" id="ScheduleName" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_SCHEDULENAME}>">
            </div>
        </div>

        <{if $ScheduleID|default:false}>
            <div class="form-group row mb-3">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <ul id="catalog">
                            <{foreach from=$schedule_subjects item=subject}>
                                <li style="cursor: move; text-align: center; color: <{$subject.color}>; background-color: <{$subject.bg_color}>;"><{$subject.Subject}></li>
                            <{/foreach}>
                            <li style="cursor: move; color: #CDCDCD; background-color: #FFFFFF;"><{$smarty.const._MD_TCW_SCHEDULE_BLANK}></li>
                        </ul>
                        <div style="clear: both;" class="text-right text-end">
                            <a href="schedule.php?WebID=<{$WebID|default:''}>&op=setup_subject&ScheduleID=<{$ScheduleID|default:''}>" class="btn btn-sm btn-xs btn-info"><{$smarty.const._MD_TCW_SCHEDULE_SETUP_SUBJECT}></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row mb-3">
                <div class="col-md-12">
                    <{$schedule_template|default:''}>
                </div>
            </div>
        <{/if}>

        <!--顯示設定-->
        <div class="form-group row mb-3">
            <div class="col-md-12">
                <div class="form-check form-check-inline checkbox-inline">
                    <label class="form-check-label" for="ScheduleDisplay">
                        <input class="form-check-input" type="checkbox" name="ScheduleDisplay" value="1" id="ScheduleDisplay" <{if $ScheduleDisplay=='1'}>checked<{/if}>>
                        <{$smarty.const._MD_TCW_SCHEDULE_SET_DEFAULT}>
                    </label>
                </div>
                <span class="text-danger"><{$smarty.const._MD_TCW_SCHEDULE_SET_DEFAULT_DESC}></span>
            </div>
        </div>

        <div class="text-center">
            <!--活動編號-->
            <input type="hidden" name="ScheduleID" value="<{$ScheduleID|default:''}>">
            <!--所屬團隊-->
            <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
            <input type="hidden" name="op" value="<{$next_op|default:''}>">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
    </form>
</div>
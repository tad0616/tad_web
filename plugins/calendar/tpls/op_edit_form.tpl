<h2><{$smarty.const._MD_TCW_CALENDAR_ADD}></h2>
<div class="my-border">
    <form action="calendar.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">

        <!--活動日期-->
        <div class="form-group row mb-3">
            <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TCW_CALENDARDATE}>
            </label>
            <div class="col-md-3">
                <input type="text" name="CalendarDate" class="form-control" value="<{$CalendarDate|default:''}>" id="CalendarDate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d'})">
            </div>

            <!--活動名稱-->
            <div class="col-md-7">
                <input type="text" name="CalendarName" value="<{$CalendarName|default:''}>" id="CalendarName" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_CALENDARNAME}>">
            </div>
        </div>

        <!--活動說明-->
        <textarea name="CalendarDesc"  rows=4 id="CalendarDesc"  class="form-control" placeholder="<{$smarty.const._MD_TCW_CALENDARDESC}>"><{$CalendarDesc|default:''}></textarea>


        <!--全校活動-->
        <div class="form-group row mb-3">
            <div class="col-md-10">
                <div class="form-check form-check-inline checkbox-inline">
                    <label class="form-check-label" for="CalendarType">
                        <input class="form-check-input" type="checkbox" name="CalendarType" id="CalendarType" value="all">
                        <{$smarty.const._MD_TCW_CALENDAR_TYPE_GLOBAL}>
                    </label>
                </div>
            </div>

            <div class="col-md-2">
                <input type="hidden" name="CalendarID" value="<{$CalendarID|default:''}>">
                <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
                <input type="hidden" name="op" value="<{$next_op|default:''}>">
                <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-disk" aria-hidden="true"></i>  <{$smarty.const._TAD_SAVE}></button>
            </div>
        </div>
    </form>
</div>
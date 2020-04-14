<div class="container-fluid">
    <h2><{$smarty.const._MA_TCW_WEB_SCHEDULE_TEMPLATE}></h2>
    <form action="schedule.php" method="post">
        <{$schedule_template}>
        <div class="text-center" style="margin: 10px auto;">
            <input type="hidden" name="op" value="save_schedule_template">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SUBMIT}></button>
        </div>
    </form>
    <div class="alert alert-info"><{$smarty.const._MA_TCW_WEB_SCHEDULE_TEMPLATE_DESC}></div>

    <hr>

    <h2><{$smarty.const._MA_TCW_WEB_SCHEDULE_SUBJECT}></h2>
    <form action="schedule.php" method="post">
        <textarea name="schedule_subjects" id="schedule_subjects" cols="30" rows="2" class="form-control"><{$schedule_subjects}></textarea>
        <div class="text-center" style="margin: 10px auto;">
            <input type="hidden" name="op" value="save_schedule_subjects">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SUBMIT}></button>
        </div>
        <div class="alert alert-info"><{$smarty.const._MA_TCW_WEB_SCHEDULE_SUBJECT_DESC}></div>
    </form>
</div>
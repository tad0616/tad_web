<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#list_mems").change(function(event) {
            var mem_name=$("#list_mems option:selected").prop('label');
            $.post("<{$xoops_url}>/modules/tad_web/plugins/aboutus/get_mems.php", { op: 'chk_unable', MemID: $('#list_mems').val(), WebID: '<{$WebID}>' , MemName:mem_name }, function(data){
                $('#unable').html(data);
            });

            $('.mem_name').html(mem_name);
            $('#step2').show();
        });
    });
</script>

<h3><{$smarty.const._MD_TCW_REGIST_BY_PARENTS}></h3>

<form action="aboutus.php" id="myForm" method="post" enctype="multipart/form-data" role="form" class="form-horizontal">
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-sm-right control-label">
            <{$cate_label}>
        </label>
        <div class="col-md-4">
            <{$cate_menu}>
        </div>
        <div class="col-md-5">
            <select name="MemID" id="list_mems" class="form-control" title="list mems">
                <option value=""><{$smarty.const._MD_TCW_ABOUTUS_SELECT_CLASS}></option>
            </select>
        </div>
    </div>
    <div id="step2" style="display:none;">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-sm-right control-label">
                <span class="mem_name"><{$smarty.const._MD_TCW_ABOUTUS_THE_STUDENT}></span><{$smarty.const._MD_TCW_ABOUTUS_THE_STUDENT_BIRTHDAY}>
            </label>
            <div class="col-md-9">
                <input type="text" name="MemBirthday" id="MemBirthday" onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d'})" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_ABOUTUS_VERIFY_BIRTHDAY}>2010-01-10">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-sm-right control-label">
                <{$smarty.const._MD_TCW_ABOUTUS_YOUR_ARE}><span class="mem_name"><$smarty.const._MD_TCW_ABOUTUS_THE_STUDENT></span><{$smarty.const._MD_TCW_ABOUTUS_S}>
            </label>
            <div class="col-md-9">
                <input type="text" name="Reationship" id="Reationship" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_ABOUTUS_REATIONSHIP_DESC}>">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-sm-right control-label">
                <{$smarty.const._MD_TCW_ABOUTUS_PARENT_EMAIL}>
            </label>
            <div class="col-md-9">
                <input type="text" name="ParentEmail" id="ParentEmail" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_ABOUTUS_PARENT_EMAIL_DESC}>">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-sm-right control-label">
                <{$smarty.const._MD_TCW_ABOUTUS_PARENT_PASSWD}>
            </label>
            <div class="col-md-9">
                <input type="text" name="ParentPasswd" id="ParentPasswd" title="<{$smarty.const._MD_TCW_ABOUTUS_PARENT_MODIFY_PASSWD_DESC}>"  class="validate[required] form-control">
            </div>
        </div>

        <div class="text-center">
            <span id="unable"></span>
            <input type="hidden" name="op" value="parents_signup">
            <input type="hidden" name="WebID" value="<{$WebID}>">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_NEXT}></button>
        </div>
    </div>
</form>
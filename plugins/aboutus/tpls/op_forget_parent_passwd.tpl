<script type="text/javascript">
    $(document).ready(function() {
        $("#list_mems").on('change', function(event) {
            var mem_name=$("#list_mems option:selected").prop('label');
            $('.mem_name').html(mem_name);
            $('#step3').show();
            $.post("<{$xoops_url}>/modules/tad_web/plugins/aboutus/get_mems.php", { op: 'get_reationship', MemID: $('#list_mems').val()}, function(data){
                $('#Reationship').html(data);
                if(data==''){
                    $('#submit_btn').hide();
                    $('#no_account').show();
                }else{
                    $('#submit_btn').show();
                    $('#no_account').hide();
                }
            });
        });
    });
</script>

<h3><{$smarty.const._MD_TCW_FORGET_PARENTS_PASSWD}></h3>

<form action="aboutus.php" id="myForm" method="post" enctype="multipart/form-data" role="form" class="form-horizontal">
    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$cate_label|default:''}>
        </label>
        <div class="col-md-4">
            <{$cate_menu|default:''}>
        </div>
        <div class="col-md-5">
            <select name="MemID" id="list_mems" class="form-control form-select" title="list mems">
                <option value=""><{$smarty.const._MD_TCW_ABOUTUS_SELECT_CLASS}></option>
            </select>
        </div>
    </div>

    <div class="form-group row mb-3" id="step3" style="display:none;">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_ABOUTUS_YOUR_ARE}><span class="mem_name"><$smarty.const._MD_TCW_ABOUTUS_THE_STUDENT></span><{$smarty.const._MD_TCW_ABOUTUS_S}>
        </label>
        <div class="col-md-4">
            <select name="Reationship" id="Reationship" class="form-control form-select" title="Reationship">
            </select>
        </div>
        <div class="col-md-5">
            <input type="hidden" name="op" value="send_parents_passwd">
            <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
            <button type="submit" class="btn btn-primary" id="submit_btn"><i class="fa fa-floppy-disk" aria-hidden="true"></i>  <{$smarty.const._TAD_SUBMIT}></button>
            <div class="alert alert-info" id="no_account" style="display:none;">
                <a href="aboutus.php?WebID=<{$WebID|default:''}>&op=parents_account"><{$smarty.const._MD_TCW_ABOUTUS_NO_PARENT_ACCOUNT}></a>
            </div>
        </div>
    </div>
</form>
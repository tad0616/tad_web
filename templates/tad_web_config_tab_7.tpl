<script type="text/javascript">
    $(document).ready(function() {
        $('#keyman').change(function(event) {
            $.post("config_ajax.php", {op: "keyman" , keyman: $('#keyman').val(), WebID: <{$WebID}>}, function(theResponse){
                $('#adm_repository').html(theResponse);
            });
        });

        function getOptions(destination,val_col)
        {
            var values = [];
            var sel = document.getElementById(destination);
            for (var i=0, n=sel.options.length;i<n;i++) {
                if (sel.options[i].value) values.push(sel.options[i].value);
            }
            document.getElementById(val_col).value=values.join(',');
        }
    });
</script>

<{$smarty.const._MD_TCW_DEFAULT_ADMIN}><{$Web.WebOwnerUid}> <{$Web.WebOwner}>
<div class="row">
    <div class="col-md-5 text-center">
        <h3><{$smarty.const._MD_TCW_USER_LIST}></h3>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-5 text-center">
        <h3><{$smarty.const._MD_TCW_USER_SELECTED}></h3>
    </div>
</div>

<form action="config.php" method="post" class="form-horizontal myForm" role="form">
    <div class="row">
        <div class="col-md-5">
            <div class="input-group">
                <input type="text" name="keyman" id="keyman" placeholder="<{$smarty.const._MD_TCW_KEYWORD_TO_SELECT_USER}>" class="form-control">
                <div class="input-group-append input-group-btn">
                    <a href="#" class="btn btn-success"><{$smarty.const._MD_TCW_SELETC_USER}></a>
                </div>
            </div>

            <select name="adm_repository" id="adm_repository" size="10" multiple="multiple" tmt:linkedselect="true" class="form-control">
                <{$user_yet}>
            </select>
        </div>
        <div class="col-md-2 text-center">
            <img src="<{$xoops_url}>/modules/tad_web/images/right.png" onclick="tmt.spry.linkedselect.util.moveOptions('adm_repository', 'adm_destination');getOptions('adm_destination','web_admins');"><br>
            <img src="<{$xoops_url}>/modules/tad_web/images/left.png" onclick="tmt.spry.linkedselect.util.moveOptions('adm_destination' , 'adm_repository');getOptions('adm_destination','web_admins');"><br><br>

            <img src="<{$xoops_url}>/modules/tad_web/images/up.png" onclick="tmt.spry.linkedselect.util.moveOptionUp('adm_destination');getOptions('adm_destination','web_admins');"><br>
            <img src="<{$xoops_url}>/modules/tad_web/images/down.png" onclick="tmt.spry.linkedselect.util.moveOptionDown('adm_destination');getOptions('adm_destination','web_admins');">
            <div class="text-center" style="margin-top: 30px;">
                <input type="hidden" name="web_admins" id="web_admins" value="<{$web_admins}>">
                <input type="hidden" name="op" value="save_adm">
                <input type="hidden" name="WebID" value="<{$WebID}>">
                <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
            </div>
        </div>
        <div class="col-md-5">
            <select id="adm_destination" size="12" multiple="multiple" tmt:linkedselect="true" class="form-control">
                <{$user_ok}>
            </select>
        </div>
    </div>
</form>
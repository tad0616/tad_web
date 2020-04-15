<h3>
    <a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a> <{$smarty.const._MD_TCW_STUDENT_POSITION}>
</h3>

<form role="form">
    <div class="row">
        <div class="col-md-9"><{$cate_menu}></div>
        <div class="col-md-3">
            <a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&op=reset_position" class="btn btn-primary"><{$smarty.const._MD_TCW_ABOUT_RESET}></a>
        </div>
    </div>
</form>

<{if $isMyWeb}>
    <{if $students1 || $students2}>
        <style>
            .draggable {padding: 5px; margin: 0 10px 10px 0; font-size: 80%; border:0px dotted gray;position:absolute;}
            #snaptarget {width:640px; height: 540px; border:1px solid black;background:#CC6633 url('images/classroom2.png') center center no-repeat;position:relative;border:1px solid red;}
        </style>

        <script>
            $(function() {
                $( ".draggable" ).draggable({
                snapMode: "outer",
                containment: "#snaptarget",
                cursor:"move",
                stop: function(e, ui) {
                    var top = ui.position.top;
                    var left = ui.position.left;
                    var MemID = $(this).attr("id");

                    $.post("aboutus.php", {op: "save_seat", MemID: MemID , top: top, left: left } , function(data) {
                    $("#save_info").html("<{$smarty.const._MD_TCW_MEM_SAVE_OK}>");
                    });
                }});
            });
        </script>

        <div id="snaptarget">
            <{$students1}>
        </div>

        <br style="clear:both;">
        <{$students2}>
        <div id="save_info"></div>
    <{else}>
        <div class="jumbotron">
            <h2><{$no_student}></h2>

            <{if $isMyWeb}>
                <p>
                    <a class="btn btn-success" href="aboutus.php?WebID=<{$WebID}>&op=import_excel_form&CateID=<{$CateID}>"><{$import_excel}></a>
                    <a class="btn btn-info" href="aboutus.php?WebID=<{$WebID}>&op=edit_stu&CateID=<{$CateID}>"><{$add_stud}></a>
                </p>
            <{/if}>
        </div>
    <{/if}>
<{/if}>
<h3>
    <{if $cate.CateName|default:false}><a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
    <{$smarty.const._MD_TCW_STUDENT_POSITION}>
</h3>

<form role="form">
    <div class="row">
        <div class="col-md-9"><{$cate_menu|default:''}></div>
        <div class="col-md-3">
            <a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>&op=reset_position" class="btn btn-primary"><{$smarty.const._MD_TCW_ABOUT_RESET}></a>
        </div>
    </div>
</form>

<{if $isMyWeb|default:false}>
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
                        console.log(data);
                        $("#save_info").html("<{$smarty.const._MD_TCW_MEM_SAVE_OK}> ("+data+":"+top+","+left+")");
                    });
                }});
            });
        </script>

        <div id="snaptarget">
            <{$students1|default:''}>
        </div>

        <br style="clear:both;">
        <{$students2|default:''}>
        <div id="save_info"></div>
    <{else}>
        <div class="jumbotron bg-light p-5 rounded-lg m-3">
            <h2><{$no_student|default:''}></h2>

            <{if $isMyWeb|default:false}>
                <p>
                    <a class="btn btn-success" href="aboutus.php?WebID=<{$WebID|default:''}>&op=import_excel_form&CateID=<{$CateID|default:''}>"><{$import_excel|default:''}></a>
                    <a class="btn btn-info" href="aboutus.php?WebID=<{$WebID|default:''}>&op=edit_stu&CateID=<{$CateID|default:''}>"><{$add_stud|default:''}></a>
                </p>
            <{/if}>
        </div>
    <{/if}>
<{/if}>
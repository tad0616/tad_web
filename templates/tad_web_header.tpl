<{if $WebID|default:false}>
    <{if $config|default:false}>
        <script>
            $(function() {
                $("#head_bg").draggable({
                    axis: "y",
                    cursor:"move",
                    stop: function(e, ui) {
                        var top = ui.position.top;
                        var left = ui.position.left;
                        $.post("config_ajax.php", {op: "save_head_bg", WebID: <{$WebID|default:''}> , head_top: top, head_left: left });
                    }
                });

                $( "#tad_web_logo" ).draggable({
                    cursor:"move",
                    stop: function(e, ui) {
                        var top = ui.position.top;
                        var left = ui.position.left;
                        $.post("config_ajax.php", {op: "save_logo", WebID: <{$WebID|default:''}> , logo_top: top, logo_left: left });
                    }
                });
            });
        </script>

        <div class="my-border" id="web_head" style="height: 240px; overflow: hidden; padding: 0px; position: relative; border: 2px dashed red;">
            <{if $web_head|strpos:"head_$WebID_" !== false}>
                <img src="<{$xoops_url}>/uploads/tad_web/<{$WebID|default:''}>/head/<{$web_head|default:''}>" alt="head bg" id="head_bg" style="margin: 0px; width: 100%; position: absolute; z-index: 0; top: <{$head_top|default:''}>px; left: <{$head_left|default:''}>px;">
            <{else}>
                <img src="<{$xoops_url}>/modules/tad_web/images/head/<{$web_head|default:''}>" alt="head bg" id="head_bg" style="margin: 0px; width: 100%; position: absolute; z-index: 0; top: <{$head_top|default:''}>px; left: <{$head_left|default:''}>px;">
            <{/if}>

            <a href="index.php?WebID=<{$WebID|default:''}>"><img src="<{$xoops_url}>/uploads/tad_web/<{$WebID|default:''}>/logo/<{$web_logo|default:''}>" alt="web log" id="tad_web_logo" class="draggable" style="position: absolute; z-index: 1; top: <{$logo_top|default:''}>px; left: <{$logo_left|default:''}>px;"></a>
        </div>
    <{else}>
        <script>
            $(function() {
                var screen=$( window ).width();
                if(screen <= 480){
                    $('#head_bg').attr('src','<{$xoops_url}>/uploads/tad_web/<{$WebID|default:''}>/header_480.png');
                }else{
                    $('#head_bg').attr('src','<{$xoops_url}>/uploads/tad_web/<{$WebID|default:''}>/header.png');
                }
            });
        </script>
        <a href="index.php?WebID=<{$WebID|default:''}>"><img src="<{$xoops_url}>/uploads/tad_web/<{$WebID|default:''}>/header.png" alt="Web Title:<{$WebTitle|default:''}>" id="head_bg" class="img-rounded img-fluid img-resopnsive" style="margin-bottom: 10px;width:100%;"><span class="sr-only visually-hidden"><{$WebTitle|default:''}></span></a>
    <{/if}>
<{/if}>

<{if $isMyWeb or $LoginMemID or $LoginParentID}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_my_menu.tpl"}>
<{else}>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_login.tpl"}>
<{/if}>
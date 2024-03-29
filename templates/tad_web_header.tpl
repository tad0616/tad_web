<{if $WebID}>
    <{if $config}>
        <script>
            $(function() {
                $("#head_bg").draggable({
                    axis: "y",
                    cursor:"move",
                    stop: function(e, ui) {
                        var top = ui.position.top;
                        var left = ui.position.left;
                        $.post("config_ajax.php", {op: "save_head_bg", WebID: <{$WebID}> , head_top: top, head_left: left });
                    }
                });

                $( "#tad_web_logo" ).draggable({
                    cursor:"move",
                    stop: function(e, ui) {
                        var top = ui.position.top;
                        var left = ui.position.left;
                        $.post("config_ajax.php", {op: "save_logo", WebID: <{$WebID}> , logo_top: top, logo_left: left });
                    }
                });
            });
        </script>

        <div class="my-border" id="web_head" style="height: 200px; overflow: hidden; padding: 0px; position: relative; border: 2px dashed red;">
            <{if $web_head|strpos:"head_$WebID_" !== false}>
                <img src="<{$xoops_url}>/uploads/tad_web/<{$WebID}>/head/<{$web_head}>" alt="head bg" id="head_bg" style="margin: 0px; width: 100%; position: absolute; z-index: 0; top: <{$head_top}>px; left: <{$head_left}>px;">
            <{else}>
                <img src="<{$xoops_url}>/modules/tad_web/images/head/<{$web_head}>" alt="head bg" id="head_bg" style="margin: 0px; width: 100%; position: absolute; z-index: 0; top: <{$head_top}>px; left: <{$head_left}>px;">
            <{/if}>

            <a href="index.php?WebID=<{$WebID}>"><img src="<{$xoops_url}>/uploads/tad_web/<{$WebID}>/logo/<{$web_logo}>" alt="web log" id="tad_web_logo" class="draggable" style="position: absolute; z-index: 1; top: <{$logo_top}>px; left: <{$logo_left}>px;"></a>
        </div>
    <{else}>
        <script>
            $(function() {
                var screen=$( window ).width();
                if(screen <= 480){
                    $('#head_bg').attr('src','<{$xoops_url}>/uploads/tad_web/<{$WebID}>/header_480.png');
                }else{
                    $('#head_bg').attr('src','<{$xoops_url}>/uploads/tad_web/<{$WebID}>/header.png');
                }
            });
        </script>
        <a href="index.php?WebID=<{$WebID}>"><img src="<{$xoops_url}>/uploads/tad_web/<{$WebID}>/header.png" alt="Web Title:<{$WebTitle}>" id="head_bg" class="img-rounded img-fluid img-resopnsive" style="margin-bottom: 10px;"><span class="sr-only visually-hidden"><{$WebTitle}></span></a>
    <{/if}>
<{/if}>

<{if $isMyWeb or $LoginMemID or $LoginParentID}>
    <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_my_menu.tpl"}>
<{else}>
    <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_login.tpl"}>
<{/if}>
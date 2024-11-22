<h3>
    <{$smarty.const._MD_TCW_BG_TOOLS}>
    <small>
        <{$smarty.const._MD_TCW_CLICK_TO_CHANG}>
    </small>
</h3>

<form action="config.php" method="post" enctype="multipart/form-data" class="form-horizontal myForm" role="form">

    <{$upform_bg|default:''}>

    <hr>

    <div class="form-group row mb-3">
        <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_BG_REPEAT}>
        </label>
        <div class="col-md-4">
            <select name="bg_repeat" id="bg_repeat" class="form-control form-select">
                <option value="" <{if $bg_repeat==""}>selected<{/if}>><{$smarty.const._MD_TCW_BG_REPEAT_NORMAL}></option>
                <option value="repeat-x" <{if $bg_repeat=="repeat-x"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_REPEAT_X}></option>
                <option value="repeat-y" <{if $bg_repeat=="repeat-y"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_REPEAT_Y}></option>
                <option value="no-repeat" <{if $bg_repeat=="no-repeat"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_NO_REPEAT}></option>
            </select>
        </div>

        <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_BG_ATTACHMENT}>
        </label>
        <div class="col-md-4">
            <select name="bg_attachment" id="bg_attachment" class="form-control form-select">
                <option value="" <{if $bg_attachment==""}>selected<{/if}>><{$smarty.const._MD_TCW_BG_ATTACHMENT_SCROLL}></option>
                <option value="fixed" <{if $bg_attachment=="fixed"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_ATTACHMENT_FIXED}></option>
            </select>
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_BG_POSITION}>
        </label>
        <div class="col-md-4">
            <select name="bg_postiton" id="bg_postiton" class="form-control form-select">
                <option value="left top" <{if $bg_postiton=="left top"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_POSITION_LT}></option>
                <option value="right top" <{if $bg_postiton=="right top"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_POSITION_RT}></option>
                <option value="left bottom" <{if $bg_postiton=="left bottom"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_POSITION_LB}></option>
                <option value="right bottom" <{if $bg_postiton=="right bottom"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_POSITION_RB}></option>
                <option value="center center" <{if $bg_postiton=="center center"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_POSITION_CC}></option>
                <option value="center top" <{if $bg_postiton=="center top"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_POSITION_CT}></option>
                <option value="center bottom" <{if $bg_postiton=="center bottom"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_POSITION_CB}></option>
            </select>
        </div>

        <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_BG_SIZE}>
        </label>
        <div class="col-md-4">
            <select name="bg_size" id="bg_size" class="form-control form-select">
                <option value="" <{if $bg_size==""}>selected<{/if}>><{$smarty.const._MD_TCW_BG_SIZE_NONE}></option>
                <option value="cover" <{if $bg_size=="cover"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_SIZE_COVER}></option>
                <option value="contain" <{if $bg_size=="contain"}>selected<{/if}>><{$smarty.const._MD_TCW_BG_SIZE_CONTAIN}></option>
            </select>
        </div>
    </div>

    <hr>

    <div style="width:100px; height:96px; display:inline-block; margin:4px;">
        <label style="width: 80px; height: 80px; background: <{$bg_color|default:''}>; border: 1px solid gray; background-size: contain;" id="none" class="bg_thumb">
        </label>

        <label class="del_img_box" style="font-size: 0.65rem; width:100%; overflow: hidden; line-height: 1rem;height: 1rem; white-space: nowrap;" id="del_img<{$bg.files_sn}>">
            <{$smarty.const._MD_TCW_CONFIG_NONE_BG}>
        </label>
    </div>
    <{if $all_bg|default:false}>
        <{foreach from=$all_bg item=bg}>
        <div style="width:100px; height:96px; display:inline-block; margin:4px;">
            <label style="width: 80px; height: 80px; background: #000000 url('<{$bg.tb_path}>') center center no-repeat; border: <{if $bg.file_name == $web_bg}>2px solid red<{else}>1px solid gray<{/if}>; background-size: contain;" id="<{$bg.file_name}>" class="bg_thumb">
            </label>

            <label class="del_img_box" style="font-size: 0.65rem; width:100%; overflow: hidden; line-height: 1rem;height: 1rem; white-space: nowrap;" id="del_img<{$bg.files_sn}>">
                <input type="checkbox" value="<{$bg.files_sn}>" name="del_file[<{$bg.files_sn}>]" title="<{$smarty.const._TAD_DEL}> <{$bg.file_name}>"> <{$smarty.const._TAD_DEL}> <{$bg.file_name}>
            </label>
        </div>
        <{/foreach}>
    <{/if}>
    <{foreach from=$all_default_bg item=bg}>
        <{if $bg.file_name|default:false}>
            <div style="width:100px; height:96px; display:inline-block; margin:4px;">
                <label style="width: 80px; height: 80px; background: #000000 url('<{$bg.tb_path}>') center center no-repeat; border: <{if $bg.file_name == $web_bg}>2px solid red<{else}>1px solid gray<{/if}>; background-size: contain;" id="<{$bg.file_name}>" class="def_bg_thumb">
                </label>

                <label style="font-size: 0.65rem; width:100%; overflow: hidden; line-height: 1rem;height: 1rem; white-space: nowrap;">
                    <{$bg.file_name}>
                </label>
            </div>
        <{/if}>
    <{/foreach}>

    <div class="text-center">
        <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
        <input type="hidden" name="op" value="upload_bg">
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>  <{$smarty.const._TAD_SAVE}></button>
    </div>
</form>

<script language="JavaScript">
    $().ready(function(){
        $(".bg_thumb").click(function(){
            var pic=$(this).attr("id");
            if(pic=="none"){
                $("body").css("background-image","none");
                $.post("config_ajax.php", {op: "save_bg" , filename: '', WebID: <{$WebID|default:''}>});
            }else{
                $("body").css("background-image","url('<{$xoops_url}>/uploads/tad_web/<{$WebID|default:''}>/bg/"+pic+"')");
                $(this).css("border","2px solid blue");
                $.post("config_ajax.php", {op: "save_bg" , filename: pic, WebID: <{$WebID|default:''}>});
            }
        });

        $(".def_bg_thumb").click(function(){
            var pic=$(this).attr("id");
            if(pic=="none"){
                $("body").css("background-image","none");
                $.post("config_ajax.php", {op: "save_bg" , filename: '', WebID: <{$WebID|default:''}>});
            }else{
                $("body").css("background-image","url('<{$xoops_url}>/modules/tad_web/images/bg/"+pic+"')");
                $(this).css("border","2px solid blue");
                $.post("config_ajax.php", {op: "save_bg" , filename: pic, WebID: <{$WebID|default:''}>});
            }
        });

        $('#bg_repeat').on('change', function() {
            $('body').css('background-repeat', $(this).val() || 'repeat');
            console.log(window.getComputedStyle(document.body).backgroundRepeat);
        });
        $('#bg_attachment').on('change', function() {
            $('body').css('background-attachment', $(this).val() || 'scroll');
            console.log(window.getComputedStyle(document.body).backgroundAttachment);
        });
        $('#bg_size').on('change', function() {
            $('body').css('background-size', $(this).val() || 'unset');
            console.log(window.getComputedStyle(document.body).backgroundSize);
        });
        $('#bg_postiton').on('change', function() {
            $('body').css('background-postiton', $(this).val() || 'left top');
            console.log(window.getComputedStyle(document.body).backgroundPostiton);
        });
    });
</script>
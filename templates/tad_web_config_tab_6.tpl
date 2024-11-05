<script language="JavaScript">
    function change_color(selector,css_name,css_val){
        $(selector).css(css_name,css_val);
    }
    function save_color(col){
        $.post("config_ajax.php", {op: "save_color" , col_name: $(col).attr('id'), col_val: $(col).val(), WebID: <{$WebID|default:''}>});
    }

    $(document).ready(function () {
        $('#container_bg_color').bind('colorpicked', function () {
            save_color(this);
        });
        $('#bg_color').bind('colorpicked', function () {
            save_color(this);
        });
        $('#navbar_bg_top').bind('colorpicked', function () {
            save_color(this);
        });
        $('#navbar_color').bind('colorpicked', function () {
            save_color(this);
        });
        $('#navbar_hover').bind('colorpicked', function () {
            save_color(this);
        });
        $('#navbar_color_hover').bind('colorpicked', function () {
            save_color(this);
        });
    });
</script>

<h3><{$smarty.const._MD_TCW_COLOR_TOOLS}></h3>
<form action="config.php" method="post" enctype="multipart/form-data" class="form-horizontal myForm" role="form">
    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_MAIN_NAV_TOP_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[navbar_bg_top]" class="form-control color" value="<{$navbar_bg_top|default:''}>" id="navbar_bg_top" data-text="true" data-hex="true" onChange="change_color('#tad_web_nav,.sf-menu li','background-color',this.value);" style="width:120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$navbar_bg_top|default:''}>
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_MAIN_NAV_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[navbar_color]" class="form-control color" value="<{$navbar_color|default:''}>" id="navbar_color" data-text="true" data-hex="true" onChange="change_color('.sf-menu a','color',this.value);" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$navbar_color|default:''}>
        </div>
    </div>

    <hr>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_MAIN_NAV_HOVER_BG_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[navbar_hover]" class="form-control color" value="<{$navbar_hover|default:''}>" id="navbar_hover" data-text="true" data-hex="true" onChange="change_color('.sf-menu li:hover,.sf-menu li.sfHover','background-color',this.value);" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$navbar_hover|default:''}>
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_MAIN_NAV_HOVER_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[navbar_color_hover]" class="form-control color" value="<{$navbar_color_hover|default:''}>" id="navbar_color_hover" data-text="true" data-hex="true" onChange="change_color('.sf-menu a:hover','color',this.value);" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$navbar_color_hover|default:''}>
        </div>
    </div>

    <hr>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_MAIN_BG_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[bg_color]" class="form-control color" value="<{$bg_color|default:''}>" id="bg_color" data-text="true" data-hex="true" onChange="change_color('body','background-color',this.value);" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$bg_color|default:''}>
        </div>
    </div>

    <hr>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_CONTAINER_BG_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[container_bg_color]" class="form-control color" value="<{$container_bg_color|default:''}>" id="container_bg_color" data-text="true" data-hex="true" onChange="change_color('#container','background-color',this.value);" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$container_bg_color|default:''}>
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_CENTER_TEXT_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[center_text_color]" class="form-control color" value="<{$center_text_color|default:''}>" id="center_text_color" data-text="true" data-hex="true" onChange="change_color('#web_center_block','color',this.value);" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$center_text_color|default:''}>
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_CENTER_LINK_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[center_link_color]" class="form-control color" value="<{$center_link_color|default:''}>" id="center_link_color" data-text="true" data-hex="true" onChange="change_color('#web_center_block a','color',this.value);" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$center_link_color|default:''}>
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_CENTER_HOVER_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[center_hover_color]" class="form-control color" value="<{$center_hover_color|default:''}>" id="center_hover_color" data-text="true" data-hex="true" onChange="change_color('#web_center_block a','color',this.value);" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$center_hover_color|default:''}>
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_CENTER_HEADER_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[center_header_color]" class="form-control color" value="<{$center_header_color|default:''}>" id="center_header_color" data-text="true" data-hex="true" onChange="change_color('#web_center_block h3','color',this.value);" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$center_header_color|default:''}>
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_CENTER_BORDER_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[center_border_color]" class="form-control color" value="<{$center_border_color|default:''}>" id="center_border_color" data-text="true" data-hex="true" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$center_border_color|default:''}>
        </div>
    </div>

    <hr>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_SIDE_BG_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[side_bg_color]" class="form-control color" value="<{$side_bg_color|default:''}>" id="side_bg_color" data-text="true" data-hex="true" onChange="change_color('#web_side_block','background-color',this.value);" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$side_bg_color|default:''}>
        </div>
    </div>


    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_SIDE_TEXT_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[side_text_color]" class="form-control color" value="<{$side_text_color|default:''}>" id="side_text_color" data-text="true" data-hex="true" onChange="change_color('#web_side_block','color',this.value);" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$side_text_color|default:''}>
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_SIDE_LINK_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[side_link_color]" class="form-control color" value="<{$side_link_color|default:''}>" id="side_link_color" data-text="true" data-hex="true" onChange="change_color('#web_side_block a','color',this.value);" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$side_link_color|default:''}>
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_SIDE_HOVER_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[side_hover_color]" class="form-control color" value="<{$side_hover_color|default:''}>" id="side_hover_color" data-text="true" data-hex="true" onChange="change_color('#web_side_block a','color',this.value);" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$side_hover_color|default:''}>
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_SIDE_HEADER_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[side_header_color]" class="form-control color" value="<{$side_header_color|default:''}>" id="side_header_color" data-text="true" data-hex="true" onChange="change_color('#web_center_block h3','color',this.value);" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$side_header_color|default:''}>
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_SIDE_BORDER_COLOR}>
        </label>
        <div class="col-md-4">
            <input type="text" name="color_setup[side_border_color]" class="form-control color" value="<{$side_border_color|default:''}>" id="side_border_color" data-text="true" data-hex="true" style="width: 120px; display: inline-block;">
        </div>
        <div class="col-md-5">
            <{$smarty.const._MD_TCW_MAIN_DEFAULT_COLOR}>: <{$side_border_color|default:''}>
        </div>
    </div>

    <hr>

    <div class="text-center">
        <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
        <input type="hidden" name="op" value="save_all_color">
        <a href="config.php?WebID=<{$WebID|default:''}>&op=default_color" class="btn btn-warning"><{$smarty.const._MD_TCW_DEFAULT_COLOR}></a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>  <{$smarty.const._TAD_SAVE}></button>
    </div>
</form>
<form action="config.php" method="post" enctype="multipart/form-data" class="form-horizontal myForm" role="form">
    <div class="row">
        <div class="col-md-8">
            <h3><{$smarty.const._MD_TCW_WEB_TOOLS}></h3>
            <{$cate_menu|default:''}>

            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label text-sm-right text-sm-end control-label">
                    <{$smarty.const._MD_TCW_WEB_NAME_SETUP}>
                </label>
                <div class="col-md-8">
                    <input type="text" name="WebName" value="<{$WebName|default:''}>" id="WebName" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_CLASS_WEB_NAME}>">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label text-sm-right text-sm-end control-label">
                    <{$smarty.const._MD_TCW_UPDATE_MY_NAME}>
                </label>
                <div class="col-md-8">
                    <input type="text" name="WebOwner" value="<{$WebOwner|default:''}>" id="WebOwner" class="validate[required] form-control" placeholder="<{$smarty.const._MD_TCW_UPDATE_MY_NAME}>">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label text-sm-right text-sm-end control-label">
                    <{$smarty.const._MD_TCW_UPLOAD_MY_PHOTO}>
                </label>
                <div class="col-md-8">
                    <{$upform_teacher|default:''}>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-md-4 col-form-label text-sm-right text-sm-end control-label">
                    <{$smarty.const._MD_TCW_OTHER_CLASS_SETUP}>
                </label>
                <div class="col-md-8">
                    <input type="text" name="other_web_url" value="<{$other_web_url|default:''}>" id="other_web_url" class="form-control" placeholder="https://">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <{if $teacher_thumb_pic|default:false}>
                <img src="<{$teacher_thumb_pic|default:''}>" alt="photo" class="img-rounded img-polaroid img-fluid img-responsive img-thumbnail">
            <{/if}>
        </div>
    </div>

    <h3><{$smarty.const._MD_TCW_THEME_TOOLS}></h3>
    <div class="row">
        <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_THEME_TOOLS_DEFAULT_THEME}>
        </label>
        <div class="col-md-4">
            <select name="defalut_theme" id="defalut_theme" class="form-control form-select">
                <option value="for_tad_web_theme" <{if $defalut_theme=="for_tad_web_theme"}>selected<{/if}>>for_tad_web_theme</option>
                <option value="for_tad_web_theme_2" <{if $defalut_theme=="for_tad_web_theme_2"}>selected<{/if}>>for_tad_web_theme_2</option>
            </select>
        </div>

        <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_USER_SIMPLE_MENU}>
        </label>
        <div class="col-md-4">
            <div class="form-check form-check-inline radio-inline">
                <label class="form-check-label" for="use_simple_menu_1">
                    <input class="form-check-input" id="use_simple_menu_1" type="radio" name="use_simple_menu" value="1" <{if $use_simple_menu=='1'}>checked<{/if}>>
                    <{$smarty.const._YES}>
                </label>
            </div>
            <div class="form-check form-check-inline radio-inline">
                <label class="form-check-label" for="use_simple_menu_0">
                    <input class="form-check-input" id="use_simple_menu_0" type="radio" name="use_simple_menu" value="0" <{if $use_simple_menu!='1'}>checked<{/if}>>
                    <{$smarty.const._NO}>
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_THEME_TOOLS_THEME_SIDE}>
        </label>
        <div class="col-md-4">
            <div class="form-check form-check-inline radio-inline">
                <input class="form-check-input" id="theme_side_left" type="radio" name="theme_side" value="left" <{if $theme_side=="left"}>checked<{/if}>>
                <label class="form-check-label" for="theme_side_left"><{$smarty.const._MD_TCW_THEME_TOOLS_THEME_SIDE_LEFT}></label>
            </div>
            <div class="form-check form-check-inline radio-inline">
                <input class="form-check-input" id="theme_side_none" type="radio" name="theme_side" value="none" <{if $theme_side=="none"}>checked<{/if}>>
                <label class="form-check-label" for="theme_side_none"><{$smarty.const._MD_TCW_THEME_TOOLS_THEME_SIDE_NONE}></label>
            </div>
            <div class="form-check form-check-inline radio-inline">
                <input class="form-check-input" id="theme_side_right" type="radio" name="theme_side" value="right" <{if $theme_side=="right"}>checked<{/if}>>
                <label class="form-check-label" for="theme_side_right"><{$smarty.const._MD_TCW_THEME_TOOLS_THEME_SIDE_RIGHT}></label>
            </div>
        </div>

        <label class="col-md-2 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TCW_THEME_TOOLS_FONT_SIZE}>
        </label>
        <div class="col-md-4">
            <input type="text" name="menu_font_size" value="<{$menu_font_size|default:''}>" class="form-control">
        </div>
    </div>

    <{if $login_method|default:false}>
        <{if $login_config==''}>
            <{assign var="login_config" value=$login_defval|default:''}>
        <{/if}>
        <h3><{$smarty.const._MD_TCW_WEB_OPENID_SETUP}></h3>
        <{assign var="i" value=0}>
        <{assign var="total" value=1}>
        <{foreach from=$login_method key=title item=openid}>
            <{if $i==0}>
                <div class="row">
            <{/if}>
            <div class="col-md-4">
                <label class="checkbox">
                    <input type="checkbox" name="login_method[]" value="<{$openid|default:''}>" <{if $openid|in_array:$login_config}>checked<{/if}>><{$title|default:''}>
                </label>
            </div>
            <{assign var="i" value=$i+1}>
            <{if $i == 3 || $total==$login_method_count}>
                </div>
                <{assign var="i" value=0}>
            <{/if}>
            <{assign var="total" value=$total+1}>
        <{/foreach}>
    <{/if}>

    <div class="text-center">
        <input type="hidden" name="WebID" value="<{$WebID|default:''}>">
        <input type="hidden" name="op" value="save_config">
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-disk" aria-hidden="true"></i>  <{$smarty.const._TAD_SAVE}></button>
    </div>
</form>

<hr>

<{if $Web.WebEnable=='1'}>
    <h3><{$smarty.const._MD_TCW_CLOSE_WEB}></h3>
    <div class="alert alert-warning">
        <p><{$smarty.const._MD_TCW_CLOSE_WEB_DESC}></p>
        <a href="config.php?WebID=<{$WebID|default:''}>&op=unable_my_web" class="btn btn-warning"><{$smarty.const._MD_TCW_CLOSE_WEB}></a>
    </div>
    <hr>
<{else}>
    <h3><{$smarty.const._MD_TCW_OPEN_WEB}></h3>
    <div class="alert alert-warning">
        <p><{$smarty.const._MD_TCW_OPEN_WEB_DESC}></p>
        <a href="config.php?WebID=<{$WebID|default:''}>&op=enable_my_web" class="btn btn-success"><{$smarty.const._MD_TCW_OPEN_WEB}></a>
    </div>
    <hr>
<{/if}>


<{if $Web.WebEnable!='1'}>
    <h3><{$smarty.const._MD_TCW_DEL_WEB}></h3>
    <div class="alert alert-danger">
        <p><{$smarty.const._MD_TCW_DEL_WEB_DESC}></p>
        <a href="javascript:delete_my_web('<{$WebID|default:''}>')" class="btn btn-danger"><{$smarty.const._MD_TCW_DEL_WEB}></a>
    </div>
<{/if}>
<link rel="stylesheet" href="<{$xoops_url}>/modules/tad_web/class/Slide-Push-Menus/css/style.css">
<style>
    #c-button--slide-right{
    width: 60px;
    height: 60px;
    position: fixed;
    bottom: 30px;
    right: 30px;
    cursor: pointer;
    z-index: 199;
    background: transparent url('images/login.png');
    }

    #c-button--slide-right:hover{
    width: 90px;
    height: 90px;
    bottom: 15px;
    right: 15px;
    background: transparent url('images/login_hover.png');
    }
</style>

<div id="o-wrapper">
    <div id="c-button--slide-right"></div>
</div>


<nav id="c-menu--slide-right" class="c-menu c-menu--slide-right">
    <button class="c-menu__close"><{$smarty.const._MD_TCW_WEB_CLOSE_MENU}> &rarr;</button>
    <div style="margin-left:10px;">
        <{if $LoginMemNickName==""}>
            <script type="text/javascript">
                $(document).ready(function() {

                    $("#login_method").change(function(event) {
                    var opt=$("#login_method").val();
                    if(opt=="openid_login"){
                        $("#openid_login").show();
                        $("#student_login").hide();
                        $("#xoops_login").hide();
                        $("#parent_login").hide();

                    }else if(opt=="student_login"){
                        $("#openid_login").hide();
                        $("#student_login").show();
                        $("#xoops_login").hide();
                        $("#parent_login").hide();

                    }else if(opt=="parent_login"){
                        $("#openid_login").hide();
                        $("#student_login").hide();
                        $("#xoops_login").hide();
                        $("#parent_login").show();

                    }else{
                        $("#openid_login").hide();
                        $("#student_login").hide();
                        $("#xoops_login").show();
                        $("#parent_login").hide();
                    }
                    });

                    $("#select_mems").change(function(event) {
                    if($("#select_mems").val()!=''){
                        $('#parent_login_div').show();
                    }else{
                        $('#parent_login_div').hide();
                    }
                    });
                });
            </script>

            <div class="my-border">
                <select id="login_method" class="form-control" style="margin-bottom: 10px;" title="search">
                    <{if $openid=='1'}>
                    <option value="openid_login"><{$smarty.const._MD_TCW_LOGIN_BY_OPENID}></option>
                    <{/if}>
                    <option value="xoops_login"><{$smarty.const._MD_TCW_LOGIN_BY_XOOPS}></option>
                    <option value="student_login"><{$student_title}><{$smarty.const._MD_TCW_LOGIN_BY_MEM}></option>
                    <{if $mem_parents=='1'}>
                    <option value="parent_login"><{$smarty.const._MD_TCW_LOGIN_BY_PARENTS}></option>
                    <{/if}>
                </select>

                <form action="<{$xoops_url}>/user.php" method="post" role="form" id="xoops_login" <{if $openid=='1'}>style="display: none;"<{/if}>>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-sm-right control-label" for="uname">
                            <{$smarty.const._MD_TCW_ID}>
                        </label>
                        <div class="col-md-8">
                            <input type="text" name="uname"  id="uname" placeholder="<{$smarty.const.TF_USER_ID}>"  class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-sm-right control-label" for="pass">
                            <{$smarty.const._MD_TCW_PASSWD}>
                        </label>
                        <div class="col-md-8">
                        <input type="password" name="pass" id="pass" placeholder="<{$smarty.const.TF_USER_PASS}>" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-sm-right control-label">
                        </label>
                        <div class="col-md-8">
                            <input type="hidden" name="xoops_redirect" value="<{$xoops_requesturi}>">
                            <input type="hidden" name="rememberme" value="On">
                            <input type="hidden" name="op" value="login">
                            <button type="submit" class="btn btn-primary btn-block"><{$smarty.const.TF_USER_ENTER}></button>
                        </div>
                    </div>
                    <p><a href="<{$xoops_url}>/user.php?op=logout&xoops_redirect=<{$xoops_requesturi}>" class="btn btn-sm btn-danger">若無法看見「網站擁有者 OpenID 登入」請按此登出，再登入即可</a></p>
                </form>

                <form action="aboutus.php" method="post" enctype="multipart/form-data" role="form" id="student_login" style="display: none;">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-sm-right control-label">
                            <{$student_title}><{$smarty.const._MD_TCW_ID}>
                        </label>
                        <div class="col-md-8 controls">
                            <input class="form-control" type="text" name="MemUname" title="<{$smarty.const._MD_TCW_PLEASE_INPUT}><{$student_title}><{$smarty.const._MD_TCW_ID}>" placeholder="<{$smarty.const._MD_TCW_PLEASE_INPUT}><{$student_title}><{$smarty.const._MD_TCW_ID}>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-sm-right control-label">
                            <{$student_title}><{$smarty.const._MD_TCW_PASSWD}>
                        </label>
                        <div class="col-md-8 controls">
                            <input class="form-control" type="password" name="MemPasswd" title="<{$smarty.const._MD_TCW_PLEASE_INPUT}><{$student_title}><{$smarty.const._MD_TCW_PASSWD}>" placeholder="<{$smarty.const._MD_TCW_PLEASE_INPUT}><{$student_title}><{$smarty.const._MD_TCW_PASSWD}>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-sm-right control-label">
                        </label>
                        <div class="col-md-8">
                            <input type="hidden" name="WebID" value="<{$WebID}>">
                            <input type="hidden" name="op" value="mem_login">
                            <button type="submit" class="btn btn-success btn-block"><{$smarty.const._MD_TCW_LOGIN}></button>
                        </div>
                    </div>
                </form>

                <form action="aboutus.php" method="post" enctype="multipart/form-data" role="form" id="parent_login" style="display: none;">
                    <div class="form-group row">
                        <label class="sr-only">
                        </label>
                        <div class="col-md-12 controls">
                            <{$login_cate_menu}>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="sr-only">
                        </label>
                        <div class="col-md-12 controls">
                            <select name='MemID' id='select_mems' title='select mems' class='form-control' style="display:none;"></select>
                        </div>
                    </div>

                    <div id="parent_login_div" style="display:none;">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-sm-right control-label">
                                <{$smarty.const._MD_TCW_PASSWD}>
                            </label>
                            <div class="col-md-9 controls">
                                <input class="form-control" type="password" name="ParentPasswd" title="<{$smarty.const._MD_TCW_PLEASE_INPUT}><{$smarty.const._MD_TCW_PASSWD}>" placeholder="<{$smarty.const._MD_TCW_PLEASE_INPUT}><{$smarty.const._MD_TCW_PASSWD}>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="sr-only">
                            </label>
                            <div class="col-md-12">
                                <input type="hidden" name="WebID" value="<{$WebID}>">
                                <input type="hidden" name="op" value="parent_login">
                                <button type="submit" class="btn btn-success btn-block"><{$smarty.const._MD_TCW_LOGIN}></button>
                            </div>
                        </div>
                    </div>

                    <{if $mem_parents=='1'}>
                        <div class="text-center">
                            <a href="aboutus.php?WebID=<{$WebID}>&op=forget_parent_passwd"><{$smarty.const._MD_TCW_FORGET_PARENTS_PASSWD}></a>
                            |
                            <a href="aboutus.php?WebID=<{$WebID}>&op=parents_account"><{$smarty.const._MD_TCW_REGIST_BY_PARENTS}></a>
                        </div>
                    <{/if}>
                </form>

                <{if $openid=='1'}>
                    <div id="openid_login" style="margin: 0px auto;">
                    <{foreach from=$tlogin item=login}>
                        <a href="<{$login.link}>" style="padding: 3px; margin: 5px; display: inline-block;">
                        <img src="<{$login.img}>" alt="<{$login.text}>" title="<{$login.text}>" style="width: 32px; height: 32px;">
                        </a>
                    <{/foreach}>
                    </div>
                <{/if}>
            </div>
        <{/if}>
    </div>
</nav>

<div id="c-mask" class="c-mask"></div><!-- /c-mask -->

<script src="<{$xoops_url}>/modules/tad_web/class/Slide-Push-Menus/js/menu.js"></script>

<script>
    /**
    * Slide right instantiation and action.
    */
    var slideRight = new Menu({
        wrapper: '#o-wrapper',
        type: 'slide-right',
        menuOpenerClass: '.c-button',
        maskId: '#c-mask'
    });

    var slideRightBtn = document.querySelector('#c-button--slide-right');

    slideRightBtn.addEventListener('click', function(e) {
        e.preventDefault;
        slideRight.open();
    });
</script>

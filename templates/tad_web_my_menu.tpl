<link rel="stylesheet" href="<{$xoops_url}>/modules/tad_web/class/Slide-Push-Menus/css/style.css">
<script type='text/javascript' src='<{$xoops_url}>/modules/tad_web/class/jquery.marquee.js'></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.marquee').marquee({
            duplicated: true,
            pauseOnHover: true,
            duration :8000,
            startVisible :false,
            gap :80
        });
    });
</script>

<style>
    #c-button--slide-right{
    width: 60px;
    height: 60px;
    position: fixed;
    bottom: 30px;
    right: 30px;
    cursor: pointer;
    z-index: 199;
    background: transparent url('images/my_menu.png');
    }

    #c-button--slide-right:hover{
    width: 90px;
    height: 90px;
    bottom: 15px;
    right: 15px;
    background: transparent url('images/my_menu_hover.png');
    }

    .marquee {
    overflow: hidden;
    border: 1px solid #E3EEEF;
    background: #E3EEEF;
    }
</style>

<div id="o-wrapper">
    <div id="c-button--slide-right"></div>
</div>

<nav id="c-menu--slide-right" class="c-menu c-menu--slide-right" style="background: #ffffff; color:#000000;">
    <button class="c-menu__close"><{$smarty.const._MD_TCW_WEB_CLOSE_MENU}> &rarr;</button>
    <{if $marquee_arr|default:false}>
        <div class="marquee">
            <{foreach from=$marquee_arr item=marquee}>
                <i class="fa fa-chevron-circle-right"></i> <a href="index.php?WebID=<{$WebID}>&op=notice&NoticeID=<{$marquee.NoticeID}>" class="show_notice" data-fancybox-type="iframe"><{$marquee.NoticeShortDate}> <{$marquee.NoticeTitle}></a>
            <{/foreach}>
        </div>
    <{/if}>

    <div style="margin-left:10px;">
        <h3><{$say_hi}></h3>
        <{if $smarty.session.tad_web_adm|default:false}>
        <div style="font-size: 0.7rem; color:rgb(180, 76, 76)"><{$adm_defaltWebName}></div>
        <{/if}>
        <div style="margin:10px 0px;">
            <a href="<{$xoops_url}>/modules/tad_web/index.php?WebID=<{$defaltWebID}>">
                <i class="fa fa-home" aria-hidden="true"></i>
                <{$back_home}>
            </a>
        </div>
        <div style="margin:10px 0px;">
            <a href="<{$xoops_url}>/modules/tad_web/index.php?op=clear_block_cache&WebID=<{$defaltWebID}>">
                <i class="fa fa-recycle" aria-hidden="true"></i>
                <{$smarty.const._MD_TCW_RE_GENERATE_SCREEN}>
            </a>
        </div>


        <{if $user_kind=="mem"}>
            <div class="btn-group">
                <a href="<{$xoops_url}>/modules/tad_web/aboutus.php?WebID=<{$LoginWebID}>&CateID=<{$LoginCateID}>&MemID=<{$LoginMemID}>&op=show_stu" class="btn btn-info">
                    <i class="fa fa-check-square-o"></i>
                    <{$smarty.const._MD_TCW_ABOUTUS_MY_ACCOUNT}>
                </a>
                <a href="aboutus.php?WebID=<{$LoginWebID}>&CateID=<{$default_class}>&MemID=<{$LoginMemID}>&op=edit_stu" class="btn btn-success">
                    <i class="fa fa-pencil-square-o"></i>
                    <{$smarty.const._MD_TCW_ABOUTUS_EDIT_ACCOUNT}>
                </a>
            </div>

            <table class="table">
                <{foreach from=$menu_plugins item=plugin}>
                    <{if $plugin.menu=='1'}>
                        <{if $plugin.enable=='1'}>
                            <tr>
                                <td>
                                    <a href="<{$xoops_url}>/modules/tad_web/<{$plugin.url}>">
                                        <i class="fa <{$plugin.icon}>"></i>
                                        <{$plugin.title}>
                                    </a>
                                </td>

                                <td>
                                    <{if $plugin.add=='1' and $plugin.dirname|in_array:$add_power}>
                                        <a href="<{$xoops_url}>/modules/tad_web/<{$plugin.url}>&op=edit_form" title="<{$smarty.const._MD_TCW_ADD}><{$plugin.short}>">
                                            <i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$plugin.short}>
                                        </a>
                                    <{/if}>
                                </td>
                            </tr>
                        <{/if}>
                    <{/if}>
                <{/foreach}>
            </table>

            <div class="d-grid gap-2">
                <a href="<{$xoops_url}>/modules/tad_web/aboutus.php?op=mem_logout&WebID=<{$WebID}>" class="btn btn-danger btn-block">
                    <i class="fa fa-check-square-o"></i>
                    <{$smarty.const._MD_TCW_EXIT}>
                </a>
            </div>
        <{elseif $user_kind=="parent"}>
            <div class="btn-group">
                <a href="<{$xoops_url}>/modules/tad_web/aboutus.php?WebID=<{$LoginWebID}>&CateID=<{$LoginCateID}>&ParentID=<{$LoginParentID}>&op=show_parent" class="btn btn-info">
                    <i class="fa fa-check-square-o"></i>
                    <{$smarty.const._MD_TCW_ABOUTUS_MY_ACCOUNT}>
                </a>
                <a href="aboutus.php?WebID=<{$LoginWebID}>&CateID=<{$default_class}>&ParentID=<{$LoginParentID}>&op=show_parent" class="btn btn-success">
                    <i class="fa fa-pencil-square-o"></i>
                    <{$smarty.const._MD_TCW_ABOUTUS_EDIT_ACCOUNT}>
                </a>
            </div>

            <table class="table">
                <{foreach from=$menu_plugins item=plugin}>
                    <{if $plugin.menu=='1'}>
                        <{if $plugin.enable=='1'}>
                            <tr>
                                <td>
                                    <a href="<{$xoops_url}>/modules/tad_web/<{$plugin.url}>">
                                        <i class="fa <{$plugin.icon}>"></i>
                                        <{$plugin.title}>
                                    </a>
                                </td>

                                <td>
                                    <{if $plugin.add=='1' and $plugin.dirname|in_array:$add_power}>
                                        <a href="<{$xoops_url}>/modules/tad_web/<{$plugin.url}>&op=edit_form" title="<{$smarty.const._MD_TCW_ADD}><{$plugin.short}>">
                                            <i class="fa fa-plus"></i> <{$smarty.const._MD_TCW_ADD}><{$plugin.short}>
                                        </a>
                                    <{/if}>
                                </td>
                            </tr>
                        <{/if}>
                    <{/if}>
                <{/foreach}>
            </table>

            <div class="d-grid gap-2">
                <a href="<{$xoops_url}>/modules/tad_web/aboutus.php?op=parent_logout&WebID=<{$WebID}>" class="btn btn-danger btn-block">
                    <i class="fa fa-check-square-o"></i>
                    <{$smarty.const._MD_TCW_EXIT}>
                </a>
            </div>
        <{else}>

            <script type="text/javascript" src="<{$xoops_url}>/modules/tad_web/class/bootstrap-progressbar/bootstrap-progressbar.js"></script>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('.progress .progress-bar').progressbar({display_text: 'fill'});
                });
            </script>

            <{if $defaltWebID|default:false}>
                <{if $webs|default:false}>
                    <select class="form-control" title="Select Web" onChange="location.href=this.value">
                        <{foreach from=$webs item=web}>
                            <option value="<{$web.url}>" <{if $web.WebID==$WebID}>selected<{/if}>><{$web.title}> (<{$web.name}>)</option>
                        <{/foreach}>
                    </select>
                <{/if}>

                <div class="btn-group">
                    <a href="<{$xoops_url}>/modules/tad_web/config.php?WebID=<{$defaltWebID}>" class="btn btn-success">
                        <i class="fa fa-check-square-o"></i>
                        <{$smarty.const._MD_TCW_WEB_CONFIG}>
                    </a>
                    <a href="<{$xoops_url}>/modules/tad_web/block.php?WebID=<{$defaltWebID}>" class="btn btn-info">
                        <i class="fa fa-check-square-o"></i>
                        <{$smarty.const._MD_TCW_WEB_BLOCK_CONFIG}>
                    </a>
                    <a href="<{$xoops_url}>/modules/tad_web/block.php?WebID=<{$defaltWebID}>&op=add_block" class="btn btn-info" title="<{$smarty.const._MD_TCW_BLOCK_ADD}>">
                        <i class="fa fa-plus"></i><span class="sr-only visually-hidden"><{$smarty.const._MD_TCW_BLOCK_ADD}></span>
                    </a>
                </div>

                <table class="table">
                    <{foreach from=$menu_plugins item=plugin}>
                        <{if $plugin.menu=='1'}>
                            <{if $plugin.enable=='1'}>
                                <tr>
                                    <td>
                                        <a href="<{$xoops_url}>/modules/tad_web/<{$plugin.url}>">
                                        <i class="fa <{$plugin.icon}>"></i>
                                        <{$plugin.title}>
                                        </a>
                                    </td>


                                    <td>
                                        <a href="<{$xoops_url}>/modules/tad_web/setup.php?WebID=<{$plugin.WebID}>&plugin=<{$plugin.dirname}>&op=plugin_setup" title="<{$smarty.const._MD_TCW_SETUP}><{$plugin.short}>">
                                        <i class="fa fa-wrench"></i><span class="sr-only visually-hidden"><{$smarty.const._MD_TCW_SETUP}><{$plugin.short}></span>
                                        </a>
                                    </td>

                                    <td>
                                        <{if $plugin.add=='1'}>
                                            <a href="<{$xoops_url}>/modules/tad_web/<{$plugin.url}>&op=edit_form" title="<{$smarty.const._MD_TCW_ADD}><{$plugin.short}>">
                                                <i class="fa fa-plus"></i><span class="sr-only visually-hidden"><{$smarty.const._MD_TCW_ADD}><{$plugin.short}></span>
                                            </a>
                                        <{/if}>
                                    </td>
                                    <td>
                                        <{if $plugin.cate=='1'}>
                                            <a href="<{$xoops_url}>/modules/tad_web/cate.php?WebID=<{$plugin.WebID}>&ColName=<{$plugin.dirname}>&table=<{$plugin.cate_table}>"  title="<{$plugin.short}><{$smarty.const._MD_TCW_CATE_TOOLS}>">
                                                <i class="fa fa-folder-open"></i><span class="sr-only visually-hidden"><{$plugin.short}><{$smarty.const._MD_TCW_CATE_TOOLS}></span>
                                            </a>
                                        <{/if}>
                                    </td>
                                    <td>
                                        <{if $plugin.assistant=='1'}>
                                            <a href="<{$xoops_url}>/modules/tad_web/assistant.php?WebID=<{$plugin.WebID}>&plugin=<{$plugin.dirname}>"  title="<{$plugin.short}><{$smarty.const._MD_TCW_CATE_ASSISTANT}>">
                                                <i class="fa fa-male"></i><span class="sr-only visually-hidden"><{$plugin.short}><{$smarty.const._MD_TCW_CATE_ASSISTANT}></span>
                                            </a>
                                        <{/if}>
                                    </td>
                                </tr>
                            <{else}>
                                <tr style="background-color: #dfdfdf;">
                                    <td style="background-color: #dfdfdf; color:#5f5f5f;">
                                        <i class="fa <{$plugin.icon}>"></i>
                                        <{$plugin.title}>
                                    </td>
                                    <td colspan=4 style="background-color: #dfdfdf;">
                                        <a href="<{$xoops_url}>/modules/tad_web/config.php?WebID=<{$plugin.WebID}>&dirname=<{$plugin.dirname}>&op=enabe_plugin" class="btn btn-primary btn-sm btn-xs"><{$smarty.const._MD_TCW_ENABLE_PLUGIN}></a>
                                    </td>
                                </tr>
                            <{/if}>
                        <{/if}>
                    <{/foreach}>
                </table>

                <div class="progress progress-striped">
                    <div class="progress-bar progress-bar-<{$progress_color}>" role="progressbar" data-transitiongoal="<{$percentage}>"></div>
                </div>
                <span title="<{$defaltWebID}>"><{$defaltWebName}></span><{$smarty.const._MD_TCW_USED_SPACE}><{$size}>MB/<{$quota}>MB (<a href="index.php?op=check_quota&WebID=<{$defaltWebID}>"><{$percentage}>%</a>)

            <{/if}>

            <div class="d-grid gap-2">
                <{if $closed_webs|default:false}>
                    <{foreach from=$closed_webs item=web}>
                        <a href="<{$web.url}>" class="btn btn-secondary btn-block"><{$smarty.const._MD_TCW_ENABLE}> <{$web.name}></a>
                    <{/foreach}>
                <{/if}>

                <a href="<{$xoops_url}>/modules/tad_web/aboutus.php?op=mem_logout&WebID=<{$WebID}>" class="btn btn-danger btn-block"><i class="fa fa-sign-out"></i> <{$smarty.const.TF_USER_EXIT}></a>
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

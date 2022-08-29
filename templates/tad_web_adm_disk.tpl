<form action="disk.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal">
    <div class="input-group">
        <div class="input-group-prepend input-group-addon">
            <span class="input-group-text">WebID</span>
        </div>
        <input type="text" name="WebID" class="form-control" placeholder="WebID">
        <div class="input-group-append input-group-btn">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SEARCH}></button>
        </div>
        <input type="hidden" name="op" value="<{$next_op}>">
    </div>
</form>

<{if $op=="view_file"}>
    <h2><{$dir}></h2>
    <{$ztree_code}>
<{else}>
    <script type="text/javascript" src="<{$xoops_url}>/modules/tad_web/class/bootstrap-progressbar/bootstrap-progressbar.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.progress .progress-bar').progressbar({display_text: 'fill'});
        });
    </script>

    <div class="container-fluid">
        <h2><{$smarty.const._MA_TCW_DISK_TOTAL_SPACE_STATUS}></h2>
        <h3><{$smarty.const._MA_TCW_DISK_SPACE_QUOTA}><{$user_space_quota}>MB<{$smarty.const._MA_TCW_DISK_SPACE_TOTAL}><{$total_space}><{$smarty.const._MA_TCW_DISK_AVAILABLE_SPACE}><{$free_space}></h3>

        <{$bar}>

        <form action="disk.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">
            <table class="table table-striped table-hover">
                <tr>
                    <th><{$smarty.const._MA_TCW_TEAMNAME}></th>
                    <th><{$smarty.const._MA_TCW_TEAMTITLE}></th>
                    <th><{$smarty.const._MA_TCW_TEAMLEADER}></th>
                    <th><{$smarty.const._MA_TCW_MEM_AMOUNT}></th>
                    <th><{$smarty.const._MA_TCW_TEAMCOUNTER}></th>
                    <th style="width:160px;"><{$smarty.const._MA_TCW_DISK_TOTAL_SPACE}></th>
                    <th><{$smarty.const._MA_TCW_DISK_PATH}></th>
                    <th style="min-width:200px; max-width:400px;"><{$smarty.const._MA_TCW_DISK_TOTAL_SPACE}></th>
                </tr>
                <tbody>
                <{foreach from=$space key=WebID item=space}>
                    <{assign var="class" value=$data.$WebID}>
                    <tr>
                        <td>
                        <{if $class.WebEnable=='1'}>
                            <img src="../images/show1.gif" alt="<{$smarty.const._TAD_ENABLE}>">
                        <{else}>
                            <img src="../images/show0.gif" alt="<{$smarty.const._TAD_UNABLE}>">
                        <{/if}>

                        <a href="../index.php?WebID=<{$WebID}>" target="_blank"><{$class.WebName}></a>
                        </td>

                        <td>
                            <a href="../index.php?WebID=<{$WebID}>" target="_blank"><{$class.WebTitle}></a>
                        </td>

                        <td>
                            <{$class.WebOwner}> (<{$class.uname}>)
                        </td>

                        <td>
                            <{$class.memAmount}>
                        </td>

                        <td>
                            <{$class.WebCounter}>
                        </td>

                        <td>
                            <{$class.disk_used_space}>MB / <input type="text" name="space_quota[<{$class.WebID}>]" value="<{$class.space_quota}>" style="width:60px;">MB
                        </td>

                        <td>
                            <a href="disk.php?op=view_file&WebID=<{$class.WebID}>"><{$class.disk_space}></a>
                            <a href="disk.php?op=check_quota&WebID=<{$class.WebID}>"><i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>
                        </td>

                        <td>
                            <div class="progress progress-striped">
                                <div class="progress-bar progress-bar-<{$class.progress_color}>" role="progressbar" data-transitiongoal="<{$class.quota}>"></div>
                            </div>
                        </td>
                    </tr>
                <{/foreach}>
                </tbody>
            </table>

            <div class="text-center">
                <input type="hidden" name="g2p" value="<{$smarty.get.g2p}>">
                <button type="submit" class="btn btn-primary" name="op" value="save_disk_setup"><{$smarty.const._TAD_SAVE}></button>
            </div>
        </form>
        <{$bar}>
    </div>
<{/if}>
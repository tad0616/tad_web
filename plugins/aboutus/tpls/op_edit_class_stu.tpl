<h3>
    <{if $cate.CateName|default:false}><a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>"><{$cate.CateName}></a><{/if}>
    <{$edit_student|default:''}>
</h3>
<{if $students|default:false}>
    <div class="text-right text-end" style="margin: 30px 0px;">
        <a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>&op=edit_stu" class="btn btn-primary"><{$add_stud|default:''}></a>
    </div>
    <table class="table table-striped table-bordered table-hover table-sm">
        <tr>
            <th><{$smarty.const._MD_TCW_MEM_NUM}></th>
            <th><{$smarty.const._MD_TCW_MEM_NAME}></th>
            <th><{$smarty.const._MD_TCW_MEM_UNICODE}></th>
            <th><{$smarty.const._MD_TCW_MEM_BIRTHDAY}></th>
            <th><{$smarty.const._MD_TCW_MEM_SEX}></th>
            <th><{$smarty.const._MD_TCW_MEM_NICKNAME}></th>
            <th><{$smarty.const._MD_TCW_MEM_UNAME}></th>
            <th><{$smarty.const._MD_TCW_MEM_PASSWD}></th>
            <th><{$smarty.const._TAD_FUNCTION}></th>
        </tr>
        <{foreach from=$students item=stud}>
            <tr>
                <td style="text-align: center;"><{$stud.MemNum}></td>
                <td style="text-align: center;"><a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>&MemID=<{$stud.MemID}>&op=show_stu"><{$stud.MemName}></a></td>
                <td style="text-align: center;"><{$stud.MemUnicode}></td>
                <td style="text-align: center;"><{$stud.MemBirthday}></td>
                <td style="text-align: center;"><{$stud.MemSex}></td>
                <td><{$stud.MemNickName}></td>
                <td><{$stud.MemUname}></td>
                <td><{$stud.MemPasswd}></td>
                <td style="text-align: center;"><a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$CateID|default:''}>&MemID=<{$stud.MemID}>&op=edit_stu" class="btn btn-sm btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a></td>
            </tr>
        <{/foreach}>
    </table>
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
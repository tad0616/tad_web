<h2><{$smarty.const._MD_TCW_IMPORT_PREVIEW}></h2>

<{$smarty.const._MD_TCW_IMPORT_DESCRIPT}>

<form action="aboutus.php" method="post">
    <table class="table table-striped table-bordered table-hover table-sm">
        <tr>
            <th><{$smarty.const._MD_TCW_MEM_NUM}></th>
            <th><{$smarty.const._MD_TCW_MEM_NAME}></th>
            <th><{$smarty.const._MD_TCW_MEM_UNICODE}></th>
            <th><{$smarty.const._MD_TCW_MEM_BIRTHDAY}></th>
            <th><{$smarty.const._MD_TCW_MEM_SEX}></th>
            <th><{$smarty.const._MD_TCW_MEM_NICKNAME}></th>
        </tr>
        <{$stud_chk_table}>
    </table>
    <input type="hidden" name="op" value="import2DB">
    <input type="hidden" name="WebID" value="<{$WebID}>">
    <input type="hidden" name="CateID" value="<{$CateID}>">
    <input type="hidden" name="newCateName" value="<{$newCateName}>">
    <button type="submit" class="btn btn-primary"><{$smarty.const._MD_TCW_IMPORT}></button>
</form>
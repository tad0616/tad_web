<{if $result=='1'}>
    <h2><{$smarty.const._MD_TCW_ABOUTUS_PARENT_ENABLE_SUCCESS}></h2>
    <div class="alert alert-success">
        <p><{$smarty.const._MD_TCW_ABOUTUS_PARENT_ENABLE_SUCCESS_CONTENT}></p>
    </div>
<{else}>
    <h2><{$smarty.const._MD_TCW_ABOUTUS_PARENT_ENABLE_FAILED}></h2>
    <div class="alert alert-danger">
        <{$failed_content}>
    </div>
<{/if}>
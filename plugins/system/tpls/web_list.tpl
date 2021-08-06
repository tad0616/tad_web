<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
    <{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>

    <select size=5 title="Select Web" onChange="location.href=this.value" class="form-control">
        <{foreach from=$bc.webs item=web}>
            <option value="<{$web.url}>" <{if $bc.DefWebID == $web.WebID}>selected="selected"<{/if}>><{if $web.name}><{$web.name}><{else}><{$web.title}><{/if}> <{if $web.title!==$web.name and $web.name!=''}>(<{$web.title}>)<{/if}></option>
        <{/foreach}>
    </select>
<{/if}>

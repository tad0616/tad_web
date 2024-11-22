<{if $block.webs|default:false}>
    <{if $block.DefWebID|default:false}>

    <{/if}>
    <select size=5 onChange="location.href=this.value" class="form-control form-select" title="Select Web">
        <{foreach item=web from=$block.webs}>
            <option value="<{$web.url}>" <{if $block.DefWebID == $web.WebID}>selected="selected"<{/if}>><{if $web.name|default:false}><{$web.name}><{else}><{$web.title}><{/if}> <{if $web.title!==$web.name and $web.name!=''}>(<{$web.title}>)<{/if}></option>
        <{/foreach}>
    </select>
<{/if}>
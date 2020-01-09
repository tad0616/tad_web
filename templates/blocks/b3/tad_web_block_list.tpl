<{if $block.webs}>
  <{if $block.DefWebID}>

  <{/if}>
  <div class="row">
    <div class="col-sm-12">
      <select size=5 onChange="location.href=this.value" class="form-control">
      <{foreach item=web from=$block.webs}>
        <option value="<{$web.url}>" <{if $block.DefWebID == $web.WebID}>selected="selected"<{/if}>><{if $web.name}><{$web.name}><{else}><{$web.title}><{/if}> <{if $web.title!==$web.name and $web.name!=''}>(<{$web.title}>)<{/if}></option>
      <{/foreach}>
      </select>
    </div>
  </div>
<{/if}>
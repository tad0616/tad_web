<{assign var="bc" value=$block.BlockContent}>
<{includeq file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
<script type="text/javascript">
  $(document).ready(function(){
    $("#get_moedict").click(function(event) {
      $("#get_moedict").attr("href","https://www.moedict.tw/"+$('#search_moedict').val());
    });

    $("#search_moedict").keypress(function(e){
      code = (e.keyCode ? e.keyCode : e.which);
      if (code == 13)
      {
        // alert("https://www.moedict.tw/"+$('#search_moedict').val());
        $.colorbox({
          href:"https://www.moedict.tw/"+$('#search_moedict').val(),
          iframe:true ,
          width:'80%' ,
          height:'90%'});
      }
    });
  });
</script>
<div class="input-group">
  <label for="search_moedict" class="sr-only"><{$smarty.const._MD_TCW_SEARCH_MOEDICT_KEYWORD}></label>
  <input type="text" id="search_moedict" class="form-control" placeholder="<{$smarty.const._MD_TCW_SEARCH_MOEDICT_KEYWORD}>">
  <div class="input-group-append input-group-btn">
    <a href="#" class="btn btn-primary" id="get_moedict"><{$smarty.const._MD_TCW_SEARCH_MOEDICT}></a>
  </div>
</div>
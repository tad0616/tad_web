<{assign var="bc" value=$block.BlockContent}>
<div class="row">
  <{if $bc.class_pic_thumb}>
    <div class="col-sm-4">
      <div class="well" style="background-image:url('<{$bc.class_pic_thumb}>');background-repeat:no-repeat;background-size:cover;background-position:center; height:150px;"></div>
    </div>
  <{/if}>
  <div class="col-sm-<{if $bc.class_pic_thumb}>8<{else}>12<{/if}>">

    <h3><a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$bc.CateID}>"><{$bc.cate.CateName}></a></h3>
    <div class="well">
      <div>
        <{$bc.teacher_name}><{$smarty.const._TAD_FOR}><{$bc.WebOwner}>
      </div>
      <div>
        <{$bc.student_amount}><{$smarty.const._TAD_FOR}> <{$smarty.const._MD_TCW_TOTAL}>
        <{$bc.class_total}> <{$smarty.const._MD_TCW_PEOPLE}>
        <a href="#" class="btn btn-info btn-xs">
          <i class="fa fa-male"></i> <{$bc.class_boy}>
        </a>
        <a href="#" class="btn btn-danger btn-xs">
          <i class="fa fa-female"></i> <{$bc.class_girl}>
        </a>
      </div>
    </div>
  </div>
</div>

<{assign var="bc" value=$block.BlockContent}>
<div class="row">
    <{if $bc.class_pic_thumb|default:false}>
        <div class="col-md-4">
            <div class="my-border" style="background-image:url('<{$bc.class_pic_thumb}>');background-repeat:no-repeat;background-size:cover;background-position:center; height:150px;"></div>
        </div>
    <{/if}>
    <div class="col-md-<{if $bc.class_pic_thumb|default:false}>8<{else}>12<{/if}>">
        <{if $bc.cate.CateName|default:false}><h3><a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$bc.CateID}>"><{$bc.cate.CateName}></a></h3><{/if}>
        <div class="my-border">
            <div>
                <{$bc.teacher_name}><{$smarty.const._TAD_FOR}><{$bc.WebOwner}>
            </div>
            <div>
                <{$bc.student_amount}><{$smarty.const._TAD_FOR}> <{$smarty.const._MD_TCW_TOTAL}>
                <{$bc.class_total}> <{$smarty.const._MD_TCW_PEOPLE}>
                <a href="#" class="btn btn-info btn-sm btn-xs"><i class="fa fa-male"></i> <{$bc.class_boy}></a>
                <a href="#" class="btn btn-danger btn-sm btn-xs"><i class="fa fa-female"></i> <{$bc.class_girl}></a>
            </div>
        </div>
    </div>
</div>

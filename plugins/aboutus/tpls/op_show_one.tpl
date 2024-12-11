<div class="row">
    <div class="col-md-4">
        <{if $class_pic_thumb|default:false}>
            <div class="my-border" style="background-image:url('<{$class_pic_thumb|default:''}>');background-repeat:no-repeat;background-size:cover;background-position:center; height:<{if $isMyWeb|default:false}>220px<{else}>150px<{/if}>;"></div>
        <{else}>
            <div class="my-border" style="height: 150px; line-height:  100px; text-align: center;"><{$no_class_photo|default:''}></div>
        <{/if}>
    </div>
    <div class="col-md-8">
        <{$cate_menu|default:''}>

        <div class="my-border">
            <div><{$teacher_name|default:''}><{$smarty.const._TAD_FOR}><{$WebOwner|default:''}></div>
            <div>
                <{$student_amount|default:''}><{$smarty.const._TAD_FOR}> <{$smarty.const._MD_TCW_TOTAL}>
                <{$class_total|default:''}> <{$smarty.const._MD_TCW_PEOPLE}>
                <a href="#" class="btn btn-info btn-sm btn-xs">
                    <i class="fa fa-male"></i> <{$class_boy|default:''}>
                </a>
                <a href="#" class="btn btn-danger btn-sm btn-xs">
                    <i class="fa fa-female"></i> <{$class_girl|default:''}>
                </a>
            </div>
        </div>

        <{if $all_mems|default:false}>
            <style>
                .draggable {padding: 5px; margin: 0 10px 10px 0; font-size: 80%; border:0px dotted gray;position:absolute;}
                #snaptarget {width:640px;height: 540px; border:1px solid black;background:#CC6633 url('images/classroom2.png') center center no-repeat;position:relative;}
            </style>

            <script>
                function IsNumeric(n){
                    return !isNaN(n);
                }

                $(function(){
                    $('#getit').click(function() {

                        var numLow = <{$min|default:''}>;
                        var numHigh = <{$max|default:''}>;

                        var adjustedHigh = (parseFloat(numHigh) - parseFloat(numLow)) + 1;

                        var numRand = Math.floor(Math.random()*adjustedHigh) + parseFloat(numLow);

                        if ((IsNumeric(numLow)) && (IsNumeric(numHigh)) && (parseFloat(numLow) <= parseFloat(numHigh)) && (numLow != '') && (numHigh != '')) {
                            $('#randomnumber').html('<div class="my-border"><{$smarty.const._MD_TCW_GOT_YOU}>' + numRand +'<{$smarty.const._MD_TCW_NUMBER}></div>');
                            $('#'+numRand).clone().appendTo('#MemRandList').css('top','0px').css('left','0px').css('position','relative').css('float','left');
                        } else {
                            $('#randomnumber').html('<{$smarty.const._MD_TCW_AGAIN}>');
                        }

                        return false;
                    });


                    $('#clear').click(function() {
                        $('#MemRandList').html('');
                        $('#randomnumber').html('');
                    });

                });
            </script>


            <{if $isMyWeb|default:false}>
                <{if 'position'|in_array:$mem_function and !'all_dont'|in_array:$mem_function}>
                    <a class="btn btn-success" href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$CateID|default:''}>&op=edit_position"><{$smarty.const._MD_TCW_STUDENT_POSITION}></a>
                <{/if}>
                <a class="btn btn-info" href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$CateID|default:''}>&op=edit_class_stu"><{$edit_student|default:''}></a>
                <{if 'export'|in_array:$mem_function and !'all_dont'|in_array:$mem_function}>
                    <a class="btn btn-warning" href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$CateID|default:''}>&op=export_config"><{$smarty.const._MD_TCW_ABOUTUS_EXPORT}></a>
                <{/if}>
            <{/if}>

            <{if 'lotto'|in_array:$mem_function and !'all_dont'|in_array:$mem_function}>
                <button id="getit" class="btn btn-primary"><{$smarty.const._MD_TCW_GET_SOMEONE}></button>
                <button id="clear" class="btn btn-danger"><{$smarty.const._MD_TCW_CLEAR}></button>
            <{/if}>

            <{if 'slot'|in_array:$mem_function and !'all_dont'|in_array:$mem_function}>
                <a class="btn btn-warning" href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$CateID|default:''}>&op=mem_slot"><{$smarty.const._MD_TCW_ABOUTUS_SLOT}></a>
            <{/if}>
        <{/if}>
    </div>
</div>

<{if $all_mems|default:false}>
    <div id="randomnumber" style="font-size:100%;"></div>
    <div id="MemRandList"></div>

    <{if $mem_list_mode=="table"}>
        <table class="table table-striped table-bordered table-hover table-sm">
            <tr>
                <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_NAME}></th>
                <th style="text-align: center;" style="text-align: center;"><{$smarty.const._MD_TCW_MEM_SEX}></th>
                <{if 'MemNickName'|in_array:$mem_column}>
                    <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_NICKNAME}></th>
                <{/if}>
                <{if 'MemExpertises'|in_array:$mem_column}>
                    <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_EXPERTISES}></th>
                <{/if}>
                <{if 'MemClassOrgan'|in_array:$mem_column}>
                    <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_CLASSORGAN}></th>
                <{/if}>
                <{if 'AboutMem'|in_array:$mem_column}>
                    <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_ABOUTME}></th>
                <{/if}>
                <{if $isMyWeb|default:false}>
                    <{if 'MemUnicode'|in_array:$mem_column}>
                    <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_UNICODE}></th>
                    <{/if}>
                    <{if 'MemBirthday'|in_array:$mem_column}>
                    <th style="text-align: center;"><{$smarty.const._MD_TCW_MEM_BIRTHDAY}></th>
                    <{/if}>
                    <th style="text-align: center;"><{$smarty.const._TAD_FUNCTION}></th>
                <{/if}>
            </tr>
            <{foreach from=$all_mems item=stud}>
                <tr>
                    <td>
                        <div class="my-border" style="width: 60px; height: 60px; background: transparent url('<{$stud.pic}>') top center no-repeat; <{$stud.style}>; <{$stud.cover}> padding: 0px; float: left; margin-right:4px;"></div>

                        <{if 'MemNum'|in_array:$mem_column}>
                            <div><{$smarty.const._MD_TCW_MEM_NUM}>: <{$stud.MemNum}></div>
                        <{/if}>
                        <div>
                        <{if $isMyWeb|default:false}>
                            <a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>&MemID=<{$stud.MemID}>&op=show_stu"><{$stud.MemName}></a>
                        <{else}>
                            <{$stud.MemName}>
                        <{/if}>
                        </div>
                        <div style="clear: both;"></div>
                    </td>
                    <td style="text-align: center;"><{$stud.MemSexTitle}></td>
                    <{if 'MemNickName'|in_array:$mem_column}>
                        <td style="text-align: center;"><{$stud.MemNickName}></td>
                    <{/if}>
                    <{if 'MemExpertises'|in_array:$mem_column}>
                        <td style="text-align: center;"><{$stud.MemExpertises}></td>
                    <{/if}>
                    <{if 'MemClassOrgan'|in_array:$mem_column}>
                        <td style="text-align: center;"><{$stud.MemClassOrgan}></td>
                    <{/if}>
                    <{if 'AboutMem'|in_array:$mem_column}>
                        <td><{$stud.AboutMem}></td>
                    <{/if}>
                    <{if $isMyWeb|default:false}>
                        <{if 'MemUnicode'|in_array:$mem_column}>
                            <td style="text-align: center;"><{$stud.MemUnicode}></td>
                        <{/if}>
                        <{if 'MemBirthday'|in_array:$mem_column}>
                            <td style="text-align: center;"><{$stud.MemBirthday}></td>
                        <{/if}>
                        <td style="text-align: center;"><a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$CateID|default:''}>&MemID=<{$stud.MemID}>&op=edit_stu" class="btn btn-sm btn-xs btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i>  <{$smarty.const._TAD_EDIT}></a></td>
                    <{/if}>
                </tr>
            <{/foreach}>
        </table>
    <{elseif $mem_list_mode=="mem_detail"}>

        <{foreach from=$all_mems item=stud}>
            <div class="my-border">
                <div class="row">
                    <div class="col-md-2">
                        <a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>&MemID=<{$stud.MemID}>&op=show_stu">
                        <img src="<{$stud.pic}>" alt="<{$stud.MemName}>" class="img-fluid rounded">
                        </a>
                    </div>
                    <div class="col-md-3">
                        <{if 'MemNum'|in_array:$mem_column}>
                            <div style="margin: 2px;">
                                <span class="badge badge-secondary bg-secondary"><{$smarty.const._MD_TCW_MEM_NUM}></span>
                                <{$stud.MemNum}>
                            </div>
                        <{/if}>

                        <div style="margin: 2px;">
                            <span class="badge badge-secondary bg-secondary"><{$smarty.const._MD_TCW_MEM_NAME}></span>
                            <{if $isMyWeb|default:false}>
                                <a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>&MemID=<{$stud.MemID}>&op=show_stu"><{$stud.MemName}></a>
                                <a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$CateID|default:''}>&MemID=<{$stud.MemID}>&op=edit_stu" class="btn btn-sm btn-xs btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i>  <{$smarty.const._TAD_EDIT}></a>
                            <{else}>
                                <{$stud.MemName}>
                            <{/if}>
                        </div>

                        <div style="margin: 2px;">
                            <span class="badge badge-secondary bg-secondary"><{$smarty.const._MD_TCW_MEM_SEX}></span>
                        <{$stud.MemSexTitle}>
                        </div>

                        <{if 'MemNickName'|in_array:$mem_column and $stud.MemNickName}>
                            <div style="margin: 2px;">
                                <span class="badge badge-secondary bg-secondary"><{$smarty.const._MD_TCW_MEM_NICKNAME}></span>
                                <{$stud.MemNickName}>
                            </div>
                        <{/if}>

                        <{if $isMyWeb|default:false}>
                            <{if 'MemUnicode'|in_array:$mem_column and $stud.MemUnicode}>
                                <div style="margin: 2px;">
                                    <span class="badge badge-danger bg-danger"><{$smarty.const._MD_TCW_MEM_UNICODE}></span>
                                <{$stud.MemUnicode}>
                                </div>
                            <{/if}>

                            <{if 'MemBirthday'|in_array:$mem_column and $stud.MemBirthday}>
                                <div style="margin: 2px;">
                                    <span class="badge badge-danger bg-danger"><{$smarty.const._MD_TCW_MEM_BIRTHDAY}></span>
                                <{$stud.MemBirthday}>
                                </div>
                            <{/if}>

                            <{if 'MemExpertises'|in_array:$mem_column and $stud.MemExpertises}>
                                <div style="margin: 2px;">
                                    <span class="badge badge-secondary bg-secondary"><{$smarty.const._MD_TCW_MEM_EXPERTISES}></span>
                                <{$stud.MemExpertises}>
                                </div>
                            <{/if}>
                        <{/if}>
                    </div>
                    <div class="col-md-7">
                        <{if 'MemClassOrgan'|in_array:$mem_column and $stud.MemClassOrgan and $stud.MemClassOrgan}>
                            <div style="margin: 2px;">
                                <span class="badge badge-secondary bg-secondary"><{$smarty.const._MD_TCW_MEM_CLASSORGAN}></span>
                                <{$stud.MemClassOrgan}>
                            </div>
                        <{/if}>


                        <{if 'AboutMem'|in_array:$mem_column and $stud.AboutMem and $stud.AboutMem}>
                            <div style="margin: 2px;">
                                <!--span class="badge badge-secondary bg-secondary"><{$smarty.const._MD_TCW_MEM_ABOUTME}></span-->
                                <div class="alert alert-info" style="margin-top:4px;"><{$stud.AboutMem}></div>
                            </div>
                        <{/if}>
                    </div>
                </div>
            </div>
        <{/foreach}>
    <{else}>
        <div class="demo">
            <div id="snaptarget"><{$students1|default:''}></div>
            <br style="clear:both;">
            <{$students2|default:''}>
            <br style="clear:both;">
        </div>
    <{/if}>
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


<{if $isMyWeb|default:false}>
    <div class="text-right text-end">
        <a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>&op=new_class" class="btn btn-primary"><{$add_class|default:''}></a>
        <a href="aboutus.php?WebID=<{$WebID|default:''}>&CateID=<{$cate.CateID}>&op=edit_form" class="btn btn-warning"><{$class_setup|default:''}></a>
    </div>
<{/if}>
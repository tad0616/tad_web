<h2><{$cate.CateName}><{$smarty.const._MD_TCW_ABOUTUS_SLOT}></h2>
<{if $all_mems}>
    <style type="text/css" media="screen">
        #slot td{
            width: 60px;
            height: 60px;
            border:1px solid #000000;
            text-align: center;
            vertical-align: middle;
            background-color: white;
        }

        #slot td.slot_active{
            background-color: yellow;
        }
    </style>

    <script type="text/javascript">
        function random_mem(i,max,times){
            // $('#log').html('');
            i=parseInt(i);
            var max=<{$mem_total}>;
            var random=Math.random();
            var max_random=random * max;
            var end = Math.floor(max_random);
            var all_times = max * times;
            var limit = all_times+ end-i;
            // $('#log').html('<p>'+limit+'</p>');
            start(i,max,limit,all_times,0);
        }

        function start(i, max, limit, all_times, counter){
            var speed;
            var left = limit-counter;
            if(i > max){
                i=1;
            }

            if(all_times > counter){
                if(counter < <{$speed1}>){
                speed=100;
                }else if(counter >= <{$speed1}> && counter < <{$speed2}>){
                speed=50;
                }else if(left<=3){
                speed=200;
                }else if(left<=5){
                speed=150;
                }else if(left<=15){
                speed=100;
                }else if(left<=20){
                speed=50;
                }else if(left<=25){
                speed=30;
                }else if(left>40){
                speed=50;
                }else{
                speed=30;
                }
            }else{
                speed=250;
            }

            // $('#log').append(limit+'-'+counter+'='+left+'=>'+speed+'<br>');

            if(counter <= limit){
                setTimeout(function(){

                $('td').removeClass('slot_active');
                $('#td_'+i).addClass('slot_active');

                i++;
                counter++;
                start(i,max,limit,all_times,counter);
                }, speed);
            }
        }

    </script>
    <table id="slot">
        <{foreach from=$all_mems key=row item=mems}>
            <tr>
                <{foreach from=$mems key=i item=stud}>
                    <td id="td_<{$stud.slot_sort}>" title="<{$stud.slot_sort}>" <{if $stud.sort==1}>class="slot_active"<{/if}>>
                        <div style="width: 60px; height: 60px; margin:2px auto; background: transparent url('<{$stud.pic}>') top center no-repeat; <{$stud.cover}> padding: 0px;"></div>
                        <div>
                            <{if $isMyWeb}>
                                <{$stud.MemNum}>
                                <a href="aboutus.php?WebID=<{$WebID}>&CateID=<{$cate.CateID}>&MemID=<{$stud.MemID}>&op=show_stu"><{$stud.MemName}></a>
                            <{else}>
                                <{$stud.MemName}>
                            <{/if}>
                        </div>
                    </td>
                    <{if $row ==2 and $i==1 and $span_num > 0}>
                        <td colspan=<{$span_num}> rowspan=<{$span_num}> >
                            <a href="javascript:random_mem($('.slot_active').attr('title'),<{$mem_total}>,<{$times}>);" class="btn btn-primary"><{$smarty.const._MD_TCW_ABOUTUS_START}></a>
                        </td>
                    <{/if}>
                <{/foreach}>
            </tr>
        <{/foreach}>
    </table>
<{/if}>

<{if $isAdmin}>
    <div id="log"></div>
<{/if}>
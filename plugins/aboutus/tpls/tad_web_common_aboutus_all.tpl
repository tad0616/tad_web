<{if $get_mode=="web"}>
    <{assign var="i" value=0}>
    <{assign var="total" value=1}>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php"><{$smarty.const._MD_TCW_ABOUTUS_ALL}></a></li>
        <li class="breadcrumb-item"><a href="index.php?county=<{$def_county}>"><{$def_county}></a></li>
        <li class="breadcrumb-item"><a href="index.php?county=<{$def_county}>&city=<{$def_city}>"><{$def_city}></a></li>
        <li class="breadcrumb-item active"><{$def_SchoolName}> (<{$total_web}>)</li>
    </ol>

    <{foreach from=$data item=webs}>
        <{if $i==0}>
            <div class="row">
        <{/if}>

        <div class="col-md-6">
            <a href="<{$xoops_url}>/<{$webs.WebID}>"><{$webs.WebTitle}></a>
        </div>

        <{assign var="i" value=$i+1}>
        <{if $i == 2 || $total==$count}>
            </div>
            <{assign var="i" value=0}>
        <{/if}>
        <{assign var="total" value=$total+1}>
    <{/foreach}>
<{elseif $get_mode=="school"}>
    <{assign var="i" value=0}>
    <{assign var="total" value=1}>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php"><{$smarty.const._MD_TCW_ABOUTUS_ALL}></a></li>
        <li class="breadcrumb-item"><a href="index.php?county=<{$def_county}>"><{$def_county}></a></li>
        <li class="breadcrumb-item active"><{$def_city}> (<{$total_web}>)</li>
    </ol>
    <{foreach from=$data item=webs}>
        <{if $i==0}>
            <div class="row">
        <{/if}>

        <div class="col-md-2">
            <a href="index.php?county=<{$def_county}>&city=<{$def_city}>&SchoolName=<{$webs.SchoolName}>"><{$webs.SchoolName}> (<{$webs.counter}>)</a>
        </div>

        <{assign var="i" value=$i+1}>
        <{if $i == 6 || $total==$count}>
            </div>
            <{assign var="i" value=0}>
        <{/if}>
        <{assign var="total" value=$total+1}>
    <{/foreach}>
<{elseif $get_mode=="city"}>
    <{assign var="i" value=0}>
    <{assign var="total" value=1}>

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php"><{$smarty.const._MD_TCW_ABOUTUS_ALL}></a></li>
        <li class="breadcrumb-item active"><{$def_county}> (<{$total_web}>)</li>
    </ol>
    <{foreach from=$data item=webs}>
        <{if $i==0}>
            <div class="row">
        <{/if}>

        <div class="col-md-2">
            <a href="index.php?county=<{$def_county}>&city=<{$webs.city}>"><{$webs.city}> (<{$webs.counter}>)</a>
        </div>

        <{assign var="i" value=$i+1}>
        <{if $i == 6 || $total==$count}>
            </div>
            <{assign var="i" value=0}>
        <{/if}>
        <{assign var="total" value=$total+1}>
    <{/foreach}>
<{else}>
    <{assign var="i" value=0}>
    <{assign var="total" value=1}>

    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><{$smarty.const._MD_TCW_ABOUTUS_ALL}> (<{$total_web}>)</li>
    </ol>
    <{foreach from=$data item=webs}>
        <{if $i==0}>
            <div class="row">
        <{/if}>

        <div class="col-md-2">
            <a href="index.php?county=<{$webs.county}>"><{$webs.county}> (<{$webs.counter}>)</a>
        </div>

        <{assign var="i" value=$i+1}>
        <{if $i == 6 || $total==$count}>
            </div>
            <{assign var="i" value=0}>
        <{/if}>
        <{assign var="total" value=$total+1}>
    <{/foreach}>
<{/if}>
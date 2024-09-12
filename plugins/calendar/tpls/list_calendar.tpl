<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data}>
    <div style="background-color: #FFFFFF; margin: 10px auto;">
        <div id="calendar"></div>
    </div>
    <{$bc.fullcalendar_code}>
<{/if}>
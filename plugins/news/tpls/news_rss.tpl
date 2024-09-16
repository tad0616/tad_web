<{assign var="bc" value=$block.BlockContent}>
<{if $bc.main_data|default:false}>
    <div style="margin-bottom:15px;"></div>
    <{include file="$xoops_rootpath/modules/tad_web/templates/tad_web_block_title.tpl"}>
    <style>
        #marquee-container {
            width: 100%;
            overflow: hidden;
            <{if $bc.config.bg_color|default:false}>
            background-color: <{$bc.config.bg_color}>;
            <{else}>
            background-color: #f0f0f0;
            <{/if}>
            padding: 10px 0;
            margin-top: 20px;
        }
        #marquee-content {
            white-space: nowrap;
            display: inline-block;
        }
        .marquee-item {
            display: inline-block;
            max-width: 32rem;
            min-width: 10rem;
            margin-right: 50px;
            overflow: hidden;
        }
        .marquee-item a {
            text-decoration: none;
        }
        .marquee-item a:hover {
            text-decoration: underline;
        }
        .marquee-summary{
            font-size:0.825rem;
            height:2.5rem;
            white-space: normal;
            overflow: hidden;
        }
    </style>
    <h3><a href="<{$bc.main_data.news_url}>" target="_blank"><{$bc.main_data.title}></a></h3>
    <div id="marquee-container">
        <div id="marquee-content">
            <{foreach from=$bc.main_data.items key=i item=news}>
                <span class="marquee-item">
                    <a href="<{$news.url}>" target="_blank">
                        <{$news.pubDate}><i class="fa fa-caret-right" aria-hidden="true"></i>
                        <{$news.title}>
                    </a>
                    <{if $bc.config.display_rss!='title_only'}>
                        <div class="marquee-summary"><{$news.summary}></div>
                    <{/if}>
                </span>
            <{/foreach}>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var marqueeContainer = document.getElementById('marquee-container');
            var marqueeContent = document.getElementById('marquee-content');

            if (marqueeContent) {
                var contentWidth = marqueeContent.offsetWidth;
                var containerWidth = marqueeContainer.offsetWidth;
                var scrollSpeed = 1; // 調整這個值來改變滾動速度（值越小，速度越慢）
                var scrollAmount = 0;
                var isScrolling = false;
                var animationId = null;

                function scrollMarquee() {
                    if (!isScrolling) return;

                    scrollAmount += scrollSpeed;
                    if (scrollAmount >= contentWidth) {
                        scrollAmount = 0;
                    }
                    marqueeContent.style.transform = `translateX(-${scrollAmount}px)`;
                    animationId = requestAnimationFrame(scrollMarquee);
                }

                function startScrolling() {
                    if (!isScrolling) {
                        console.log('非滾動中，開始滾動');

                        isScrolling = true;
                        scrollMarquee();
                    }else{
                        console.log('滾動中');
                    }
                }

                function stopScrolling() {
                    isScrolling = false;
                    if (animationId) {
                        cancelAnimationFrame(animationId);
                        animationId = null;
                    }
                }

                // 開始滾動
                startScrolling();

                // 滑鼠進入時停止滾動
                marqueeContainer.addEventListener('mouseenter', stopScrolling);

                // 滑鼠離開時繼續滾動
                marqueeContainer.addEventListener('mouseleave', startScrolling);
            }
        });
    </script>
<{/if}>

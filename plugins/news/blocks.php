<?php
use XoopsModules\Tadtools\Utility;

function list_news($WebID, $config = [])
{
    if (empty($WebID)) {
        return;
    }
    require_once __DIR__ . '/class.php';

    $tad_web_news = new tad_web_news($WebID);

    $block = $tad_web_news->list_all('', $config['limit'], 'return');

    return $block;
}

function news_rss($WebID, $config = [])
{
    // RSS feed URL
    $feedUrl = $config['feed_url'];

    // feed2json.org API URL
    $apiUrl = 'https://feed2json.org/convert?url=' . urlencode($feedUrl);

    // 使用 file_get_contents() 獲取 feed 內容
    $response = @file_get_contents($apiUrl);

    if ($response === false) {
        $feedData = false;
    } else {
        $feedData = json_decode($response, true);
        $feedData['news_url'] = str_replace('index.php', 'news.php', $feedData['home_page_url']);
        $i = 0;
        foreach ($feedData['items'] as $k => $news) {
            if ($i >= 10) {
                break;
            } else {
                $i++;
            }
            $feedData['items'][$k]['pubDate'] = date('Y-m-d', strtotime($news['date_published']));
            $feedData['items'][$k]['title'] = xoops_substr($news['title'], 0, 60);
            $feedData['items'][$k]['summary'] = xoops_substr(strip_tags($news['summary']), 0, 180);
        }
    }

    $block['main_data'] = $feedData;
    $block['WebID'] = $WebID;
    $block['config'] = $config;

    return $block;
}

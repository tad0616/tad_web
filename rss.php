<?php
/*-----------引入檔案區--------------*/
include_once 'header.php';
$plugin = 'news';
include_once 'plugin_header.php';
include_once XOOPS_ROOT_PATH . '/header.php';
/*-----------function區--------------*/

$all_news = $tad_web_news->list_all('', 50, 'return');
// die(var_export($all_news['main_data']));
$item = '';
foreach ($all_news['main_data'] as $key => $news) {
    # code...

    $news['NewsTitle'] = htmlspecialchars($news['NewsTitle']);
    $news['NewsDate'] = date('r', strtotime($news['NewsDate']));
    $news['NewsContent'] = htmlspecialchars($news['NewsContent']);

    $item .= '
      <item>
        <guid>' . XOOPS_URL . "/modules/tad_web/news.php?WebID={$news['WebID']}&amp;NewsID={$news['NewsID']}</guid>
        <title>{$news['NewsTitle']}</title>
        <link>" . XOOPS_URL . "/modules/tad_web/news.php?WebID={$news['WebID']}&amp;NewsID={$news['NewsID']}</link>
        <pubDate>{$news['NewsDate']}</pubDate>
        <description>{$news['NewsContent']}</description>
      </item>
      ";
}
header('Content-type: application/rss+xml');
echo '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">

  <channel>
    <atom:link href="' . XOOPS_URL . "/modules/tad_web/rss.php?WebID={$WebID}\" rel=\"self\" type=\"application/rss+xml\" />
    <title>{$WebTitle}</title>
    <link>" . XOOPS_URL . "/modules/tad_web/index.php?WebID={$WebID}</link>
    <description>{$WebTitle}</description>
    <lastBuildDate>" . date('r') . "</lastBuildDate>
    {$item}
  </channel>
</rss>
";

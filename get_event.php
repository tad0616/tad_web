<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
require_once __DIR__ . '/header.php';

$start = Request::getString('start', date("Y-m-01"));
$d = date('t');
$end = Request::getString('end', date("Y-m-01"), date("Y-m-$d"));
$WebID = Request::getInt('WebID');

// $start = empty($_REQUEST['start']) ? date('Y-m-01') : date('Y-m-d', strtotime($_REQUEST['start']));
// $end = empty($_REQUEST['end']) ? date('Y-m-t') : date('Y-m-d', strtotime($_REQUEST['end']));

if (!isset($xoopsModuleConfig)) {
    $moduleHandler = xoops_getHandler('module');
    $xoopsModule = $moduleHandler->getByDirname('tad_web');
    $configHandler = xoops_getHandler('config');
    $xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));
}

$cal_cols = $xoopsModuleConfig['cal_cols'];

if ('homework' === $_REQUEST['CalKind']) {
    //抓取聯絡簿
    $myEvents = get_homework_event($start, $end, $WebID);
} else {
    $i = 0;
    //抓取母站行事曆
    //不是全國版才抓
    if (in_array('all', $cal_cols) or $WebID) {
        $allEvents = get_all_event($start, $end, $WebID);
        foreach ($allEvents as $evens) {
            $myEvents[$i] = $evens;
            $i++;
        }
    }

    //抓取子站行事曆
    if (in_array('web', $cal_cols) or $WebID) {
        $calEvents = get_web_event($start, $end, $WebID);
        foreach ($calEvents as $evens) {
            $myEvents[$i] = $evens;
            $i++;
        }
    }

    //抓取新聞
    if (in_array('news', $cal_cols) or $WebID) {
        $newsEvents = get_news_event($start, $end, $WebID);
        foreach ($newsEvents as $evens) {
            $myEvents[$i] = $evens;
            $i++;
        }
    }

    //抓取聯絡簿
    if (in_array('homework', $cal_cols) or $WebID) {
        $homeworkEvents = get_homework_event($start, $end, $WebID);
        foreach ($homeworkEvents as $evens) {
            $myEvents[$i] = $evens;
            $i++;
        }
    }
}
//die(var_export($myEvents));
header('HTTP/1.1 200 OK');
// echo json_encode($myEvents, 256);
if ($myEvents) {
    Utility::dd($myEvents);
} else {
    die('[]');
}

//抓取聯絡簿
function get_homework_event($start, $end, $WebID)
{
    global $xoopsDB;

    $andWebID = empty($WebID) ? '' : "and `WebID`='{$WebID}'";
    $now = date('Y-m-d H:i:s');
    $sql = 'select HomeworkID,HomeworkTitle,toCal,WebID from ' . $xoopsDB->prefix('tad_web_homework') . " where toCal >= '$start' and toCal <= '$end' and HomeworkPostDate <= '$now' $andWebID order by toCal";
    $result = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    $i = 0;
    while (list($ID, $Title, $toCal, $WebID) = $xoopsDB->fetchRow($result)) {
        $toCal = userTimeToServerTime(strtotime($toCal));

        $myEvents[$i]['id'] = $ID;
        $myEvents[$i]['title'] = $Title;
        $myEvents[$i]['url'] = XOOPS_URL . "/modules/tad_web/homework.php?WebID=$WebID&HomeworkID={$ID}";
        $myEvents[$i]['start'] = date('Y-m-d', $toCal);
        $myEvents[$i]['allDay'] = true;
        $myEvents[$i]['className'] = 'fc-event';
        $myEvents[$i]['color'] = '#D37545';
        $i++;
    }

    return $myEvents;
}

//抓取新聞
function get_news_event($start, $end, $WebID)
{
    global $xoopsDB;

    $andWebID = empty($WebID) ? '' : "and `WebID`='{$WebID}'";

    $sql = 'select NewsID,NewsTitle,toCal,WebID from ' . $xoopsDB->prefix('tad_web_news') . " where toCal >= '$start' and toCal <= '$end' $andWebID order by toCal";
    $result = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    $i = 0;
    while (list($ID, $Title, $toCal, $WebID) = $xoopsDB->fetchRow($result)) {
        $toCal = userTimeToServerTime(strtotime($toCal));

        $myEvents[$i]['id'] = $ID;
        $myEvents[$i]['title'] = $Title;
        $myEvents[$i]['url'] = XOOPS_URL . "/modules/tad_web/news.php?WebID=$WebID&NewsID={$ID}";
        $myEvents[$i]['start'] = date('Y-m-d', $toCal);
        $myEvents[$i]['allDay'] = true;
        $myEvents[$i]['className'] = 'fc-event';
        $myEvents[$i]['color'] = '#639674';
        $i++;
    }

    return $myEvents;
}

//抓取行事曆事件
function get_all_event($start, $end, $WebID)
{
    global $xoopsDB;

    $andWebID = '';
    if (_IS_EZCLASS) {
        $andWebID = "and WebID='$WebID'";
    } elseif ($WebID) {
        $calendar_setup = get_plugin_setup_values($WebID, 'calendar');
        if ('1' != $calendar_setup['show_global_event']) {
            $andWebID = "and WebID='$WebID'";
        }
    }

    $sql = 'select CalendarID,CalendarName,CalendarDate,WebID from ' . $xoopsDB->prefix('tad_web_calendar') . " where CalendarDate >= '$start' and CalendarDate <= '$end' and CalendarType='all' $andWebID order by CalendarDate";
    $result = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    $i = 0;
    while (list($ID, $Title, $toCal, $WebID) = $xoopsDB->fetchRow($result)) {
        $toCal = userTimeToServerTime(strtotime($toCal));

        $myEvents[$i]['id'] = $ID;
        $myEvents[$i]['title'] = $Title;
        $myEvents[$i]['url'] = XOOPS_URL . "/modules/tad_web/calendar.php?WebID=$WebID&CalendarID={$ID}";
        $myEvents[$i]['start'] = date('Y-m-d', $toCal);
        $myEvents[$i]['allDay'] = true;
        $myEvents[$i]['className'] = 'fc-event';
        $myEvents[$i]['color'] = '#AA1D1D';
        $i++;
    }

    return $myEvents;
}

//抓取子站行事曆事件
function get_web_event($start, $end, $WebID)
{
    global $xoopsDB;

    $andWebID = empty($WebID) ? '' : "and `WebID`='{$WebID}'";

    $sql = 'select CalendarID,CalendarName,CalendarDate,WebID from ' . $xoopsDB->prefix('tad_web_calendar') . " where CalendarDate >= '$start' and CalendarDate <= '$end' $andWebID  and CalendarType!='all' order by CalendarDate";
    $result = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    $i = 0;
    while (list($ID, $Title, $toCal, $WebID) = $xoopsDB->fetchRow($result)) {
        $toCal = userTimeToServerTime(strtotime($toCal));

        $myEvents[$i]['id'] = $ID;
        $myEvents[$i]['title'] = $Title;
        $myEvents[$i]['url'] = XOOPS_URL . "/modules/tad_web/calendar.php?WebID=$WebID&CalendarID={$ID}";
        $myEvents[$i]['start'] = date('Y-m-d', $toCal);
        $myEvents[$i]['allDay'] = true;
        $myEvents[$i]['className'] = 'fc-event';
        $myEvents[$i]['color'] = '#1990EA';
        $i++;
    }

    return $myEvents;
}

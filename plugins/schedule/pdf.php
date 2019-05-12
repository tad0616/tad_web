<?php

use XoopsModules\Tadtools\Utility;

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/mainfile.php';
require_once dirname(dirname(__DIR__)) . '/function.php';
require_once dirname(dirname(__DIR__)) . '/class/cate.php';
require_once __DIR__ . '/class.php';

set_time_limit(0);
ini_set('memory_limit', '150M');

$WebID = empty($_REQUEST['WebID']) ? '' : (int)$_REQUEST['WebID'];
$ScheduleID = empty($_REQUEST['ScheduleID']) ? '' : (int)$_REQUEST['ScheduleID'];

$schedule = new tad_web_schedule($WebID);
$schedule_data = $schedule->get_one_data($ScheduleID);
$schedule_data = $schedule->get_one_data($ScheduleID);
$content = "<h2>{$schedule_data['ScheduleName']}</h2>";
$content .= $schedule->get_one_schedule($ScheduleID);
$html = Utility::html5($content, false, false, null, false);
$html = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $html);
$html = preg_replace('/(<[^>]+) style=\'.*?\'/i', '$1', $html);
$html = preg_replace('/(<[^>]+) class=".*?"/i', '$1', $html);
$html = preg_replace('/(<[^>]+) class=\'.*?\'/i', '$1', $html);
$html = str_replace('</div><div></div>', '', $html);
$html = str_replace('</div><div>', '<br>', $html);
$html = str_replace('<div>', '', $html);
$html = str_replace('</div>', '', $html);
$html = str_replace('<table', '<table border="1"', $html);
$html = str_replace('<td', '<td align="center" valign="middle" cellpadding=6', $html);
//die($html);
$filename = "{$schedule_data['ScheduleName']}.pdf";

require_once XOOPS_ROOT_PATH . '/modules/tadtools/tcpdf/tcpdf.php';
$pdf = new TCPDF('PDF_PAGE_ORIENTATION', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false); //不要頁首
$pdf->setPrintFooter(false); //不要頁尾
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM); //設定自動分頁
$pdf->setLanguageArray($l); //設定語言相關字串
$pdf->setFontSubsetting(true); //產生字型子集（有用到的字才放到文件中）
$pdf->SetFont('droidsansfallback', '', 10, '', true); //設定字型
$pdf->AddPage(); //新增頁面
//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));//文字陰影

$pdf->writeHTML($html);
$pdf->Output($filename, 'D');

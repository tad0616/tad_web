<?php
require_once dirname(dirname(__DIR__)) . '/mainfile.php';
$start = time();
$folders['bg'] = XOOPS_ROOT_PATH . '/modules/tad_web/images/bg/';
$folders['head'] = XOOPS_ROOT_PATH . '/modules/tad_web/images/head/';
$form_files = [];
foreach ($folders as $kind => $dir) {
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (false !== ($file = readdir($dh))) {
                if ('.' === mb_substr($file, 0, 1)) {
                    continue;
                }
                $form_files[$kind][$file] = $dir . $file;
            }
            closedir($dh);
        }
    }
}

$sql = 'select WebID from xx_tad_web where WebID >=6000 and WebID < 7000 order by WebID';
$result = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
$sum = 0;
while (false !== (list($WebID) = $xoopsDB->fetchRow($result))) {
    echo "<h3>開始處理 {$WebID}</h3>";
    $ok = 0;
    $folder['bg'] = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/bg/";
    $folder['head'] = XOOPS_ROOT_PATH . "/uploads/tad_web/{$WebID}/head/";
    foreach ($folder as $kind => $dir) {
        $filenames = array_keys($form_files[$kind]);

        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (false !== ($file = readdir($dh))) {
                    if ('.' === mb_substr($file, 0, 1)) {
                        continue;
                    }
                    if (in_array($file, $filenames)) {
                        if (unlink($dir . $file)) {
                            if (symlink($form_files[$kind][$file], $dir . $file)) {
                                // echo "<div style='color: blue'>移除 {$dir}{$file}，新增軟連結 {$form_files[$kind][$file]}成功！</div>";
                                $ok++;
                            } else {
                                echo "<div style='color:red'>新增軟連結 {$form_files[$kind][$file]} 失敗！</div>";
                            }
                        } else {
                            echo "<div style='color:red'>移除 {$dir}{$file} 失敗！</div>";
                        }
                    }
                }
                closedir($dh);
            }
        }
    }
    $sum++;
    echo "<div style='color: blue'>成功處理 $ok 個檔案</div>";
}

$end = time();

$s = round(($end - $start) / 60, 0);

echo "<div>處理 $sum 個網站，共使用 $s 分鐘</div>";

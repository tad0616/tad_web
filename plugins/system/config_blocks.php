<?php
global $WebID;

$blocksArr['search']['name'] = _MD_TCW_SYSTEM_BLOCK_SEARCH;
$blocksArr['search']['plugin'] = 'system';
$blocksArr['search']['tpl'] = 'search.tpl';
$blocksArr['search']['position'] = 'side';

$blocksArr['qrcode']['name'] = _MD_TCW_SYSTEM_BLOCK_QRCODE;
$blocksArr['qrcode']['plugin'] = 'system';
$blocksArr['qrcode']['tpl'] = 'qrcode.tpl';
$blocksArr['qrcode']['position'] = 'side';

$blocksArr['web_list']['name'] = _MD_TCW_SYSTEM_BLOCK_WEBLIST;
$blocksArr['web_list']['plugin'] = 'system';
$blocksArr['web_list']['tpl'] = 'web_list.tpl';
$blocksArr['web_list']['position'] = 'side';

$blocksArr['rrssb']['name'] = _MD_TCW_SYSTEM_BLOCK_RRSSB;
$blocksArr['rrssb']['plugin'] = 'system';
$blocksArr['rrssb']['tpl'] = 'rrssb.tpl';
$blocksArr['rrssb']['position'] = 'side';

$blocksArr['moedict']['name'] = _MD_TCW_SYSTEM_BLOCK_MOEDICT;
$blocksArr['moedict']['plugin'] = 'system';
$blocksArr['moedict']['tpl'] = 'moedict.tpl';
$blocksArr['moedict']['position'] = 'side';

$blocksArr['dreye']['name'] = _MD_TCW_SYSTEM_BLOCK_DREYE;
$blocksArr['dreye']['plugin'] = 'system';
$blocksArr['dreye']['tpl'] = 'dreye.tpl';
$blocksArr['dreye']['position'] = 'side';

$blocksArr['wiki']['name'] = _MD_TCW_SYSTEM_BLOCK_WIKI;
$blocksArr['wiki']['plugin'] = 'system';
$blocksArr['wiki']['tpl'] = 'wiki.tpl';
$blocksArr['wiki']['position'] = 'side';

$sites = ['基隆市-基隆' => '46', '臺北市-士林' => '5', '臺北市-大同' => '6', '臺北市-中山' => '11', '臺北市-古亭' => '16', '臺北市-松山' => '29', '臺北市-陽明' => '54', '臺北市-萬華' => '62', '新北市-三重' => '2', '新北市-土城' => '4', '新北市-永和' => '19', '新北市-汐止' => '22', '新北市-板橋' => '30', '新北市-林口' => '31', '新北市-淡水' => '48', '新北市-菜寮' => '53', '新北市-新店' => '56', '新北市-新莊' => '57', '新北市-萬里' => '61', '新北市-新北(樹林)' => '86', '新北市-富貴角' => '96', '桃園市-大園' => '8', '桃園市-中壢' => '12', '桃園市-平鎮' => '18', '桃園市-桃園' => '43', '桃園市-龍潭' => '73', '桃園市-觀音' => '76', '桃園市-桃園(觀音工業區)' => '92', '新竹市-新竹' => '55', '新竹縣-竹東' => '24', '新竹縣-湖口' => '52', '苗栗縣-三義' => '3', '苗栗縣-苗栗' => '41', '苗栗縣-頭份' => '72', '臺中市-大里' => '7', '臺中市-西屯' => '25', '臺中市-沙鹿' => '26', '臺中市-忠明' => '28', '臺中市-豐原' => '74', '南投縣-竹山' => '23', '南投縣-南投' => '37', '南投縣-埔里' => '42', '彰化縣-二林' => '1', '彰化縣-彰化' => '64', '彰化縣-線西' => '70', '彰化縣-彰化(大城)' => '85', '雲林縣-斗六' => '14', '雲林縣-崙背' => '47', '雲林縣-麥寮' => '49', '雲林縣-臺西' => '65', '嘉義市-嘉義' => '63', '嘉義縣-朴子' => '21', '嘉義縣-新港' => '58', '臺南市-安南' => '20', '臺南市-善化' => '50', '臺南市-新營' => '59', '臺南市-臺南' => '67', '臺南市-臺南(麻豆)' => '84', '臺南市-臺南(北門)' => '90', '高雄市-大寮' => '9', '高雄市-小港' => '10', '高雄市-仁武' => '13', '高雄市-左營' => '17', '高雄市-林園' => '32', '高雄市-前金' => '35', '高雄市-前鎮' => '36', '高雄市-美濃' => '40', '高雄市-復興' => '51', '高雄市-楠梓' => '60', '高雄市-鳳山' => '68', '高雄市-橋頭' => '71', '屏東縣-屏東' => '38', '屏東縣-恆春' => '39', '屏東縣-潮州' => '69', '屏東縣-屏東(琉球)' => '87', '宜蘭縣-冬山' => '15', '宜蘭縣-宜蘭' => '27', '花蓮縣-花蓮' => '33', '臺東縣-臺東' => '66', '臺東縣-關山' => '75', '澎湖縣-馬公' => '44', '金門縣-金門' => '34', '連江縣-馬祖' => '45'];

$blocksArr['pm25']['name'] = _MD_TCW_SYSTEM_BLOCK_PM25;
$blocksArr['pm25']['plugin'] = 'system';
$blocksArr['pm25']['tpl'] = 'pm25.tpl';
$blocksArr['pm25']['position'] = 'side';
$blocksArr['pm25']['config']['pm25_site'] = '67';
$blocksArr['pm25']['colset']['pm25_site'] = ['label' => _MD_TCW_SYSTEM_BLOCK_PM25_SITE, 'type' => 'select', 'options' => $sites];

$blocksArr['psi']['name'] = _MD_TCW_SYSTEM_BLOCK_PSI;
$blocksArr['psi']['plugin'] = 'system';
$blocksArr['psi']['tpl'] = 'psi.tpl';
$blocksArr['psi']['position'] = 'side';
$blocksArr['psi']['config']['psi_site'] = '67';
$blocksArr['psi']['colset']['psi_site'] = ['label' => _MD_TCW_SYSTEM_BLOCK_PM25_SITE, 'type' => 'select', 'options' => $sites];

$blocksArr['tlkio']['name'] = _MD_TCW_SYSTEM_BLOCK_TALKIO;
$blocksArr['tlkio']['plugin'] = 'system';
$blocksArr['tlkio']['tpl'] = 'tlkio.tpl';
$blocksArr['tlkio']['position'] = 'side';
$blocksArr['tlkio']['config']['tlkio_name'] = 'chat_room_{{WebID}}';
$blocksArr['tlkio']['colset']['tlkio_name'] = ['label' => _MD_TCW_SYSTEM_BLOCK_TLKIO_NAME, 'type' => 'text'];
$blocksArr['tlkio']['config']['tlkio_theme'] = 'day';
$blocksArr['tlkio']['colset']['tlkio_theme'] = ['label' => _MD_TCW_SYSTEM_BLOCK_TLKIO_THEME, 'type' => 'select', 'options' => [_MD_TCW_SYSTEM_BLOCK_TLKIO_THEME_DAY => 'day', _MD_TCW_SYSTEM_BLOCK_TLKIO_THEME_NIGHT => 'night', _MD_TCW_SYSTEM_BLOCK_TLKIO_THEME_POP => 'pop', _MD_TCW_SYSTEM_BLOCK_TLKIO_THEME_MINIMAL => 'minimal']];
$blocksArr['tlkio']['config']['tlkio_height'] = '400';
$blocksArr['tlkio']['colset']['tlkio_height'] = ['label' => _MD_TCW_SYSTEM_BLOCK_TLKIO_HEIGHT, 'type' => 'text'];

$blocksArr['countdown']['name'] = _MD_TCW_SYSTEM_BLOCK_COUNTDOWN;
$blocksArr['countdown']['plugin'] = 'system';
$blocksArr['countdown']['tpl'] = 'countdown.tpl';
$blocksArr['countdown']['position'] = 'side';
$blocksArr['countdown']['config']['countdown_title'] = _MD_TCW_SYSTEM_BLOCK_COUNTDOWN_TITLE_DEF;
$blocksArr['countdown']['colset']['countdown_title'] = ['label' => _MD_TCW_SYSTEM_BLOCK_COUNTDOWN_TITLE, 'type' => 'text'];
$blocksArr['countdown']['config']['countdown_date'] = date('12/25/Y 00:00:00');
$blocksArr['countdown']['colset']['countdown_date'] = ['label' => _MD_TCW_SYSTEM_BLOCK_COUNTDOWN_DATE, 'type' => 'datetime'];

// $blocksArr['flickrit']['name']                     = _MD_TCW_SYSTEM_BLOCK_FLICKRIT;
// $blocksArr['flickrit']['plugin']   = 'system';
// $blocksArr['flickrit']['tpl']                      = 'flickrit.tpl';
// $blocksArr['flickrit']['position']                 = 'side';
// $blocksArr['flickrit']['config']['flickrit_type']  = 'slideshowholderpicasa';
// $blocksArr['flickrit']['colset']['flickrit_type']  = array('label' => _MD_TCW_SYSTEM_BLOCK_FLICKRIT_TYPE, 'type' => 'select', 'options' => array(_MD_TCW_SYSTEM_BLOCK_FLICKRIT_TYPE_FLICKR => 'slideshowholder', _MD_TCW_SYSTEM_BLOCK_FLICKRIT_TYPE_PICASA => 'slideshowholderpicasa'));
// $blocksArr['flickrit']['config']['flickrit_kind']  = 'setId';
// $blocksArr['flickrit']['colset']['flickrit_kind']  = array('label' => _MD_TCW_SYSTEM_BLOCK_FLICKRIT_KIND, 'type' => 'select', 'options' => array(_MD_TCW_SYSTEM_BLOCK_FLICKRIT_KIND_SETID => 'setId', _MD_TCW_SYSTEM_BLOCK_FLICKRIT_KIND_USERID => 'userId'));
// $blocksArr['flickrit']['config']['flickrit_setid'] = '110168492315217261022/Flickrit';
// $blocksArr['flickrit']['colset']['flickrit_setid'] = array('label' => _MD_TCW_SYSTEM_BLOCK_FLICKRIT_SETID, 'type' => 'text');

$blocksArr['tags']['name'] = _MD_TCW_SYSTEM_BLOCK_TAGS;
$blocksArr['tags']['plugin'] = 'system';
$blocksArr['tags']['tpl'] = 'tags.tpl';
$blocksArr['tags']['position'] = 'side';
$blocksArr['tags']['config']['tags_mode'] = 'list';
$blocksArr['tags']['colset']['tags_mode'] = ['label' => _MD_TCW_SYSTEM_BLOCK_TAGS_MODE, 'type' => 'radio', 'options' => [_MD_TCW_SYSTEM_BLOCK_TAGS_MODE_LIST => 'list', _MD_TCW_SYSTEM_BLOCK_TAGS_MODE_CLOUD => 'cloud']];
$blocksArr['tags']['config']['min_height'] = '250';
$blocksArr['tags']['colset']['min_height'] = ['label' => _MD_TCW_SYSTEM_BLOCK_MIN_HEIGHT, 'type' => 'text'];

//不能刪，否則會導致無法設定
$blockConfig['system'] = $blocksArr;

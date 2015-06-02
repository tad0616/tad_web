<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2011-04-15
// $Id:$
// ------------------------------------------------------------------------- //

//需加入模組語系
define('_TAD_NEED_TADTOOLS', "This module needs TadTools module. You can download TadTools from <a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad's web</a>.");

define('_MD_TCW_HOME', 'Home');
define('_MD_TCW_ADMIN', 'Management');
define('_MD_TCW_CLEAR', 'Clear');
define('_MD_TCW_TOTAL', 'Total');
define('_MD_TCW_PEOPLE', 'People');

//header.php
define('_MD_TCW_ALL_CLASS', 'Class List');
define('_MD_TCW_MY_CLASS', 'Class members');
define('_MD_TCW_ALL_WEB', 'Pages list');
define('_MD_TCW_ALL_WEB_TITLE', 'Official Name');
define('_MD_TCW_ALL_WEB_NAME', 'Page Name');
define('_MD_TCW_ALL_WEB_COUNTER', 'Popular');
define('_MD_TCW_ALL_WEB_OWNER', 'Owner');

// Common
define('_MD_TCW_TEAMID', 'Belongs to the page');
define('_MD_TCW_NOT_OWNER', 'Non-page owner, you can not use the new functions.');
define('_MD_TCW_AUTO_TO_HOME', 'Automatically go to the page to which you belong, to use the new features.');
define('_MD_TCW_NEED_LOGIN', 'Login to view the full content');
define('_MD_TCW_EMPTY', 'No data');
define('_MD_TCW_MORE', 'More');
define('_MD_TADWEB_SELECT_TO_DEL', 'Edit files: Select files to be deleted:');
define('_MD_TCW_INFO', '%s to %s release, viewed by %s people');
define('_MD_TCW_MKPIC_ERROR', 'GD image can not be created');
define('_MD_TCW_EMPTY_TITLE', 'Untitled');

//aboutus.php
define('_MD_TCW_ABOUTUS', 'About Us');
define('_MD_TCW_STUDENT_SETUP', 'Student set');
define('_MD_TCW_ABOUTUS_SHORT', 'Members');
define('_MD_TCW_UID', 'Members');
define('_MD_TCW_MEMSCHOOLORGAN', 'School title');
define('_MD_TCW_MEMEXPERTISES', 'Specialty');
define('_MD_TCW_MEMURL', 'Personal page');
define('_MD_TCW_MEMTEAMORGAN', 'Team title');
define('_MD_TCW_MEMORDER', 'Sorting');
define('_MD_TCW_MEMENABLE', 'State');
define('_MD_TCW_UPLOAD_MY_PHOTO', 'Photo');
define('_MD_TCW_COMMING_SOON', 'Still working ...');
define('_MD_TCW_CLASS_WEB_NAME', 'Class network name');
define('_MD_TCW_IMPORT', 'Import');
define('_MD_TCW_ADD_MEM', 'Manually add student');
define('_MD_TCW_MEM_NAME', 'Name');
define('_MD_TCW_MEM_NICKNAME', 'Nickname');
define('_MD_TCW_MEM_BIRTHDAY', 'Birthday');
define('_MD_TCW_MEM_SEX', 'Gender');
define('_MD_TCW_MEM_UNICODE', 'Study');
define('_MD_TCW_MEM_NUM', 'Seat number');
define('_MD_TCW_MEM_STATUS', 'Status');
define('_MD_TCW_MEM_SAVE_OK', 'Seat is saved!');
define('_MD_TCW_AGAIN', 'Pulls out again ...');
define('_MD_TCW_GOT_YOU', 'That\'s you!');
define('_MD_TCW_NUMBER', 'No!');
define('_MD_TCW_SOMEBODY', 'Students');
define('_MD_TCW_DEMO_NAME', 'Wu Hongkai');
define('_MD_TCW_BOY', 'Male');
define('_MD_TCW_GIRL', 'Female');
define('_MD_TCW_IMPORT_OK', 'Import completed');
define('_MD_TCW_MEM_ENABLE', 'In the class');
define('_MD_TCW_MEM_UNABLE', 'Not in the class');
define('_MD_TCW_OWNER_NAME', 'Teacher name');
define('_MD_TCW_MEM_AMOUNT', 'Class size');
define('_MD_TCW_GET_SOMEONE', 'A few lucky draw it!');
define('_MD_TCW_IMPORT_PREVIEW', 'Preview Import Results');
define('_MD_TCW_IMPORT_DESCRIPT', '
                               < ol>
   < li> If the result is correct, direct push the "Import" button. </li>
  <li> If a small portion of the error, the line changes. </li>
  <li> If large amounts are incorrect, please re-corrected Excel file and then re-imported. </li>
  <li> If the field is blank, indicating that the field is left blank. </li>
  <li> If the drop-down menu is blank, indicating that the option value does not match the Excel file and specified in the input. </li>
</ol> ');
define('_MD_TCW_IMPORT_EXCEL', 'Import student data as Excel file');
define('_MD_TCW_SELECT_TO_EDIT', 'Click on the name of the student to edit the Student Information');
define('_MD_TCW_IMPORT_LINK', "
<ul>
  <li> <a href='http://120.115.2.90/modules/student_data/' target='_blank'> Tainan dedicated teacher student data import files made </a> </li>
   < li> <a href = 'import.xls' > student data import Excel files example </a > </li >
   <li style = 'color: red;' > only supports xls format(csv, xlsx not support) </li >
</ul > ");

define('_MD_TCW_CLASS_SETUP', 'Class setup');
define('_MD_TCW_FCNCTION_SETUP', 'Function setting');
define('_MD_TCW_HEADER_SETUP', 'Set title');
define('_MD_TCW_SELECT_TO_CANCEL', 'Please select the function you want to hide.');
define('_MD_TCW_CLICK_TO_CHANG', 'Click on a picture to selected title background image for this page');
define('_MD_TCW_RAND_IMAGE', 'Select random background');
define('_MD_TCW_BG_TOP', 'Ton');
define('_MD_TCW_BG_CENTER', 'Medium');
define('_MD_TCW_BG_BOTTOM', 'Bottom');
define('_MD_TCW_BG_POSITION', 'Picture Location:');
define('_MD_TCW_MEM_UNAME', 'Account');
define('_MD_TCW_MEM_PASSWD', 'Password');
define('_MD_TCW_PLEASE_INPUT', 'Enter');
define('_MD_TCW_LOGIN', 'Login');
define('_MD_TCW_HELLO', 'Hello, welcome to use the Forums! Please pay attention to manners!!');
define('_MD_TCW_EXIT', 'Exit');

//news.php
define('_MD_TCW_NEWS', 'News');
define('_MD_TCW_NEWS_SHORT', 'Message');
define('_MD_TCW_NEWSID', 'Number');
define('_MD_TCW_NEWSTITLE', 'Message Title');
define('_MD_TCW_NEWSCONTENT', 'Message content');
define('_MD_TCW_NEWSDATE', 'Release date');
define('_MD_TCW_TOCAL', 'Save to Calendar');
define('_MD_TCW_NEWSPLACE', 'Place');
define('_MD_TCW_NEWSMASTER', 'Host');
define('_MD_TCW_NEWSURL', 'Related Links');
define('_MD_TCW_NEWSKIND', 'Article types');
define('_MD_TCW_NEWSCOUNTER', 'Popular');
define('_MD_TCW_NEWS_ADD', 'Add news release');
define('_MD_TCW_NEWS_TO_CAL', 'Fill in the date in this message, it can be added to the calendar');
define('_MD_TCW_NEWS_NOT_EXIST', 'The message does not exist');
define('_MD_TCW_NEWS_TEACHER', 'Teacher');
define('_MD_TCW_NEWS_FILES', 'Related Accessories');
define('_MD_TCW_NEWS_TODAY', 'Today');
define('_MD_TCW_NEWS_PREV_MONTH', 'Previous Month');
define('_MD_TCW_NEWS_NEXT_MONTH', 'Next Month');

//homework.php
define('_MD_TCW_HOMEWORK', 'Contact book');
define('_MD_TCW_HOMEWORK_SHORT', 'Content');
define('_MD_TCW_HOMEWORK_ADD', 'Write contact book');
define('_MD_TCW_HOMEWORK_DEFAULT', " < h3> [Today's job] </h3> <ol> <li> & nbsp; </li> </ol> <h3> [tomorrow Preparation] </h3> <ol> < li> & nbsp; </li> </ol> <p> & nbsp; </p> ");

//action.php
define('_MD_TCW_ACTION', 'Events');
define('_MD_TCW_ACTION_SHORT', 'Events');
define('_MD_TCW_ACTIONID', 'Event number');
define('_MD_TCW_ACTIONNAME', 'Event Name');
define('_MD_TCW_ACTIONDESC', 'Description of Event');
define('_MD_TCW_ACTIONDATE', 'Event date');
define('_MD_TCW_ACTIONPLACE', 'Venue');
define('_MD_TCW_ACTIONUID', 'Author');
define('_MD_TCW_ACTIONCOUNT', 'Popular');
define('_MD_TCW_ACTION_UPLOAD', 'Upload image file');

//discuss.php
define('_MD_TCW_DISCUSS', 'ChatBox');
define('_MD_TCW_DISCUSS_SHORT', 'Message');
define('_MD_TCW_DISCUSSID', 'Number');
define('_MD_TCW_REDISCUSSID', 'Reply ID');
define('_MD_TCW_DISCUSS_UID', 'Author');
define('_MD_TCW_DISCUSSTITLE', 'Topic');
define('_MD_TCW_DISCUSSCONTENT', 'Discussion');
define('_MD_TCW_DISCUSSDATE', 'Name');
define('_MD_TCW_LASTTIME', 'Last updated');
define('_MD_TCW_DISCUSSCOUNTER', 'Popular');
define('_MD_TCW_LOGIN_TO_POST', 'You must first to login to post');
define('_MD_TCW_DISCUSS_SUBMIT', 'Submit Topic');
define('_MD_TCW_DISCUSS_TO_REPLY', 'I want to reply');
define('_MD_TCW_DISCUSS_REPLY', 'Reply');
define('_MD_TCW_DISCUSS_ADD', 'Message');

//files.php
define('_MD_TCW_FILES', 'Downloads');
define('_MD_TCW_FILES_SHORT', 'File');
define('_MD_TCW_FSN', 'File serial number');
define('_MD_TCW_FILES_UID', 'Uploaders');
define('_MD_TCW_FILE_KIND', 'Category');
define('_MD_TCW_FILE_DATE', 'Date');
define('_MD_TCW_FILENAME', 'Filename');
define('_MD_TCW_FILES_UPLOAD', 'Upload file');

//link.php
define('_MD_TCW_LINK', 'Common Website');
define('_MD_TCW_LINK_SHORT', 'Website');
define('_MD_TCW_LINKURL', 'Links URL');
define('_MD_TCW_LINKID', 'Number');
define('_MD_TCW_LINKTITLE', 'Site name');
define('_MD_TCW_LINKDESC', 'Description');
define('_MD_TCW_LINKSORT', 'Sorting');
define('_MD_TCW_LINKUID', 'Link Author');
define('_MD_TCW_LINKCOUNTER', 'Popular');
define('_MD_TCW_LINK_AUTO_GET', 'Auto-screenshot');

//video.php
define('_MD_TCW_VIDEO', 'Online video');
define('_MD_TCW_VIDEO_SHORT', 'Film');
define('_MD_TCW_VIDEOID', 'Video number');
define('_MD_TCW_VIDEONAME', 'Video name');
define('_MD_TCW_VIDEODESC', 'Video description');
define('_MD_TCW_VIDEODATE', 'Release date');
define('_MD_TCW_VIDEOPLACE', 'Thumbnail position');
define('_MD_TCW_VIDEOUID', 'Author');
define('_MD_TCW_VIDEOCOUNT', 'Popular');
define('_MD_TCW_VIDEOYOUTUBE', 'Youtube URL');
define('_MD_TCW_VIDEOCOUNTINFO', 'People watched a total of %s');

//calenda.php
define('_MD_TCW_CALENDA', 'Calendar');
define('_MD_TCW_CALENDA_TODAY', 'Today');
define('_MD_TCW_CALENDA_PREV_MONTH', 'Previous Mmonth');
define('_MD_TCW_CALENDA_NEXT_MONTH', 'Next Month');

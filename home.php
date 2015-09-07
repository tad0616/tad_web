<?php
include_once "../../mainfile.php";
//本檔的存在只是為了消除$WebID
$_SESSION['WebID'] = "";
$WebID             = "";
header("location:index.php");

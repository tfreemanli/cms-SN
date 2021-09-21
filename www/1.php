<?php
echo 'chalala';
session_start();
$_SESSION['Name']='UrPiG';
$_SESSION['SS_ID'] = '1';
$_SESSION['NS_CODE'] = array('','','');
$_SESSION['NS_NAME'] = array('','','');
echo $_SESSION['Name'];
echo $_SESSION['SS_ID'];
?>
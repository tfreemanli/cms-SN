<?php
session_start();
$_SESSION = array();
session_destroy();
if (!isset($_SESSION['Name'])){
echo 'no no no!';
}else{
echo 'yeah yeah yeah.';
}
?>
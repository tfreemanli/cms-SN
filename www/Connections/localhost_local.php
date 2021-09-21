<?php
# Type="MYSQL"
# HTTP="true"
$host = 'localhost';
$database = 'dbsutra';
$username = 'root';
$password = 'chalala123321';

// 创建对象并打开连接，最后一个参数是选择的数据库名称
$mysqli = new mysqli($host, $username, $password, $database);
?>
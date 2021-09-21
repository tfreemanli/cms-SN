<?php
# Type="MYSQL"
# HTTP="true"
$host = 'localhost';
$database = 'dbsutra';
$username = 'root';
$password = 'chalala123321';


try{
    // 创建对象并打开连接，最后一个参数是选择的数据库名称
    $mysqli = new mysqli($host, $username, $password, $database);

    // 编码转化为 utf8
    if (!$mysqli->set_charset("utf8")) {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
    // else {
    //    printf("<!-- Current character set: %s //-->\n", $mysqli->character_set_name());
    //}

    if (mysqli_connect_errno()) {
        // 诊断连接错误
        die("could not connect to the database.\n" . mysqli_connect_error());
    }

    $selectedDb = $mysqli->select_db($database);//选择数据库
    if (!$selectedDb) {
        die("MyNote could not locate the database\n" . mysql_error());
    }
} catch (Exception $e){
    $error = $e->getMessage();
    echo $error;
}
?>
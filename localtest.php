<?php
$hostname = "127.0.0.1";  //指定主机名，可以是IP
$database = "test";  //要连接的数据库名称
$username = "root";  //MYSQL的ROOT管理员名称
$password = "My?24680";  //ROOT的密码

mysql_connect($hostname,$username,$password) or die("connect database error!");

echo "Connect MySQL OK!";

?>
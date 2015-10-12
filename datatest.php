<?php
$hostname = "localhost";  //指定主机名，可以是IP  121.42.50.92
$database = "CUHere";  //要连接的数据库名称
$username = "root";  //MYSQL的ROOT管理员名称
$password = "root";  //ROOT的密码

mysql_connect($hostname,$username,$password) or die("connect database error!");

echo "Connect MySQL OK!";

?>
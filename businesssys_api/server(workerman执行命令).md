启动
以debug（调试）方式启动

php server.php start

以daemon（守护进程）方式启动

php server.php start -d

停止
php server.php stop

重启
php server.php restart

平滑重启
php server.php reload

查看状态
php server.php status

查看连接状态（需要Workerman版本>=3.5.0）
php server.php connections
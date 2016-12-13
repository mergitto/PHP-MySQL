<?php
session_start();
session_destroy(); //セッション変数を破棄する

header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/login.php");
?>
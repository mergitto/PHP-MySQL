<?php
function getDb() {
  //dbName, userName, passwordを自分の環境に合わせて変更してください
  $dsn = 'mysql:dbname=dbName; host=localhost; charset=utf8';
  $usr = 'userName';
  $passwd = 'password';

  $db = new PDO($dsn, $usr, $passwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//エラーをはきだせるようにする
  return $db;
}
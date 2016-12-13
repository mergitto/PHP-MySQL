<?php
require_once('getcsv.php');
require_once('../DbManager.php');

try{
$db=getDb();
$table_name = "kagoshima";

  $create = "CREATE TABLE IF NOT EXISTS {$table_name}("; //同じtable名が存在しないか確認
  for ($i = 0; $i < count($thArray); $i++) {
    $create .= $thArray[$i]." text".",";
  }
  $create = substr($create, 0, -1);
  $create .= ");";
  echo $create; //実行されるquery
  //$create_result = $db->query($create); //create文を実行するときのみ外す

  $create_user = "CREATE TABLE IF NOT EXISTS user(id int(5) primary key auto_increment, name varchar(20), password varchar(100));";
  echo $create_user;
  //$create_user_result = $db->query($create_user); //create文を実行するときのみ外す

}catch(PDOException $e){
  print "エラーメッセージ：{$e->getMessage()}";
}
?>
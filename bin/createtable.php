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
  echo "<br>";
  //$create_result = $db->query($create); //create文を実行するときのみ外す

  $create_user = "CREATE TABLE IF NOT EXISTS user(id int(5) primary key auto_increment, name varchar(20), password varchar(100));";
  echo $create_user;
  //$create_user_result = $db->query($create_user); //create文を実行するときのみ外す

  $create_favorite = "CREATE TABLE IF NOT EXISTS favorite(id int(5) primary key auto_increment, name varchar(20),管理ID text);";
  //$create_favorite_result = $db->query($create_favorite); //create文を実行するときのみ外す

echo '<br>';
  $arrays = array();
  $select = "select * from kagoshima;";
  $select_result = $db->query($select);
  foreach ($select_result as $key => $value) {
    echo $value[$key];
    //$arrays = $value;
  }
  //var_dump($arrays);


}catch(PDOException $e){
  print "エラーメッセージ：{$e->getMessage()}";
}
?>
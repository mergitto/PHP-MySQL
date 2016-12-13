<?php
require_once('../DbManager.php');

try{
  $db = getDb();
  //kagoshimaテーブルのカラム名を取るためのクエリ
  $columnName = "show columns from kagoshima;";
  $col_result = $db->query($columnName);

  $genre = 'select distinct ジャンル from kagoshima;';
  $genre_result = $db->query($genre);


}catch(PDOException $e){
  print "エラーメッセージ：{$e->getMessage()}";
}

//kagoshimaテーブルのカラム名を配列に格納する
$colName = array();
while($row = $col_result->fetch()){
  array_push($colName, $row['Field']);
}


?>
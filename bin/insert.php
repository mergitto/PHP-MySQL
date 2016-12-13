<?php
/***********************************************************
*このファイルはgetcsv.phpにてcsvファイルから$csvArray配列に入っている
*データをMySQLに挿入する処理を行うだけのファイルである
************************************************************/
require_once('../DbManager.php');
require_once('getcsv.php');

try{
  $db = getDb();

  //insert文のカラム指定行を作成
  $insert_column = 'insert into kagoshima (';
  foreach ($csvArray as $key => $value) {
    if($key>0){break;}
    for($i = 0; $i < count($thArray); $i++){
      if($key === 0){
        if($i === count($thArray)-1){$insert_column .= $value[$i].') values(';}
        else{$insert_column .= $value[$i].',';}
      }
    }
  }
  //上で作成したinsert文のvaluesの中身を作る
  foreach ($csvArray as $key => $value) {
    $insertKago = $insert_column;
    if($key==0){continue;}//1行目はカラム名なので処理を飛ばして中身だけをkagoshimaテーブルに挿入する
    for($i = 0; $i < count($thArray); $i++){
      $insertKago .= '"'.$value[$thArray[$i]].'",';
    }
    $insertKago = substr($insertKago, 0 , -1); //最後の,が不要なので削除
    $insertKago .= ');';
    //echo $insertKago; //実行されるquery
    //$insert_K = $db->query($insertKago); //insert文を実行するときのみコメントを外す
  }
  header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/top.php");

} catch(PDOException $e){
  print "エラーメッセージ：{$e->getMessage()}";
}

?>

<?php
//===========csvデータを配列に格納する===========//
$csvArray = array();
if( ($fp = fopen("https://www.city.kagoshima.lg.jp/jousys/documents/5-1_kankou.csv","r"))=== false ){
	die("CSVファイル読み込みエラー");
}
$y = 0;
while (($array = fgetcsv($fp)) !== FALSE) {

	//空行を取り除く
	if(!array_diff($array, array(''))){
		continue;
	}
	for($i = 0; $i < count($array); ++$i ){
		$elem = nl2br(mb_convert_encoding($array[$i], 'UTF-8', 'SJIS'));
		$elem = $elem === "" ?  "&nbsp;" : $elem;
		if($y == 0){
	      $csvArray[$y][$i] = $elem;
	    }else{
	      //連想配列にするための処理
	      $csvArray[$y][$csvArray[0][$i]] = $elem;
	    }
		}
	$y++;
}
fclose($fp);

//連想配列を取り出すために1行目のみ取り出す
$thArray = array();
foreach ($csvArray as $key => $value) {
	if($key===1){break;}
	for($i = 0; $i < count($value); $i++){
		array_push($thArray, $value[$i]);
	}
}
?>
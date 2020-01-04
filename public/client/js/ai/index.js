 // DOM読み込み
 $(function() {
     Main(); // JQueryによるメイン処理
 });

 /* JQueryによる処理の流れ
  *  引数：
  *  戻り値：
  */
 function Main() {
    //   alert('jQuery動作確認');
     console.log( navigator.geolocation.getCurrentPosition(test));
    $('span#coord-x').text();
 }

function test(position) {

    var geo_text = "緯度:" + position.coords.latitude + "\n";
    geo_text += "経度:" + position.coords.longitude + "\n";
    geo_text += "高度:" + position.coords.altitude + "\n";
    geo_text += "位置精度:" + position.coords.accuracy + "\n";
    geo_text += "高度精度:" + position.coords.altitudeAccuracy  + "\n";
    geo_text += "移動方向:" + position.coords.heading + "\n";
    geo_text += "速度:" + position.coords.speed + "\n";

    var date = new Date(position.timestamp);

    geo_text += "取得時刻:" + date.toLocaleString() + "\n";

    alert(geo_text);

}

 /*
  * 参考：

  // DOM読み込み
 // $(function() {
 //    Main();     // メイン処理
 // });

 // 全体読み込み (画像まで読み込んでから実行)
 // $(window).on('load', function() {
     // });
     //    Main();     // メイン処理

 // JQueryを使わない場合のDOM読み込み
 onload = function() {
 //    Main();     // メイン処理
 }
  */
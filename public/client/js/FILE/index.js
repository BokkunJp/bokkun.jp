 // DOM読み込み
 $(function() {
     Main(); // JQueryによるメイン処理
 });

 /* JQueryによる処理の流れ
  *  引数：
  *  戻り値：
  */
 function Main() {
    //  $('.image-value').change(function(e) {
    //      e.preventDefault();
    //      url = location.href;
    //      data = $('.image-value').val();
    //      console.log( "image-value=" + data);
    //      $.post( url,"image-value=" + data)
    //          .done(function(data) {
    //              console.log(data);
    //          })
    //          .fail(function(){
    //              console.error('send error');
    //          });
    //  });
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
 // DOM読み込み
 $(function() {
     Main(); // JQueryによるメイン処理
 });

 /* JQueryによる処理の流れ
  *  引数：
  *  戻り値：
  */
 function Main() {
     //  $('.fileButton').on('click', function(e) {
     //      var ajax = Ajax('POST', location.href + '/subdirectory/server/fileServer.php', 'test');
     //      ajax.always(function() {
     //              alert('Complate!!!');
     //          })
     //          .done(function(response) {
     //              $('.result').html(response);
     //              $('.createResult').show();
     //          })
     //          .fail(function() {
     //              alert('Failure!!');
     //          });

     //  });
 }

 function Ajax(type, url, data) {
     sendData = {
         type: type,
         url: url,
         data: data,
     }
     return $.ajax(sendData);

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
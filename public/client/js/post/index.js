 // DOM読み込み
 $(function() {
     main(); // JQueryによるメイン処理
 });

 /* JQueryによる処理の流れ
  *  引数：
  *  戻り値：
  */
function main() {
    $('.jsSend').on('click', function () {
        if (!confirm('データを送信しますか？')) {
            return false;
        } else {
            alert('JS(Ajax)形式で受信します');
            PostData($('input[name="data"]').val());

        }
    });
    $('.send').on('click', function ()
    {
        if (!confirm('データを送信しますか？')) {
            return false;
        } else {
            alert('PHP形式で受信します');
        }
    });
}


 /*
  * 参考：

  // DOM読み込み
 // $(function() {
 //    main();     // メイン処理
 // });

 // 全体読み込み (画像まで読み込んでから実行)
 // $(window).on('load', function() {
     // });
     //    main();     // メイン処理

 // JQueryを使わない場合のDOM読み込み
 onload = function() {
 //    main();     // メイン処理
 }
  */
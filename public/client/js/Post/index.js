 // DOM読み込み
 $(function() {
     Main(); // JQueryによるメイン処理
 });

 /* JQueryによる処理の流れ
  *  引数：
  *  戻り値：
  */
function Main() {
    $( '.jsSend' ).on( 'click', function ()
    {
        if ( !confirm( 'データを送信しますか？' ) )
        {
            return false;
        } else
        {
            alert( 'JS(Ajax)形式で受信します' );
            PostData( $( 'input[name="data"]' ).val() );

        }
    } );
    $( '.send' ).on( 'click', function ()
    {
        if ( !confirm( 'データを送信しますか？' ) )
        {
            return false;
        } else
        {
            alert( 'PHP形式で受信します' );
        }
    } );
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
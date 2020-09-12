// DOM読み込み
$( function ()
{
    Main(); // JQueryによるメイン処理
} );

/* JQueryによる処理の流れ
 *  引数：
 *  戻り値：
 */
function Main ()
{
    // 選択したファイル名のログを読み込む
    $( 'button[name="edit"]' ).on( 'click', function ( e )
    {
        var url = location.href;
        var selectObj = { "select_log": $( 'select[name="access_log"]' ).val() };

        AjaxMain( url, null, 'server.php', 'POST', selectObj, 'json' );
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
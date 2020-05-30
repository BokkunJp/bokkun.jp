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
    //  alert('jQuery動作確認');
    // 選択したソースを読み込む
    $( 'button[name="edit"]' ).on( 'click', function ( e )
    {
        var url = location.href;
        var selectObj = { "select": $( 'select[name="select"]' ).val() };
        // console.log(  );
        var ajax = AjaxMain( url, null, 'server.php', 'POST', selectObj, 'json' );
    } );

    // ソースの中身を更新する
    $( 'button[name="save"]' ).on( 'click', function ( e )
    {
        if ( confirm( '本当に更新しますか？' ) )
        {
            var url = location.href;
            var saveObj = { "select": $( 'select[name="select"]' ).val(), "input": $( '.result-src' ).val(), "save": 'true' };
            // console.log(  );
            var ajax = AjaxMain( url, null, 'server.php', 'POST', saveObj, 'json' );

            // $( '.result-src' ).val();
            alert( '更新しました。' );

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
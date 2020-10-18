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
    var num;
    // 選択したバージョンからログ一覧を出力する
    $( 'select[name="error_log"]' ).on( 'change', function ( e )
    {
        var url = location.href;
        var selectVersion = { "ver": $( this ).val() };
        num = $( this ).val();
        // 選択したバージョンを渡して、バージョン内のログ一覧を作成
        AjaxMain( url, null, 'server.php', 'POST', selectVersion, 'json', readFileList );
    } );

    // 選択したログを読み込む
    $( 'button[name="edit"]' ).on( 'click', function ( e )
    {
        console.log( num );
        var url = location.href;
        var selectObj = { "ver": num, "select_log": $( 'select[name="select_log"]' ).val() };
        console.log( selectObj );
        AjaxMain( url, null, 'server.php', 'POST', selectObj, 'json' );
    } );

}

function readFileList (ver)
{
    $select = $( 'select[name="select_log"]' );

    console.log( $select );
    // オプションの初期化
    $select.children().remove();
    console.log( $select );
    $option = $( '<option>' )
        .val( null)
        .text( '---' )
        .prop( 'selected', 'select_log' );
    $select.append( $option );
    console.log( $select );

    $.each( ver, function ( index, value )
    {
        if (value !== '.' && value !== '..' && value !== '_old') {
            console.log( index + ':' + value );
            $option = $( '<option>' )
                .val( value )
                .text( value )
                .prop( 'selected', 'select_log' );
            $select.append( $option );
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
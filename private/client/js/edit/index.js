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

    tinyMCE.init( {
        selector: 'textarea',
    });
    // 選択したディレクトリ名からファイル・サブディレクトリ一覧を出力する
    $( 'select[name="select"]' ).on( 'change', function ( e )
    {
        var url = location.href;
        var selectVersion = {
            "dir_name": $( this ).val(),
            'token': $( '.token' ).val()
        };
        num = $( this ).val();
        // 選択したバージョンを渡して、バージョン内のログ一覧を作成
        AjaxMain( url, null, 'server.php', 'POST', selectVersion, 'json', ReadFileList );
    } );

    // ファイル名またはディレクトリ名からファイルリストを生成する
    $( 'select[name="select_directory"]' ).on( 'change', function ( e )
    {
        var url = location.href;
        var selectDirectory = {
            "select_directory": $( this ).val(),
            "dir_name": $( 'select[name="select"]' ).val(),
            "token": $( '.token' ).val()
        };
        num = $( this ).val();
        // 選択したバージョンを渡して、バージョン内のログ一覧を作成
        AjaxMain( url, null, 'server.php', 'POST', selectDirectory, 'json', SetFileList);
    } );

    // 選択したソースを読み込む
    $( 'button[name="edit"]' ).on( 'click', function ( e )
    {
        var url = location.href;
        var selectObj = {
            "edit": $('.edit').val(),
            "directory": $( 'select[name="select"]' ).val(),
            "subdirectory": $( 'select[name="select_directory"]' ).val(),
            "file": $( 'select[name="select_file"]' ).val(),
            "token": $( '.token' ).val()
        };
        AjaxMain( url, null, 'server.php', 'POST', selectObj, 'json' );
    } );

    // ソースの中身を更新する
    $( 'button[name="save"]' ).on( 'click', function ( e )
    {
        if ( confirm( '本当に更新しますか？' ) )
        {
            var url = location.href;
            var saveObj = {
                "edit": $( '.edit' ).val(),
                "directory": $( 'select[name="select"]' ).val(),
                "subdirectory": $( 'select[name="select_directory"]' ).val(),
                "file": $( 'select[name="select_file"]' ).val(),
                "input": $( '.result-src' ).val(),
                "save": 'true',
                'token': $( '.token' ).val()
            };
            // console.log(  );
            AjaxMain( url, null, 'server.php', 'POST', saveObj, 'json' );

            // $( '.result-src' ).val();
            alert( '更新しました。' );

        } else
        {
            alert( '更新を中止しました。' );
        }
    } );

}

function ReadFileList ( ver )
{
    select = $( 'select[name="select_directory"]' );

    // オプションの初期化
    select.children().remove();
    option = $( '<option>' )
        .val( null )
        .text( '---' )
        .prop( 'selected', 'select' );
    select.append( option );

    $.each( ver, function ( index, value )
    {
        if ( value !== '.' && value !== '..' && value !== '_old' )
        {
            option = $( '<option>' )
                .val( value )
                .text( value )
            select.append( option );
        }
    } );
}

function SetFileList ( dir )
{
    select = $( 'select[name="select_file"]' );

    // オプションの初期化
    select.children().remove();
    option = $( '<option>' )
        .val( null )
        .text( '---' )
        .prop( 'selected', 'select' );
    select.append( option );

    if ( $.isArray( dir ) )
    {

        $.each( dir, function ( index, value )
        {
            if ( value !== '.' && value !== '..' && value !== '_old' && value !== 'notAutoInclude' )
            {
                option = $( '<option>' )
                    .val( value )
                    .text( value )
                select.append( option );
            }
        } );
    } else
    {
        // 選択肢を削除
        select.children().remove();
    }
}

/* テキストエリアの幅を自動で調整
 *  引数：
 *  戻り値：
 */
function AutoSetTextArea ( argObj )
{
    // 一旦テキストエリアを小さくしてスクロールバー（縦の長さを取得）
    argObj.style.height = "10px";
    var wSclollHeight = parseInt( argObj.scrollHeight );
    // 1行の長さを取得する
    var wLineH = parseInt( argObj.style.lineHeight.replace( /px/, '' ) );
    // 最低2行の表示エリアにする
    if ( wSclollHeight < ( wLineH * 2 ) )
    {
        wSclollHeight = ( wLineH * 2 );
    }
    // テキストエリアの高さを設定する
    argObj.style.height = wSclollHeight + "px";

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
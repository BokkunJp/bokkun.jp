 // DOM読み込み
 $(function() {
    Main(); // JQueryによるメイン処理
});

/* JQueryによる処理の流れ
 *  引数：
 *  戻り値：
 */
function Main() {

   // 選択したページ数を判別し、問題なければページ遷ar移する。
    $( '.image-type' ).on( 'change', function ()
    {
        if ( $( this ).val() != '---' ) {
            var url = location.href;
            var query = location.search;
            var selectValue = {
                "type": $( this ).val(),
                'select-token': $( 'input[name="select-token"]' ).val()
            };

            var url = url.replace( /\?.*$/, "" );
            url = url.replace( /\#.*$/, "" );

            // 選択した画像ページの種類とトークンを渡して、そのページにある画像群(とタイムスタンプから構成される配列)を取得
            AjaxMain( url, '/subdirectory/ajax/', 'server.php' + query, 'POST', selectValue, 'json', ViewImage );
        }
    } );

    // 全体チェックのチェックボックスにチェックが入ったら、各画像のチェックボックスにチェックを入れる(または外す)
    $( '.all-check-box' ).on( 'click', function ()
    {
        checkFlg = AllCheck( $( '.image-list' ).find( "input[type='checkbox']" ) );
        if (!checkFlg)
        {
            $( '.all-check-label' ).children( 'span' ).html( 'すべてのチェックを外す' );
            $( '.image-list' ).find( "input[type='checkbox']" ).prop( 'checked', true );
        } else
        {
            $( '.all-check-label' ).children( 'span' ).html( 'すべてチェックする' );
            $( '.image-list' ).find( "input[type='checkbox']" ).prop( 'checked', false );
        }

        allCehck = $( '.all-check-box' ).prop( "checked", false );
    } );

    function AllCheck ( elm )
    {
        ret = true;
        $.each( elm, function ( index, val )
        {
            if ( val.checked == false )
            {
                ret = false;
                return false;
            }
        } );
        return ret;
    }

    // ページ数に問題がある場合はエラーを出力し、送信を中止する。
    $( '.update_page' ).on( 'keypress', function ( e )
    {
        if(e.key == 'Enter') {
            var url = $(location).attr('pathname');
            var query = parseInt($('.update_page').val());
            var min = parseInt($('.update_page').attr('min'));
            var max = parseInt($('.update_page').attr('max'));
            var sendUrl = url + "?page=" + query;
            if (!$.isNumeric(query)) {
                alert('ページの指定が不正です。');
                return false;
            } else if (query < min) {
                alert(min + 'ページ以上のページ番号を指定してください。');
                return false;
            } else if (query > max) {
                alert(max + 'ページ以下のページ番号を指定してください。');
                return false;
            } else {
                $('.pageForm').attr('action', sendUrl);
                $('.pageForm').submit();
            }
        }
    } );

    // 画像横の各チェックボックスが押されたときの処理
    $( '.image-check' ).on( 'click', function ()
    {
        checkFlg = AllCheck( $( '.image-list' ).find( "input[type='checkbox']" ) );
        if ( !checkFlg )
        {
            $( '.all-check-label' ).children( 'span' ).html( 'すべてチェックする' );
        } else
        {
            $( '.all-check-label' ).children( 'span' ).html( 'すべてのチェックを外す' );
        }

    } );

    // 移動ボタンを押したときの処理
    $( 'button[name="move"]' ).on( 'click', function ()
    {
        var url = $( location ).attr( 'pathname' );
        var query = parseInt( $( '.update_page' ).val() );
        var min = parseInt( $( '.update_page' ).attr( 'min' ) );
        var max = parseInt( $( '.update_page' ).attr( 'max' ) );
        var sendUrl = url + "?page=" + query;
        if ( !$.isNumeric( query ) )
        {
            alert( 'ページの指定が不正です。' );
            return false;
        } else if ( query < min )
        {
            alert( min + 'ページ以上のページ番号を指定してください。' );
            return false;
        } else if ( query > max )
        {
            alert( max + 'ページ以下のページ番号を指定してください。' );
            return false;
        } else
        {
            $( '.pageForm' ).attr( 'action', sendUrl );
            $( '.pageForm' ).submit();
        }
    } );

    // 削除コピーボタンを押したときの処理
    $( 'button[name="delete"]' ).on( 'click', function ()
    {
        if ( !confirm( '画像を削除しますか？' ) )
        {
            return false;
        }
    });

    // 画像コピーボタンを押したときの処理
    $( 'button[name="copy"]' ).on( 'click', function ()
    {
        var copy = prompt( '画像コピー先のページ名を入力してください。' );
        while ( !copy )
        {
            alert( 'ページ名が入力されていません。' );
            copy = prompt( 'コピー先のページを入力してください。' );
        }
        $( '.copy-image-name' ).val( copy );
    } );

}

function ViewImage ( data )
{
    $( '.view-image-type' ).html( data[ 'view-image-type' ] );
    if (data['src']) {
        htmlVal = '<div class="image - list"><br>\
            <div class="warning" > 不正な遷移です。もう一度操作してください。</div > <a href="./" class="page" target="_self">画像管理ページへ戻る</a></div >';
        $( '.image-list' ).html( htmlVal );
        $( '.image-pager' ).html( '' );
    } else if (data['result'] == false) {
        htmlVal = '<div class="image - list"><br>\
            <div class="warning" > 画像がありません。</div > <a href="./" class="page" target="_self">画像管理ページへ戻る</a></div >';
        $( '.image-list' ).html( htmlVal );
        $( '.image-pager' ).html('');
    } else {
        htmlVal = '';
        $.each( data, function ( index, val )
        {
            if ( index == 'url' || index == 'view-image-type' )
            {
                htmlVal += '<br/>';
                return false;
            }

            htmlVal += "<a href='" + data[ 'url' ] + val[ 'name' ] + "' target='new'><img src='" + data[ 'url' ] + val[ 'name' ] + "' title='" + val[ 'name' ] + "' width=400px height=400px /></a><label><input type='checkbox' name='" + "img_" + val[ 'name' ] + "' value='" + val[ 'name' ] + "' /><span>削除・コピーする</span></label> <br/>アップロード日時: " + val[ 'time' ] + "<br/>";
        } )

        $( '.image-list' ).html( htmlVal );
        $( '.image-pager' ).html( data[ 'pager' ] );
    }

    // 移動ボタンを押したときの処理
    $( 'button[name="move"]' ).on( 'click', function ()
    {
        var url = $( location ).attr( 'pathname' );
        var query = parseInt( $( '.update_page' ).val() );
        var min = parseInt( $( '.update_page' ).attr( 'min' ) );
        var max = parseInt( $( '.update_page' ).attr( 'max' ) );
        var sendUrl = url + "?page=" + query;
        if ( !$.isNumeric( query ) )
        {
            alert( 'ページの指定が不正です。' );
            return false;
        } else if ( query < min )
        {
            alert( min + 'ページ以上のページ番号を指定してください。' );
            return false;
        } else if ( query > max )
        {
            alert( max + 'ページ以下のページ番号を指定してください。' );
            return false;
        } else
        {
            $( '.pageForm' ).attr( 'action', sendUrl );
            $( '.pageForm' ).submit();
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
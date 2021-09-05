 // DOM読み込み
 $(function() {
    Main(); // JQueryによるメイン処理
});

/* JQueryによる処理の流れ
 *  引数：
 *  戻り値：
 */
function Main() {

    // 選択した内容を調整

   // 選択したページ数を判別し、問題なければページ遷移する。
    $( '.image-type' ).on( 'change', function ( e )
    {
        var url = location.href;
        var query = location.search;
        var selectValue = {
            "type": $( this ).val(),
            'select-token': $( 'input[name="select-token"]' ).val()
        };
         var url = url.replace( /\?.*$/, "" );
         // 選択した画像ページの種類とトークンを渡して、そのページにある画像群(とタイムスタンプの配列)を取得
         AjaxMain( url, '/subdirectory/ajax/', 'server.php' + query, 'POST', selectValue, 'json', ViewImage );
    } );

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
    });
}

function ViewImage ( data )
{
    $( '.view-image-type' ).html( data[ 'view-image-type' ] );
    if (data['src-view']) {
        htmlVal = '<div class="image - list"><br>\
            <div class="warning" > 不正な操作を検知しました。再読み込みしてください。</div > <a href="./" class="page" target="_self">画像管理ページへ戻る</a></div >';
        $( '.image-list' ).html( htmlVal );
        $( '.image-pager' ).html( '' );
    }
    if (data['result'] == false) {
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

            htmlVal += "<a href='" + data[ 'url' ] + "/" + val[ 'name' ] + "' target='new'><img src='" + data[ 'url' ] + "/" + val[ 'name' ] + "' title='" + val[ 'name' ] + "' width=400px height=400px /></a><label><input type='checkbox' name='" + val[ 'name' ] + "' value='" + val[ 'name' ] + "' /><span>削除する</span></label> <br/>アップロード日時: " + val[ 'time' ] + "<br/>";
        } )

        $( '.image-list' ).html( htmlVal );
        $( '.image-pager' ).html( data[ 'pager' ] );
    }
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
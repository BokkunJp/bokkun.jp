 // DOM読み込み
 $(function() {
     Main(); // JQueryによるメイン処理
 });

 /* JQueryによる処理の流れ
  *  引数：
  *  戻り値：
  */
 function Main() {
    // 選択したページ数を判別し、問題なければページ遷移する。
    // ページ数に問題がある場合はエラーを出力し、送信を中止する。
    $( '.update_page' ).on( 'keypress', function ( e )
    {
        if(e.key == 'Enter'){
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

    $('.image-range').on('change', function(event) {
        var url = location.href;
        var query = location.search;
        var selectValue = {
            'select-token': $( 'input[name="token"]' ).val(),
            'image-value': $(this).val(),
        };

        var url = url.replace( /\?.*$/, "" );
        url = url.replace( /\#.*$/, "" );

        $('.page-value').html($(this).val());

        AjaxMain( url, 'subdirectory/ajax/', 'server.php' + query, 'POST', selectValue, 'json', ViewImage);
    });
 }

function ViewImage ( data )
{
    $( '.view-image-type' ).html( data[ 'view-image-type' ] );
    if (data['select-notice']) {
        $('.select-notice').html('不正な内容が選択されました。');
    } else {
        $('.select-notice').html('');
    }

    console.log(data);

    if (data['error']) {
        htmlVal = '<div class="image-box">\
            <div class="warning">' + data['error-view'] + '</div><a href="./" class="page" target="_self">画像閲覧ページへ戻る</a></div>';
        $( '.image-box' ).html( htmlVal );
        $( '.image-pager' ).html( '' );
    } else if (data['result'] == false) {
        htmlVal = '<div class="image-box">\
            <div class="warning">画像がありません。</div><a href="./" class="page" target="_self">画像閲覧ページへ戻る</a></div>';
        $( '.image-box' ).html( htmlVal );
        $( '.image-pager' ).html('');
    } else {
        htmlVal = '<ul>';
        $.each( data, function ( index, val )
        {
            if ( index == 'url' || index == 'view-image-type' || index == 'pager' )
            {
                return false;
            }

            htmlVal += '<li>';

            htmlVal += "<a href='" + data[ 'url' ] + val[ 'name' ] + "' target='new'><img src='" + data[ 'url' ] + val[ 'name' ] + "' title='" + val[ 'name' ] + "' width=400px height=400px /></a>";
            htmlVal += "<p class='image-info'>画像名:" + val[ 'name' ] + "<br/>アップロード日時: " + val[ 'time' ] + "</p>";

            htmlVal += '</li>';
        })

        htmlVal += '</ul>';

        $( '.image-box' ).html( htmlVal );
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
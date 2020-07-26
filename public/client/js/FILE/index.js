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
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            var url = $(location).attr('pathname');
            var queryArray = $('update_page');
            $.each(queryArray, function(index, val) {
                console.log(index);
                console.log(val);
            });
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
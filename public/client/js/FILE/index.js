 // DOM読み込み
 $(function() {
     Main(); // JQueryによるメイン処理
 });

 /* JQueryによる処理の流れ
  *  引数：
  *  戻り値：
  */
 function Main() {
    // // 選択したディレクトリ名からファイル・サブディレクトリ一覧を出力する
    $( '.page_number' ).on( 'keypress', function ( e )
    {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            var url = location.href;
            var selectVersion = {
                "dir_name": $( this ).val(),
                'token': $( '.token' ).val()
            };
            var num = $( this ).val();
            // 選択したバージョンを渡して、バージョン内のログ一覧を作成
            var ajax = AjaxMain( url, null, 'server.php', 'POST', selectVersion, 'json', ReadFileList );
        }
    } );
 }

 function ReadFileList ( ver )
 {
    //  select = $( 'select[name="select_directory"]' );
 
     // オプションの初期化
    //  select.children().remove();
    //  option = $( '<option>' )
    //      .val( null )
    //      .text( '---' )
    //      .prop( 'selected', 'select' );
    //  select.append( option );
 
    //  $.each( ver, function ( index, value )
    //  {
    //      if ( value !== '.' && value !== '..' && value !== '_old' )
    //      {
    //          option = $( '<option>' )
    //              .val( value )
    //              .text( value )
    //          select.append( option );
    //      }
    //  } );

     alert('クリックされました');

 }


 /**
  * ReadF
  * 
  */

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
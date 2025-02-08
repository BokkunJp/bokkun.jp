// DOM読み込み
$(function ()
{
    main(); // JQueryによるメイン処理
});

/* JQueryによる処理の流れ
 *  引数：
 *  戻り値：
 */
function main()
{
    // 選択したページ数を判別し、問題なければページ遷移する。
    // ページ数に問題がある場合はエラーを出力し、送信を中止する。
    $('.update_page').on('keypress', function (e)
    {
        if (e.key == 'Enter') {
            const url = $(location).attr('pathname');
            const query = parseInt($('.update_page').val());
            const min = parseInt($('.update_page').attr('min'));
            const max = parseInt($('.update_page').attr('max'));
            const sendUrl = url + "?page=" + query;
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
    $('.image').on('click', function (e)
    {
        $('#back').css({
            'width': $(window).width(),
            'height': $(document).height()
        }).show();
    });

    $('.imagePopUp').css({
        'position': 'absolute',
        'left': Math.floor(($(window).width() - $('.imagePopUp').width()) / 2) + 'px',
        'top': $(window).scrollTop() + 50 + 'px'
    }).fadeIn();

    //拡大表示クリック
    $('.imagePopUp, #back').click(function ()
    {
        $('.imagePopUp').fadeOut('slow', function ()
 {
            $('#back').hide();
        });
    });


}

/*
 * 参考：

 // DOM読み込み
// $(function() {
//    main();     // メイン処理
// });

// 全体読み込み (画像まで読み込んでから実行)
// $(window).on('load', function() {
    // });
    //    main();     // メイン処理

// JQueryを使わない場合のDOM読み込み
onload = function() {
//    main();     // メイン処理
}
 */
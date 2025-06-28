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
    let num;
    // 選択したバージョンからログ一覧を出力する
    $('select[name="error_log"]').on('change', function (e)
    {
        const url = location.href;
        const selectVersion = { "ver": $(this).val() };
        num = $(this).val();
        // 選択したバージョンを渡して、バージョン内のログ一覧を作成
        ajaxMain(url, null, 'server.php', 'POST', selectVersion, 'json', readFileList);
    });

    // 選択したログを読み込む
    $('button[name="edit"]').on('click', function (e)
    {
        const url = location.href;
        const selectObj = { "ver": num, "select_log": $('select[name="select_log"]').val() };
        ajaxMain(url, null, 'server.php', 'POST', selectObj, 'json');
    });

}

function readFileList (ver)
{
    $select = $('select[name="select_log"]');

    console.log($select);
    // オプションの初期化
    $select.children().remove();
    console.log($select);
    $option = $('<option>')
        .val(null)
        .text('---')
        .prop('selected', 'select_log');
    $select.append($option);
    console.log($select);

    $.each(ver, function (index, value)
    {
        if (value !== '.' && value !== '..' && value !== '_old') {
            $option = $('<option>')
                .val(value)
                .text(value)
                .prop('selected', 'select_log');
            $select.append($option);
        }
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
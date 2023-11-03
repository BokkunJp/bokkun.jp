// DOM読み込み
$(function() {
    main(); // JQueryによるメイン処理
});

/* JQueryによる処理の流れ
 *  引数：
 *  戻り値：
 */
function main() {
    $('button[name="delete"]').on('click', function (e)
    {
        if (!confirm('本当に削除しますか？')) {
            return false;
        }
    });

    $('select[name="select"]').on('change', function () {
        var url = location.href;
        var query = location.search;
        var value = {
            "page": $(this).val(),
            'edit-token': $('input[name="edit-token"]').val()
        };

        var url = url.replace(/\?.*$/, "");
        url = url.replace(/\#.*$/, "");

        // 選択したページ名とトークンを渡して、そのページが削除不可かどうかの判定結果を取得
        ajaxMain(url, '/subdirectory/ajax/', 'server.php' + query, 'POST', value, 'json', Result);
    });
}

function Result(data) {
    if (data)    {
        // 削除不可のページの場合は削除ボタンを非活性に
        $('button[name="delete"]').prop('disabled', true);
    } else
    {
        // 削除可のページの場合は削除ボタンを活性に
        $('button[name="delete"]').prop('disabled', false);
    }
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
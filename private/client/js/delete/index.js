// 全体読み込み (画像まで読み込んでから実行)
$(window).on('load', function() {
    main(); // JQueryによるメイン処理
});

/* JQueryによる処理の流れ
 *  引数：
 *  戻り値：
 */
function main() {

   // 選択したページ数を判別し、問題なければページ遷移する。
    $('.image-type').on('change', function ()
    {
        if ($(this).val() != '---') {
            let url = location.href;
            const query = location.search;
            const selectValue = {
                "type": $(this).val(),
                'select-token': $('input[name="select-token"]').val(),
            };

            url = url.replace(/\?.*$/, "");
            url = url.replace(/\#.*$/, "");

            // 選択した画像ページの種類とトークンを渡して、そのページにある画像群(とタイムスタンプから構成される配列)を取得
            ajaxMain(url, '/subdirectory/ajax/', 'server.php' + query, 'POST', selectValue, 'json', viewImage);
        }
    });

    // 全体チェックのチェックボックスにチェックが入ったら、各画像のチェックボックスにチェックを入れる(または外す)
    $('.all-check-box').on('click', function ()
    {
        checkFlg = allCheck($('.image-list').find("input[type='checkbox']"));
        if (!checkFlg) {
            $('.all-check-label').children('span').html('すべてのチェックを外す');
            $('.image-list').find("input[type='checkbox']").prop('checked', true);
        } else {
            $('.all-check-label').children('span').html('すべてチェックする');
            $('.image-list').find("input[type='checkbox']").prop('checked', false);
        }

        allCehck = $('.all-check-box').prop("checked", false);
    });

    function allCheck(elm)
    {
        ret = true;
        $.each(elm, function (index, val)
 {
            if (val.checked == false) {
                ret = false;
                return false;
            }
        });
        return ret;
    }

    // ページ数に問題がある場合はエラーを出力し、送信を中止する。
    $('.update_page').on('keypress', function (e)
    {
        if(e.key == 'Enter') {
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

    // 画像横の各チェックボックスが押されたときの処理
    $('.image-check').on('click', function ()
    {
        checkFlg = allCheck($('.image-list').find("input[type='checkbox']"));
        if (!checkFlg) {
            $('.all-check-label').children('span').html('すべてチェックする');
        } else {
            $('.all-check-label').children('span').html('すべてのチェックを外す');
        }

    });

    // 移動ボタンを押したときの処理
    $('button[name="move"]').on('click', function ()
    {
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
    });

    // 画像削除ボタンを押したときの処理
    $('button[name="delete"]').on('click', function ()
    {
        if (!confirm('画像を完全に削除しますか？\n※この操作は取り消せません。')) {
            return false;
        }
    });

    // 画像コピーボタンを押したときの処理
    $('button[name="copy"]').on('click', function ()
    {
        const copy = prompt('画像コピー先のページ名を入力してください。');
        while (!copy)
 {
            alert('ページ名が入力されていません。');
            copy = prompt('コピー先のページを入力してください。');
        }
        $('.copy-image-name').val(copy);
    });

}

function viewImage(data)
{
    // チェックの保持
    const checkFind = $('.image-list').find("input[type='checkbox']");
    const checkIndexList = [];
    const page = getParam('page');
    checkFind.each(function (index, val)
    {
        if (page) {
            index += (page - 1) * 10;
        }
        checkIndexList[index] = $(val).prop('checked');
    });

    $('.view-image-type').html(data['view-image-type']);
    if (data['select-notice']) {
        $('.select-notice').html('不正な内容が選択されました。');
    } else {
        $('.select-notice').html('');
    }
    if (data['error']) {
        htmlVal = '<div class="image-list">\
            <div class="warning">' + data['error-view'] + '</div><a href="./" class="page" target="_self">画像管理ページへ戻る</a></div>';
        $('.image-list').html(htmlVal);
        $('.image-pager').html('');
    } else if (data['result'] == false) {
        htmlVal = '<div class="image-list">\
            <div class="warning">現在の枚数表示では、そのページには画像はありません。</div><a href="./" class="page" target="_self">画像管理ページへ戻る</a></div>';
        $('.image-list').html(htmlVal);
        $('.image-pager').html('');
    } else {
        htmlVal = '';
        $.each(data, function (index, val)
 {
            if (index == 'url' || index == 'view-image-type') {
                htmlVal += '<br/>';
                return false;
            }

            htmlVal += "<div class='image-info'><span class='image-name'>画像名:" + val['name'] + "</span> <span class='image-size'>(" + val['info']['size'] + val['info']['sizeUnit'] + "B)</span></div>" + "<div class='image-info2'><a href='" + data['url'] + val['name'] + "' target='new'><img src='" + data['url'] + val['name'] + "' title='" + val['name'] + "' width=400px height=400px /></a><label><input type='checkbox' class='image-check' name='" + "img_" + val['name'] + "' value='" + val['name'] + "'";

            if (checkIndexList[index]) {
                htmlVal += " checked";
            }

            htmlVal += " /><span>削除・コピーする</span></label> <br/>アップロード日時: " + val['time'] + "</div><br/>";
        })

        $('.image-list').html(htmlVal);
        $('.image-pager').html(data['pager']);
    }

    // 移動ボタンを押したときの処理
    $('button[name="move"]').on('click', function ()
    {
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
    });


    // 改めて全チェック・全チェック解除の判定を行う
    $('.all-check-label').children('span').html('すべてのチェックを外す');

    let loop = false;
    $.each(checkIndexList, function (index, val)
    {
        if (loop === false) {
            loop = true;
        }

        if (!val) {
            $('.all-check-label').children('span').html('すべてチェックする');
            return false;
        }
    });

    if (loop === false)    {
        $('.all-check-label').children('span').html('すべてチェックする');
    }

    function allCheck(elm)
    {
        ret = true;
        $.each(elm, function (index, val)
 {
            if (val.checked == false) {
                ret = false;
                return false;
            }
        });
        return ret;
    }

    // 画像横の各チェックボックスが押されたときの処理
    $('.image-check').on('click', function ()
    {
        checkFlg = allCheck($('.image-list').find("input[type='checkbox']"));
        if (!checkFlg) {
            $('.all-check-label').children('span').html('すべてチェックする');
        } else {
            $('.all-check-label').children('span').html('すべてのチェックを外す');
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
 // DOM読み込み
 $(function() {
//    main();     // メイン処理
 }
 */
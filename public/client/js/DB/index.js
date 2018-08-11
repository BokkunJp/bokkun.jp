// 全体読み込み (画像まで読み込んでから実行)
$(window).on('load', function() {
    Main(); // JQueryによるメイン処理
});

/* JQueryによる処理の流れ
 *  引数：
 *  戻り値：
 */
function Main() {

}

function View() {
    var view = $('.view');
    var id = $('.id');
    view.val('true');
}

function Save() {
    var val = $('.val').val();
    if (!val) {
        alert('値を設定してください。');
        return -1;
    } else if (!val.match(/^[1-9]*[0-9]$/)) {
        alert('数値以外が入力されています。数値を設定してください。');
        return -1;
    }
    var save = $('.save');
    save.val('true');
    alert(val + 'を登録しました。');
}

function Update() {
    var id = $('.id');
    var val = $('.val');
    var upd = $('.upd');

    console.log(id.val());
    console.log(val.attr('min'));

    if (!id.val() || !val.val()) {
        var elm = !id.val() ? 'id' : '値';
        alert(elm + 'を設定してください。');
        return -1;
    } else if (!val.val().match(/^[1-9]*[0-9]$/)) {
        alert('数値以外が入力されています。数値を設定してください。');
        return -1;
    } else if (id.val() < val.attr('min')) {
        alert((val.attr('min') - 1) + '以下の ID は登録されていません。');
        return -1;
    }

    upd.val(true);
    alert('ID:' + id.val() + 'の値を' + val.val() + 'に更新しました。');
}

function Delete() {
    var id = $('.id');
    var dlt = $('.dlt');
    var conf;

    if (id.val()) {
        conf = confirm('ID: ' + id.val() + 'の値を削除しますか？');
    } else {
        conf = confirm('すべてのデータを削除しますか？');
    }
    dlt.val(conf);
    if (conf == true) {
        alert('削除しました。');
    } else {
        alert('処理を中断しました。');
        return -1;
    }
}


// 全体読み込み
// $(window).load(function() {
//     // 画像処理用
// });

/*
 * 参考： JQueryを使わない場合のDOM読み込み

onload = function() {
    Main();
}

 */
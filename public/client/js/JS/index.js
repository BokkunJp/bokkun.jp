// DOM読み込み
$(window).on('load', function() {
    // alert('jQuery専用処理');
    Main(); // JQueryによるメイン処理
});

/* JQueryによる処理の流れ
 *  引数：
 *  戻り値：
 */
function Main() {
    $('.radio').on('dblclick', function(e) {
        $(this).prop('checked', false);
    });
    eventElement.subEvent();
    var ajaxElement = AjaxMain('https://bokkun.xyz/public/');

}

var eventElement = {
    'subEvent': function() {
        console.log('EventFunction');
        $('.radio').on('mouseover', function(e) { $('.hidden').css('display', 'inline-block'); });
        $('.radio').on('mouseout', function(e) { $('.hidden').css('display', 'none'); });

    },
};

function AjaxMain(url) {
    if (!url) {
        console.log(url);
        url = location.href + '/subdirectory/clientCreate.php';
    }
    var ajx = Ajax('POST', url, 'test');
    ajx.always(function() {
            alert('Complate!!!');
        })
        .done(function(response) {
            alert('Success!!!');
            $('.result').html(response);
            $('.createResult').show();
        })
        .fail(function() {
            alert('Failure!!');
        });

}

function Ajax(type, url, data) {
    sendData = {
        type: type,
        url: url,
        data: data,
    }
    return $.ajax(sendData);

}


// DOM読み込み
// $(function() {
//     // メイン処理
// });

// 全体読み込み (画像まで読み込んでから実行)
// $(window).on('load', function() {
//     // メイン処理
// });
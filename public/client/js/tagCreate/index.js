// 全体読み込み (画像まで読み込んでから実行)
$(window).on('load', function() {
    eventLoad();
});

function eventLoad() {
    $('.tagCreateButton').on('click', function(e) {
        alert('タグを作成します。');
        var ajx = Ajax('POST', location.href + '/subdirectory/clientCreate.php', 'test');
        ajx
            .always(function() {
                alert('Complate!!!');
            })
            .done(function(response) {
                $('.result').html(response);
                $('.createResult').show();
            })
            .fail(function() {
                alert('Failure!!');
            });

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
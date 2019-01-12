function AjaxMain(url, dir, file) {
    if (!url) {
        if (!dir) {
            dir = '/subdirectory/';
            if (!file) {
                alert('File is undefined.');
                eixt;
            } else {
                dir += file;
            }
        }
        url = location.href + dir;
    }
    var ajx = Ajax('GET', url, 'test');
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
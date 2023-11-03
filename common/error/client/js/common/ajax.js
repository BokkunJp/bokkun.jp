function ajaxMain(url, dir, file, data, type) {
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
    var ajx = ajax(type, url, data);
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

function ajax(type, url, data) {
    sendData = {
        type: type,
        url: url,
        data: data,
    }
    return $.ajax(sendData);

}
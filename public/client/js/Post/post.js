function PostData(data)
{
    if (!data) {
        Response("<span class='warning'>Ajax:データがありません</span>", 'html');
    } else
    {
        ajaxData = ajaxMain('', null, 'server.php', 'POST', {post: data}, 'json', Response);

    }
}

function Response(jsonData, type)
{
    if (type === 'html') {
        $('.jsForm').html(jsonData);
    } else
    {
        $('.jsForm').val(jsonData);
    }
}
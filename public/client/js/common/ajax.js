function AjaxMain ( url, dir, file, type = 'POST', data, datatype = "text", CallBack = null )
{
    try
    {
        if ( !url )
        {
            url = location.href;
        }
        if ( !dir )
        {
            dir = 'subdirectory/';
        }
        if ( !file )
        {
            alert( 'File is undefined.' );
        } else
        {
            dir += file;
        }
        url += dir;
        var ajx = Ajax( type, url, datatype, data );

        ajx.always( function ( xmlhttp )
        {
            // console.log( xmlhttp.responseText ); // JSONデータ(デバッグ用)
        } )
            .done( function ( response )
            {
                var jsonData = JSON.stringify( response );  // レスポンスデータをエンコード
                jsonData = JSON.parse( jsonData ); // エンコードしたJSONデータをデコード

                // コールバック関数が定義されていない場合は、取得・成形したデータを出力するのみ
                if ( CallBack === null )
                {
                    for ( var _key in jsonData )
                    {
                        $( '.result-' + _key ).html( jsonData[ _key ] );

                    }
                    // コールバック関数が定義されている場合は、取得したJSONデータをコールバック関数に渡す
                } else
                {
                    CallBack( jsonData );
                }
            } )
            .fail( function ( xhr, textStatus, errorThrown )
            {
                console.log( "NG" );
                console.log( "textStatus: " + textStatus );
                console.log( "errorThrown    : " + errorThrown.message );
            } );
    } catch ( e )
    {
        alert( e.message );
    }

}

function Ajax ( type, url, datatype, data )
{
    // console.log( [ type, url, datatype, data ] );
    sendData = {
        type: type,
        url: url,
        dataType: datatype,
        data: data,
    }
    return $.ajax( sendData );

}

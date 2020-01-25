function AjaxMain ( url, dir, file, type = 'POST', data, datatype = "text" )
{
    try
    {
        if ( !url )
        {
            url = location.href;
        }
        if ( !dir )
        {
            dir = '/subdirectory/';
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
        ajx.always( function ()
        {
            //        alert('Complate!!!');
        } )
            .done( function ( response )
            {
                //                alert('Success!!!');
                console.log( JSON.parse( response ) );
                var jsonData = JSON.parse( response );
                for ( var _key in jsonData )
                {
                    $( '.result-' + _key ).html( jsonData[ _key ] );

                }
            } )
            .fail( function ( xhr, textStatus, errorThrown )
            {
                alert( "NG:" + textStatus.status );
            } );
    } catch ( e )
    {
        alert( e.message );
    }

}

function Ajax ( type, url, datatype, data )
{
    console.log( [ type, url, datatype, data ] );
    sendData = {
        type: type,
        url: url,
        dataType: datatype,
        data: data,
    }
    return $.ajax( sendData );

}

Ajax.prototype.Get = function ( url, data, callback )
{

}

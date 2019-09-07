 // DOM読み込み
 $(function() {
     Main(); // JQueryによるメイン処理
});

/* JQueryによる処理の流れ
  *  引数：
  *  戻り値：
  */
function Main ()
{
     //  alert('jQuery動作確認');



    $( '.test' ).on( 'click', function ( e ) { EventHandler( e ); });
}

function EventHandler ( e )
{
    console.log( 'test' );
    console.log( cache );

    if ( cache === undefined ) {
        alert( 'Undefined' );
        cache = 1;
    } else {
        alert( 'cache:' + cache );
    }
    var clickCoord = [ e.clientX, e.clientY ];
    $( '.result' ).html('offset(x, y) = (' + e.offsetX +',' + e.offsetY+ ')');
}

/**
 * ベクトル同士からスカラー積を求める
 * @param array vector1
 * @param array vector2
 * @return number
 */
function MulScalar ( vector1, vector2 )
{
    return vector1.x * vector2.x + vector1.y * vector1.y + vector1.z * vector2.z;
}

/**
 * ベクトル同士からベクトル積を求める
 * @param array vector1
 * @param array vector2
 * @return array
 */
function MulVector ( vector1, vector2 )
{
    var vector_ans = [];
    return [];
}

/**
 * ベクトル同士から距離を求める
 * @param array vector1
 * @param array vector2
 * @return number
 */
function CalcDistance ( vector1, vector2 )
{
    var distanceX2 = Math.pow(vector1.x - vector2.x, 2 );
    var distanceY2 = Math.pow( vector1.Y - vector2.Y, 2 );
    var distanceZ2 = Math.pow( vector1.Z - vector2.Z, 2 );
    return Math.sqrt(distanceX2 + distanceY2 + distanceZ2);
}

class Vector
{
    constructor(mode)
    {
        this.Init(mode);
    }

    Init (mode)
    {
        this.setMode( mode );

        this.x = this.y = this.z = 0;
    }

    SetMode (mode)
    {
        this.mode = mode;
        if ( mode === 2 )
        {
            this.z = null;
        }
    }

    Validate ( elm )
    {

    }

    SetData ( x, y, z )
    {
        this.x = x;
        this.y = y;
        this.z = z;
    }

    ViewResult (className)
    {
        result = '(x,y,z)='+this.x+this.y+this.z;
        ( className ).html( result );
    }

}

/*
  * 参考：

  // DOM読み込み
 // $(function() {
 //    Main();     // メイン処理
 // });

 // 全体読み込み (画像まで読み込んでから実行)
 // $(window).on('load', function() {
     // 求める
     //    Main();     // メイン処理

 // JQueryを使わない場合のDOM読み込み
 onload = function() {
 //    Main();     // メイン処理
 }
  */
 // DOM読み込み
$( function ()
{
    Main(); // JQueryによるメイン処理
});

 /*
  * イベントドリブンによる処理を記述する
  *  引数：なし
  *  戻り値：なし
  */
function Main ()
{
    $( 'input[name="answer1"]' ).change( function ()
    {
        Judge($(this).val(), '#val1', '#val2', 'add');
        $( this ).prop( 'disabled', true );
    } );
    $( 'input[name="answer2"]' ).change( function ()
    {
        Judge( $( this ).val(), '#val3', '#val4', 'sub' );
        $( this ).prop( 'disabled', true );
    } );
    $( 'input[name="answer3"]' ).change( function ()
    {
        Judge( $( this ).val(), '#val5', '#val6', 'mul' );
        $( this ).prop( 'disabled', true );
    } );
    $( 'input[name="answer4"]' ).change( function ()
    {
        Judge( $( this ).val(), '#val7', '#val8', 'div' );
        $( this ).prop( 'disabled', true );
    } );

    // スコアリセット処理 (仕様が固まっていないので、一部コメントアウトしている)
    $( '#reset' ).click( function ()
    {
        SetCsore( 0 );
    //     $( 'input[name="answer1"]' ).prop( 'disabled', false );
    //     $( 'input[name="answer2"]' ).prop( 'disabled', false );
    //     $( 'input[name="answer3"]' ).prop( 'disabled', false );
    //     $( 'input[name="answer4"]' ).prop( 'disabled', false );
    } );
}

/*
 * テキストファイルにスコア値を書き込む

    テキストファイルが存在しない場合は自動で作成
    書き込みに成功した場合はtrueを返す
    書き込みに失敗した場合はfalseを返す

    @param number
    @return boolean
 */
function WriteScore ()
{

}

/*
 * テキストファイルからスコア値を取得する
  テキストファイルから値を取得できない場合は0を出力する
 */
function GetScore ()
{

}
/*
 * 画面上のスコアの表示を任意の数字に設定する
 */
function SetCsore (score_num)
{
    var score = parseInt( $( '#score' ).text() );
    $( '#score' ).text( score_num );

}

/*
 * 入力値から演算結果を取得する
 * @param text, text
 * @return int
 */
function GetAnswer (val1, val2, ope)
{

    var _answer;

    switch ( ope )
    {
        case 'add':
            _answer = parseInt( val1.text() ) + parseInt( val2.text() ); break;
        case 'sub':
            _answer = parseInt( val1.text() ) - parseInt( val2.text() ); break;
        case 'mul':
            _answer = parseInt( val1.text() ) * parseInt( val2.text() ); break;
        case 'div':
            if ( parseInt( val2.text() ) == 0 )
            {
                _answer = false;
            } else
            {
                _answer = parseInt( val1.text() ) / parseInt( val2.text() ); break;
            }
    }  // 取得した値はテキストなので、intに変換して演算

    return _answer;
}

function Judge (answer, target_id1='#val1', target_id2='#val2', ope='add')
{
    val1 = $( target_id1 );
    val2 = $( target_id2 );
    if ( answer == GetAnswer(val1, val2, ope) )
    {
        // スコアを加算
        var score = parseInt( $( '#score' ).text() );
        if ( isNaN(score) )
        {
            score = 0;
        }
        $( '#score' ).text(++score);
        // 正解を表示
        $( '#judge' ).html( '〇' );

    } else
    {
        // 不正解を表示
        $( '#judge' ).html( '×' );
    }

}

function getValue ()
{
    //  return [$('#val1').]
 }

 /*
  * 参考：

  // DOM読み込み
 // $(function() {
 //    Main();     // メイン処理
 // });

 // 全体読み込み (画像まで読み込んでから実行)
 // $(window).on('load', function() {
     //    Main();     // メイン処理
     // });

 // JQueryを使わない場合のDOM読み込み
 onload = function() {
 //    Main();     // メイン処理
 }
  */
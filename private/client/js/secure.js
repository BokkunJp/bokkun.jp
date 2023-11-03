// DOM読み込み
$(window).on('load', function () {
        main(); // JQueryによるメイン処理
    }
);

/* JQueryによる処理の流れ
 *  引数：
 *  戻り値：
 */
function main()
{
    $('.id').prop('disabled', true);
    $('.pass').prop('disabled', true);
    $('.send').prop('disabled', true);
}

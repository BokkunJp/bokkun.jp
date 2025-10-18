<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
$loanSession = new \Public\Important\Session('loan');

$loanSession->view('test');
$loanSession->write('test', 1);
$loanSession->onlyView('test');

if (!isset($loan)) {
    $loan = new Loan();
    $loan->setLoanData([
        'loan' => 100,
        'debt' => 200,
        'interest' => 18,
    ]);
}
?>
<form action='./' method='POST'>
    <p>
        借入：
        <input type='number' name='loan' /> 円
    </p>
    <p>
        利息 <span name='interest'><?php debug($loan); ?></span>%
    </p>
    <p>
        残高
        <output name='debt'></output>円
    </p>
    <p><button>計算する</button></p>
</form>
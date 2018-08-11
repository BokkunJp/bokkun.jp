<!-- デザイン用ファイル (PHPで処理を記述)-->
<p><input type='radio' id='auto' name='select' />自動入力 <input type='radio' id='manual' name='select' />手動入力</p>
<p>
  <form method='POST' action='./Time/' name='FormAuto'>
    明日 <input type='number' name='hourAdd' min=0 max=23 />時から開始 <br/>
    <input type='date' name='dateSetDate' /> <input type='time' name='timeSetTime' />
    <button type='submit' value=1 name='registA'>登録する</button>
    <input type='hidden' /> まで
    <button type='submit' value=2 name='updateA'>更新する</button>
  </form>
</p>

<p>
  <form method='POST' action='./Time/' name='FormManual'>
    明日<input type='number' min=0 max=23 name='hourAdd2' />時から開始 <br/>
    <input type='text' name='dateSetYear' /> 年 <input type='text' name='dateSetMonth' /> 月 <input type='text' name='dateSetDay' /> 日 <br/>
     <input type='text' name='timeSetHour' /> 時 <input type='text' name='timeSetMinute' /> 分 まで
     <input type='hidden' />
    <button type='submit' value=1 name='registB'>登録する</button>
    <button type='submit' value=2 name='updateB'>更新する</button>
  </form>
</p>

<?php
$time = new Date();
if (!$time->err_flg) {
  $time->DatePeriodLoop();
  $time->ForLoopwithHour();
  $time->ForLoopwithnotHour();
}

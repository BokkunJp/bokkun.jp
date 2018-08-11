<?php
require_once 'Time.php';
class Date {
  private $hour, $start, $end, $setDate, $interval, $period;
  public $err_flg;
  function __construct() {
    $post = PublicSetting\GetPost();
    $this->err_flg = false;
    $this->setDate = '';

    if (empty($post)) {
      $this->err_flg = true;
    }

    foreach ($post as $_key => $_post) {
      if (strpos($_key, 'dateSetDate') !== false) {
        $valid = Time\DateValid($_post);
        if ($valid === false) { // おかしい値が入力されたら
          $err_flg = true;   // err_flgを強制的にonにする
        }
      }
      if (strpos($_key, 'hourAdd') === false &&strpos($_key, 'regist') === false && strpos($_key, 'update') === false) {
        $this->setDate .= $_post;
        $this->setDate .= ' ';
      }
      if (empty($_post)) {
        $this->err_flg = true;
      }
    }

    // 未記入があったらその場で終了
    if ($this->err_flg) {
      return false;
    }
    //// 後処理 ////
    $this->setDate = rtrim($this->setDate);   // 最後の空白は除く
    $this->setDate .= ':1.000000';      // 秒数は1秒を指定
    $this->setDate = new DateTime($this->setDate);  //DateTime型に変換
    ///// datePeriod, dateInteval //////
    $this->hour = $post['hourAdd'];
    if ($this->hour >= 25) {
      $this->err_flg = false;
      return false;
    }

  }
  ///// やりたいこと //////
  public function DatePeriodLoop() {
    echo 'やりたいこと datePeriod設定後の調整版<br/>';
    $this->start = new \DateTime();       // 開始日時を設定
    // 現在日時から1日後の0時に調整
    $this->start->modify('noon +1day');
    $this->start->sub(new \DateInterval('PT12H'));
    $this->start->add(new \DateInterval("PT". $this->hour. "H")); // 開始時間を追加

    $this->end = $this->setDate;                                // 終了日を入力日時に設定
    $this->interval = new \DateInterval('P1D');            // 期間の間隔(1日毎)
    $this->period = new DatePeriod($this->start, $this->interval, $this->end);  // 期間設定
    // date_add($period->start->date);

    if ($this->start > $this->end) {
      echo '開始日が終了日よりも短いです。';
    }
    foreach ($this->period as $key => $date) {
      if ($date != $this->period->start) {
        $date->sub(new \DateInterval("PT". $this->hour. "H"));
      }
      echo $date->format('Y-m-d H:i:s')."<br/>";
    }

    echo '<br/>';
  }

  public function ForLoopwithHour() {
    ///// やりたいこと forループ版 //////
    $this->start = new \DateTime();       // 開始日時を現在日時に設定
    // 現在日時から1日後の0時に調整
    $this->start->modify('noon +1day');
    $this->start->sub(new \DateInterval('PT12H'));
    // $start = new DateTime("2016-11-02 $hour:00:00"); // 最初から開始時間を変数に持たせる

    $this->end = $this->setDate;                                // 終了日を入力日時に設定

    $this->interval = new DateInterval('P1D');            // 期間の間隔(1日毎)
    $this->start->add(new DateInterval('PT'. $this->hour.'H')); // 開始時間(h)を追加
    echo 'やりたいこと forループ版<br/>';
    if ($this->start > $this->end) {
      echo '開始日が終了日よりも短いです。';
    }
    for ($currentDate = clone $this->start; $currentDate < $this->end; $currentDate->add($this->interval)){
        echo $currentDate->format('Y-m-d H:i:s')."<br/>";
        if ($currentDate == $this->start) {   // 最初の時だけ、時間分だけ引く
          $currentDate->sub(new \DateInterval("PT". $this->hour. "H"));
        }
    }
    echo '<br/>';

  }
  public function ForLoopwithnotHour() {
    ///// やりたいこと forループ版 (事前に値を持たせるのではなく、ループの中で時刻を変更する) //////
    $this->start = new \DateTime();       // 開始日時を現在日時に設定
    // 現在日時から1日後の0時に調整
    $this->start->modify('noon +1day');
    $this->start->sub(new \DateInterval('PT12H'));

    $this->end = $this->setDate;                                // 終了日を入力日時に設定
    $this->interval = new DateInterval('P1D');            // 期間の間隔(1日毎)

    echo 'やりたいこと forループ版 (事前に値を持たせるのではなく、ループの中で時刻を変更する)<br/>';
    if ($this->start > $this->end) {
      echo '開始日が終了日よりも短いです。';
    }
    for ($currentDate = clone $this->start; $currentDate < $this->end; $currentDate->add($this->interval)){
        $cloneCurrentDate = clone $currentDate;  // 現在時刻をクローンする
        if ($currentDate == $this->start) {   // 最初の時には開始時刻を追加
          $currentDate->add(new \DateInterval("PT". $this->hour. "H"));
        }
        echo $currentDate->format('Y-m-d H:i:s').'<br/>';


        if ($cloneCurrentDate == $this->start) {   // 処理が終わったら元に戻す
          $currentDate->sub(new \DateInterval("PT". $this->hour. "H"));
        }
    }

    echo '<br/>';
  }
}

/*
	時刻の取得・更新を行う。
	時刻を用いた応用的な処理に関しては別ファイルにクラス定義する。
 */

// 時計を表示
/*
			引数：DOMの要素
         */
$(window).on('load', function() {
    var time = new Time();
    time.Update();

});
Time.prototype.Draw = function(elm) {
    elm[0]['date'].html(elm[1].time.year);
    elm[0]['time'].html(elm[1].time.nowTime);

    var time = this;
    var anime = new realTime();
    var data = this.data;
    // アニメーション処理実行時にデータを失ってしまわないよう、
    // コールバック関数を二重呼出し
    anime.viewAnimation(function() {
        time.Update();
    });
}

// 時刻の取得・更新
Time.prototype.Update = function() {
    this.time = new Time();
    var elm = { 'date': $('.date'), 'time': $('.time') };
    this.Draw([elm, this]);
}

// 年月日を表示用に変換
function Year(date) {
    year = date.getFullYear() + '/' +
        ("0" + (date.getMonth() + 1)).slice(-2) + '/' +
        ("0" + date.getDate()).slice(-2);

    return year;
}

// 時刻を表示用に成型する
function Time(data) {
    this.data = data; // ファイルデータを記録
    this.sourceTime = new Date();

    // 年月日を設定
    this.year = Year(this.sourceTime);

    // 時刻を設定
    this.hour = ("0" + this.sourceTime.getHours()).slice(-2);
    this.minute = ("0" + this.sourceTime.getMinutes()).slice(-2);
    this.second = ("0" + this.sourceTime.getSeconds()).slice(-2);


    // 不要になったオブジェクトの削除
    this.sourceTime = null;
    delete this.sourceTime;

    this.nowTime =
        this.hour + ':' +
        this.minute + ':' +
        this.second;
}
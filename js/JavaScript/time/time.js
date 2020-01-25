/*
	時刻の取得・更新を行う。
	時刻を用いた応用的な処理に関しては別ファイルにクラス定義する。
 */

// 時計を表示
/*
			引数：DOMの要素
 		*/
Time.prototype.Draw = function(elm) {
    elm[0].innerHTML = elm[1].time.nowTime + '<br/>';

    var anime = new realTime();
    var data = this.data;
    // アニメーション処理実行時にデータを失ってしまわないよう、
    // コールバック関数を二重呼出し
    anime.viewAnimation(function() {
        Main(data);
    });
}

// 時刻の取得・更新
Time.prototype.Update = function() {
    this.time = new Time();
    var elm = document.getElementsByTagName('time')[0];
    this.Draw([elm, this]);
}

// 年月日を表示用に変換
function Year(date) {
    year = date.getFullYear() + '/' +
        (date.getMonth() + 1) + '/' +
        date.getDate() + ' ';

    return year;
}

// 時刻を表示用に成型する
function Time(data) {
    this.data = data; // ファイルデータを記録
    this.sourceTime = new Date();

    // 年月日を設定
    this.year = Year(this.sourceTime);

    // 時刻を設定
    this.hour = this.sourceTime.getHours();
    this.minute = this.sourceTime.getMinutes();
    this.second = this.sourceTime.getSeconds();

    // 不要になったオブジェクトの削除
    this.sourceTime = null;
    delete this.sourceTime;

    this.nowTime =
        this.year +
        this.hour + '：' +
        this.minute + '：' +
        this.second;
}
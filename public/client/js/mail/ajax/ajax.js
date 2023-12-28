/*
	ajaxを用いて、ローカルファイルにアクセスする
*/
function ajax(TYPE = null, URL = null, DATA = null, CallBack = Loop) {

    this.load(TYPE, URL, DATA); // XHR起動
    var xhr = this; // Ajax処理の引継ぎ
    this.xhr.onload = function() { // ロード完了

        var data = xhr.getData(this); // テキストファイル内のデータを取得
        xhr.ShowHTML(document.getElementsByName("xhr")[0], data); // データの出力

        console.log(CallBack);

        CallBack(data); // 指定した処理へ遷移
    };
}

// XHRの初期処理
Ajax.prototype.Load = function(TYPE, URL, DATA = null) {
    // Ajaxが対応していないタイプは除外
    if (!(TYPE === 'GET' || TYPE === 'POST' ||
          TYPE === 'PUT' || TYPE === 'DELETE')
    ) {
        throw new Error('サポートされていないタイプです。');
    } else {
        console.log('TYPE: ' + TYPE);
        console.log('URL: ' + URL);
        console.log('DATA: ' + DATA);
    }

    // POSTタイプ、かつデータ存在する場合
    if (TYPE === 'POST' && DATA !== null) {
        // 		this.data = {data: DATA};			// データ用のオブジェクトの生成
        this.data = [DATA]; // データの成型

        // 元データの削除
        DATA = null;
        delete DATA;
    } else {
        this.data = DATA; // データの挿入(NULL)
    }

    this.xhr = new XMLHttpRequest();
    this.xhr.open(TYPE, URL);
    this.xhr.setRequestHeader('Content-Type',
        'application/x-www-form-urlencoded;charset=UTF-8');
    console.log(this.data);
    this.xhr.send(encodeURIComponent(this.data));
};

/* レスポンスデータ(テキスト)を返す */
Ajax.prototype.getData = function(xhr) {
    return xhr.responseText;
};

/* レスポンスデータ(PHPから取得したカンマ区切りのキー名)を配列で返す */
Ajax.prototype.GetArrayData = function(xhr) {
    this.response = this.getData(xhr).split(',');
    // 配列でなければ
    if (typeof xhr == Array) {
        return this.getData(xhr); // 通常のレスポンスデータを返す
    } else {
        // 配列なら
        return this.response; // 成形したデータを返す
    }
}

/* XHRデータのHTML出力 */
Ajax.prototype.ShowHTML = function(elm, data) {
    elm.innerHTML = data;
}
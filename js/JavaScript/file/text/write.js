/*
	引数で指定した名前のテキストファイルを読み込む
*/
function Text(filename) {
	var blob = new Blob(["UTF-8"], {type:"text/plain"});
	this.file = new FileReader();
	console.log(this.file.readAsText);
	this.Common();eixt;
	// ファイル操作するかどうか判定

	// ファイル操作しない場合はクローズ
}

Text.prototype.Common = function() {
	// テキストファイル指定(それ伊賀のファイルではエラー投下)
	// ファイル読み込み処理
	console.log(this.file);
	this.Judge();
}

Text.prototype.Judge = function() {
	this.common++;
};

Text.prototype.Load = function(fileName) {
}

Text.prototype.Write = function(fileName) {
}

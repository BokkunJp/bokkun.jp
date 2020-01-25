/*
	引数で指定した名前のテキストファイルを読み込む
*/
function Text(filename) {
	var blob = new Blob(["UTF-8"], {type:"text/plain;charset=UTF-8"});
	this.file = new FileReader();
	console.log(this.file.readAsDataURL);
	this.file = this.file.readAsDataURL(blob);
	this.Common();
	// ファイル操作するかどうか判定

	// ファイル操作しない場合はクローズ
}

Text.prototype.Common = function() {
	// テキストファイル指定(それ伊賀のファイルではエラー投下)
	// ファイル読み込み処理
	this.Judge();
}

Text.prototype.Judge = function() {
	this.common++;
}

Text.prototype.Load = function(fileName) {
}

Text.prototype.Read = function(fileName) {
}

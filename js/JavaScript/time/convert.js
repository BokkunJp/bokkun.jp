/* 
	指定した日付から、必要な要素を取り出す。
	取り出した値の表示用関数もこちらのクラス群で用意する。
 */


Time.prototype.Alert = function() {
	if (this.hour == 0 && this.minute == 0 && this.second == 0) {
		this.data = 0;						// 日が変わったらカウントをリセットする
	    var post = new Ajax("POST", "js/JavaScript/ajax/send.php", this.data);
		// Write();							// ファイルにデータを書き出す(PHP)
		console.log(post);
		if (post.getData()) {

		}
	}

}
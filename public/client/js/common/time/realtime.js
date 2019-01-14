/*
	リアルタイムに動作させるための処理 v1.0
*/
function realTime(op, method) {
    if (typeof op === 'undefined') {
        op = 'time';
    }

    if (typeof method === 'undefined') {
        method = 'requestAnimationFrame';
    }
    
    this.setAnimation(method);
}

/*
	時刻の取得、連続描画
	引数:
		op: どのアニメーションメソッドを用いるかを決定する。
		
		method: アニメーションを行う対象のメソッドを決定する。
*/
realTime.prototype.viewAnimation = function(method, cancel) {
    this.anime = this.startAnimation(method);
    if (cancel) {
        this.stopAnimation(this.anime);
    }
};


// アニメーション選択
realTime.prototype.setAnimation = function(method) {
    if (typeof method === 'undefined') {
        method = 'requestAnimationFrame';
    }

    this.name = method;

    switch (this.name) {
        case 'requestAnimationFrame':
            this.startAnimation = function(val, t) { return requestAnimationFrame(val); };
            this.stopAnimation = function(val) { cancelAnimationFrame(val); };
            break;
        case 'setTimeout':
            this.startAnimation = function(val, t) { return setTimeout(val, t) };
            this.stopAnimation = function(val) { clearTimeout(val); };
            break;
        case 'setInterval':
            this.startAnimation = function(val, t) { return setInterval(val, t) };
            this.stopAnimation = function(val) { clearInterval(val); };
            break;
        default:
            break;
    }
};
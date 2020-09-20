function Shader(id) {
    this.create(id);
}
/* シェーダの作成 */
Shader.prototype.create = function (id) {

    var scriptElm = document.getElementById(id);

    // エラー処理
    if (!scriptElm) {
        console.error("スクリプトが見つかりません。");
        return;
    }

    // scriptタグのタイプチェック
    switch (scriptElm.type) {
        // 頂点シェーダ
        case 'x-shader/x-vertex':
            this.shader = context.createShader(context.VERTEX_SHADER);
            break;

        case 'x-shader/x-fragment':
            this.shader = context.createShader(context.FRAGMENT_SHADER);
            break;
        default:
            console.error('Not shader.');
            return;
    }
        
    context.shaderSource(this.shader, scriptElm.text);	// 生成されたシェーダへのソース割り当て

    console.log(this.shader);

    context.compileShader(this.shader);					// シェーダコンパイル

    if (context.getShaderParameter(this.shader, context.COMPILE_STATUS)) {	// コンパイルチェック
        console.log('Success.');
    } else {
        console.error('コンパイルに失敗しました！');
        console.error(context.getShaderInfoLog(this.shader));		// 失敗
    }
}


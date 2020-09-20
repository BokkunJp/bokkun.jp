/* プログラムオブジェクトの生成 */
function ProgramObject(vs, fs) {
    this.create_program(vs, fs);
}
ProgramObject.prototype.create_program = function (vShader, fShader) {

    this.programObject = context.createProgram();			// プログラムオブジェクトの生成	

    context.attachShader(this.programObject, vShader);		// プログラムオブジェクトへシェーダ割り当て
    context.attachShader(this.programObject, fShader);

    context.linkProgram(this.programObject);					// シェーダリンク

    if (context.getProgramParameter(this.programObject, context.LINK_STATUS)) {
        context.useProgram(this.programObject);

    } else {
        console.error("リンクエラー: " + context.LINK_STATUS);
        alert(context.getProgramInfoLog(this.programObject));
    }
}

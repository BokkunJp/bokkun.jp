function VBO(data, context) {
    this.create(data, context);
}

VBO.prototype.create = function (data, context) {

    this.buffer = context.createBuffer();														// バッファオブジェクトの生成

    context.bindBuffer(context.ARRAY_BUFFER, this.buffer);											// バッファのバインド
    context.bufferData(context.ARRAY_BUFFER, new Float32Array(data), context.STATIC_DRAW);	// バッファにデータをセット
    context.bindBuffer(context.ARRAY_BUFFER, null);											// バッファのバインド無効化
}


VBO.prototype.make = function (attLocation, attStride, context) {

    context.bindBuffer(context.ARRAY_BUFFER, this.buffer);
    context.enableVertexAttribArray(attLocation);
    context.vertexAttribPointer(attLocation, attStride, context.FLOAT, false, 0, 0);
}

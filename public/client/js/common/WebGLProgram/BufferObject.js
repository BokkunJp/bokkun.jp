function BufferObject(context, data, type) {
    this.create(context, data);
}

BufferObject.prototype.create = function (context, data, type) {
    this.buffer = context.createBuffer();  // バッファオブジェクトの生成
    context.bindBuffer(context.data, this.buffer); // バッファのバインド
    // バッファの種類の決定
    if (type == 'VBO') {
        this.type = ARRAY_BUFFER;
        this.datatype = new Float32Array(data);
    } else if (type == 'IBO') {
        this.type = ELEMENT_ARRAY_BUFFER;
        this.datatype = new Int16Array(data);
    }
    context.bufferData(this.type, this.datatype, context.STATIC_DRAW);   // バッファにデータをセット
    context.bindBuffer(this.type, null);    // バインドの無効化
};


BufferObject.prototype.make = function (context, type)
{
    if (type == 'VBO')
        this.type = context.ARRAY_BUFFER;
    else if (type == 'IBO')
        this.type = context.ELEMENT_ARRAYBUFFER;
    context.bindBuffer(this.type, this.buffer);
    if (type == 'VBO') {
        context.enableVertexAttribArray(attLocation);
        context.vertexAttribPointer(attLocation, attStride, context.FLOAT, false, 0, 0);
    }

};


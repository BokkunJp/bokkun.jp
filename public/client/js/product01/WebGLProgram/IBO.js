function IBO(data) {
    this.create(data);
}

IBO.prototype.create = function (data) {
    this.buffer = context.createBuffer();
    context.bindBuffer(context.ELEMENT_ARRAY_BUFFER, this.buffer);
    context.bufferData(context.ELEMENT_ARRAY_BUFFER, new Int16Array(data), context.STATIC_DRAW);
    context.bindBuffer(context.ELEMENT_ARRAY_BUFFER, null);
}


IBO.prototype.make = function ()
{
    context.bindBuffer(context.ELEMENT_ARRAY_BUFFER, this.buffer);

}


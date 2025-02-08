// テクスチャ
function Texture(width, height) {
    this.buffer = context.createTexture();
    context.bindTexture(context.TEXTURE_2D, this.buffer);
    this.textureImage(width, height, null);
}

function createTexture(source) {
    const img = new Image();

    img.onload = function () {
        const tex = context.createTexture();
        context.bindTexture(context.TEXTURE_2D, tex);
        context.texImage2D(context.TEXTURE_2D, 0, context.RGBA, context.RGBA, context.UNSIGNED_BYTE, img);
        context.generateMipmap(context.TEXTURE_2D);
        context.bindTexture(context.TEXTURE_2D, null);
        texture = tex;

    };

    img.src = source;
}
const canvas, context;
$(function() {

    try {
        main();
    } catch (e) {
        //alert('プログラムにエラーが発生しました。');
        document.write('エラー内容: ' + canvas);
    }

});

function main() {
    // 入力情報(RGB)を取得
    const rgb = $('*[name=color]');

    // 枠の初期化(width, height)
    init(300, 300);

    // webGLのコンテキストの読み込み
    context = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');

    // 色や深度値の初期化
    clear();

    if (!context) {
        alert('要素が存在しません。');
    }


    // プログラムオブジェクトの作成
    const prg = new ProgramObject(new Shader('vshader').shader, new Shader('fshader').shader);


    const att = [
        [
            context.getAttribLocation(prg.programObject, 'position'),
            3 // xyz
        ],
        [
            context.getAttribLocation(prg.programObject, 'color'),
            4 // rgba
        ]
    ];

    // 頂点の座標情報を格納する配列
    // Ajaxでサーバからデータを取ってくる (3x3のデータがあることが前提)
    const vertexPosition = [
        1.0, 0.0, 0.0,
        0.5, 1.0, 1.0,
        0.0, 0.0, 1.0
    ];

    // 頂点の色情報を格納する配列
    const vertexColor = [
        parseFloat(rgb[0].value) / 256, parseFloat(rgb[1].value) / 256, parseFloat(rgb[2].value) / 256, parseFloat(rgb[3].value),
        parseFloat(rgb[0].value) / 256, parseFloat(rgb[1].value) / 256, parseFloat(rgb[2].value) / 256, parseFloat(rgb[3].value),
        parseFloat(rgb[0].value) / 256, parseFloat(rgb[1].value) / 256, parseFloat(rgb[2].value) / 256, parseFloat(rgb[3].value)
    ];

    const vbo = new VBO(vertexPosition).make(att[0][0], att[0][1]);
    const cbo = new VBO(vertexColor).make(att[1][0], att[1][1]);

    // minMatrix.js を用いた行列関連処理
    // matIVオブジェクトを生成
    const m = new matIV();

    // 各種行列の生成と初期化
    const mMatrix = m.identity(m.create());
    const vMatrix = m.identity(m.create());
    const pMatrix = m.identity(m.create());
    const mvpMatrix = m.identity(m.create());

    // ビュー座標変換行列
    m.lookAt([0.0, 1.0, 3.0], [0, 0, 0], [0, 1, 0], vMatrix);

    // プロジェクション座標変換行列
    m.perspective(90, canvas.width / canvas.height, 0.1, 100, pMatrix);

    // 各行列を掛け合わせ座標変換行列を完成させる
    m.multiply(pMatrix, vMatrix, mvpMatrix);
    m.multiply(mvpMatrix, mMatrix, mvpMatrix);

    // uniformLocationの取得
    const uniLocation = context.getUniformLocation(prg.programObject, 'mvpMatrix');

    // uniformLocationへ座標変換行列を登録
    context.uniformMatrix4fv(uniLocation, false, mvpMatrix);

    // モデルの描画
    context.drawArrays(context.TRIANGLES, 0, 3);

    // コンテキストの再描画
    context.flush();
}

// 初期設定
function init(width, height) {
    canvas = document.getElementById('canvas');
    const w = canvas.width;
    const h = canvas.height;

    // canvas要素から値を取得する場合
    // canvas要素の値が0の時の処理
    if (!w && !h) {
        alert('属性が設定されていません.');

        if (!width && !height) {
            alert('値を設定してください。');
            return -1;
        } else {
            canvas.width = width;
            canvas.height = height;
        }

    }

    // JavaScriptで値を設定する場合
    if (!width && !height) {
        alert('値を設定してください。');
        return -1;
    } else {
        canvas.width = width;
        canvas.height = height;
    }

}

function clear() {
    context.clearColor(0 / 255, 190 / 255, 255 / 255, 255 / 255);
    context.clearDepth(1.0);
    context.clear(context.COLOR_BUFFER_BIT | context.DEPTH_BUFFER_BIT);
}
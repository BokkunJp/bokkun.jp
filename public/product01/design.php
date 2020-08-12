<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php

use BasicTag\ScriptClass;

$post = PublicSetting\Setting::GetPost('csv');
if ($post) {
    $alert = new ScriptClass();

    $alert->Alert('CSVを作成します。');
    $inputFlg = true;

    Main($inputFlg);
}
?>
<div class='product-csv'>
    <form method='POST'>
        <table>
            <caption>
                <h2>CSV作成／データ追加・変更</h2>
            </caption>
            <thead>
                <tr>
                    <th>
                        ファイル名
                    </th>
                    <td>
                        <input type='text' name='file-name' />
                    </td>

                    <th>
                        列番号
                    </th>
                    <td>
                        <input type='text' name='col-number' />
                    </td>
                </tr>
                <tr>
                    <th>要素x</th>
                    <th>要素y</th>
                    <th>要素z</th>
                </tr>
            </thead>
            <tbody>
                <td>
                    <input type='text' name='x-value' />
                </td>
                <td>
                    <input type='text' name='y-value' />
                </td>
                <td>
                    <input type='text' name='z-value' />
                </td>
            </tbody>
        </table>
        <input type='hidden' name='csv' value="make" />
        <input type='hidden' name='token' value="<?= $token=MakeToken() ?>" />
        <button type='submit'>データを送信</button>
    </form>
</div>
<?php
$filePath = AddPath(PUBLIC_CSV_DIR, basename(__DIR__));
$fileArray = IncludeFiles($filePath, 'csv', true);
$base = new PublicSetting\Setting();

// 次期改修
//$downloadHtml = new CustomTagCreate();
//$downloadHtml->SetHref('test', 'download', 'csv', false, "download");
//$downloadHtml->ExecTag(true);
foreach ($fileArray as $_value) {
    $_filePath = AddPath($base->GetUrl(basename(__DIR__), 'csv'), $_value, false);
    echo "<a href=\"{$_filePath}\" download>{$_value}ダウンロード</a><br/>";
}
?>
<base href='../' />
<div class='product-webgl'>
    <script src="client/js/<?php echo basename(__DIR__); ?>/WebGLProgram/shader.js"></script>
    <script src="client/js/<?php echo basename(__DIR__); ?>/WebGLProgram/ProgramObject.js"></script>
    <script src="client/js/<?php echo basename(__DIR__); ?>/WebGLProgram/BufferObject.js"></script>
    <script src="client/js/<?php echo basename(__DIR__); ?>/WebGLProgram/VBO.js"></script>
    <script src="client/js/<?php echo basename(__DIR__); ?>/WebGLProgram/IBO.js"></script>
    <script src="client/js/<?php echo basename(__DIR__); ?>/WebGLProgram/texture.js"></script>
    <script src="client/js/<?php echo basename(__DIR__); ?>/WebGLProgram/minMatrix.js"></script>


    <script id='vshader' type='x-shader/x-vertex'>
        /* 頂点シェーダ  */
        attribute vec3 position;
        attribute vec4 color;
        uniform mat4 mvpMatrix;
        varying vec4 vColor;
        void main(void) {
        gl_Position = mvpMatrix * vec4(position, 1.0);
        vColor = color;
        }

    </script>
    <script id="fshader" type='x-shader/x-fragment'>
        /* フラグメントシェーダ  */
        precision mediump float;
        varying vec4 vColor;
        void main(void) {
        gl_FragColor = vColor;
        }
    </script>

    <canvas id="canvas" width=30 height=30>
        このブラウザは、webGLに対応していません。
    </canvas>

    <form>
        R <input type="range" name="color" min="0" max="255" value="0" />
        G <input type="range" name="color" min="0" max="255" value="0" />
        B <input type="range" name="color" min="0" max="255" value="0" />
        A <input type="range" name="color" min="0" max="1.0" step="0.01" value="1.0" />

        <button type="button" name="redraw" onclick="console.clear(); main();">再描画</button>
    </form>
    <contents>
        色付きの三角形ポリゴンを表示する。
        色はバーで選択できる。
    </contents>
</div>
<?php
SetToken($token);

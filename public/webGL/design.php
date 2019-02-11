<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

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

</head>

<body>
    <div class="continar">
        <div class="contents">

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
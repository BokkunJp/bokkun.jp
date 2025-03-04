// DOM読み込み
onload = function ()
{
    main();     // メイン処理
}

/* JQueryによる処理の流れ
 *  引数：
 *  戻り値：
 */
function main()
{

    const vue = new VueClass();
    vue.setData('#test', 'Default');
    vue.Exec();


    // const vue = new Vue(
    // {
    //     el: '#app-1',
    //     data: { message: 'Test' }
    // });
}

class VueClass
{
    constructor() {
        this.makeData();
    }
    makeData()
    {

        this.el = null;
        this.data = null;
    }
    setData(el, data)
    {
        this.SetElement(el);
        this.SetMessage(data);

    }

    SetObject()
    {
        // 各プロパティをオブジェクトにまとめる (vueで実行できる形式に)
        this.obj = new Object();
        this.obj.el = this.el;
        this.obj.watch = this.watch;
        this.obj.data = this.data;
    }

    SetElement(el)
    {
        this.el = el;
    }

    SetMessage(message)
    {
        this.data = { message: message };
    }

    SetError()
    {
        return;
    }

    Exec()
    {
        if (!this.obj) {
            this.SetObject();
        }
        if (document.domain == 'bokkun.jp.project') {
            Vue.config.devtools = true;
        } else {
            console.log('real server');
        }
        return new Vue(this.obj);
    }
};



/*
 * 参考：

 // DOM読み込み
// $(function() {
//    main();     // メイン処理
// });

// 全体読み込み (画像まで読み込んでから実行)
// $(window).on('load', function() {
    // });
    //    main();     // メイン処理

// JQueryを使わない場合のDOM読み込み
onload = function() {
//    main();     // メイン処理
}
 */
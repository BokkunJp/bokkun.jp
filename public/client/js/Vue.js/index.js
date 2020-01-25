 // DOM読み込み
onload = function() {
    Main();     // メイン処理
}

 /* JQueryによる処理の流れ
  *  引数：
  *  戻り値：
  */
function Main() {

    var vue = new VueClass();
    vue.SetData('#test', 'Message');
    vue.Exec();


    // var vue = new Vue(
    // {
    //     el: '#app-1',
    //     data: { message: 'Test' }
    // });
}

class VueClass
{
    constructor() {
        this.MakeData();
    }
    MakeData(){
        this.el = null;
        this.data = null;
    }
    SetData(el, data) {
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

    SetElement(el) {
        this.el = el;
    }

    SetMessage(message) {
        this.data = {message:message};
    }

    SetError ()
    {
        return;
    }

    Exec ()
    {
        if ( !this.obj )
        {
            this.SetObject();
        }
        return new Vue(this.obj);
    }
};



 /*
  * 参考：

  // DOM読み込み
 // $(function() {
 //    Main();     // メイン処理
 // });

 // 全体読み込み (画像まで読み込んでから実行)
 // $(window).on('load', function() {
     // });
     //    Main();     // メイン処理

 // JQueryを使わない場合のDOM読み込み
 onload = function() {
 //    Main();     // メイン処理
 }
  */
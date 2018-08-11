<!--
    ヘッダー・フッターを用いて、オリジナルのレイアウトを作成する
-->
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8" />
        <base href="http://zend.netbeans.project/" />
        <title>レイアウトテスト</title>
    </head>
    <body>

        <div>
            画像テスト：画像が表示されていれば成功です。<br/>
            <img src="/img/layout/img055.jpg">
        </div>
        
        <div>
            JSテスト:<script src="/js/test/test.js"></script>
            <div id="test"></div>
        </div>


        <div style="border:1px solid red; padding:5px;">
            この下がアクションによって切り替わります。
        </div>
                
        <?php echo $this->content; ?>
        
        <div style="border:1px solid red; padding:5px;">
            この上がアクションによって切り替わります。
        </div>
    </body>
</html>
// 全体読み込み (画像まで読み込んでから実行)
$(window).on('load', function() {
    var datepicker = $('.datepicker').children('.datepicker_button');

    console.log(datepicker);

    datepicker.on('click', function() {
        console.log(datepicker);
        $('.warning').toggle(1);

    });

    datepicker.on('dblclick', function() {
        console.clear();
    })

    $('.childAttr').on('click', function() {
        console.log(devicePixelRatio);
        console.log(navigator.userAgent);
        $('.none').attr('value');
    })

});
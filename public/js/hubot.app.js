/**
 * Created by t-mizuma on 2017/12/03.
 */

var customerMailFilterUrlMap = {
    1: '/',
    2: '/notice/mail/mailsend',
    3: '/notice/mail/mailnosend',
    4: '/notice/mail/check',
    5: '/notice/mail/nocheck'
};

function loading() {
    $('#page_loading').removeClass("hide");
    $('#top_container').addClass("display_blinder");
}

function loadingEnd() {
    $('#page_loading').addClass("hide");
    $('#top_container').removeClass("display_blinder");
}

function getPdf() {
    var currentUrl = location.href;
    if (currentUrl.slice(-1) === '/') {
        currentUrl = currentUrl.slice(0,-1);
    }
    location.href = currentUrl + '/pdf';
}

// 全ての画面が読み込まれたらロードを解除
jQuery.event.add(window, "load", function(){
    loadingEnd();
    setTimeout(function(){
        $('.alert').hide('slow');
    }, 2000);
});


// DOM監視
$(function($) {
    // 一覧のフィルタ
    $('#customer_mail_filter').change(function() {
        $val = $('[name=customer_mail_filter]').val();
        if ($val == 0) {
            return;
        }
        loading();
        location.href = customerMailFilterUrlMap[$val];
    });
    // メール送信ボタンの押下
    $('#send_mail_btn').click(function() {
        $('#send_mail_btn').addClass('disabled');
        loading();
    });
    // 一覧取得ボタンの押下
    $('#pdf_output').click(function() {
        $checkedObject = $('input[type="checkbox"]:checked');
        if ($checkedObject.length == 0) {
            getPdf();
            return;
        }
        if (confirm('メール送信のチェックが残っています。PDFを出力しますか？')) {
            getPdf();
            return;
        }
    });
    // 全てにチェック
    $("#all_check").change(function() {
        var isCheck = $("#all_check:checked").val() == 'on';
        $('input[type="checkbox"]').prop("checked",isCheck);
    });
    // 完了ボタンの押下
    $("#close_window").click(function() {
        $checkedObject = $('input[type="checkbox"]:checked');
        if ($checkedObject.length == 0) {
            window.open('about:blank','_self').close();
            return;
        }
        if (confirm('メール送信のチェックが残っています。ウィンドウを閉じますか？')) {
            window.open('about:blank','_self').close();
            return;
        }
    });
    // 画面下部へスクロール
    $("#scroll_bottom").click(function() {
        $("html,body").animate({scrollTop:$('#page_bottom').offset().top},'slow');
    });
    // 画面上部へスクロール
    $("#scroll_top").click(function() {
        $("html,body").animate({scrollTop:$('#page_top').offset().top},'slow');
    });
});


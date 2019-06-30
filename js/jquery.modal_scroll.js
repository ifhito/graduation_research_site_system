/* =========================================================*/
// jquery.modal_scroll.js / ver.1.0

// Copyright (c) 2018 CoolWebWindow
// This code is released under the MIT License
// https://osdn.jp/projects/opensource/wiki/licenses%2FMIT_license

// Date: 2018-01-07
// Author: CoolWebWindow
// Mail: info@coolwebwindow.com
// Web: http://www.coolwebwindow.com

// Used jquery.js
// http://jquery.com/
/* =========================================================*/

$(function(){
	// スクロールバーの横幅を取得
	var scroll = window.innerWidth - $(window).outerWidth(true);
	if(scroll > 0 ){
		var scrollsize = scroll;
	} else {
		$('html').append('<div class="scrollbar" style="overflow:scroll;height:50px"}></div>');
		var scrollsize = window.innerWidth - $('.scrollbar').prop('clientWidth');
		$('.scrollbar').hide();
	}

	// 「.modal-open」をクリック
	$('.modal-open').click(function(){
		// html、bodyを固定（overflow:hiddenにする）
		//本文を表示した時間を取得する
		var start=new Date();
		//btnにボタンの文を格納
		var btn = $(this).val();

		$('html, body').addClass('lock');

		// オーバーレイ用の要素を追加
		$('body').append('<div class="modal-overlay"></div>');

		// オーバーレイをフェードイン
		$('.modal-overlay').fadeIn('slow');

		// モーダルコンテンツのIDを取得
		var modal = '#' + $(this).attr('data-target');

		 // モーダルコンテンツを囲む要素を追加
		$(modal).wrap("<div class='modal-wrap'></div>");

		// モーダルコンテンツを囲む要素を表示
		$('.modal-wrap').show();

		// モーダルコンテンツの表示位置を設定
		modalResize();

		 // モーダルコンテンツフェードイン
		$(modal).fadeIn('slow');

		// モーダルコンテンツをクリックした時はフェードアウトしない
		$(modal).click(function(e){
			e.stopPropagation();
		});

		// 「.modal-overlay」あるいは「.modal-close」をクリック
		$('.modal-close').off().click(function(){

			//ここで本文の読み終わりの時間を取得する。
			var stop=new Date();

			

			// モーダルコンテンツとオーバーレイをフェードアウト
			$(modal).fadeOut('slow');
			$('.modal-overlay').fadeOut('slow',function(){
				// html、bodyの固定解除
				$('html, body').removeClass('lock');
				// オーバーレイを削除
				$('.modal-overlay').remove();
				// モーダルコンテンツを囲む要素を削除
				$(modal).unwrap();
		   });

		   //終わり時間ー始まり時間で読んでいた時間
		   var ms =stop.getTime() - start.getTime();
		   //秒にする
		   var second=ms/1000;
		   //alert(second+btn);

		   //ajaxのpost(secondとtitleをpostする)
		   $.ajax({
			url:'./abst.php',
			type:'POST',
			data:{
				'second': second,
				'title':btn
			}
		})
		// Ajaxリクエストが成功した時発動
		.done( (data) => {
			//$('.result').html(data);
			//console.log(data);
			//alert(btn)
		})
		// Ajaxリクエストが失敗した時発動
		.fail( (data) => {
			//$('.result').html(data);
			//console.log(data);
			//alert("失敗")
		})
		// Ajaxリクエストが成功・失敗どちらでも発動
		.always( (data) => {

		});
		});

		// リサイズしたら表示位置を再取得
		$(window).on('resize', function(){
			modalResize();
		});

		// モーダルコンテンツの表示位置を設定する関数
		function modalResize(){
			// ウィンドウの横幅、高さを取得
			var w = $(window).width();
			var h = $(window).height();

			// モーダルコンテンツの横幅、高さを取得
			var mw = $(modal).outerWidth(true);
			var mh = $(modal).outerHeight(true);

			// モーダルコンテンツの表示位置を設定
			if ((mh > h) && (mw > w)) {
				$(modal).css({'left': 0 + 'px','top': 0 + 'px'});
			} else if ((mh > h) && (mw < w)) {
				var x = (w - scrollsize - mw) / 2;
				$(modal).css({'left': x + 'px','top': 0 + 'px'});
			} else if ((mh < h) && (mw > w)) {
				var y = (h - scrollsize - mh) / 2;
				$(modal).css({'left': 0 + 'px','top': y + 'px'});
			} else {
				var x = (w - mw) / 2;
				var y = (h - mh) / 2;
				$(modal).css({'left': x + 'px','top': y + 'px'});
			}
		}

	});
});


jQuery(function(){
    setInterval(countDown,1000);
});


function countDown() {
    var s = parseInt($("#s").text());
    var m = parseInt($("#m").text());

    
    if(s<=0&&m<=0){
        location.href = 'check_abst.php';
    }else{
        s--;
    if(s < 0) {
       s = 59;
       m--; 
    }
    
    if(s < 10) {
        $("#s").text("0" + s);
    }
    else {
        $("#s").text(s);
    }
    if(m < 10) {
        $("#m").text("0" + m);
    }
    else {
        $("#m").text(m);
    }

    if(m<=0 && s<=30){
        $(".side").css("display", "block");
    }
    }

    
}



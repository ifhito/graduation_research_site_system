$(function() {
 
    // チェックボックスをチェックしたら発動
    $('input[name="abst[]"]').change(function() {
      //idをチェックしたボックスのidに
      var id = $(this).attr('id');
      //alert(id)
      //$('label[for="' + id + '"]').after('<label for="fname">Abstract</label>');

      //チェックされたら
      if($(this).prop("checked")==true){
        //ラベルの下にテキストエリアの追加
        $('label[for="' + id + '"]').after('<div class="'+id+'"><br><p>20~100文字で入力してください　　<span class="'+id+'">0</span>文字</p><textarea class="abst_user"id="'+id+'" name="abst_user[]" maxlength="100"">ここに要約を入力してください</textarea></div>');
      }else{
        //チェックが外れたらremoveする
        $('div.'+id).remove();
      }
   
      // ①チェックが入ったチェックボックスの個数を変数に格納
      var len = $('input[name="abst[]"]:checked').length;
   
      // ②チェックが3つ以上入ったら
      if (len >= 3) {
   
        // ③disabledを付けてチェックできなくする
        $('input[name="abst[]"]').not(':checked').attr('disabled', 'disabled');
   
      // チェックが2つ未満だったら
      } else {
   
        // ④disabledを削除してチェックできるようになる
        $('input[name="abst[]"]').not(':checked').removeAttr('disabled');
      }
   
    });
    //textareaの文字数表示(keyが押された時、戻った時、フォーカスされた時、フォーカスされない時にデータ取得)
    $(document).on("keyup keypress blur focus keydown", "textarea", function(){
      //入力しているtextareaのid
      var id=$(this).attr('id');
      //長さのカウント
      var count = $(this).val().length;
      //spanに表示
      $('span.'+id).text(count);
    //alert(count);
    });

    

  });


  function check(){
    var type=[];
 
      // inputTextに.abst_userのvalueを入れる
      var inputText = $(".abst_user").map(function (index, el) {
        return $(this).val();
      });


      var showtext = [];
      //上記のテキストの長さで回してshowtextに入れていく(おそらく正規化？)
        for (i = 0; i < inputText.length; i++) {
          showtext[i]= inputText[i];
        }
        //$("#output").html(showtext);
      
      //長さが30以上ならPOSTできるようにする
      var checkFunc = function ( value, index, sourceArray ) {
        if( value.length >= 20 ) {
          return true ;
      
        } else {
          return false ;
      
        }
      }

    
    return showtext.every(checkFunc)
  
  }

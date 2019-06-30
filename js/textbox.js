$(function () {
  $("textarea").keyup(function(){
    var txtcount = $(this).val().length;
    $("#txtlmt").text(txtcount);
    if(txtcount == 0){
      $("#txtlmt").text("0");
    } 
    if(txtcount >= 7){
      $("#txtlmt").css("color","#d577ab");
    } else {
      $("#txtlmt").css("color","#333");
    }
  });
});


jQuery(function(){
    setInterval(countDown,1000);
});


function countDown() {
    var s = parseInt($("#s").text());
    var m = parseInt($("#m").text());

    
    if(s<=0&&m<=0){
        location.href = 'php_test2.php';
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

    if(m<=0 && s<=25){
        $(".side").css("display", "block");
    }
    }

    
}
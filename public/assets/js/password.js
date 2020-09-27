$("input[type=password]").keyup(function(){
    let ucase = new RegExp("[A-Z]+");
    let lcase = new RegExp("[a-z]+");
    let num = new RegExp("[0-9]+");
    var spec = new RegExp("[-.*&^%$#@()/_]+");

    if($("#password1").val().length >= 8){
        $("#8char").removeClass("far fa-times-circle");
        $("#8char").addClass("far fa-check-circle");
        $("#8char").css("color","#00A41E");
    }else{
        $("#8char").removeClass("far fa-check-circle");
        $("#8char").addClass("far fa-times-circle");
        $("#8char").css("color","#FF0004");
    }

    if(ucase.test($("#password1").val())){
        $("#ucase").removeClass("far fa-times-circle");
        $("#ucase").addClass("far fa-check-circle");
        $("#ucase").css("color","#00A41E");
    }else{
        $("#ucase").removeClass("far fa-check-circle");
        $("#ucase").addClass("far fa-times-circle");
        $("#ucase").css("color","#FF0004");
    }

    if(lcase.test($("#password1").val())){
        $("#lcase").removeClass("far fa-times-circle");
        $("#lcase").addClass("far fa-check-circle");
        $("#lcase").css("color","#00A41E");
    }else{
        $("#lcase").removeClass("far fa-check-circle");
        $("#lcase").addClass("far fa-times-circle");
        $("#lcase").css("color","#FF0004");
    }

    if(num.test($("#password1").val())){
        $("#num").removeClass("far fa-times-circle");
        $("#num").addClass("far fa-check-circle");
        $("#num").css("color","#00A41E");
    }else{
        $("#num").removeClass("far fa-check-circle");
        $("#num").addClass("far fa-times-circle");
        $("#num").css("color","#FF0004");
    }

    if(spec.test($("#password1").val())){
        $("#spec").removeClass("far fa-times-circle");
        $("#spec").addClass("far fa-check-circle");
        $("#spec").css("color","#00A41E");
    }else{
        $("#spec").removeClass("far fa-check-circle");
        $("#spec").addClass("far fa-times-circle");
        $("#spec").css("color","#FF0004");
    }

    if($("#password1").val() == $("#password2").val()){
        $("#pwmatch").removeClass("far fa-times-circle");
        $("#pwmatch").addClass("far fa-check-circle");
        $("#pwmatch").css("color","#00A41E");
    }else{
        $("#pwmatch").removeClass("far fa-check-circle");
        $("#pwmatch").addClass("far fa-times-circle");
        $("#pwmatch").css("color","#FF0004");
    }
});

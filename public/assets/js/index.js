setInterval(function(){

    var obj = document.getElementById("bloc-10");
    obj.innerHTML = "";

},3000);
$('#monBouton').click(function() {
    var obj = document.getElementById("bloc-10");
    obj.innerHTML = "<p>{{session.message}} </p>";
    var url = '../../Controller/SecurityController.php';
    $.post(url, function(data){

    });
});

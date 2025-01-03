document.addEventListener("DOMContentLoaded", function() {
    var yilButton = document.getElementById("yil");
    var ayButton = document.getElementById("ay");
    var purchaseContentM = document.querySelector(".purchaseM").innerHTML;
    var purchaseContentY = document.querySelector(".purchaseY").innerHTML;
    var yillikContent = document.querySelector(".yillik").innerHTML;
    var aylikContent = document.querySelector(".aylik").innerHTML;


    yilButton.addEventListener("click", function() {
        document.querySelector(".yillik").innerHTML = purchaseContentY;
    });

    ayButton.addEventListener("click", function() {
        document.querySelector(".aylik").innerHTML = purchaseContentM;
    });


});



var tarihInput = document.getElementById('date');
    
// Bugünün tarihini al
var bugun = new Date();

// Tarih alanına min özelliği ekleyerek bugünün tarihinden öncesini seçimi engelle
tarihInput.setAttribute('min', bugun.toISOString().slice(0, 7));




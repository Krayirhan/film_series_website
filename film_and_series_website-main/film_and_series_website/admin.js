document.getElementById('add-production-form').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(event.target);

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            alert('Yapım başarıyla eklendi!');
            event.target.reset();
        }
    };
    xhttp.open("POST", "addProduction.php", true);
    xhttp.send(formData);
});

document.getElementById('delete-production-form').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(event.target);

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            alert('Yapım başarıyla silindi!');
            event.target.reset();
        }
    };
    xhttp.open("POST", "delProduction.php", true);
    xhttp.send(formData);
});
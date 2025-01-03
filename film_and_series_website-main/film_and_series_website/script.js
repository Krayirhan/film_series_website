document.addEventListener("DOMContentLoaded", function() {
    showHomePage();
});

function showContent(type) {
    function addCustomCSS() {
        var style = document.createElement('style');
        style.innerHTML = `
            #content {
                margin-top: 20px;

                display: flex;
                flex-wrap: wrap;
            }
    
            .production-card {
                width: calc(16.666% - 20px); /* 6 tane kart olacak şekilde hesaplanmış genişlik */
                margin-bottom: 20px;
                margin-right: 10px; /* Kartlar arası boşluk */
                margin-left: 10px; /* Kartlar arası boşluk */
                padding: 10px; /* Kart içeriği ile kenarlar arasındaki boşluk */
                box-sizing: border-box; /* Kenar boşluklarını içeriğin dışında hesaplamak için */
            }
    
            .production-card img {
                width: 100%;
                height: auto;
            }
    
            .production-card h3 {
                margin-top: 10px;
                font-size: 18px;
            }
    
            .production-card p {
                margin-top: 5px;
                font-size: 14px;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Custom CSS'i ekle
    addCustomCSS();
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "getContent.php?type=" + type, true);
    xhttp.send();


}

function showCategory(category) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "getCategory.php?category=" + category, true);
    xhttp.send();
}

document.getElementById("ana-sayfa-link").addEventListener("click", function(event) {
    event.preventDefault();
    showHomePage();
});


function showHomePage() {
    var categories = ['Bilimkurgu', 'Macera', 'Aksiyon', 'Belgesel', 'Dram', 'Komedi', 'Gerilim', 'Korku', 'Romantik', 'Animasyon', 'Fantastik'];

    var categoryPromises = categories.map(function(category) {
        return getCategoryPromise(category);
    });

    Promise.all(categoryPromises)
        .then(function(results) {
            var content = "";
            categories.forEach(function(category, index) {
                var categoryContent = "<section class='category-section'><h2>" + category + "</h2><div class='slider'><button class='prev' onclick=\"moveSlide('" + category.toLowerCase() + "-productions', -1)\">&#10094;</button><div id='" + category.toLowerCase() + "-productions' class='productions-container'>" + results[index] + "</div><button class='next' onclick=\"moveSlide('" + category.toLowerCase() + "-productions', 1)\">&#10095;</button></div></section>";
                content += categoryContent;
            });
            document.getElementById('content').innerHTML = content;
        })
        .catch(function(error) {
            console.error('Hata oluştu:', error);
        });
}


function moveSlide(containerId, direction) {
    var container = document.getElementById(containerId);
    var scrollAmount = direction > 0 ? container.offsetWidth : -container.offsetWidth;
    container.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
    });
}


function getCategoryPromise(category) {
    return new Promise(function(resolve, reject) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    resolve(this.responseText);
                } else {
                    reject('Kategori alınamadı: ' + category);
                }
            }
        };
        xhttp.open("GET", "getCategory.php?category=" + category, true);
        xhttp.send();
    });
}

function searchProduction() {
    var searchQuery = document.getElementById("search-input").value;
   function addCustomCSS() {
        var style = document.createElement('style');
        style.innerHTML = `
            #content {
                margin-top: 20px;
                display: flex;
                flex-wrap: wrap;
            }
            .production-card {
                margin-bottom: 20px;
                margin-right: 10px; /* Kartlar arası boşluk */
                margin-left: 10px; /* Kartlar arası boşluk */
                padding: 10px; /* Kart içeriği ile kenarlar arasındaki boşluk */
                box-sizing: border-box; /* Kenar boşluklarını içeriğin dışında hesaplamak için */
            }
    
            .production-card img {
                width: 100%;
                height: auto;
            }
    
            .production-card h3 {
                margin-top: 10px;
                font-size: 18px;
            }
    
            .production-card p {
                margin-top: 5px;
                font-size: 14px;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Custom CSS'i ekle
    addCustomCSS();
    var xhttp = new XMLHttpRequest();
    
   
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "searchProduction.php?query=" + searchQuery, true);
    xhttp.send();
}



document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("click", function (event) {
        if (!event.target.closest(".profile-card") && !event.target.closest(".profile img")) {
            closeProfileCard();
        }
    });
});

function toggleProfileCard() {
    const profileCard = document.getElementById("profile-card");
    profileCard.style.display = profileCard.style.display === "block" ? "none" : "block";
}

function closeProfileCard() {
    const profileCard = document.getElementById("profile-card");
    profileCard.style.display = "none";
}


function redirectToProduction(id) {
    window.location.href = `production.php?id=${id}`;
}

function showProfileModal() {
    document.getElementById("profile-modal").style.display = "block";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

function openModal(modalId) {
    closeProfileCard();
    document.getElementById(modalId).style.display = "block";
}

function showUploadModal() {
    document.getElementById("upload-modal").style.display = "block";
}

document.getElementById('add-userProduction-form').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(event.target);

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            alert('Yapım başarıyla eklendi!');
            event.target.reset();
        }
    };
    xhttp.open("POST", "uploadProduction.php", true);
    xhttp.send(formData);
});

document.addEventListener("DOMContentLoaded", function() {
    const sliders = document.querySelectorAll('.slider');

    sliders.forEach(function(slider) {
        const productionsContainer = slider.querySelector('.productions-container');
        const prevButton = slider.querySelector('.prev');
        const nextButton = slider.querySelector('.next');

        // Sol butona tıklama olayı
        prevButton.addEventListener('click', function() {
            productionsContainer.scrollBy({
                left: -200, // Sol yönde 200 piksel kaydır
                behavior: 'smooth'
            });
        });

        // Sağ butona tıklama olayı
        nextButton.addEventListener('click', function() {
            productionsContainer.scrollBy({
                left: 200, // Sağ yönde 200 piksel kaydır
                behavior: 'smooth'
            });
        });

        // İçerik kaydığında butonları görünür yapma
        productionsContainer.addEventListener('scroll', function() {
            prevButton.style.visibility = productionsContainer.scrollLeft > 0 ? 'visible' : 'hidden';
            nextButton.style.visibility = productionsContainer.scrollLeft < productionsContainer.scrollWidth - productionsContainer.clientWidth ? 'visible' : 'hidden';
        });
    });
});

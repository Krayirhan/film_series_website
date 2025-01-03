<?php
require_once "database.php";

$response = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $complaintType = $_POST['complaintType'];
    $details = $_POST['details'];

    $sql = "INSERT INTO complaints (name, email, complaint_type, details) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $name, $email, $complaintType, $details);

        if ($stmt->execute()) {
            $response = "Şikayetiniz başarıyla gönderildi.";
        } else {
            $response = "Hata: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $response = "Hata: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShowTime Şikayet Formu</title>
    <link rel="stylesheet" href="contact.css">
</head>
<body>
    <header id="header">
        <div class="menu-container">
            <div class="logo">
                <img src="showss.png" alt="Logo">
            </div>
            <nav>
                <ul class="menu">
                    <li><a href="index.php" id="ana-sayfa-link">Ana Sayfa</a></li>
                    <li><a href="#" onclick="showContent('film')">Filmler</a></li>
                    <li><a href="#" onclick="showContent('dizi')">Diziler</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropbtn">Kategoriler</a>
                        <div class="dropdown-content">
                            <a href="#" onclick="showCategory('Aksiyon')">Aksiyon</a>
                            <a href="#" onclick="showCategory('Belgesel')">Belgesel</a>
                            <a href="#" onclick="showCategory('Bilimkurgu')">Bilimkurgu</a>
                            <a href="#" onclick="showCategory('Dram')">Dram</a>
                            <a href="#" onclick="showCategory('Komedi')">Komedi</a>
                            <a href="#" onclick="showCategory('Gerilim')">Gerilim</a>
                            <a href="#" onclick="showCategory('Korku')">Korku</a>
                            <a href="#" onclick="showCategory('Romantik')">Romantik</a>
                            <a href="#" onclick="showCategory('Macera')">Macera</a>
                            <a href="#" onclick="showCategory('Animasyon')">Animasyon</a>
                            <a href="#" onclick="showCategory('Fantastik')">Fantastik</a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="search-container">
                <input type="text" id="search-input" class="search-input" placeholder="Yapım ara...">
                <button class="search-button" onclick="searchProduction()">Ara</button>
            </div>
            <div class="profile">
                <img src="profile.png" alt="Profil">
            </div>
        </div>
    </header>
    <main id="content">
        <div class="container">
            <h2>ShowTime Şikayet Formu</h2>
            <form id="complaintForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="name">Adınız:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">E-posta Adresiniz:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="complaintType">Şikayet Türü:</label>
                    <select id="complaintType" name="complaintType" required>
                        <option value="">Lütfen birini seçin</option>
                        <option value="İçerik Hatası">İçerik Hatası</option>
                        <option value="Site Performansı">Site Performansı</option>
                        <option value="Diğer">Diğer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="details">Şikayet Detayı:</label>
                    <textarea id="details" name="details" rows="4" required></textarea>
                </div>
                <button type="submit">Gönder</button>
            </form>
            <div id="form-feedback"><?php echo $response; ?></div>
        </div>
    </main>
    <footer>
        <div class="footer-container">
            <ul class="footer-links">
                <li><a href="#" onclick="showContent('terms')">Kullanım Koşulları</a></li>
                <li><a href="#" onclick="showContent('privacy')">Gizlilik Politikası</a></li>
                <li><a href="contact.php">İletişim</a></li>
            </ul>
            <div class="footer-socials">
                <a href="#"><img src="facebook.png" alt="Facebook"></a>
                <a href="#"><img src="twitter.png" alt="Twitter"></a>
                <a href="#"><img src="instagram.png" alt="Instagram"></a>
            </div>
            <p>&copy; 2024 Film ve Dizi Platformu. Tüm hakları saklıdır.</p>
        </div>
    </footer>
    <script>
    // Form gönderildiğinde geri bildirimi göster
document.querySelector('#complaintForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var formData = new FormData(this);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('form-feedback').innerText = "Mesajınız gönderildi.";
        } else {
            document.getElementById('form-feedback').innerText = 'Bir hata oluştu. Lütfen tekrar deneyin.';
        }
    };
    xhr.send(formData);
});

    </script>
</body>
</html>

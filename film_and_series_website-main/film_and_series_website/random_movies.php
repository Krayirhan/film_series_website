<?php
// Bağlantı bilgilerini tanımlayın
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "veritabanıproje";

// Veritabanı bağlantısını oluşturun
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol edin
if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

// Veritabanından rastgele üç filmi seçmek için sorguyu hazırlayın ve çalıştırın
$sql = "SELECT * FROM productions ORDER BY RAND() LIMIT 3";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="random_moviecss.css">
    <title>Rastgele Filmler</title>
</head>
<body>
    <header id="header">
        <div class="menu-container">
            <div class="logo">
                <img src="showss.png" alt="Logo">
            </div>
            <nav>
                <ul class="menu">
                    <li><a href="index.php">Ana Sayfa</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <h1>Rastgele Yapımlar</h1>
        <div class="productions-container">
    <?php
    // Sonuçları işleyin
    if ($result->num_rows > 0) {
        // Veritabanından her bir film için işlem yapın
        while($row = $result->fetch_assoc()) {
            // Her bir film için gerekli işlemleri yapın
            echo "<a href='production.php?id=" . $row['id'] . "' class='production-card'>";
            echo "<img src='" . $row["image"] . "' alt='" . $row["name"] . "' class='film-image'>";
            echo "<div class='play-button'></div>";
            echo "<div class='film-details'>";
            echo "<h3 class='film-name'>" . $row["name"] . "</h3>";
            echo "<p class='film-description'>" . $row["description"] . "</p>";
            echo "</div>";
            echo "</a>";
        }
    } else {
        echo "Veritabanında film bulunamadı.";
    }
    ?>
</div>

<script>
    // Her film kartına tıklanma olayını ekleyin
    var productionCards = document.querySelectorAll('.production-card');
    productionCards.forEach(function(card) {
        card.addEventListener('click', function(event) {
            var productionId = card.getAttribute('href').split('=')[1];
            // Film sayfasına yönlendirme
            window.location.href = "film.php?id=" + productionId;
        });
    });
</script>

    </main>
</body>
</html>

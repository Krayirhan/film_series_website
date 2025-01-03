<?php
session_start();
require_once "database.php";
$id = $_SESSION['id'];
$username = $_SESSION['username'];
$name = $_SESSION['name'];
$surname = $_SESSION['surname'];
$email = $_SESSION['email'];
$password = $_SESSION['password'];
$balance = $_SESSION['balance'];

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
} else {
    
    $stmt = $conn->prepare("SELECT * FROM cards WHERE id = $id");
    $stmt->execute();
    $result = $stmt->get_result();
    $rowCount = $result->num_rows;
    
    if (!($rowCount > 0)) {
        header("Location: purchase.php");
        exit;
    } 
}
$userId = $_SESSION['id'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film ve Dizi Platformu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header id="header">
        <div class="menu-container">
            <div class="logo">
                <a href="index.php" class="logo"><img src="img/showss.png" alt=""></a>
            </div>
            <nav>
                <ul class="menu">
                    <li><a href="#" id="ana-sayfa-link">Ana Sayfa</a></li>
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
                            <li><a href="random_movies.php">Rastgele Yapımlar</a></li>

                        </div>
                    </li>
                </ul>
            </nav>
            <div class="search-container">
                <input type="text" id="search-input" class="search-input" placeholder="Yapım ara...">
                <button class="search-button" onclick="searchProduction()">Ara</button>
            </div>
            <div class="upload-container">
                <button class="upload-button" onclick="showUploadModal()">
                    <img src="img/upload.png" style="vertical-align: middle; width: 16px; height: 16px;">
                    Yükle
                </button>
            </div>
            <div class="profile">      
            
            <img src="profile.png" alt="Profil" onclick="toggleProfileCard()">
                <div id="profile-card" class="profile-card">
                    <?php echo "<h4>{$_SESSION['username']}</h4>"; ?>
                    <ul>
                        <li><a href="#" onclick="showProfileModal()">Profil</a></li>
                        <?php
                        if ($id<5){
                            echo '<li><a href="admin.php">Admin Panel</a></li>';
                        }
                        ?>
                        <li><a href="logout.php">Çıkış Yap</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <main id="content">
      
  
    
    <?php while ($row = $result->fetch_assoc()): ?>
            <div class='production-card' onclick='redirectToProduction(<?= $row['id'] ?>)'>
                <img src='<?= $row['image'] ?>' alt='<?= $row['name'] ?>'>
                <h3><?= $row['name'] ?></h3>
                <p><?= $row['description'] ?></p>
            </div>
        <?php endwhile; ?>

    
    
    
    </main>
    <footer>
        <div class="footer-container">
            <ul class="footer-links">
                <li><a href="#" onclick="showContent('terms')">Kullanım Koşulları</a></li>
                <li><a href="#" onclick="showContent('privacy')">Gizlilik Politikası</a></li>
                <li><a href="contact.html">İletişim</a></li>
            </ul>
            <div class="footer-socials">
                <a href="#"><img src="facebook.png" alt="Facebook"></a>
                <a href="#"><img src="twitter.png" alt="Twitter"></a>
                <a href="#"><img src="instagram.png" alt="Instagram"></a>
            </div>
            <p>&copy; 2024 Film ve Dizi Platformu. Tüm hakları saklıdır.</p>
        </div>
    </footer>
    <div id="profile-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('profile-modal')">&times;</span>
        <h2>Profil Bilgileri</h2>
        <p>Kullanıcı Adı: <?= $_SESSION['username'] ?> <img src='img/edit-icon.png' class='edit-icon' onclick="openModal('username-modal')"></p>
        <p>Ad Soyad: <?= $_SESSION['name'] ?> <?= $_SESSION['surname'] ?> <img src='img/edit-icon.png' class='edit-icon' onclick="openModal('name-surname-modal')"></p>
        <p>Email: <?= $_SESSION['email'] ?> <img src='img/edit-icon.png' class='edit-icon' onclick="openModal('email-modal')"></p>
        <p>Bakiye: <?= $_SESSION['balance'] ?>₺</p>
        <p>Paket Tipi: <?= (isset($_SESSION['type']) && $_SESSION['type'] == 0) ? 'Aylık' : 'Yıllık' ?> <img src='img/edit-icon.png' class='edit-icon' onclick="openModal('type-modal')"></p>
    </div>
</div>

    <div id="username-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('username-modal')">&times;</span>
            <h2>Kullanıcı Adı Düzenle</h2>
            <form action="update_profile.php" method="post">
                <input type="hidden" name="field" value="username">
                <label for="username">Yeni Kullanıcı Adı:</label>
                <input type="text" id="username" name="username" value="<?= $_SESSION['username'] ?>">
                <button type="submit">Güncelle</button>
            </form>
        </div>
    </div>
    <div id="name-surname-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('name-surname-modal')">&times;</span>
            <h2>Ad Soyad Düzenle</h2>
            <form action="update_profile.php" method="post">
                <input type="hidden" name="field" value="name_surname">
                <label for="name">Yeni Ad:</label>
                <input type="text" id="name" name="name" value="<?= $_SESSION['name'] ?>"><br>
                <label for="surname">Yeni Soyad:</label>
                <input type="text" id="surname" name="surname" value="<?= $_SESSION['surname'] ?>">
                <button type="submit">Güncelle</button>
            </form>
        </div>
    </div>
    <div id="email-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('email-modal')">&times;</span>
            <h2>Email Düzenle</h2>
            <form action="update_profile.php" method="post">
                <input type="hidden" name="field" value="email">
                <label for="email">Yeni Email:</label>
                <input type="email" id="email" name="email" value="<?= $_SESSION['email'] ?>">
                <button type="submit">Güncelle</button>
            </form>
        </div>
    </div>
    <div id="type-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('type-modal')">&times;</span>
            <h2>Paket İptali</h2>
            <form action="update_profile.php" method="post">
                <input type="hidden" name="field" value="delete">
                <label for="type">Paket İptali İçin Butona Basınız:</label>
                <input type="hidden" id="delete" name="delete" value="">
                <button type="submit">İptal Et</button>
            </form>
        </div>
    </div>
    


    <div id="upload-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('upload-modal')">&times;</span>
            <h2>Dizi ve Film Yükleme Alanı</h2>
            <form id="add-userProduction-form">
                <label for="name">Yapım Adı:</label>
                <input type="text" id="name" name="name" required><br>
                
                <label for="category">Kategori:</label>
                <select id="category" name="category" required>
                    <option value="Aksiyon">Aksiyon</option>
                    <option value="Belgesel">Belgesel</option>
                    <option value="Bilimkurgu">Bilimkurgu</option>
                    <option value="Dram">Dram</option>
                    <option value="Komedi">Komedi</option>
                    <option value="Gerilim">Gerilim</option>
                    <option value="Korku">Korku</option>
                    <option value="Romantik">Romantik</option>
                    <option value="Macera">Macera</option>
                    <option value="Animasyon">Animasyon</option>
                    <option value="Fantastik">Fantastik</option>
                </select><br>

                <label for="type">Tür:</label>
                <select id="type" name="type" required>
                    <option value="film">Film</option>
                    <option value="dizi">Dizi</option>
                </select><br>
                
                <label for="image">Görsel URL:</label>
                <input type="text" id="image" name="image" required><br>

                <button type="submit">Ekle</button>
            </form>
        </div>
    </div>


    <script>
    function redirectToProduction(id) {
        window.location.href = "uploadProduction.php?id=" + id;
    }
    </script>





    <script src="script.js"></script>
</body>
</html>

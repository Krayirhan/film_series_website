<?php
session_start();
require_once "database.php";

$id = $_GET['id'];
if ($id) {
    // İzlenen yapının türüne bağlı olarak doğru tabloyu seçmek için gereken veritabanı sorgusu
    $sql = "SELECT type FROM productions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $type = $row['type'];
    $stmt->close();

    // İzleme sayısını güncelleme SQL sorgusu
    $sql = "UPDATE " . ($type == 'film' ? 'films' : 'series') . " SET watch_count = watch_count + 1 WHERE production_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
} else {
    // Hata durumunda buraya düşebilirsiniz
    echo "Hatalı istek.";
}
$sql = "SELECT watch_count FROM " . ($type == 'film' ? 'films' : 'series') . " WHERE production_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$watch_count = $row['watch_count']; // İzleme sayısını al
$stmt->close();

$sql = "SELECT * FROM " . ($production['type'] == 'film' ? 'films' : 'series') . " WHERE production_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
}
$stmt->close();

// Fetch production details from the productions table
$sql = "SELECT * FROM productions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$production = $result->fetch_assoc();

if (!$production) {
    header("Location: index.php");
    exit;
}

$stmt->close();

// Fetch video URL if the production is a film
$video_url = '';
if ($production['type'] == 'film') {
    $sql = "SELECT video_url FROM films WHERE production_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $film = $result->fetch_assoc();
    if ($film) {
        $video_url = $film['video_url'];
    }
    $stmt->close();
}

// Fetch seasons if the production is a series
$series = [];
if ($production['type'] == 'dizi') {
    $sql = "SELECT * FROM series WHERE production_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $series[] = $row;
    }
    $stmt->close();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $production['name']; ?></title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <header id="header">
        <div class="menu-container">
            <div class="logo">
                <a href="index.php" class="logo"><img src="img/showss.png" alt=""></a>
            </div>
            <nav>
                <ul class="menu">
                    <li><a href="index.php" onclick="showContent('ana-sayfa-link')">Ana Sayfa</a></li>
                    </li>
                </ul>
            </nav>
            <div class="search-container">
                <input type="text" id="search-input" class="search-input" placeholder="Yapım ara...">
                <button class="search-button" onclick="searchProduction()">Ara</button>
            </div>
            
            <div class="profile">
                <img src="profile.png" alt="Profil" onclick="toggleProfileCard()">
                <div id="profile-card" class="profile-card">
                    <?php echo "<h4>{$_SESSION['username']}</h4>"; ?>
                    <ul>
                        <li><a href="#" onclick="showProfileModal()">Profil</a></li>
                        <?php
                        if ($_SESSION['user_id'] < 5){
                            echo '<li><a href="admin.php">Admin Panel</a></li>';
                        }
                        ?>
                        <li><a href="logout.php">Çıkış Yap</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    
    
    <div class="container">
    <div id="video-container">
    </div>

    <div class="header">
    <img src="<?php echo htmlspecialchars($production['image']); ?>" alt="<?php echo htmlspecialchars($production['name']); ?> Resmi">
    <div class="isim">
        <h2><?php echo htmlspecialchars($production['name']); ?></h2>
        <div class="buton">
            <?php if ($video_url): ?>
                <button class="btn" onclick="watchVideoAndPlay('<?php echo htmlspecialchars($video_url); ?>', '<?php echo $id; ?>')">İzle</button>           
                <span id="click-counter"><?php echo $watch_count; ?></span> kere izlendi
                <?php endif; ?>
        </div>
    </div>
</div>
        <div class="sidebar">
            <div class="imdb">
                <h3>Yıl</h3>
                <p><?php echo $production['year']; ?></p>
            </div>
            <div class="dizi-pal">
                <h3>Tür</h3>
                <p><?php echo $production['type']; ?></p>
            </div>
            <div class="durum">
                <h3>Kategori</h3>
                <p><?php echo $production['category']; ?></p>
            </div>
        </div>

        <div class="overview">
            <div class="aciklama">
                <p><?php echo $production['description']; ?></p>
            </div>
        </div>
        
    <?php if ($production['type'] == 'dizi'): ?>
    <div class="season-selectbox">
        <select id="season-select" onchange="showEpisodes()">
            <?php foreach ($series as $season_id => $season_episodes): ?>
                <option value="<?php echo $season_id; ?>"><?php echo $season_id +1; ?>. Sezon</option>
            <?php endforeach; ?>
        </select>
    </div>


<?php endif; ?>


        <div id="video-container"></div>

        <script>
           function watchVideoAndPlay(videoUrl, productionId) {
        // AJAX isteği gönderme
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_watch_count.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // İşlem tamamlandığında videoyu oynat
                playVideo(videoUrl);
                console.log("İzleme sayacı güncellendi.");
            }
        };
        xhr.send("id=" + productionId);
    }

    function playVideo(videoUrl) {
        var videoContainer = document.getElementById("video-container");
        videoContainer.innerHTML = "";

        var videoElement = document.createElement("video");
        videoElement.src = videoUrl;
        videoElement.controls = true;

        // Kapatma düğmesi ekleme
        var closeButton = document.createElement("button");
        closeButton.innerHTML = "×";
        closeButton.className = "close-button";
        closeButton.onclick = function() {
            videoContainer.style.display = "none"; // Video konteynırını gizleme
            videoContainer.innerHTML = ""; // Video konteynırını temizleme
            // Filtreleri kaldırma
            document.body.classList.remove('modal-open');
        };

        videoContainer.style.display = "block";
        videoContainer.appendChild(videoElement);
        videoContainer.appendChild(closeButton); // Kapatma düğmesini ekleme
        videoElement.play();
    }
    function showWatchCount(productionId) {
        // AJAX isteği gönderme
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "get_watch_count.php?id=" + productionId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // İzleme sayacını göster
                var watchCountElement = document.getElementById("watchCount");
                watchCountElement.innerText = xhr.responseText;
            }
        };
        xhr.send();
    }

            function playEpisode(videoUrl) {
                var videoContainer = document.getElementById("video-container");
                videoContainer.innerHTML = "";

                var videoElement = document.createElement("video");
                videoElement.src = videoUrl;
                videoElement.controls = true;

                videoContainer.style.display = "block";
                videoContainer.appendChild(videoElement);
                videoElement.play();

            }

            function showEpisodes() {
                var allEpisodes = document.querySelectorAll('.season-episodes');
                allEpisodes.forEach(function(episode) {
                    episode.style.display = 'none';
                });

                var selectedSeason = document.getElementById('season-select').value;
                var selectedEpisodes = document.getElementById('season-' + selectedSeason + '-episodes');
                if (selectedEpisodes) {
                    selectedEpisodes.style.display = 'block';
                }
            }

           

            
        </script>

<div class="comment-form">
    <form action="submit_review.php" method="post">
        <input type="hidden" name="production_id" value="<?php echo htmlspecialchars($production['id']); ?>">
        <input type="hidden" name="episode_id" value="0">
        <div class="form-group">
            <label for="nickname">Takma Ad:</label>
            <input type="text" id="nickname" name="nickname" required>
        </div>
        <div class="form-group">
            <label for="rating">Puanlama:</label>
            <select id="rating" name="rating" required>
                <option value="">Seçiniz</option>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="comment">Yorum:</label>
            <textarea id="comment" name="comment" required></textarea>
        </div>
        <button type="submit">Gönder</button>
    </form>
</div>


<!-- Yorum gösterme -->
<div class="previous-comments">
    <?php
    // İlgili film için yorumları çek
    $production_id = isset($_GET['id']) ? intval($_GET['id']) : null;

    if ($production_id) {
        $sql = "SELECT * FROM comments WHERE production_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $production_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='comment'>";
                echo "<p><strong>Takma Ad:</strong> " . htmlspecialchars($row["nickname"]) . "</p>";
                echo "<p><strong>Puan:</strong> " . htmlspecialchars($row["rating"]) . "</p>";
                echo "<p><strong>Yorum:</strong> " . htmlspecialchars($row["comment"]) . "</p>";
                if (isset($row["submission_date"])) {
                    $submission_date = date('Y-m-d', strtotime($row["submission_date"]));
                    echo "<p><strong>Yorum Tarihi:</strong> " . $submission_date . "</p>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>Henüz yorum yok.</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Geçersiz yapım kimliği.</p>";
    }
    ?>
</div>


 </div>

    <div id="profile-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('profile-modal')">&times;</span>
            <h2>Profil Bilgileri</h2>
            <p>Kullanıcı Adı: <?= $_SESSION['username'] ?> <img src='img/edit-icon.png' class='edit-icon' onclick="openModal('username-modal')"></p>
            <p>Ad Soyad: <?= $_SESSION['name'] ?> <?= $_SESSION['surname'] ?> <img src='img/edit-icon.png' class='edit-icon' onclick="openModal('name-surname-modal')"></p>
            <p>Email: <?= $_SESSION['email'] ?> <img src='img/edit-icon.png' class='edit-icon' onclick="openModal('email-modal')"></p>
            <p>Bakiye: 0₺<?//= $_SESSION['wallet'] ?></p>
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

</body>
</html>
<script src="script.js"></script>

<?php
$conn->close();
?>
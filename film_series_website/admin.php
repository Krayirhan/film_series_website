<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <cıntaş name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="adminstyle.css">
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
                    <li><a href="admin.php">Admin Paneli</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <section class="admin-section">
            <div class="admin-forms">
                <div>
                    <h2>Yapım Ekle</h2>
                    <form id="add-production-form" class="admin-form">
                        <label for="name">Yapım Adı:</label>
                        <input type="text" id="name" name="name" required>
                        
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
                        </select>

                        <label for="type">Tür:</label>
                        <select id="type" name="type" required>
                            <option value="film">Film</option>
                            <option value="dizi">Dizi</option>
                        </select>
                        
                        <label for="image">Görsel URL:</label>
                        <input type="text" id="image" name="image" required>

                        <button type="submit">Ekle</button>
                    </form>
                </div>

                <div>
                    <h2>Yapım Sil</h2>
                    <form id="delete-production-form" class="admin-form">
                        <label for="name">Yapım Adı:</label>
                        <input type="text" id="name" name="name" required>
                        
                        <button type="submit">Sil</button>
                    </form>
                </div>

                <div>
                    <h2>Kullanıcı Yapımı Onaylama</h2>
                    <form id="get-userProduction-form" class="admin-form" method="POST" action="handle_form.php">
                        <?php
                            require_once "database.php";

                            // Mevcut kayıt sayacını belirle
                            if (!isset($_SESSION)) {
                                session_start();
                            }

                            if (!isset($_SESSION['current_record'])) {
                                $_SESSION['current_record'] = 0;
                            }

                            $current_record = $_SESSION['current_record'];

                            // Veritabanından tüm kayıtları çek
                            $query = "SELECT name, category, type, image, user_id FROM userProductions";
                            $result = $conn->query($query);

                            // Kayıtları bir diziye yerleştir
                            $records = [];
                            while ($row = $result->fetch_assoc()) {
                                $records[] = $row;
                            }

                            // Mevcut kaydı belirle
                            if ($current_record >= 0 && $current_record < count($records)) {
                                $current_data = $records[$current_record];
                            } else {
                                $current_data = ['user_id' => '', 'name' => '', 'category' => '', 'type' => '', 'image' => ''];
                            }
                        ?>
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo htmlspecialchars($current_data['user_id']); ?>">

                        <label for="name">Yapım Adı:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($current_data['name']); ?>" readonly>
                        
                        <label for="category">Kategori:</label>
                        <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($current_data['category']); ?>" readonly>

                        <label for="type">Tür:</label>
                        <input id="type" name="type" value="<?php echo htmlspecialchars($current_data['type']); ?>" readonly>
                        
                        <label for="image">Görsel URL:</label>
                        <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($current_data['image']); ?>" readonly>
                        <img src="<?php echo htmlspecialchars($current_data['image']); ?>" id="adm_img" alt="Görsel"><br>
                        <button type="submit" name="action" value="previous"><-Önceki</button>
                        <button type="submit" name="action" value="next">Sonraki-></button><br>
                        <button type="submit" name="action" value="approve">Onayla</button>
                        <button type="submit" name="action" value="delete">Reddet</button>
                    </form>
                </div>

                <div>
                    <h2>Kullanıcı Sil</h2>
                    <form id="delete-user-form" class="admin-form" method="POST" action="delete_user.php">
                        <label for="user">Kullanıcı Seç:</label>
                        <select id="user" name="user" required>
                            <?php
                                // Kullanıcıları veritabanından çek
                                $user_query = "SELECT id, username FROM users";
                                $user_result = $conn->query($user_query);

                                while ($user_row = $user_result->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($user_row['id']) . "'>" . htmlspecialchars($user_row['username']) . "</option>";
                                }
                            ?>
                        </select>
                        
                        <button type="submit">Kullanıcıyı Sil</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
   
    <script src="admin.js"></script>
</body>
</html>

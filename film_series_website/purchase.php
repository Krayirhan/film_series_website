<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film ve Dizi Platformu</title>
    <link rel="stylesheet" href="purchase.css">
</head>
<body>
    <header id="header">
        <div class="menu-container">
            <div class="logo">
                <a href="index.php" class="logo"><img src="img/showss.png" alt=""></a>
            </div>
            <nav>
                <ul class="menu">

                </ul>
            </nav>
        </div>
    </header>
   
    
    <main id="content">
            <div class="aylik">   
                <div class="month">
                    <label for="chk" aria-hidden="true">Aylık Paket</label>
                    <p>Showtime ile Dizi ve Film Keyfini Reklamsız Doyasıya Yaşa. </p><p>Webde, Cepte ve Şimdi Televizyonlarınızda.</p>
                    <button id="ay" name = "ay">49.99₺/1 Ay</button>
                    <p>Güvenli ödeme, Kolay iptal. </p>
                </div>                 
            </div>          
            
            <div class="yillik">     
                <div class="year">
                    <label for="chk" aria-hidden="true">Yıllık Paket</label>
                    <p>Showtime ile Dizi ve Film Keyfini Reklamsız Doyasıya Yaşa. </p><p>Yıllık Paket ile Bu Keyfi Daha Uyguna Yaşa. </p>
                    <button id="yil" name = "yil">44.99₺/12 Ay</button>
                    <p>Yıllık Tek Çekim 540₺'dir.</p>
                    <p>Güvenli ödeme, Kolay iptal. </p>
                </div>  
            </div>                             

                <?php
                    session_start();
                    if (isset($_POST["ode"])) {
                        $id = $_SESSION['id'];
                        $number = $_POST["number"];
                        $date = $_POST["date"];
                        $cvv = $_POST["cvv"];
                        $currentDateTime = date('Y-m-d H:i:s');
                        $type = $_POST["type"];
                        $_SESSION["type"] = $_POST["type"];
                        $errors = array();
                        
                        if (empty($number) OR empty($date) OR empty($cvv)) {
                            array_push($errors,"Bütün alanlar dolu olmalıdır.");
                        }
                        if (strlen($cvv)>3) {
                            array_push($errors,"CVV maksimum 3 haneli olabilir");
                        }
                        require_once "database.php";
                            
                        $sql = "INSERT INTO cards (id, number, cvv, date, purchaseDate, packageType) VALUES ( ?, ?, ?, ?, ?, ? )";
                        $stmt = mysqli_stmt_init($conn);
                        $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
                        if ($prepareStmt) {
                            mysqli_stmt_bind_param($stmt,"iiissi", $id, $number, $date, $cvv, $currentDateTime, $type);
                            mysqli_stmt_execute($stmt);
                            $_SESSION['success_message'] = "Ödeme alındı, ana sayfaya yönlendiriliyorsunuz.";
                            header("Location: index.php");
                            exit();
                        }else{
                            die("Something went wrong");
                        }
                    }
                ?>
                <div class="purchaseM">
                        <form action="" method="post">
                            <label for="chk" aria-hidden="true">Ödeme</label>
                            <input type="hidden" id="gizli_deger" name="type" value="0">
                            <input type="number" name="number" placeholder="Kart Numarası" required="">
                            <input type="month" id="date" name="date" placeholder="Tarih" required="">
                            <input type="number" id="cvv" name="cvv" pattern="[0-9]{3}" alt="En fazla 3 karakter" placeholder="CVV Kodu" required="">
                            <button name="ode">Ödeme Yap</button>
                            <a href="purchase.php" id="back"> <- Geri Gel</a>
                        </form>
                </div>
                <div class="purchaseY">
                        <form action="" method="post">
                            <label for="chk" aria-hidden="true">Ödeme</label>
                            <input type="hidden" id="gizli_deger" name="type" value="1">
                            <input type="number" name="number" placeholder="Kart Numarası" required="">
                            <input type="month" id="date" name="date" placeholder="Tarih" required="">
                            <input type="number" id="cvv" name="cvv" pattern="[0-9]{3}" alt="En fazla 3 karakter" placeholder="CVV Kodu" required="">
                            <button name="ode">Ödeme Yap</button>
                            <a href="purchase.php" id="back"> <- Geri Gel</a>
                        </form>
                </div>
                
    </main>
    <footer>
        <div class="footer-container">
            <ul class="footer-links">
                <li><a href="#" onclick="showContent('terms')">Kullanım Koşulları</a></li>
                <li><a href="#" onclick="showContent('privacy')">Gizlilik Politikası</a></li>
                <li><a href="contact.html" >İletişim</a></li>
            </ul>
            <div class="footer-socials">
                <a href="#"><img src="facebook.png" alt="Facebook"></a>
                <a href="#"><img src="twitter.png" alt="Twitter"></a>
                <a href="#"><img src="instagram.png" alt="Instagram"></a>
            </div>
            <p>&copy; 2024 Film ve Dizi Platformu. Tüm hakları saklıdır.</p>
        </div>
    </footer>
    <script src="purchasee.js"></script>
</body>
</html>

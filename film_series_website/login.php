
<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
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
                
            </nav>
        </div>
    </header>
   
    <main id="login">
        <div class="bg-img">
            <div class="content">

                <?php
                    if (isset($_POST["login"])) {
                        $email = $_POST["email"];
                        $password = $_POST["password"];
                        require_once "database.php";
                        $sql = "SELECT * FROM users WHERE email = '$email'";
                        $result = mysqli_query($conn, $sql);
                        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        
                        
                        if ($user) {
                            if (password_verify($password, $user["password"])) {
                                session_start();
                                $_SESSION["user"] = "yes";           
                                $_SESSION['email']=$user['email'];    
                                $_SESSION['username']=$user['username']; 
                                $_SESSION['name']=$user['name'];
                                $_SESSION['surname']=$user['surname'];   
                                $_SESSION['password']=$user['password']; 
                                $_SESSION['id']=$user['id'];
                                $_SESSION['balance']=$user["balance"];
                                header("Location: index.php");
                                die();
                            }else{
                                echo "<div class='alert alert-danger'>Şifre Yanlış.</div>";
                            }
                        }else{
                            echo "<div class='alert alert-danger'>Email Kayıtlı Değil</div>";
                        }
                    }
                ?>
                <header id="form-header">Giriş Yap</header>
                <form id="login-form" action="login.php" method="post">
                    <div class="field space">
                        <span class="fa fa-user"></span>
                        <input type="email" name="email" required placeholder="Email">
                    </div>
                    <div class="field space">
                        <span class="fa fa-lock"></span>
                        <input type="password" name="password" class="pass-key" required placeholder="Şifre">
                        <span class="show">Göster</span>
                    </div>
                    <div class="field space">
                        <input type="submit"name="login" value="Giriş Yap">
                    </div>
                </form>




                <?php
                    if (isset($_POST["submit"])) {
                    $username = $_POST["username"];
                    $name = $_POST["name"];
                    $surname = $_POST["surname"];
                    $email = $_POST["email"];
                    $password = $_POST["password"];
                    
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                    $errors = array();
                    
                    if (empty($username) OR empty($email) OR empty($password)) {
                        array_push($errors,"Bütün Alanlar Dolu Olmak Zorundadır.");
                    }
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        array_push($errors, "Email Geçerli Değil");
                    }
                    if (strlen($password)<8) {
                        array_push($errors,"Şifre En Az 8 Karakter Uzunluğunda Olmalıdır.");
                    }
                    require_once "database.php";
                    $sql = "SELECT * FROM users WHERE email = '$email'";
                    $result = mysqli_query($conn, $sql);
                    $rowCount = mysqli_num_rows($result);
                    if ($rowCount>0) {
                        array_push($errors,"Email Zaten Kayıtlı!");
                    }
                    if (count($errors)>0) {
                        foreach ($errors as  $error) {
                            echo "<div class='alert alert-danger'>$error</div>";
                        }
                    }else{
                        
                        $sql = "INSERT INTO users (username, name, surname, email, password) VALUES ( ?, ?, ?, ?, ? )";
                        $stmt = mysqli_stmt_init($conn);
                        $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
                        if ($prepareStmt) {
                            mysqli_stmt_bind_param($stmt,"sssss", $username,$name ,$surname, $email, $passwordHash);
                            mysqli_stmt_execute($stmt);
                            echo "Kayıt Başarıyla Oluşturuldu.";
                        }else{
                            die("Something went wrong");
                        }
                    }
                    

                    }
                ?>

                <form id="signup-form" action="login.php" style="display: none;" method="post">
                    <div class="field spacespacespacespace">
                        <span class="fa fa-user"></span>
                        <input type="name"name="name" required placeholder="Ad">
                    </div>
                    <div class="field space">
                        <span class="fa fa-user"></span>
                        <input type="surname"name="surname" required placeholder="Soyad">
                    </div>
                    <div class="field space">
                        <span class="fa fa-user"></span>
                        <input type="username" name="username" required placeholder="Kullanıcı Adı">
                    </div>
                    <div class="field space">
                        <span class="fa fa-envelope"></span>
                        <input type="email" name="email" required placeholder="Email">
                    </div>
                    <div class="field space">
                        <span class="fa fa-lock"></span>
                        <input type="password" name="password" class="pass-key" required placeholder="Şifre">
                        <span class="show">Göster</span>
                    </div>
                    <div class="field space">
                        <input type="submit" name="submit" value="Kayıt Ol">
                    </div>
                </form>

                <?php
                    if (isset($_POST["forgot"])) {
                        $email = $_POST["email"];
                        $password = $_POST["password"];
                        
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                        $errors = array();
                        
                        if (empty($email) OR empty($password)) {
                            array_push($errors,"Bütün Alanlar Dolu Olmak Zorundadır.");
                        }
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            array_push($errors, "Email Geçerli Değil");
                        }
                        if (strlen($password)<8) {
                            array_push($errors,"Şifre En Az 8 Karakter Uzunluğunda Olmalıdır.");
                        }
                        require_once "database.php";
                        $sql = "SELECT * FROM users WHERE email = '$email'";
                        $result = mysqli_query($conn, $sql);
                        $rowCount = mysqli_num_rows($result); 
                        if (count($errors)>0) {
                            foreach ($errors as  $error) {
                                echo "<div class='alert alert-danger'>$error</div>";
                            }
                        }else{
                            
                            $sql = "UPDATE users SET password = ? WHERE email = ?";
                            $stmt = mysqli_stmt_init($conn);
                            $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                            if ($prepareStmt) {
                                mysqli_stmt_bind_param($stmt, "ss", $passwordHash, $email);
                                mysqli_stmt_execute($stmt);
                                echo "Şifreniz Başarıyla Güncellendi.";
                            } else {
                                die("Something went wrong");
                            }
                        }
                    

                    }
                ?>
                
                <form id="forgotPass-form" action="login.php" style="display: none;" method="post">
                    <div class="field space">
                        <span class="fa fa-envelope"></span>
                        <input type="email" name="email" required placeholder="Email">
                    </div>
                    <div class="field space">
                        <span class="fa fa-lock"></span>
                        <input type="password" name="password" class="pass-key" required placeholder="Yeni Şifre">
                        <span class="show">Göster</span>
                    </div>
                    <div class="field space">
                        <input type="submit" name="forgot" value="Şifre Yenile">
                    </div>
                </form>

                <div class="toggle-forms">
                    <div class="signup">
                        Henüz hesabınız yok mu?
                        <a href="#" id="show-signup-form">Kayıt Ol</a>
                    </div>
                    <div class="login" style="display: none;">
                        Hesabınız var mı?
                        <a href="#" id="show-login-form">Giriş Yap</a>
                    </div>
                    <div class="frgt">
                        <a href="#" id="show-forgotPass-form">Şifremi Unuttum?</a>
                    </div>
                </div>
        </div>
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
    <script>
        const passField = document.querySelectorAll('.pass-key');
        const showBtns = document.querySelectorAll('.show');

        showBtns.forEach(showBtn => {
            showBtn.addEventListener('click', function() {
                passField.forEach(field => {
                    if (field.type === "password") {
                        field.type = "text";
                        showBtn.textContent = "Gizle";
                        showBtn.style.color = "#3498db";
                    } else {
                        field.type = "password";
                        showBtn.textContent = "Göster";
                        showBtn.style.color = "#222";
                    }
                });
            });
        });

        const signupForm = document.getElementById('signup-form');
        const loginForm = document.getElementById('login-form');
        const forgotPassForm = document.getElementById('forgotPass-form');
        const formHeader = document.getElementById('form-header');
       
        const showSignupForm = document.getElementById('show-signup-form');
        const showLoginForm = document.getElementById('show-login-form');
        const showForgotPassForm = document.getElementById('show-forgotPass-form');

        
        const toggleSignup = document.querySelector('.signup');
        const toggleLogin = document.querySelector('.login');
        const toggleForgotPass = document.querySelector('.forgotPass');

        showSignupForm.addEventListener('click', function() {
            loginForm.style.display = 'none';
            signupForm.style.display = 'block';
            forgotPassForm.style.display = 'none';
            formHeader.textContent = 'Kayıt Ol';
            toggleSignup.style.display = 'none';
            toggleLogin.style.display = 'block';
        });

        showLoginForm.addEventListener('click', function() {
            signupForm.style.display = 'none';
            forgotPassForm.style.display = 'none';
            loginForm.style.display = 'block';
            formHeader.textContent = 'Giriş Yap';
            toggleSignup.style.display = 'block';
            toggleForgotPass.style.display = 'none';
            toggleLogin.style.display = 'none';
        });

        showForgotPassForm.addEventListener('click', function() {
            loginForm.style.display = 'none';
            signupForm.style.display = 'none';
            forgotPassForm.style.display = 'block';
            formHeader.textContent = 'Şifremi Unuttum';
            toggleForgotPass.style.display = 'none';
            toggleLogin.style.display = 'block';
            toggleSignup.style.display = 'none';
        });
    </script>
</body>
</html>

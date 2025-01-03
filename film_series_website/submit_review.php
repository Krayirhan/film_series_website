<?php
session_start();
require_once "database.php";

// Formdan gelen verileri al
$nickname = mysqli_real_escape_string($conn, $_POST['nickname']);
$rating = mysqli_real_escape_string($conn, $_POST['rating']);
$comment = mysqli_real_escape_string($conn, $_POST['comment']);
$production_id = mysqli_real_escape_string($conn, $_POST['production_id']);
$episode_id = mysqli_real_escape_string($conn, $_POST['episode_id']);

// Yorumu veritabanına ekle
$sql = "INSERT INTO comments (nickname, rating, comment, production_id, episode_id) VALUES ('$nickname', '$rating', '$comment', '$production_id', '$episode_id')";

if ($conn->query($sql) === TRUE) {
    echo "Yorum başarıyla eklendi.";
} else {
    echo "Hata: " . $sql . "<br>" . $conn->error;
}

header("Location: ".$_SERVER['HTTP_REFERER']);
$conn->close();
?>

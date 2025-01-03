<?php
require_once "database.php";
session_start();
$name = $_POST['name'];
$category = $_POST['category'];
$type = $_POST['type'];
$image = $_POST['image'];
$user_id=$_SESSION['id'];

$sql = "INSERT INTO userProductions (name, category, type, image, user_id) VALUES ('$name', '$category', '$type', '$image','$user_id')";

if ($conn->query($sql) === TRUE) {
    echo "Yeni yapım başarıyla eklendi";
} else {
    echo "Hata: " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>

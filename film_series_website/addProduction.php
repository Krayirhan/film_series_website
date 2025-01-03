<?php
require_once "database.php";

$name = $_POST['name'];
$category = $_POST['category'];
$type = $_POST['type'];
$image = $_POST['image'];

$sql = "INSERT INTO productions (name, category, type, image) VALUES ('$name', '$category', '$type', '$image')";

if ($conn->query($sql) === TRUE) {
    echo "Yeni yapım başarıyla eklendi";
} else {
    echo "Hata: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

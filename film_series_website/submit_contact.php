<?php
require_once "database.php";

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$sql = "INSERT INTO complaints (name, email, message) VALUES ('$name', '$email', '$message')";

if ($conn->query($sql) === TRUE) {
    $response = "Mesajınız başarıyla gönderildi.";
} else {
    $response = "Hata: " . $sql . "<br>" . $conn->error;
}

$conn->close();

echo json_encode(["response" => $response]);
?>

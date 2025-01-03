<?php
session_start();
require_once "database.php";

// İzlenen yapının ID'sini alalım
$id = isset($_POST['id']) ? intval($_POST['id']) : null;

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

$conn->close();
?>

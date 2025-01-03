<?php
require_once "database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user'];

    // Kullanıcıyı veritabanından sil
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "Kullanıcı başarıyla silindi.";
    } else {
        echo "Kullanıcı silinirken bir hata oluştu: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

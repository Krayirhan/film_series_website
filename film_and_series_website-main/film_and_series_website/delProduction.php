<?php
require_once "database.php";
$name = $_POST['name'];
$stmt = $conn->prepare("DELETE FROM productions WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();

if ($conn->query($sql) === TRUE) {
        echo "Yapım başarıyla silindi";
} else {
        echo "Hata: " . $sql . "<br>" . $conn->error;
}


?>


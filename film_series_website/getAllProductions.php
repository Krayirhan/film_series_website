<?php
require_once "database.php";
// Tüm yapımları al
$sql = "SELECT * FROM productions";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Verileri ekrana yazdır
    while($row = $result->fetch_assoc()) {
        echo "<div class='production-card'>";
        echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "'>";
        echo "<div class='production-info'>";
        echo "<p class='production-title'>" . $row['name'] . "</p>";
        echo "<p class='production-category'>" . $row['category'] . "</p>";
        echo "<p class='production-type'>" . $row['type'] . "</p>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "Sonuç bulunamadı.";
}
$conn->close();
?>

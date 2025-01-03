<?php
require_once "database.php";
$type = $_GET['type'];
$sql = "SELECT * FROM productions WHERE type = '$type'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='production-card' onclick='redirectToProduction(" . $row['id'] . ")'>";
        echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "'>";
        echo "<div class='play-button'></div>";
        echo "<p class='production-title'>" . $row['name'] . "</p>";
        echo "</div>";
    }
    
} else {
    echo "0 sonuç";
}

$conn->close();
?>

<?php
require_once "database.php";
$query = $_GET['query'];
$sql = "SELECT * FROM productions WHERE name LIKE '%$query%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='production-card'>";
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

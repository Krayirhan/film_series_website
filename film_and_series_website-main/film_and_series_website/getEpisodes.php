<?php
require_once "database.php";

if (!isset($_GET['season_id'])) {
    echo "Invalid request.";
    exit;
}

$season_id = $_GET['season_id'];

$sql = "SELECT * FROM episodes WHERE season_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $season_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='episode' onclick=\"playEpisode('" . htmlspecialchars($row['video_url']) . "')\">";
        echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
        echo "</div>";
    }
} else {
    echo "No episodes found.";
}

$stmt->close();
$conn->close();
?>

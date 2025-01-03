<?php
require_once "database.php";

if (isset($_POST['production_id']) && isset($_POST['season_id'])) {
    $production_id = intval($_POST['production_id']);
    $season_id = intval($_POST['season_id']);

    $sql = "SELECT * FROM episodes WHERE production_id = ? AND season_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $production_id, $season_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $episodes = [];

    while ($row = $result->fetch_assoc()) {
        $episodes[] = $row;
    }

    $stmt->close();
    $conn->close();

    echo json_encode($episodes);
} else {
    echo json_encode([]);
}
?>

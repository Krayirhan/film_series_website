<?php
session_start();
require_once "database.php";

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM productions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$production = $result->fetch_assoc();

if (!$production) {
    echo "Production not found.";
    exit;
}

$stmt->close();

// Fetch seasons if the production is a series
$seasons = [];
if ($production['type'] == 'dizi') {
    $sql = "SELECT * FROM seasons WHERE production_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $seasons[] = $row;
    }
    $stmt->close();
}

$conn->close();

// Return production details and seasons as JSON
echo json_encode(['production' => $production, 'seasons' => $seasons]);
?>

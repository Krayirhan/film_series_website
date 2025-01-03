<?php
session_start();
require_once "database.php";

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$field = $_POST['field'];

switch ($field) {
    case 'username':
        $newUsername = trim($_POST['username']);
        if (!empty($newUsername)) {
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->bind_param("si", $newUsername, $_SESSION['id']);
            $stmt->execute();
            $stmt->close();
            $_SESSION['username'] = $newUsername;
        }
        break;
    case 'name_surname':
        $newName = trim($_POST['name']);
        $newSurname = trim($_POST['surname']);
        if (!empty($newName) && !empty($newSurname)) {
            $stmt = $conn->prepare("UPDATE users SET name = ?, surname = ? WHERE id = ?");
            $stmt->bind_param("ssi", $newName, $newSurname, $_SESSION['id']);
            $stmt->execute();
            $stmt->close();
            $_SESSION['name'] = $newName;
            $_SESSION['surname'] = $newSurname;
        }
        break;
    case 'email':
        $newEmail = trim($_POST['email']);
        if (!empty($newEmail) && filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
            $stmt->bind_param("si", $newEmail, $_SESSION['id']);
            $stmt->execute();
            $stmt->close();
            $_SESSION['email'] = $newEmail;
        }
        break;
    case 'delete':
        $stmt = $conn->prepare("DELETE FROM cards WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['id']);
        $stmt->execute();
        $stmt->close();
        break;
}

$conn->close();
header("Location: index.php");
exit();
?>

<?php
require_once "database.php";
session_start();

if (!isset($_SESSION['current_record'])) {
    $_SESSION['current_record'] = 0;
}

$current_record = $_SESSION['current_record'];

$query = "SELECT id, name, category, type, image FROM userProductions";
$result = $conn->query($query);

$records = [];
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

if ($current_record >= 0 && $current_record < count($records)) {
    $current_data = $records[$current_record];
} else {
    $current_data = ['id' => '', 'name' => '', 'category' => '', 'type' => '', 'image' => ''];
}
// kullınıcı yüklemesi için admin işlemleri
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'previous':
            if ($_SESSION['current_record'] > 0) {
                $_SESSION['current_record']--;
            }
            break;
        case 'next':
            if ($_SESSION['current_record'] < count($records) - 1) {
                $_SESSION['current_record']++;
            }
            break;
        case 'approve':
            if (!empty($current_data['id'])) {
                $stmt = $conn->prepare("INSERT INTO productions (name, category, type, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $current_data['name'], $current_data['category'], $current_data['type'], $current_data['image']);
                $stmt->execute();
                $stmt->close();

                
                $name= $_POST['name'];
                $user_id = $_POST['user_id'];
                $userResult = $conn->query("SELECT user_id FROM userproductions WHERE name = '$name'");

                if ($userResult->num_rows > 0) {
                    

                    $updateBalanceSql = "UPDATE users SET balance = balance + 10 WHERE id = $user_id";
                    if ($conn->query($updateBalanceSql) === TRUE) {
                        echo "Kullanıcının bakiyesi güncellendi.";
                    } else {
                        echo "Hata: " . $updateBalanceSql . "<br>" . $conn->error;
                    }
                } else {
                    echo "Kullanıcı bulunamadı.";
                }
              
                $stmt = $conn->prepare("DELETE FROM userproductions WHERE id = ?");
                $stmt->bind_param("i", $current_data['id']);
                $stmt->execute();
                $stmt->close();
            }
            break;
        case 'delete':
            if (!empty($current_data['id'])) {
                $stmt = $conn->prepare("DELETE FROM userProductions WHERE id = ?");
                $stmt->bind_param("i", $current_data['id']);
                $stmt->execute();
                $stmt->close();
            }
            break;
    }

    $current_record = $_SESSION['current_record'];
    $query = "SELECT id, name, category, type, image FROM userProductions";
    $result = $conn->query($query);

    $records = [];
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }

    if ($current_record >= 0 && $current_record < count($records)) {
        $current_data = $records[$current_record];
    } else {
        $current_data = ['id' => '', 'name' => '', 'category' => '', 'type' => '', 'image' => ''];
    }
}

header("Location: admin.php");
?>

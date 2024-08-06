<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.html");
    exit();
}

// connect to database
include_once 'db_connection.php';

// add post to database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $user_id = $_SESSION['user_id'];

        $sql = "INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $user_id);

        if ($stmt->execute()) {
            header("Location: ../views/dashboard.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $stmt->close(); 
}

$conn->close();
?>

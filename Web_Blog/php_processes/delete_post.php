<?php
session_start();

// if not logged in head to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.html");
    exit();
}

// connect to database
include_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM posts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);

    if ($stmt->execute()) {
        header("Location: ../views/dashboard.php");
        exit();
    } else {
        echo "Error deleting post.";
    }

    $stmt->close();
} else {
    header("Location: ../views/dashboard.php");
    exit();
}

$conn->close();
?>

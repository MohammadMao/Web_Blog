<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// connect to database
include_once 'db_connection.php';

// update post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $title, $content, $post_id, $user_id);

    if ($stmt->execute()) {
        header("Location: ../views/dashboard.php");
        exit();
    } else {
        echo "Error updating post.";
    }

    $stmt->close();
} else {
    header("Location: ../views/dashboard.php");
    exit();
}

$conn->close();
?>

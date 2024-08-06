<?php

// connect to database
include_once 'db_connection.php';

if (isset($_POST['id']) && isset($_POST['action'])) {
    $postId = intval($_POST['id']);
    $action = $_POST['action'];

    // Fetch the updated likes count
    $sql = "SELECT likes FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $postId);
    $stmt->execute();
    $stmt->bind_result($likesCount);
    $stmt->fetch();
    $stmt->close();

    // Fetch the updated dislikes count
    $sql = "SELECT dislikes FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $postId);
    $stmt->execute();
    $stmt->bind_result($dislikesCount);
    $stmt->fetch();
    $stmt->close();

    if ($action === 'like') {
        // Check if likes are greater than 0
        if ($likesCount > 0) {
            // Decrement likes by 1
            $sql = "UPDATE posts SET likes = likes - 1 WHERE id = ?";
        } elseif ($dislikesCount > 0) {
            $sql = "UPDATE posts SET likes = likes + 1 WHERE id = ?";
            $sql2 = "UPDATE posts SET dislikes = dislikes - 1 WHERE id = ?";
        } else {
            $sql = "UPDATE posts SET likes = likes + 1 WHERE id = ?";    
        }
        
    } elseif ($action === 'dislike') {
        // Check if dislikes are greater than 0
        if ($dislikesCount > 0){
            // Decrement dislikes by 1
            $sql = "UPDATE posts SET dislikes = dislikes - 1 WHERE id = ?";
        } elseif ($likesCount > 0) {
            $sql = "UPDATE posts SET dislikes = dislikes + 1 WHERE id = ?";
            $sql2 = "UPDATE posts SET likes = likes - 1 WHERE id = ?";
        } else {
            $sql = "UPDATE posts SET dislikes = dislikes + 1 WHERE id = ?";
        }
        
    }

    // execute statments
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $postId);
    $stmt->execute();
    $stmt->close();

    if(!empty($sql2)){
        $stmt = $conn->prepare($sql2);
        $stmt->bind_param('i', $postId);
        $stmt->execute();
        $stmt->close();
    }
    
    // display likes/dislikes count
    if ($action === 'like') {
        $sql = "SELECT likes FROM posts WHERE id = ?";
    } elseif ($action === 'dislike') {
        $sql = "SELECT dislikes FROM posts WHERE id = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $postId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    
    echo $count;

    $stmt->close();
    $conn->close();
}
?>
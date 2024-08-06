<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <!-- Style for icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- Script -->
    <script src="../scripts/like_dislike.js"></script>
</head>
<body>
    <div>
        <?php
            session_start();
            // Connect to database
            include_once 'db_connection.php';
            // Fetch posts
            $sql = "SELECT posts.id, posts.title, posts.content, posts.post_date, users.username, users.profile_image, posts.likes, posts.dislikes 
                    FROM posts 
                    JOIN users ON posts.user_id = users.id 
                    ORDER BY posts.post_date DESC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Display posts
                    echo "<div class='post'>";
                    echo "<img src='../user_images/" . htmlspecialchars($row['profile_image']) . "' alt='Profile Picture' width='100px'>" . "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                    echo "<p>By " . htmlspecialchars($row['username']) . " on " . htmlspecialchars($row['post_date']) . "</p>";
                    echo '<p align="justify">' . nl2br(htmlspecialchars($row['content'])) . '</p>';
                    echo "<button onclick='updateLike(" . $row['id'] . ")'><span class='material-symbols-outlined' id='like'>thumb_up</span></button> <span id='like_count_" . $row['id'] . "'>" . $row['likes'] . "  </span>";
                    echo "<button onclick='updateDislike(" . $row['id'] . ")'><span class='material-symbols-outlined' id='dislike'>thumb_down</span></button> <span id='dislike_count_" . $row['id'] . "'>" . $row['dislikes'] . "  </span>";
                    echo "</div>";
                }
            } else {
                // if no posts
                echo "<p>No posts found.</p>";
            }
            $conn->close();
        ?>
    </div>
</body>
</html>

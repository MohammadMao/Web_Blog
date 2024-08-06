<?php

// connect to database
include_once 'db_connection.php';
session_start();
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Blog</title>
    <link rel="stylesheet" href="../styles/homeStyle.css">
    <!-- styles for icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <!-- scripts -->
    <script src="../scripts/dark_thame.js"></script>
    <script src="../scripts/like_dislike.js"></script>
</head>
<body onload="pageMode()">
    <div class="container">
        <header>
            <div class="header-left">
                <!-- logo -->
                <img src="../webblog.png" alt="logo" height="70px">
            </div>
            <div class="header-right">
                <nav>
                    <!-- Navbar -->
                    <ul>
                        <li><a href="../views/index.html"><center><span class="material-icons">home</span></center>Home</a></li>
                        <li><a href="../views/about.html"><center><span class="material-symbols-outlined">info</span></center>About</a></li>
                        <?php require_once('nav.php'); ?>
                            <img src="../moon.png" alt="mode icon" id="thame_icon" onclick="changeMode()">
                    </ul>
                </nav>
                <!-- Search bar -->
                <form action="index.php" method="GET" class="search-form">
                    <input type="text" name="query" placeholder="Search by user or post" required>
                    <button type="submit">Search</button>
                </form>
            </div>
        </header>

        <main>
            <!-- Display posts according to search -->
            <section class="latest-posts">
                <h2>Posts by Search</h2>
                <?php
                if (isset($_GET['query'])) {
                    $search_param = "%" . $_GET['query'] . "%";
                    $sql = "SELECT posts.id, posts.title, posts.content, posts.post_date, users.username, users.profile_image, posts.likes, posts.dislikes 
                            FROM posts 
                            JOIN users ON posts.user_id = users.id 
                            WHERE posts.title LIKE ? OR users.username LIKE ?
                            ORDER BY posts.post_date DESC";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $search_param, $search_param);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->close();
                } else {
                    $sql = "SELECT posts.id, posts.title, posts.content, posts.post_date, users.username, users.profile_image
                            FROM posts 
                            JOIN users ON posts.user_id = users.id 
                            ORDER BY posts.post_date DESC";
                    $result = $conn->query($sql);
                }

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='post'>";
                        echo "<img src='../user_images/" . htmlspecialchars($row['profile_image']) . "' alt='Profile Picture' width='40px'>" . "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                        echo "<p>By " . htmlspecialchars($row['username']) . " on " . htmlspecialchars($row['post_date']) . "</p>";
                        echo '<p>' . nl2br(htmlspecialchars($row['content'])) . '</p>';
                        echo "<button onclick='updateLike(" . $row['id'] . ")'><span class='material-symbols-outlined' id='like'>thumb_up</span></button> <span id='like_count_" . $row['id'] . "'>" . $row['likes'] . "  </span>";
                    echo "<button onclick='updateDislike(" . $row['id'] . ")'><span class='material-symbols-outlined' id='dislike'>thumb_down</span></button> <span id='dislike_count_" . $row['id'] . "'>" . $row['dislikes'] . "  </span>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No posts found.</p>";
                }

                $conn->close();
                ?>
            </section>

            <aside class="sidebar">
                <section class="social-media">
                    <h3>Follow Us</h3>
                    <a href="#"><img src="https://cdn3.iconfinder.com/data/icons/capsocial-round/500/facebook-512.png" alt="Facebook">Facebook</a>
                    <a href="#"><img src="https://cdn3.iconfinder.com/data/icons/social-media-2169/24/social_media_social_media_logo_twitter-512.png" alt="Twitter">Twitter</a>
                    <a href="#"><img src="https://cdn3.iconfinder.com/data/icons/social-media-2169/24/social_media_social_media_logo_instagram-512.png" alt="Instagram">Instagram</a>
                </section>
            </aside>
        </main>

        <footer>
            <p>&copy; 2024 Web Blog. All rights reserved.</p>
            <nav>
                <ul>
                    <li><a href="../views/index.html">Home</a></li>
                    <li><a href="../views/about.html">About</a></li>
                </ul>
            </nav>
            <p>Contact Information: info@webblog.com</p>
        </footer>
    </div>
</body>
</html>
